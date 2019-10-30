<?php
/**
 * @package     RedShop
 * @subpackage  Order
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Order;

use Joomla\Registry\Registry;

defined('_JEXEC') or die;

/**
 * Order helper
 *
 * @since  2.1.0
 */
class Template
{
	/**
	 * Method for replace template order
	 *
	 * @param   object  $row      Order data.
	 * @param   string  $template Template content.
	 * @param   boolean $sendMail In send mail
	 *
	 * @return  string
	 *
	 * @throws  \Exception
	 *
	 * @since   2.1.0
	 */
	public static function replaceTemplate($row, $template, $sendMail = false)
	{
		$orderEntity = \RedshopEntityOrder::getInstance($row->order_id)->bind($row);

		if (!$orderEntity->isValid())
		{
			return $template;
		}

		$orderId            = (int) $orderEntity->get('order_id');
		$subTotalExcludeVAT = 0.0;

		// Replace products
		self::replaceProducts($template, $subTotalExcludeVAT, $orderId, $sendMail);

		$search  = array();
		$replace = array();

		// Replace payment
		self::replacePayment($template, $orderEntity);

		// Replace shipping
		self::replaceShipping($template, $orderEntity);

		$totalExcludeVAT = $subTotalExcludeVAT + ($row->order_shipping - $row->order_shipping_tax)
			- ($row->order_discount - $row->order_discount_vat);
		$subTotalVAT     = $row->order_tax + $row->order_shipping_tax;

		$row->voucher_discount = (!isset($row->voucher_discount)) ? 0 : $row->voucher_discount;

		$totalDiscount    = $row->coupon_discount + $row->order_discount + $row->special_discount + $row->tax_after_discount + $row->voucher_discount;
		$totalForDiscount = !\Redshop::getConfig()->get('APPLY_VAT_ON_DISCOUNT') ? $subTotalExcludeVAT : $row->order_subtotal;

		$template   = \Redshop\Cart\Render\Label::replace($template);
		$isApplyVAT = \Redshop\Template\Helper::isApplyVat($template);

		// Order sub-total
		$search[] = "{order_subtotal}";

		if (!empty($isApplyVAT))
		{
			$replace[] = \RedshopHelperProductPrice::formattedPrice($row->order_total);
		}
		else
		{
			$replace[] = \RedshopHelperProductPrice::formattedPrice($totalExcludeVAT);
		}

		$search[]  = "{subtotal_excl_vat}";
		$replace[] = \RedshopHelperProductPrice::formattedPrice($totalExcludeVAT);

		$search[] = "{product_subtotal}";

		if (!empty($isApplyVAT))
		{
			$replace[] = \RedshopHelperProductPrice::formattedPrice($row->order_subtotal);
		}
		else
		{
			$replace[] = \RedshopHelperProductPrice::formattedPrice($subTotalExcludeVAT);
		}

		// Replace Tracking
		$search[]      = "{tracking_number_lbl}";
		$replace[]     = \JText::_('COM_REDSHOP_ORDER_TRACKING_NUMBER');
		$search[]      = "{tracking_number}";
		$replace[]     = $row->track_no;
		$orderTrackURL = '';

		\JPluginHelper::importPlugin('redshop_shipping');
		\RedshopHelperUtility::getDispatcher()->trigger('onReplaceTrackingUrl', array($row->order_id, &$orderTrackURL));

		if ($row->track_no)
		{
			$search[]  = "{tracking_url}";
			$replace[] = "<a href='" . $orderTrackURL . "'>" . \JText::_("COM_REDSHOP_TRACK_LINK_LBL") . "</a>";
		}
		else
		{
			$search[]  = "{tracking_url}";
			$replace[] = "";
		}

		$search[]  = "{product_subtotal_excl_vat}";
		$replace[] = \RedshopHelperProductPrice::formattedPrice($subTotalExcludeVAT);
		$search[]  = "{order_subtotal_excl_vat}";
		$replace[] = \RedshopHelperProductPrice::formattedPrice($totalExcludeVAT);
		$search[]  = "{order_number_lbl}";
		$replace[] = \JText::_('COM_REDSHOP_ORDER_NUMBER_LBL');
		$search[]  = "{order_number}";
		$replace[] = $row->order_number;
		$search[]  = "{special_discount}";
		$replace[] = $row->special_discount . '%';
		$search[]  = "{special_discount_amount}";
		$replace[] = \RedshopHelperProductPrice::formattedPrice($row->special_discount_amount);
		$search[]  = "{special_discount_lbl}";
		$replace[] = \JText::_('COM_REDSHOP_SPECIAL_DISCOUNT');

		$orderDetailUrl = \JUri::root() . 'index.php?option=com_redshop&view=order_detail&oid=' . $orderId . '&encr=' . $row->encr_key;
		$search[]       = "{order_detail_link}";
		$replace[]      = "<a href='" . $orderDetailUrl . "'>" . \JText::_("COM_REDSHOP_ORDER_MAIL") . "</a>";

		// Replace product downloads
		self::replaceDownloadProducts($template, $orderId);

		if ((strpos($template, "{discount_denotation}") !== false || strpos($template, "{shipping_denotation}") !== false)
			&& ($totalDiscount != 0 || $row->order_shipping != 0))
		{
			$search[]  = "{denotation_label}";
			$replace[] = \JText::_('COM_REDSHOP_DENOTATION_TXT');
		}
		else
		{
			$search[]  = "{denotation_label}";
			$replace[] = "";
		}

		$search[] = "{discount_denotation}";

		if (strpos($template, "{discount_excl_vat}") !== false)
		{
			$replace[] = "*";
		}
		else
		{
			$replace[] = "";
		}

		$search[] = "{shipping_denotation}";

		if (strpos($template, "{shipping_excl_vat}") !== false)
		{
			$replace[] = "*";
		}
		else
		{
			$replace[] = "";
		}

		$search[]  = "{order_total}";
		$replace[] = \RedshopHelperProductPrice::formattedPrice($row->order_total);
		$search[]  = "{total_excl_vat}";
		$replace[] = \RedshopHelperProductPrice::formattedPrice($totalExcludeVAT);
		$search[]  = "{sub_total_vat}";
		$replace[] = \RedshopHelperProductPrice::formattedPrice($subTotalVAT);
		$search[]  = "{order_id}";
		$replace[] = $orderId;

		$discounts    = explode('@', $row->discount_type);
		$discountType = '';

		foreach ($discounts as $discount)
		{
			if (empty($discount))
			{
				continue;
			}

			$discountTypes = explode(':', $discount);

			if ($discountTypes[0] == 'c')
			{
				$discountType .= \JText::_('COM_REDSHOP_COUPON_CODE') . ' : ' . $discountTypes[1] . '<br>';
			}

			if ($discountTypes[0] == 'v')
			{
				$discountType .= \JText::_('COM_REDSHOP_VOUCHER_CODE') . ' : ' . $discountTypes[1] . '<br>';
			}
		}

		$search[]  = "{discount_type}";
		$replace[] = $discountType;

		$search[]  = "{discount_excl_vat}";
		$replace[] = \RedshopHelperProductPrice::formattedPrice($row->order_discount - $row->order_discount_vat);
		$search[]  = "{order_status}";
		$replace[] = \RedshopHelperOrder::getOrderStatusTitle($orderEntity->get('order_status'));
		$search[]  = "{order_id_lbl}";
		$replace[] = \JText::_('COM_REDSHOP_ORDER_ID_LBL');
		$search[]  = "{order_date}";
		$replace[] = \RedshopHelperDatetime::convertDateFormat($row->cdate);
		$search[]  = "{customer_note}";
		$replace[] = $row->customer_note;
		$search[]  = "{customer_message}";
		$replace[] = $row->customer_message;
		$search[]  = "{referral_code}";
		$replace[] = $row->referral_code;

		// Replace
		self::replaceOrderLabel($template, $search, $replace);

		$billingAddresses  = $orderEntity->getBilling()->getItem();
		$shippingAddresses = $orderEntity->getShipping()->getItem();

		$search [] = "{requisition_number}";
		$replace[] = !empty($row->requisition_number) ? $row->requisition_number : "N/A";

		$template = \RedshopHelperBillingTag::replaceBillingAddress($template, $billingAddresses, $sendMail);
		$template = \Redshop\Shipping\Tag::replaceShippingAddress($template, $shippingAddresses, $sendMail);

		$template = self::replaceOrderStatusLog($template, $row->order_id);

		$message = str_replace($search, $replace, $template);
		$message = \RedshopHelperPayment::replaceConditionTag($message, $row->payment_discount, 0, $row->payment_oprand);
		$message = \RedshopHelperCartTag::replaceDiscount($message, $row->order_discount, $totalForDiscount);
		$message = \RedshopHelperCartTag::replaceTax($message, $row->order_tax + $row->order_shipping_tax, $row->tax_after_discount, 1);

		return $message;
	}

