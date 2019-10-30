<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Send friend model
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 * @since       1.0
 */
class RedshopModelSend_Friend extends RedshopModel
{
	/**
	 * @var integer
	 */
	public $_id = null;

	/**
	 * @var null
	 */
	public $_data = null;

	/**
	 * @var null
	 */
	public $_product = null;

	/**
	 * @var string
	 */
	public $_table_prefix = null;

	/**
	 * @var null
	 */
	public $_template = null;

	/**
	 * RedshopModelSend_Friend constructor.
	 *
	 * @throws Exception
	 */
	public function __construct()
	{
		parent::__construct();

		$this->_table_prefix = '#__redshop_';

		$this->setId(JFactory::getApplication()->input->getInt('pid', 0));
	}

	/**
	 * Method for set ID
	 *
	 * @param   integer $id ID
	 *
	 * @return  void
	 */
	public function setId($id)
	{
		$this->_id   = $id;
		$this->_data = null;
	}

	/**
	 * Method for send mail to friend
	 *
	 * @param   string  $yourName   Your name
	 * @param   string  $friendName Friend name
	 * @param   integer $productId  Product ID
	 * @param   string  $email      Friend email
	 *
	 * @return  void
	 * @throws  Exception
	 */
	public function sendProductMailToFriend($yourName, $friendName, $productId, $email)
	{
		$mailTemplate = Redshop\Mail\Helper::getTemplate(0, "product");
		$mailBcc      = null;

		if (!empty($mailTemplate))
		{
			$mailBody = $mailTemplate[0]->mail_body;
			$subject  = $mailTemplate[0]->mail_subject;

			if (trim($mailTemplate[0]->mail_bcc) != "")
			{
				$mailBcc = explode(",", $mailTemplate[0]->mail_bcc);
			}
		}
		else
		{
			$mailBody = "<p>Hi {friend_name} ,</p>\r\n<p>New Product  : {product_name}</p>\r\n"
				. "<p>{product_desc} Please check this link : {product_url}</p>\r\n<p> </p>\r\n<p> </p>";
			$subject  = "Send to friend";
		}

		$mailBody = str_replace("{friend_name}", $friendName, $mailBody);
		$mailBody = str_replace("{your_name}", $yourName, $mailBody);

		$product = RedshopHelperProduct::getProductById($productId);

		$mailBody = str_replace("{product_name}", $product->product_name, $mailBody);
		$mailBody = str_replace("{product_desc}", $product->product_desc, $mailBody);

		$productLink = JRoute::_(JUri::base() . 'index.php?option=com_redshop&view=product&pid=' . $productId, false);
		$productLink = "<a href=" . $productLink . ">" . $productLink . "</a>";
		$mailBody    = str_replace("{product_url}", $productLink, $mailBody);
		Redshop\Mail\Helper::imgInMail($mailBody);

		$config   = JFactory::getConfig();
		$from     = (string) $config->get('mailfrom');
		$fromName = (string) $config->get('fromname');

		$subject = str_replace("{product_name}", $product->product_name, $subject);
		$subject = str_replace("{shopname}", Redshop::getConfig()->get('SHOP_NAME'), $subject);

		if (!empty($email))
		{
			if (JFactory::getMailer()->sendMail($from, $fromName, $email, $subject, $mailBody, 1, null, $mailBcc))
			{
				echo "<div class='' align='center'>" . JText::_('COM_REDSHOP_EMAIL_HAS_BEEN_SENT_SUCCESSFULLY') . "</div>";
			}
			else
			{
				echo "<div class='' align='center'>" . JText::_('COM_REDSHOP_EMAIL_HAS_NOT_BEEN_SENT_SUCCESSFULLY') . "</div>";
			}
		}
	}
}
