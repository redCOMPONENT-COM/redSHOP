<?php
/**
 * @package     RedShop
 * @subpackage  Order
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Mail;

/**
 * @package     Redshop\Mail
 *
 * @since       2.1.0
 */
class Giftcard
{
	/**
	 * @param   integer $orderId Order id
	 *
	 * @return  boolean
	 *
	 * @since   2.1.0
	 * @throws  \Exception
	 */
	public static function sendMail($orderId)
	{
		$giftCardTemplate = self::getTemplate();
		$productHelper    = \productHelper::getInstance();
		$giftCards        = \RedshopHelperOrder::giftCardItems((int) $orderId);
		$giftcardData = '';
		$giftCardPrice = '';
		$giftCardValue = '';
		$userFields = '';
		$giftCode = '';

		if (empty($giftCards))
		{
			return false;
		}

		foreach ($giftCards as $eachOrder)
		{
			for ($i = 0; $i < $eachOrder->product_quantity; $i++)
			{
				$giftcardData = \RedshopEntityGiftcard::getInstance($eachOrder->product_id)->getItem();
				$giftCardValue = \RedshopHelperProductPrice::formattedPrice($giftcardData->giftcard_value, true);
				$giftCardPrice = $eachOrder->product_final_price;
				$giftCode = \Redshop\Crypto\Helper\Encrypt::generateCustomRandomEncryptKey(12);
				$userFields = $productHelper->GetProdcutUserfield($eachOrder->order_item_id, 13);

				/** @var \RedshopTableCoupon $couponItems */
				$couponItem = \RedshopTable::getAdminInstance('Coupon');

				if ($giftcardData->customer_amount)
				{
					$giftcardData->giftcard_value = $eachOrder->product_final_price;
				}

				$couponEndDate = mktime(0, 0, 0, date('m'), date('d') + $giftcardData->giftcard_validity, date('Y'));

				$couponItem->id = null;
				$couponItem->code = $giftCode;
				$couponItem->type = 0;
				$couponItem->value = $giftcardData->giftcard_value;
				$couponItem->start_date = \JFactory::getDate()->toSql();
				$couponItem->end_date = $couponEndDate === false ? \JFactory::getDbo()->getNullDate() : \JFactory::getDate($couponEndDate)->toSql();
				$couponItem->effect = 0;
				$couponItem->userid = 0;
				$couponItem->amount_left = 1;
				$couponItem->published = 1;
				$couponItem->free_shipping = $giftcardData->free_shipping;

				try
				{
					$couponItem->store();
				}
				catch (\Exception $exception)
				{
					throw new \Exception($exception->getMessage());
				}
			}

			$mailSubject = $giftCardTemplate->mail_subject;
			$mailSubject = str_replace('{giftcard_name}', $giftcardData->giftcard_name, $mailSubject);
			$mailSubject = str_replace('{giftcard_price}', \RedshopHelperProductPrice::formattedPrice($giftCardPrice), $mailSubject);
			$mailSubject = str_replace('{giftcard_value}', $giftCardValue, $mailSubject);
			$mailSubject = str_replace('{giftcard_validity}', $giftcardData->giftcard_validity, $mailSubject);

			$mailBody = $giftCardTemplate->mail_body;
			$mailBody = str_replace('{giftcard_name}', $giftcardData->giftcard_name, $mailBody);
			$mailBody = str_replace("{product_userfields}", $userFields, $mailBody);
			$mailBody = str_replace("{giftcard_price_lbl}", \JText::_('LIB_REDSHOP_GIFTCARD_PRICE_LBL'), $mailBody);
			$mailBody = str_replace("{giftcard_price}", \RedshopHelperProductPrice::formattedPrice($giftCardPrice), $mailBody);
			$mailBody = str_replace("{giftcard_reciver_name_lbl}", \JText::_('LIB_REDSHOP_GIFTCARD_RECIVER_NAME_LBL'), $mailBody);
			$mailBody = str_replace("{giftcard_reciver_email_lbl}", \JText::_('LIB_REDSHOP_GIFTCARD_RECIVER_EMAIL_LBL'), $mailBody);
			$mailBody = str_replace("{giftcard_reciver_email}", $eachOrder->giftcard_user_email, $mailBody);
			$mailBody = str_replace("{giftcard_reciver_name}", $eachOrder->giftcard_user_name, $mailBody);
			$mailBody = $productHelper->getValidityDate($giftcardData->giftcard_validity, $mailBody);
			$mailBody = str_replace("{giftcard_value}", $giftCardValue, $mailBody);
			$mailBody = str_replace("{giftcard_value_lbl}", \JText::_('LIB_REDSHOP_GIFTCARD_VALUE_LBL'), $mailBody);
			$mailBody = str_replace("{giftcard_desc}", $giftcardData->giftcard_desc, $mailBody);
			$mailBody = str_replace("{giftcard_validity}", $giftcardData->giftcard_validity, $mailBody);
			$mailBody = str_replace("{giftcard_code_lbl}", \JText::_('LIB_REDSHOP_GIFTCARD_CODE_LBL'), $mailBody);
			$mailBody = str_replace("{giftcard_code}", $giftCode, $mailBody);

			ob_flush();
			ob_clean();

			echo "<div id='redshopcomponent' class='redshop'>";

			$giftCardAttachment = null;
			$pdfImage           = '';
			$mailImage          = '';

			if ($giftcardData->giftcard_image && file_exists(REDSHOP_FRONT_IMAGES_RELPATH . 'giftcard/' . $giftcardData->giftcard_image))
			{
				$pdfImage  = '<img src="' . REDSHOP_FRONT_IMAGES_RELPATH . 'giftcard/' . $giftcardData->giftcard_image . '" alt="test alt attribute" width="150px" height="150px" border="0" />';
				$mailImage = '<img src="components/com_redshop/assets/images/giftcard/' . $giftcardData->giftcard_image . '" alt="test alt attribute" width="150px" height="150px" border="0" />';
			}

			if (\RedshopHelperPdf::isAvailablePdfPlugins())
			{
				$pdfMailBody = $mailBody;
				$pdfMailBody = str_replace("{giftcard_image}", $pdfImage, $pdfMailBody);

				\JPluginHelper::importPlugin('redshop_pdf');
				$backgroundImage = '';

				$pdfFile = \RedshopHelperUtility::getDispatcher()->trigger(
					'onRedshopCreateGiftCardPdf',
					array($giftcardData, $pdfMailBody, $backgroundImage)
				);

				if (!empty($pdfFile))
				{
					$giftCardAttachment = JPATH_SITE . '/components/com_redshop/assets/orders/' . $pdfFile[0] . ".pdf";
				}
			}

			$mailBody = str_replace("{giftcard_image}", $mailImage, $mailBody);
			Helper::imgInMail($mailBody);

			self::executeSendMail($eachOrder->giftcard_user_email, $mailSubject, $mailBody, $giftCardAttachment);
		}

		return true;
	}

	/**
	 *
	 * @return array
	 *
	 * @since  2.1.0
	 */
	protected static function getTemplate()
	{
		$giftCardTemplate = Helper::getTemplate(0, "giftcard_mail");

		if (!empty($giftCardTemplate))
		{
			$giftCardTemplate = $giftCardTemplate[0];
		}

		return $giftCardTemplate;
	}

	/**
	 * @param   string $receipt     Receipt email
	 * @param   string $mailSubject Subject
	 * @param   string $mailBody    Body
	 * @param   string $attachment  File attachment
	 *
	 * @return boolean
	 *
	 * @since  2.1.0
	 */
	protected static function executeSendMail($receipt, $mailSubject, $mailBody, $attachment)
	{
		$config   = \JFactory::getConfig();
		$from     = $config->get('mailfrom');
		$fromname = $config->get('fromname');

		return \JFactory::getMailer()->sendMail(
			$from, $fromname, $receipt, $mailSubject, $mailBody, 1, null, null, $attachment
		);
	}
}