	/**
	 * Replace general string & label in order template
	 *
	 * @param   string  $template  Template
	 * @param   array   $search    Array of search
	 * @param   array   $replace   Array of replace
	 *
	 * @return  void
	 * @throws  \Exception
	 *
	 * @since  2.1.0
	 */
	protected static function replaceOrderLabel($template, &$search, &$replace)
	{
		$search[]  = "{discount_denotation}";
		$replace[] = "*";

		if (\JFactory::getApplication()->input->get('order_delivery'))
		{
			$search[]  = "{delivery_time_lbl}";
			$replace[] = \JText::_('COM_REDSHOP_DELIVERY_TIME');
		}
		else
		{
			$search[]  = "{delivery_time_lbl}";
			$replace[] = " ";
		}

		$search[]  = "{delivery_time}";
		$replace[] = \JFactory::getApplication()->input->get('order_delivery');
		$search[]  = "{without_vat}";
		$replace[] = '';
		$search[]  = "{with_vat}";
		$replace[] = '';

		if (strpos($template, '{order_detail_link_lbl}') !== false)
		{
			$search [] = "{order_detail_link_lbl}";
			$replace[] = \JText::_('COM_REDSHOP_ORDER_DETAIL_LINK_LBL');
		}

		if (strpos($template, '{product_subtotal_lbl}') !== false)
		{
			$search [] = "{product_subtotal_lbl}";
			$replace[] = \JText::_('COM_REDSHOP_PRODUCT_SUBTOTAL_LBL');
		}

		if (strpos($template, '{product_subtotal_excl_vat_lbl}') !== false)
		{
			$search [] = "{product_subtotal_excl_vat_lbl}";
			$replace[] = \JText::_('COM_REDSHOP_PRODUCT_SUBTOTAL_EXCL_LBL');
		}

		if (strpos($template, '{shipping_with_vat_lbl}') !== false)
		{
			$search [] = "{shipping_with_vat_lbl}";
			$replace[] = \JText::_('COM_REDSHOP_SHIPPING_WITH_VAT_LBL');
		}

		if (strpos($template, '{shipping_excl_vat_lbl}') !== false)
		{
			$search [] = "{shipping_excl_vat_lbl}";
			$replace[] = \JText::_('COM_REDSHOP_SHIPPING_EXCL_VAT_LBL');
		}

		if (strpos($template, '{product_price_excl_lbl}') !== false)
		{
			$search [] = "{product_price_excl_lbl}";
			$replace[] = \JText::_('COM_REDSHOP_PRODUCT_PRICE_EXCL_LBL');
		}

		$search [] = "{requisition_number_lbl}";
		$replace[] = \JText::_('COM_REDSHOP_REQUISITION_NUMBER');

		$search [] = "{product_attribute_calculated_price}";
		$replace[] = "";
	}

