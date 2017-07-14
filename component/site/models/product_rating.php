<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Class product_ratingModelproduct_rating
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 * @since       1.0
 */
class RedshopModelProduct_Rating extends RedshopModelForm
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  Configuration array
	 *
	 * @throws  RuntimeException
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);

		if (array_key_exists('context', $config))
		{
			$this->context = $config['context'];
		}
		else
		{
			$this->context = $this->context . '.' . JFactory::getApplication()->input->getInt('product_id', 0);
		}
	}

	/**
	 * Method to get the record form.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  mixed  A JForm object on success, false on failure
	 *
	 * @since   1.5
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm($this->context . '.' . $this->formName, $this->formName, array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form))
		{
			return false;
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  array    The default data is an empty array.
	 *
	 * @since   1.5
	 */
	protected function loadFormData()
	{
		$data = (array) JFactory::getApplication()->getUserState($this->context . '.data', array());

		return $data;
	}

	/**
	 * Method to store the records
	 *
	 * @param   array  $data  array of data
	 *
	 * @return bool
	 */
	public function store($data)
	{
		$row = $this->getTable('rating_detail');

		if (!$row->bind($data))
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		if (!$row->store())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		return true;
	}

	/**
	 * Send Mail For Ask Review
	 *
	 * @param   array  $data  Review data
	 *
	 * @return  bool
	 */
	public function sendMailForReview($data)
	{
		if (!$this->store($data))
		{
			return false;
		}

		$redshopMail = redshopMail::getInstance();
		$mailbcc = null;
		$subject = "";
		$mailbody = $redshopMail->getMailtemplate(0, "review_mail");
		$data_add = $data['title'];

		if (count($mailbody) > 0)
		{
			$data_add = $mailbody[0]->mail_body;
			$subject = $mailbody[0]->mail_subject;

			if (trim($mailbody[0]->mail_bcc) != "")
			{
				$mailbcc = explode(",", $mailbody[0]->mail_bcc);
			}
		}

		$product = RedshopHelperProduct::getProductById($data['product_id']);
		$link = JRoute::_(JURI::base() . "index.php?option=com_redshop&view=product&pid=" . $data['product_id'] . '&Itemid=' . $data['Itemid'], false);
		$data_add = str_replace("{product_link}", "<a href=" . $link . ">" . $product->product_name . "</a>", $data_add);
		$data_add = str_replace("{product_name}", $product->product_name, $data_add);
		$data_add = str_replace("{title}", $data['title'], $data_add);
		$data_add = str_replace("{comment}", $data['comment'], $data_add);
		$data_add = str_replace("{username}", $data['username'], $data_add);
		$data_add = $redshopMail->imginmail($data_add);

		if (Redshop::getConfig()->get('ADMINISTRATOR_EMAIL') != "")
		{
			$sendto = explode(",", Redshop::getConfig()->get('ADMINISTRATOR_EMAIL'));

			if (JFactory::getMailer()->sendMail($data['email'], $data['username'], $sendto, $subject, $data_add, $mode = 1, null, $mailbcc))
			{
				return true;
			}
			else
			{
				$this->setError(JText::_('COM_REDSHOP_EMAIL_HAS_NOT_BEEN_SENT_SUCCESSFULLY'));

				return false;
			}
		}

		return true;
	}

	/**
	 * Check Rated Product
	 *
	 * @param   int     $pid    Product id
	 * @param   int     $uid    User id
	 * @param   string  $email  User mail
	 *
	 * @return mixed
	 */
	public function checkRatedProduct($pid, $uid = 0, $email = '')
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('COUNT(rating_id)')
			->from($db->qn('#__redshop_product_rating'))
			->where('product_id = ' . (int) $pid)
			->where('userid = ' . (int) $uid);

		if ($email)
		{
			$query->where('email = ' . $db->q($email));
		}

		return $db->setQuery($query)->loadResult();
	}
}
