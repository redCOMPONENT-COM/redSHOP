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
 * Class send_friendModelsend_friend
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 * @since       1.0
 */
class RedshopModelSend_friend extends RedshopModel
{
	public $_id = null;

	public $_data = null;

	// Product data
	public $_product = null;

	public $_table_prefix = null;

	public $_template = null;

	public function __construct()
	{
		parent::__construct();

		$this->_table_prefix = '#__redshop_';

		$this->setId((int) JFactory::getApplication()->input->getInt('pid', 0));
	}

	public function setId($id)
	{
		$this->_id   = $id;
		$this->_data = null;
	}

	public function sendProductMailToFriend($your_name, $friend_name, $product_id, $email)
	{
		$producthelper = productHelper::getInstance();
		$redshopMail   = redshopMail::getInstance();
		$url           = JURI::base();

		$mailinfo = $redshopMail->getMailtemplate(0, "product");
		$data_add = "";
		$subject  = "";
		$mailbcc  = null;

		if (count($mailinfo) > 0)
		{
			$data_add = $mailinfo[0]->mail_body;
			$subject  = $mailinfo[0]->mail_subject;

			if (trim($mailinfo[0]->mail_bcc) != "")
			{
				$mailbcc = explode(",", $mailinfo[0]->mail_bcc);
			}
		}
		else
		{
			$data_add = "<p>Hi {friend_name} ,</p>\r\n<p>New Product  : {product_name}</p>\r\n<p>{product_desc} Please check this link : {product_url}</p>\r\n<p> </p>\r\n<p> </p>";
			$subject  = "Send to friend";
		}

		$data_add = str_replace("{friend_name}", $friend_name, $data_add);
		$data_add = str_replace("{your_name}", $your_name, $data_add);

		$product = $producthelper->getProductById($product_id);

		$data_add = str_replace("{product_name}", $product->product_name, $data_add);
		$data_add = str_replace("{product_desc}", $product->product_desc, $data_add);

		$rlink       = JRoute::_($url . "index.php?option=com_redshop&view=product&pid=" . $product_id);
		$product_url = "<a href=" . $rlink . ">" . $rlink . "</a>";
		$data_add    = str_replace("{product_url}", $product_url, $data_add);
		$data_add = $redshopMail->imginmail($data_add);

		$config   = JFactory::getConfig();
		$from     = $config->get('mailfrom');
		$fromname = $config->get('fromname');

		$subject = str_replace("{product_name}", $product->product_name, $subject);
		$subject = str_replace("{shopname}", Redshop::getConfig()->get('SHOP_NAME'), $subject);

		if ($email != "")
		{
			if (JFactory::getMailer()->sendMail($from, $fromname, $email, $subject, $data_add, 1, null, $mailbcc))
			{
				echo "<div class='' align='center'>" . JText::_('COM_REDSHOP_EMAIL_HAS_BEEN_SENT_SUCCESSFULLY') . "</div>";
			}
			else
			{
				echo "<div class='' align='center'>" . JText::_('COM_REDSHOP_EMAIL_HAS_NOT_BEEN_SENT_SUCCESSFULLY') . "</div>";
			}
		}

		JFactory::getApplication()->close();
	}
}