	/**
	 * @param   string   $template  Template
	 * @param   integer  $orderId   Order ID
	 *
	 * @return  string
	 *
	 * @since   2.1.0
	 */
	protected static function replaceOrderStatusLog($template, $orderId)
	{
		if (strpos($template, '{order_status_log}') !== false)
		{
			$orderStatusLogs = \RedshopEntityOrder::getInstance((int) $orderId)->getStatusLog();

			$logLayout = \RedshopLayoutHelper::render(
				'order.status_log',
				array(
					'orderStatusLogs' => $orderStatusLogs,
				),
				'',
				array(
					'client'    => 0,
					'component' => 'com_redshop'
				)
			);

			$template = str_replace('{order_status_log}', $logLayout, $template);
		}

		return $template;
	}

	/**
	 * Method for replace products inside template
	 *
	 * @param   string   $template            Template html
	 * @param   float    $subTotalExcludeVAT  Sub-total exclude VAT
	 * @param   integer  $orderId             Order ID
	 * @param   boolean  $sendMail            Is in send mail
	 *
	 * @return  void
	 * @throws  \Exception
	 *
	 * @since   2.1.0
	 */
	public static function replaceProducts(&$template, &$subTotalExcludeVAT, $orderId = 0, $sendMail = false)
	{
		if (strpos($template, "{product_loop_start}") === false || strpos($template, "{product_loop_end}") === false)
		{
			return;
		}

		$orderItems         = \RedshopHelperOrder::getOrderItemDetail($orderId);
		$orderItems         = $orderItems === false ? array() : $orderItems;
		$startHtml          = explode('{product_loop_start}', $template);
		$productHtml        = $startHtml[0];
		$endHtml            = explode('{product_loop_end}', $startHtml[1]);
		$templateEnd        = $endHtml[1];
		$templateMiddle     = $endHtml[0];
		$cart               = \Redshop\Order\Item::replaceItems($templateMiddle, $orderItems, $sendMail);
		$template           = $productHtml . $cart[0] . $templateEnd;
		$subTotalExcludeVAT = $cart[1];
	}

