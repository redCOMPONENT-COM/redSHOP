<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('joomla.application.component.model');

require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/mail.php';
require_once JPATH_COMPONENT_SITE . '/helpers/product.php';

/**
 * Class ask_questionModelask_question
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 * @since       1.0
 */
class ask_questionModelask_question extends JModel
{
	public $_id = null;

	public $_table_prefix = null;

	public function __construct()
	{
		parent::__construct();
		$this->_table_prefix = '#__redshop_';
		$this->setId((int) JRequest::getInt('pid', 0));
	}

	public function setId($id)
	{
		$this->_id = $id;
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
		$data['product_id']    = $data['pid'];
		$data['published']     = 1;
		$data['question_date'] = time();

		$row              = $this->getTable('question_detail');
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
		$query = "SELECT (MAX(ordering)+1) FROM " . $this->_table_prefix . "customer_question "
			. "WHERE parent_id=0 ";
		$this->_db->setQuery($query);

		return $this->_db->loadResult();
	}

	public function sendMailForAskQuestion($data)
	{
		$this->store($data);
		$producthelper = new producthelper;
		$redshopMail   = new redshopMail;

		$url        = JURI::base();
		$option     = JRequest::getVar('option');
		$Itemid     = JRequest::getVar('Itemid');
		$mailbcc    = null;
		$fromname   = $data['your_name'];
		$from       = $data['your_email'];
		$subject    = "";
		$message    = $data['your_question'];
		$product_id = $data['pid'];

		$mailbody = $redshopMail->getMailtemplate(0, "ask_question_mail");

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

		$data_add = str_replace("{product_name}", $product->product_name, $data_add);
		$data_add = str_replace("{product_desc}", $product->product_desc, $data_add);

		$link        = JRoute::_($url . "index.php?option=" . $option . "&view=product&pid=" . $product_id . '&Itemid=' . $Itemid);
		$product_url = "<a href=" . $link . ">" . $product->product_name . "</a>";
		$data_add    = str_replace("{product_link}", $product_url, $data_add);
		$data_add    = str_replace("{user_question}", $message, $data_add);
		$data_add    = str_replace("{answer}", "", $data_add);
		$subject     = str_replace("{user_question}", $message, $subject);
		$subject     = str_replace("{shopname}", SHOP_NAME, $subject);
		$data_add    = str_replace("{user_address}", $data['address'], $data_add);
		$data_add    = str_replace("{user_telephone}", $data['telephone'], $data_add);
		$data_add    = str_replace("{user_telephone_lbl}", JText::_('COM_REDSHOP_USER_PHONE_LBL'), $data_add);
		$data_add    = str_replace("{user_address_lbl}", JText::_('COM_REDSHOP_USER_ADDRESS_LBL'), $data_add);

		if (ADMINISTRATOR_EMAIL != "")
		{
			$sendto = explode(",", ADMINISTRATOR_EMAIL);

			if (JFactory::getMailer()->sendMail($from, $fromname, $sendto, $subject, $data_add, $mode = 1, null, $mailbcc))
			{
				return true;
			}
			else
			{
				return false;
			}
		}
	}
}
