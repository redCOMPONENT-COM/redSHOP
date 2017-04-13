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
 * Class ask question model
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 * @since       1.0
 */
class RedshopModelAsk_Question extends RedshopModelForm
{
	protected $context = 'com_redshop.ask_question';

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
		$form = $this->loadForm('com_redshop.ask_question', 'ask_question', array('control' => 'jform', 'load_data' => $loadData));

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
		$data = (array) JFactory::getApplication()->getUserState('com_redshop.ask_question.data', array());

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
		$user                  = JFactory::getUser();
		$data['user_id']       = $user->id;
		$data['user_name']     = $data['your_name'];
		$data['user_email']    = $data['your_email'];
		$data['question']      = $data['your_question'];
		$data['published']     = 1;
		$data['question_date'] = time();

		$row              = $this->getTable('Question');

		$data['ordering'] = $this->MaxOrdering();

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
	 * Method to get max ordering
	 *
	 * @access public
	 *
	 * @return boolean
	 */
	public function MaxOrdering()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('MAX(ordering)+1')
			->from($db->qn('#__redshop_customer_question'))
			->where('parent_id = 0');

		return $db->setQuery($query)->loadResult();
	}

	/**
	 * Send Mail For Ask Question
	 *
	 * @param   array  $data  Question data
	 *
	 * @return  bool
	 */
	public function sendMailForAskQuestion($data)
	{
		if (!$this->store($data))
		{
			return false;
		}

		$redshopMail = redshopMail::getInstance();
		$Itemid     = $data['Itemid'];
		$mailbcc    = null;
		$subject    = '';
		$message    = $data['your_question'];
		$productId  = $data['product_id'];
		$mailbody   = $redshopMail->getMailtemplate(0, 'ask_question_mail');
		$data_add   = $message;

		if (count($mailbody) > 0)
		{
			$data_add = $mailbody[0]->mail_body;
			$subject  = $mailbody[0]->mail_subject;

			if (trim($mailbody[0]->mail_bcc) != '')
			{
				$mailbcc = explode(',', $mailbody[0]->mail_bcc);
			}
		}

		$product = RedshopHelperProduct::getProductById($productId);
		$data_add = str_replace('{product_name}', $product->product_name, $data_add);
		$data_add = str_replace('{product_desc}', $product->product_desc, $data_add);

		// Init required properties
		$data['address']   = isset($data['address']) ? $data['address'] : null;
		$data['telephone'] = isset($data['telephone']) ? $data['telephone'] : null;

		$link        = JRoute::_(JURI::base() . 'index.php?option=com_redshop&view=product&pid=' . $productId . '&Itemid=' . $Itemid);
		$data_add    = str_replace('{product_link}', '<a href="' . $link . '">' . $product->product_name . '</a>', $data_add);
		$data_add    = str_replace('{user_question}', $message, $data_add);
		$data_add    = str_replace('{answer}', '', $data_add);
		$subject     = str_replace('{user_question}', $message, $subject);
		$subject     = str_replace('{shopname}', Redshop::getConfig()->get('SHOP_NAME'), $subject);
		$data_add    = str_replace('{user_address}', $data['address'], $data_add);
		$data_add    = str_replace('{user_telephone}', $data['telephone'], $data_add);
		$data_add    = str_replace('{user_telephone_lbl}', JText::_('COM_REDSHOP_USER_PHONE_LBL'), $data_add);
		$data_add    = str_replace('{user_address_lbl}', JText::_('COM_REDSHOP_USER_ADDRESS_LBL'), $data_add);
		$data_add = $redshopMail->imginmail($data_add);

		if (Redshop::getConfig()->get('ADMINISTRATOR_EMAIL') != '')
		{
			if (JFactory::getMailer()->sendMail(
				$data['your_email'], $data['your_name'], explode(',', Redshop::getConfig()->get('ADMINISTRATOR_EMAIL')),
				$subject, $data_add, $mode = 1, null, $mailbcc
			))
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
}