	/**
	 * Method for replace payment
	 *
	 * @param   string              $template    Template HTML
	 * @param   \RedshopEntityOrder $orderEntity Order entity
	 *
	 * @return  void
	 *
	 * @throws  \Exception
	 *
	 * @since   2.1.0
	 */
	public static function replacePayment(&$template, $orderEntity)
	{
		if (!$orderEntity->isValid() || empty($template))
		{
			return;
		}

		$paymentMethod = $orderEntity->getPayment()->getItem();

		// Initialize Transaction label
		$transactionIdLabel = $paymentMethod->order_payment_trans_id != null ? \JText::_('COM_REDSHOP_PAYMENT_TRANSACTION_ID_LABEL') : '';

		// Replace Transaction Id and Label
		$template = str_replace("{transaction_id_label}", $transactionIdLabel, $template);
		$template = str_replace("{transaction_id}", $paymentMethod->order_payment_trans_id, $template);

		// Get Payment Method information
		$paymentMethodDetail = \RedshopHelperOrder::getPaymentMethodInfo($paymentMethod->payment_method_class);
		$paymentMethodDetail = $paymentMethodDetail [0];

		// For Payment and Extra Fields
		if (strpos($template, '{payment_extrafields}') !== false)
		{
			$paymentExtraFields = \productHelper::getInstance()->getPaymentandShippingExtrafields(
				$orderEntity->getItem(), \RedshopHelperExtrafields::SECTION_PAYMENT_GATEWAY
			);

			if ($paymentExtraFields == "")
			{
				$template = str_replace("{payment_extrafields_lbl}", "", $template);
				$template = str_replace("{payment_extrafields}", "", $template);
			}
			else
			{
				$template = str_replace("{payment_extrafields_lbl}", \JText::_("COM_REDSHOP_ORDER_PAYMENT_EXTRA_FILEDS"), $template);
				$template = str_replace("{payment_extrafields}", $paymentExtraFields, $template);
			}
		}

		\RedshopHelperPayment::loadLanguages();

		// Replace payment method
		$template = str_replace("{payment_method}", \JText::_("$paymentMethod->order_payment_name"), $template);

		// Replace extra infor
		$textExtraInfor = '';

		// Check for bank transfer payment type plugin - `rs_payment_banktransfer` suffixed
		$isBankTransferPaymentType = \RedshopHelperPayment::isPaymentType($paymentMethodDetail->element);

		if ($isBankTransferPaymentType)
		{
			$paymentParams  = new Registry($paymentMethodDetail->params);
			$textExtraInfor = (string) $paymentParams->get('txtextra_info', '');
		}

		$template = str_replace("{payment_extrainfo}", $textExtraInfor, $template);

		// Set order transaction fee tag
		$orderTransFeeLabel = '';
		$orderTransFee      = '';

		if ($paymentMethod->order_transfee > 0)
		{
			$orderTransFeeLabel = \JText::_('COM_REDSHOP_ORDER_TRANSACTION_FEE_LABEL');
			$orderTransFee      = \RedshopHelperProductPrice::formattedPrice($paymentMethod->order_transfee);
		}

		$template = str_replace("{order_transfee_label}", $orderTransFeeLabel, $template);
		$template = str_replace("{order_transfee}", $orderTransFee, $template);
		$template = str_replace(
			"{order_total_incl_transfee}",
			\RedshopHelperProductPrice::formattedPrice($paymentMethod->order_transfee + (float) $orderEntity->get('order_total')),
			$template
		);

		// Payment status
		$orderPaymentStatus = (string) $orderEntity->get('order_payment_status');

		if (trim($orderPaymentStatus) === 'Paid')
		{
			$orderPaymentStatus = \JText::_('COM_REDSHOP_PAYMENT_STA_PAID');
		}
		elseif (trim($orderPaymentStatus) == 'Unpaid')
		{
			$orderPaymentStatus = \JText::_('COM_REDSHOP_PAYMENT_STA_UNPAID');
		}
		elseif (trim($orderPaymentStatus) == 'Partial Paid')
		{
			$orderPaymentStatus = \JText::_('COM_REDSHOP_PAYMENT_STA_PARTIAL_PAID');
		}

		$orderPaymentStatus .= " " . \JFactory::getApplication()->input->get('order_payment_log');

		$template = str_replace("{payment_status}", $orderPaymentStatus, $template);
		$template = str_replace("{order_payment_status}", $orderPaymentStatus, $template);
	}

