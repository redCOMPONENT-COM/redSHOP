<?php
/**
 * @package     RedSHOP.Plugin
 * @subpackage  System.RedSHOP
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * PlgSystemRedSHOP class.
 *
 * @extends JPlugin
 * @since  1.5.0.1
 */
class PlgSystemRedSHOP_Send_Discountcode extends JPlugin
{
	/**
	 * Constructor
	 *
	 * @param   object  &$subject  The object to observe
	 * @param   array   $config    An optional associative array of configuration settings.
	 *                             Recognized key values include 'name', 'group', 'params', 'language'
	 *                             (this list is not meant to be comprehensive).
	 */
	public function __construct(&$subject, $config = array())
	{
		JLoader::import('redshop.library');
		JPlugin::loadLanguage('plg_system_redshop_send_discountcode');

		parent::__construct($subject, $config);
	}

	/**
	 * Add Send Discount Button for Menu Bar on voucher and coupon view
	 *
	 * @return  void
	 */
	public function onAfterRoute()
	{
		$app = JFactory::getApplication();

		if (!$app->isAdmin())
		{
			return;
		}

		$jinput = $app->input;

		if ($jinput->get('option', '') != 'com_redshop')
		{
			return;
		}

		if ($jinput->get('view', '') != 'voucher')
		{
			return;
		}

		JToolBarHelper::modal('popupSendDiscountCode', 'icon-send', JText::_('PLG_SYSTEM_REDSHOP_SEND_EMAIL_BUTTON'));
	}

	/**
	 * This function for send discount code by email - Call from AJAX interface of Joomla
	 *
	 * @return  bool  Send mail
	 */
	public function onAjaxRedShop_SendDiscountCodeByMail()
	{
		$config = JFactory::getConfig();

		if (!$config->get('mailonline'))
		{
			return false;
		}

		$mailBcc  = null;
		$mailInfo = RedshopHelperMail::getMailTemplate(0, "send_discount_code_mail");

		if (empty($mailInfo))
		{
			return false;
		}

		$mailBody = $mailInfo[0]->mail_body;
		$subject = $mailInfo[0]->mail_subject;

		$from     = $config->get('mailfrom');
		$fromName = $config->get('fromname');

		if (trim($mailInfo[0]->mail_bcc) != "")
		{
			$mailBcc = explode(",", $mailInfo[0]->mail_bcc);
		}

		$mailBody = RedshopHelperMail::imgInMail($mailBody);

		$app = JFactory::getApplication();
		$jinput = $app->input;

		$email        = $jinput->get('email', '');
		$discountId = $jinput->get('discountId', '');

		// Get Code


		$mailBody = str_replace('{discount_code}', $discountCode, $mailBody);

		if (!RedshopHelperMail::sendEmail($from, $fromName, $email, $subject, $mailBody, true, null, $mailBcc))
		{
			JError::raiseWarning(21, JText::_('COM_REDSHOP_ERROR_SENDING_CONFIRMATION_MAIL'));

			return false;
		}

		return true;
	}

	/**
	 * This function add more Mail Sections on redSHOP
	 *
	 * @param   array  &$options  Mail Sections
	 *
	 * @return  void
	 */
	public function onMailSections(&$options)
	{
		$options['send_discount_code_mail'] = JText::_('PLG_SYSTEM_REDSHOP_SEND_DISCOUNT_CODE_EMAIL_SECTION');
	}

	/**
	 * Load a layout for modal button on onAfterRoute function
	 *
	 * @param   string  &$render  Render of layout
	 *
	 * @return  void
	 */
	public function onRedshopAdminRender(&$render)
	{
		$app = JFactory::getApplication();
		$jinput = $app->input;

		$render .= RedshopLayoutHelper::render(
			'form',
			array(
				'view' => $jinput->get('view', '')
			),
			JPATH_SITE . '/plugins/' . $this->_type . '/' . $this->_name . '/layouts'
		);
	}
}
