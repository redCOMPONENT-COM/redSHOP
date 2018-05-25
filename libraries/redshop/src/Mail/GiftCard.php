<?php
/**
 * @package     RedShop
 * @subpackage  Order
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Mail;

use function str_replace;

defined('_JEXEC') or die;

/**
 * Mail Gift Card helper
 *
 * @since  __DEPLOY_VERSION__
 */
class GiftCard
{
	/**
	 * Method for send giftcard email to customer.
	 *
	 * @param   integer $orderId ID of order.
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 *
	 * @throws  \Exception
	 */
	public static function sendMail($orderId)
	{
		$mail = Helper::getTemplate(0, 'giftcard_mail');

		if (count($mail) > 0)
		{
			$mail = $mail[0];
		}

		$giftCards = \RedshopHelperOrder::giftCardItems((int) $orderId);
		$config    = \JFactory::getConfig();
		$from      = $config->get('mailfrom');
		$fromName  = $config->get('fromname');

		foreach ($giftCards as $giftCard)
		{
			self::processGiftCard($giftCard, $mail, $from, $fromName);
		}
	}

	/**
	 * Method for send giftcard email to customer.
	 *
	 * @param   object  $giftCard  Gift Card data.
	 * @param   object  $mail      Mail data.
	 * @param   string  $from      From email.
	 * @param   string  $fromName  From name
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 *
	 * @throws  \Exception
	 */
	public static function processGiftCard($giftCard, $mail, $from, $fromName)
	{
		$giftCardData   = \RedshopEntityGiftcard::getInstance($giftCard->product_id)->getItem();
		$giftcard_value = \RedshopHelperProductPrice::formattedPrice($giftCardData->giftcard_value, true);
		$giftcard_price = $giftCard->product_final_price;
		$userFields     = \productHelper::getInstance()->GetProdcutUserfield($giftCard->order_item_id, \RedshopHelperExtrafields::SECTION_GIFT_CARD_USER_FIELD);
		$mailSubject    = $mail->mail_subject;
		$mailBody       = $mail->mail_body;

		$searchs  = array(
			'{giftcard_name}',
			'{product_userfields}',
			'{giftcard_price_lbl}',
			'{giftcard_price}',
			'{giftcard_reciver_name_lbl}',
			'{giftcard_reciver_email_lbl}',
			'{giftcard_reciver_email}',
			'{giftcard_reciver_name}',
			'{giftcard_value}',
			'{giftcard_value_lbl}',
			'{giftcard_desc}',
			'{giftcard_validity}'
		);
		$replaces = array(
			$giftCardData->giftcard_name,
			$userFields,
			\JText::_('LIB_REDSHOP_GIFTCARD_PRICE_LBL'),
			\RedshopHelperProductPrice::formattedPrice($giftcard_price),
			\JText::_('LIB_REDSHOP_GIFTCARD_RECIVER_NAME_LBL'),
			\JText::_('LIB_REDSHOP_GIFTCARD_RECIVER_EMAIL_LBL'),
			$giftCard->giftcard_user_email,
			$giftCard->giftcard_user_name,
			$giftcard_value,
			\JText::_('LIB_REDSHOP_GIFTCARD_VALUE_LBL'),
			$giftCardData->giftcard_desc,
			$giftCardData->giftcard_validity
		);

		$mailBody = str_replace($searchs, $replaces, $mailBody);
		$mailBody = \productHelper::getInstance()->getValidityDate($giftCardData->giftcard_validity, $mailBody);
		$giftCode = \Redshop\Crypto\Helper\Encrypt::generateCustomRandomEncryptKey(12);

		/** @var \RedshopTableCoupon $couponItems */
		$couponItems = \RedshopTable::getAdminInstance('Coupon');

		if ($giftCardData->customer_amount)
		{
			$giftCardData->giftcard_value = $giftCard->product_final_price;
		}

		$couponEndDate = mktime(0, 0, 0, date('m'), date('d') + $giftCardData->giftcard_validity, date('Y'));

		$couponItems->code          = $giftCode;
		$couponItems->type          = 0;
		$couponItems->value         = $giftCardData->giftcard_value;
		$couponItems->start_date    = \JFactory::getDate()->toSql();
		$couponItems->end_date      = $couponEndDate === false ? \JFactory::getDbo()->getNullDate() : \JFactory::getDate($couponEndDate)->toSql();
		$couponItems->effect        = 0;
		$couponItems->userid        = 0;
		$couponItems->amount_left   = 1;
		$couponItems->published     = 1;
		$couponItems->free_shipping = $giftCardData->free_shipping;

		if (!$couponItems->store())
		{
			return;
		}

		$mailBody = str_replace(
			array('{giftcard_code_lbl}', '{giftcard_code}'),
			array(\JText::_('LIB_REDSHOP_GIFTCARD_CODE_LBL'), $giftCode),
			$mailBody
		);

		ob_flush();
		ob_clean();
		echo '<div id="redshopcomponent" class="redshop">';
		$giftcard_attachment = null;
		$pdfImage            = '';
		$mailImage           = '';

		if ($giftCardData->giftcard_image && file_exists(REDSHOP_FRONT_IMAGES_RELPATH . 'giftcard/' . $giftCardData->giftcard_image))
		{
			$pdfImage  = '<img src="' . REDSHOP_FRONT_IMAGES_RELPATH . 'giftcard/' . $giftCardData->giftcard_image . '" alt="test alt attribute" width="150px" height="150px" border="0" />';
			$mailImage = '<img src="components/com_redshop/assets/images/giftcard/' . $giftCardData->giftcard_image . '" alt="test alt attribute" width="150px" height="150px" border="0" />';
		}

		if (\RedshopHelperPdf::isAvailablePdfPlugins())
		{
			$pdfMailBody = $mailBody;
			$pdfMailBody = str_replace('{giftcard_image}', $pdfImage, $pdfMailBody);

			\JPluginHelper::importPlugin('redshop_pdf');

			$pdfFile = \RedshopHelperUtility::getDispatcher()->trigger(
				'onRedshopCreateGiftCardPdf',
				array($giftCardData, $pdfMailBody, $giftCardData->giftcard_bgimage)
			);

			if (!empty($pdfFile))
			{
				$giftcard_attachment = JPATH_SITE . '/components/com_redshop/assets/orders/' . $pdfFile[0] . '.pdf';
			}
		}

		$mailBody = str_replace('{giftcard_image}', $mailImage, $mailBody);
		Helper::imgInMail($mailBody);

		\JFactory::getMailer()->sendMail(
			$from, $fromName, $giftCard->giftcard_user_email, $mailSubject, $mailBody, 1, null, null, $giftcard_attachment
		);
	}
}
