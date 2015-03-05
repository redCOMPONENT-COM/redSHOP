<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
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
	protected $context = 'com_reshop.product_rating';

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
		$form = $this->loadForm('com_redshop.product_rating', 'rating', array('control' => 'jform', 'load_data' => $loadData));

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
		$data = (array) JFactory::getApplication()->getUserState('com_redshop.product_rating.data', array());

		return $data;
	}

	public function store($data)
	{
		$user                = JFactory::getUser();
		$data['userid']      = $user->id;
		$data['email']       = $user->email;
		$data['published']   = 0;

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

	public function sendMailForReview($data)
	{
		$this->store($data);
		$producthelper = new producthelper;
		$redshopMail   = new redshopMail;
		$user          = JFactory::getUser();

		$url        = JURI::base();
		$Itemid     = $data['Itemid'];
		$mailbcc    = null;
		$fromname   = $data['username'];
		$from       = $user->email;
		$subject    = "";
		$message    = $data['title'];
		$comment    = $data['comment'];
		$username   = $data['username'];
		$product_id = $data['product_id'];

		$mailbody = $redshopMail->getMailtemplate(0, "review_mail");

		$data_add = $message;

		if (count($mailbody) > 0)
		{
			$data_add = $mailbody[0]->mail_body;
			$subject  = $mailbody[0]->mail_subject;

			if (trim($mailbody[0]->mail_bcc) != "")
			{
				$mailbcc = explode(",", $mailbody[0]->mail_bcc);
			}
		}

		$product = $producthelper->getProductById($product_id);

		$link        = JRoute::_($url . "index.php?option=com_redshop&view=product&pid=" . $product_id . '&Itemid=' . $Itemid);
		$product_url = "<a href=" . $link . ">" . $product->product_name . "</a>";
		$data_add    = str_replace("{product_link}", $product_url, $data_add);
		$data_add    = str_replace("{product_name}", $product->product_name, $data_add);
		$data_add    = str_replace("{title}", $message, $data_add);
		$data_add    = str_replace("{comment}", $comment, $data_add);
		$data_add    = str_replace("{username}", $username, $data_add);

		if (ADMINISTRATOR_EMAIL != "")
		{
			$sendto = explode(",", ADMINISTRATOR_EMAIL);

			if (JFactory::getMailer()->sendMail($from, $fromname, $sendto, $subject, $data_add, $mode = 1, null, $mailbcc))
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

	public function getuserfullname($uid)
	{
		$db = JFactory::getDbo();

		$query = "SELECT firstname,lastname from #__redshop_users_info WHERE user_id=" . (int) $uid . " AND address_type like 'BT'";
		$db->setQuery($query);
		$userfullname = $db->loadObject();

		return $userfullname;
	}

	public function checkRatedProduct($pid, $uid)
	{
		$db    = JFactory::getDbo();
		$query = "SELECT count(*) as rec from #__redshop_product_rating WHERE product_id=" . (int) $pid . " AND userid=" . (int) $uid;
		$db->setQuery($query);
		$already_rated = $db->loadResult();

		return $already_rated;
	}
}
