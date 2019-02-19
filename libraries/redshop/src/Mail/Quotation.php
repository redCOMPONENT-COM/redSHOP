<?php
/**
 * @package     RedShop
 * @subpackage  Order
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Mail;

defined('_JEXEC') or die;

/**
 * Mail Quotation helper
 *
 * @since  2.1.0
 */
class Quotation
{
	/**
	 * Use absolute paths instead of relative ones when linking images
	 *
	 * @param   integer  $quotationId  Quotation id
	 * @param   integer  $status       Status
	 *
	 * @return  boolean
	 * @throws  \Exception
	 *
	 * @since   2.1.0
	 */
	public static function sendMail($quotationId, $status = 0)
	{
		$mailSection  = "quotation_mail";
		$mailTemplate = Helper::getTemplate(0, $mailSection);

		if (empty($mailTemplate) || !$quotationId)
		{
			return false;
		}

		$mailTemplate = $mailTemplate[0];
		$mailBcc      = array();
		$message      = $mailTemplate->mail_body;
		$subject      = $mailTemplate->mail_subject;

		if (trim($mailTemplate->mail_bcc) != "")
		{
			$mailBcc = explode(",", $mailTemplate->mail_bcc);
		}

		$templateStart  = "";
		$templateEnd    = "";
		$templateMiddle = "";
		$cart           = "";
		$templateSdata  = explode('{product_loop_start}', $message);
		$fieldArray     = \RedshopHelperExtrafields::getSectionFieldList(\RedshopHelperExtrafields::SECTION_PRODUCT_FINDER_DATE_PICKER, 0, 0);

		if (count($templateSdata) > 0)
		{
			$templateStart = $templateSdata[0];

			if (count($templateSdata) > 1)
			{
				$templateEdata = explode('{product_loop_end}', $templateSdata[1]);

				if (count($templateEdata) > 1)
				{
					$templateEnd = $templateEdata[1];
				}

				if (count($templateEdata) > 0)
				{
					$templateMiddle = $templateEdata[0];
				}
			}
		}

		$quotation = \RedshopHelperQuotation::getQuotationDetail($quotationId);

		if (!$quotation)
		{
			return false;
		}

		$quotationProducts = \RedshopHelperQuotation::getQuotationProduct($quotationId);

		foreach ($quotationProducts as $quotationProduct)
		{
			$productId                = $quotationProduct->product_id;
			$product                  = \Redshop::product((int) $productId);
			$productName              = "<div class='product_name'>" . $quotationProduct->product_name . "</div>";
			$productTotalPrice        = "<div class='product_price'>" .
				\RedshopHelperProductPrice::formattedPrice(($quotationProduct->product_price * $quotationProduct->product_quantity)) . "</div>";
			$productPrice             = "<div class='product_price'>" .
				\RedshopHelperProductPrice::formattedPrice($quotationProduct->product_price) . "</div>";
			$productPriceExclVat      = "<div class='product_price'>" .
				\RedshopHelperProductPrice::formattedPrice($quotationProduct->product_excl_price) . "</div>";
			$productQuantity          = '<div class="update_cart">' . $quotationProduct->product_quantity . '</div>';
			$productTotalPriceExclVat = "<div class='product_price'>" .
				\RedshopHelperProductPrice::formattedPrice(($quotationProduct->product_excl_price * $quotationProduct->product_quantity)) . "</div>";

			$cartMdata   = $templateMiddle;
			$wrapperName = "";

			if ($quotationProduct->product_wrapperid)
			{
				$wrapper = \productHelper::getInstance()->getWrapper($productId, $quotationProduct->product_wrapperid);

				if (count($wrapper) > 0)
				{
					$wrapperName = $wrapper[0]->wrapper_name;
				}

				$wrapperName = \JText::_('COM_REDSHOP_WRAPPER') . ": " . $wrapperName;
			}

			$productImagePath = '';

			if ($product->product_full_image)
			{
				if (\JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $product->product_full_image))
				{
					$productImagePath = $product->product_full_image;
				}
				else
				{
					if (\JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . \Redshop::getConfig()->get('PRODUCT_DEFAULT_IMAGE')))
					{
						$productImagePath = \Redshop::getConfig()->get('PRODUCT_DEFAULT_IMAGE');
					}
				}
			}
			else
			{
				if (\JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . \Redshop::getConfig()->get('PRODUCT_DEFAULT_IMAGE')))
				{
					$productImagePath = \Redshop::getConfig()->get('PRODUCT_DEFAULT_IMAGE');
				}
			}

			if ($productImagePath)
			{
				$thumbUrl     = \RedshopHelperMedia::getImagePath(
					$productImagePath,
					'',
					'thumb',
					'product',
					\Redshop::getConfig()->get('CART_THUMB_WIDTH'),
					\Redshop::getConfig()->get('CART_THUMB_HEIGHT'),
					\Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
				);
				$productImage = "<div  class='product_image'><img src='" . $thumbUrl . "'></div>";
			}
			else
			{
				$productImage = "<div  class='product_image'></div>";
			}

			$cartMdata = str_replace("{product_name}", $productName, $cartMdata);
			$cartMdata = str_replace("{product_s_desc}", $product->product_s_desc, $cartMdata);
			$cartMdata = str_replace("{product_thumb_image}", $productImage, $cartMdata);

			$productNote       = "<div class='product_note'>" . $wrapperName . "</div>";
			$cartMdata         = str_replace("{product_wrapper}", $productNote, $cartMdata);
			$productUserFields = \RedshopHelperQuotation::displayQuotationUserField($quotationProduct->quotation_item_id, 12);

			$cartMdata = str_replace("{product_userfields}", $productUserFields, $cartMdata);
			$cartMdata = str_replace("{product_number_lbl}", \JText::_('COM_REDSHOP_PRODUCT_NUMBER'), $cartMdata);
			$cartMdata = str_replace("{product_number}", $product->product_number, $cartMdata);
			$cartMdata = str_replace(
				"{product_attribute}",
				\productHelper::getInstance()->makeAttributeQuotation(
					$quotationProduct->quotation_item_id,
					0,
					$quotationProduct->product_id,
					$quotation->quotation_status
				),
				$cartMdata
			);
			$cartMdata = str_replace(
				"{product_accessory}",
				\productHelper::getInstance()->makeAccessoryQuotation(
					$quotationProduct->quotation_item_id,
					$quotation->quotation_status
				),
				$cartMdata
			);

			// ProductFinderDatepicker Extra Field Start
			$cartMdata = \productHelper::getInstance()->getProductFinderDatepickerValue($cartMdata, $productId, $fieldArray);

			// ProductFinderDatepicker Extra Field End
			if ($quotation->quotation_status == 1 && !\Redshop::getConfig()->get('SHOW_QUOTATION_PRICE'))
			{
				$cartMdata = str_replace("{product_price_excl_vat}", "", $cartMdata);
				$cartMdata = str_replace("{product_price}", " ", $cartMdata);
				$cartMdata = str_replace("{product_total_price}", " ", $cartMdata);
				$cartMdata = str_replace("{product_subtotal_excl_vat}", " ", $cartMdata);
			}
			else
			{
				$cartMdata = str_replace("{product_price_excl_vat}", $productPriceExclVat, $cartMdata);
				$cartMdata = str_replace("{product_price}", $productPrice, $cartMdata);
				$cartMdata = str_replace("{product_total_price}", $productTotalPrice, $cartMdata);
				$cartMdata = str_replace("{product_subtotal_excl_vat}", $productTotalPriceExclVat, $cartMdata);
			}

			$cartMdata = str_replace("{product_quantity}", $productQuantity, $cartMdata);
			$cart     .= $cartMdata;
		}

		// End for

		$message = $templateStart . $cart . $templateEnd;
		$search  = array('{quotation_note}', '{shopname}', '{quotation_id}', '{quotation_number}', '{quotation_date}', '{quotation_status}');
		$replace = array(
			$quotation->quotation_note,
			\Redshop::getConfig()->get('SHOP_NAME'),
			$quotation->quotation_id,
			$quotation->quotation_number,
			\RedshopHelperDatetime::convertDateFormat($quotation->quotation_cdate),
			\RedshopHelperQuotation::getQuotationStatusName($quotation->quotation_status)
		);

		$billAdd = '';

		if ($quotation->user_id != 0)
		{
			$message = \RedshopHelperBillingTag::replaceBillingAddress($message, $quotation, true);
		}
		else
		{
			if ($quotation->quotation_email != "")
			{
				$billAdd .= \JText::_("COM_REDSHOP_EMAIL") . ' : ' . $quotation->quotation_email . '<br />';
			}

			$message = str_replace("{billing_address_information_lbl}", \JText::_('COM_REDSHOP_BILLING_ADDRESS_INFORMATION_LBL'), $message);

			if (strstr($message, "{quotation_custom_field_list}"))
			{
				$billAdd .= \RedshopHelperExtrafields::listAllFieldDisplay(16, $quotation->user_info_id, 1, $quotation->quotation_email);
				$message  = str_replace("{quotation_custom_field_list}", "", $message);
			}
			else
			{
				$message = \RedshopHelperExtrafields::listAllFieldDisplay(16, $quotation->user_info_id, 1, $quotation->quotation_email, $message);
			}
		}

		$search[]    = "{billing_address}";
		$replace[]   = $billAdd;
		$totalLbl    = '';
		$subTotalLbl = '';
		$vatLbl      = '';

		if ($quotation->quotation_status != 1 || ($quotation->quotation_status == 1 && \Redshop::getConfig()->get('SHOW_QUOTATION_PRICE')))
		{
			$totalLbl    = \JText::_('COM_REDSHOP_TOTAL_LBL');
			$subTotalLbl = \JText::_('COM_REDSHOP_QUOTATION_SUBTOTAL');
			$vatLbl      = \JText::_('COM_REDSHOP_QUOTATION_VAT');
		}

		$message = str_replace('{total_lbl}', $totalLbl, $message);
		$message = str_replace('{quotation_subtotal_lbl}', $subTotalLbl, $message);
		$message = str_replace('{quotation_vat_lbl}', $vatLbl, $message);
		$message = \Redshop\Cart\Render\Label::replace($message);

		$search[]  = "{quotation_note}";
		$replace[] = $quotation->quotation_note;

		if ($quotation->quotation_status == 1 && !\Redshop::getConfig()->getBool('SHOW_QUOTATION_PRICE'))
		{
			$quotationSubtotal              = " ";
			$quotationTotal                 = " ";
			$quotationDiscount              = " ";
			$quotationVat                   = " ";
			$quotationSubtotalExclVat       = " ";
			$quotationSubtotalMinusDiscount = " ";
		}
		else
		{
			$tax = $quotation->quotation_tax;

			if ((float) \Redshop::getConfig()->get('VAT_RATE_AFTER_DISCOUNT'))
			{
				$discountVAT = (
						(float) \Redshop::getConfig()->get('VAT_RATE_AFTER_DISCOUNT') * $quotation->quotation_discount) /
					(1 + (float) \Redshop::getConfig()->get('VAT_RATE_AFTER_DISCOUNT')
					);

				$quotation->quotation_discount = $quotation->quotation_discount - $discountVAT;
				$tax                           = $tax - $discountVAT;
			}

			if (\Redshop::getConfig()->getFloat('VAT_RATE_AFTER_DISCOUNT'))
			{
				$specialDiscount = ($quotation->quotation_special_discount * ($quotation->quotation_subtotal + $quotation->quotation_tax)) / 100;

				$specialDiscountVAT = (
						$specialDiscount * (float) \Redshop::getConfig()->get('VAT_RATE_AFTER_DISCOUNT')) /
					(1 + (float) \Redshop::getConfig()->get('VAT_RATE_AFTER_DISCOUNT')
					);

				$specialDiscountNoVAT = $specialDiscount - $specialDiscountVAT;

				$quotation->quotation_discount = $quotation->quotation_discount + $specialDiscountNoVAT;
			}

			$quotationSubtotalExclVat       = \RedshopHelperProductPrice::formattedPrice($quotation->quotation_subtotal - $quotation->quotation_tax);
			$quotationSubtotalMinusDiscount = \RedshopHelperProductPrice::formattedPrice(
				$quotation->quotation_subtotal - $quotation->quotation_discount
			);
			$quotationSubtotal              = \RedshopHelperProductPrice::formattedPrice($quotation->quotation_subtotal);
			$quotationTotal                 = \RedshopHelperProductPrice::formattedPrice($quotation->quotation_total);
			$quotationDiscount              = \RedshopHelperProductPrice::formattedPrice($quotation->quotation_discount);
			$quotationVat                   = \RedshopHelperProductPrice::formattedPrice($quotation->quotation_tax);
		}

		$search[]  = "{quotation_subtotal}";
		$replace[] = $quotationSubtotal;
		$search[]  = "{quotation_total}";
		$replace[] = $quotationTotal;
		$search[]  = "{quotation_subtotal_minus_discount}";
		$replace[] = $quotationSubtotalMinusDiscount;
		$search[]  = "{quotation_subtotal_excl_vat}";
		$replace[] = $quotationSubtotalExclVat;
		$search[]  = "{quotation_discount}";
		$replace[] = $quotationDiscount;
		$search[]  = "{quotation_vat}";
		$replace[] = $quotationVat;

		$quotationDetailUrl = \JUri::root() . 'index.php?option=com_redshop&view=quotation_detail&quoid=' . $quotationId . '&encr='
			. $quotation->quotation_encrkey;

		$search[]  = "{quotation_detail_link}";
		$replace[] = "<a href='" . $quotationDetailUrl . "'>" . \JText::_("COM_REDSHOP_QUOTATION_DETAILS") . "</a>";

		$message = str_replace($search, $replace, $message);

		Helper::imgInMail($message);

		$email = $quotation->quotation_email;

		// Set the e-mail parameters
		$from     = \JFactory::getConfig()->get('mailfrom');
		$fromname = \JFactory::getConfig()->get('fromname');
		$body     = $message;
		$subject  = str_replace($search, $replace, $subject);

		// Send the e-mail

		if ($email != "")
		{
			$bcc = array();

			if (trim(\Redshop::getConfig()->getString('ADMINISTRATOR_EMAIL')) != '')
			{
				$bcc = explode(",", trim(\Redshop::getConfig()->getString('ADMINISTRATOR_EMAIL')));
			}

			$bcc = array_merge($bcc, $mailBcc);

			if (!Helper::sendEmail($from, $fromname, $email, $subject, $body, true, null, $bcc, null, $mailSection, func_get_args()))
			{
				\JError::raiseWarning(21, \JText::_('ERROR_SENDING_QUOTATION_MAIL'));
			}
		}

		if ($status != 0)
		{
			\RedshopHelperQuotation::updateQuotationStatus($quotationId, $status);
		}

		return true;
	}
}