	/**
	 * Method for replace shipping
	 *
	 * @param   string              $template    Template HTML
	 * @param   \RedshopEntityOrder $orderEntity Order entity
	 *
	 * @return  void
	 *
	 * @throws  \Exception
	 *
	 * @since   2.1.0
	 */
	public static function replaceShipping(&$template, $orderEntity)
	{
		$template = str_replace('{shipping_address_info_lbl}', \JText::_('COM_REDSHOP_SHIPPING_ADDRESS_INFORMATION'), $template);

		if (!$orderEntity->isValid() || empty($template))
		{
			return;
		}

		// Replace shipping extra fields.
		if (strpos($template, '{shipping_extrafields}') !== false)
		{
			$shippingExtraFields = \productHelper::getInstance()->getPaymentandShippingExtrafields(
				$orderEntity->getItem(), \RedshopHelperExtrafields::SECTION_SHIPPING_GATEWAY
			);

			if ($shippingExtraFields == "")
			{
				$template = str_replace("{shipping_extrafields_lbl}", "", $template);
				$template = str_replace("{shipping_extrafields}", "", $template);
			}
			else
			{
				$template = str_replace("{shipping_extrafields_lbl}", \JText::_("COM_REDSHOP_ORDER_SHIPPING_EXTRA_FILEDS"), $template);
				$template = str_replace("{shipping_extrafields}", $shippingExtraFields, $template);
			}
		}

		$template = \Redshop\Shipping\Tag::replaceShippingMethod($orderEntity->getItem(), $template);
	}

	/**
	 * Method for replace shipping
	 *
	 * @param   string  $template Template HTML
	 * @param   integer $orderId  Order id
	 *
	 * @return  void
	 *
	 * @since   2.1.0
	 */
	public static function replaceDownloadProducts(&$template, $orderId)
	{
		if (!$orderId || empty($template))
		{
			return;
		}

		$orderEntity      = \RedshopEntityOrder::getInstance($orderId);
		$downloadProducts = \RedshopHelperOrder::getDownloadProduct($orderId);
		$tokenHtml        = '';
		$tokenLabel       = '';

		if (count($downloadProducts) > 0)
		{
			$tokenHtml .= "<table>";

			foreach ($downloadProducts as $index => $downloadProduct)
			{
				$number           = $index + 1;
				$downloadFileName = substr(basename($downloadProduct->file_name), 11);
				$downloadToken    = $downloadProduct->download_id;
				$productName      = $downloadProduct->product_name;
				$mailToken        = $productName . ": <a href='"
					. \JRoute::_(
						\JUri::root() . "index.php?option=com_redshop&view=product&layout=downloadproduct&tid=" . $downloadToken,
						false
					)
					. "'>" . $downloadFileName . "</a>";

				$tokenHtml .= "</tr><td>(" . $number . ") " . $mailToken . "</td></tr>";
			}

			$tokenHtml .= "</table>";
		}

		if (!empty($tokenHtml) && $orderEntity->get('order_status') == "C" && $orderEntity->get('order_payment_status') == "Paid")
		{
			$tokenLabel = \JText::_('COM_REDSHOP_DOWNLOAD_TOKEN');
		}

		$template = str_replace('{download_token}', $tokenHtml, $template);
		$template = str_replace('{download_token_lbl}', $tokenLabel, $template);
	}
}
