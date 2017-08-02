<?php
/**
 * @package     RedSHOP.Plugin
 * @subpackage  System.RedSHOP
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * PlgSystemRedSHOP class.
 *
 * @extends JPlugin
 * @since  1.0.0
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
			return true;
		}

		$jinput = $app->input;

		if ($jinput->get('option', '') != 'com_redshop')
		{
			return true;
		}

		if ($jinput->get('view', '') != 'voucher' && $jinput->get('view', '') != 'coupon')
		{
			return true;
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
		$productHelper = productHelper::getInstance();
		$config        = JFactory::getConfig();

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

		$email        = $jinput->getString('email', '');
		$view        = $jinput->getString('view', '');
		$discountId = $jinput->getInt('discountId', '');

		// Get Code
		$discountDetail = $this->getDiscountCode($discountId, $view);

		if (empty($discountDetail))
		{
			return true;
		}

		$value = $productHelper->getProductFormattedPrice($discountDetail->value);

		if ($discountDetail->type == '1' || $discountDetail->type == 'Percentage')
		{
			$value = number_format($discountDetail->value) . "%";
		}

		$mailBody = str_replace('{discount_code}', $discountDetail->code, $mailBody);
		$mailBody = str_replace('{discount_value}', $value, $mailBody);

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

		if (!$app->isAdmin())
		{
			return true;
		}

		$jinput = $app->input;

		if ($jinput->get('option', '') != 'com_redshop')
		{
			return true;
		}

		if ($jinput->get('view', '') != 'voucher' && $jinput->get('view', '') != 'coupon')
		{
			return true;
		}

		$render .= RedshopLayoutHelper::render(
			'form',
			array(
				'view' => $jinput->get('view', '')
			),
			JPATH_SITE . '/plugins/' . $this->_type . '/' . $this->_name . '/layouts'
		);
	}

	/**
	 * [getDiscountCode description]
	 *
	 * @param   int     $id    Discount ID
	 * @param   string  $type  Voucher or Coupon
	 *
	 * @return  string         Discount Code
	 */
	private function getDiscountCode($id, $type = "voucher")
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true);

		if ($type == "voucher")
		{
			$query->select(
					array
					(
						$db->qn('code', 'code'),
						$db->qn('amount', 'value'),
						$db->qn('type', 'type'),
					)
				)
				->from($db->qn('#__redshop_voucher'))
				->where($db->qn('id') . ' = ' . (int) $id);
		}
		else
		{
			$query->select(
					array
					(
						$db->qn('coupon_code', 'code'),
						$db->qn('coupon_value', 'value'),
						$db->qn('percent_or_total', 'type'),
					)
				)
				->from($db->qn('#__redshop_coupons'))
				->where($db->qn('coupon_id') . ' = ' . (int) $id);
		}

		return $db->setQuery($query)->loadObject();
	}
}

