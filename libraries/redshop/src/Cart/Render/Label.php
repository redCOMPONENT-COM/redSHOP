<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Cart\Render;

defined('_JEXEC') or die;

/**
 * Render Label class
 *
 * @since  2.1.0
 */
class Label
{
	/**
	 * Method for replace label in template
	 *
	 * @param   string $content Template content
	 *
	 * @return  string
	 *
	 * @since   2.1.0
	 */
	public static function replace($content)
	{
		$search  = array();
		$replace = array();

		self::checkTag($search, $replace, $content, 'cart_lbl', \JText::_('COM_REDSHOP_CART_LBL'));
		self::checkTag($search, $replace, $content, 'copy_orderitem_lbl', \JText::_('COM_REDSHOP_COPY_ORDERITEM_LBL'));
		self::checkTag($search, $replace, $content, 'totalpurchase_lbl', \JText::_('COM_REDSHOP_CART_TOTAL_PURCHASE_TBL'));
		self::checkTag($search, $replace, $content, 'subtotal_excl_vat_lbl', \JText::_('COM_REDSHOP_SUBTOTAL_EXCL_VAT_LBL'));
		self::checkTag($search, $replace, $content, 'product_name_lbl', \JText::_('COM_REDSHOP_PRODUCT_NAME_LBL'));
		self::checkTag($search, $replace, $content, 'price_lbl', \JText::_('COM_REDSHOP_PRICE_LBL'));
		self::checkTag($search, $replace, $content, 'quantity_lbl', \JText::_('COM_REDSHOP_QUANTITY_LBL'));
		self::checkTag($search, $replace, $content, 'total_price_lbl', \JText::_('COM_REDSHOP_TOTAL_PRICE_LBL'));
		self::checkTag($search, $replace, $content, 'total_price_exe_lbl', \JText::_('COM_REDSHOP_TOTAL_PRICE_EXEL_LBL'));
		self::checkTag($search, $replace, $content, 'order_id_lbl', \JText::_('COM_REDSHOP_ORDER_ID_LBL'));
		self::checkTag($search, $replace, $content, 'order_number_lbl', \JText::_('COM_REDSHOP_ORDER_NUMBER_LBL'));
		self::checkTag($search, $replace, $content, 'order_date_lbl', \JText::_('COM_REDSHOP_ORDER_DATE_LBL'));
		self::checkTag($search, $replace, $content, 'requisition_number_lbl', \JText::_('COM_REDSHOP_REQUISITION_NUMBER'));
		self::checkTag($search, $replace, $content, 'order_status_lbl', \JText::_('COM_REDSHOP_ORDER_STAUS_LBL'));
		self::checkTag($search, $replace, $content, 'order_status_order_only_lbl', \JText::_('COM_REDSHOP_ORDER_STAUS_LBL'));
		self::checkTag($search, $replace, $content, 'order_status_payment_only_lbl', \JText::_('COM_REDSHOP_PAYMENT_STAUS_LBL'));
		self::checkTag($search, $replace, $content, 'order_information_lbl', \JText::_('COM_REDSHOP_ORDER_INFORMATION_LBL'));
		self::checkTag($search, $replace, $content, 'order_detail_lbl', \JText::_('COM_REDSHOP_ORDER_DETAIL_LBL'));
		self::checkTag($search, $replace, $content, 'product_name_lbl', \JText::_('COM_REDSHOP_PRODUCT_NAME_LBL'));
		self::checkTag($search, $replace, $content, 'note_lbl', \JText::_('COM_REDSHOP_NOTE_LBL'));
		self::checkTag($search, $replace, $content, 'price_lbl', \JText::_('COM_REDSHOP_PRICE_LBL'));
		self::checkTag($search, $replace, $content, 'quantity_lbl', \JText::_('COM_REDSHOP_QUANTITY_LBL'));
		self::checkTag($search, $replace, $content, 'total_price_lbl', \JText::_('COM_REDSHOP_TOTAL_PRICE_LBL'));
		self::checkTag($search, $replace, $content, 'order_subtotal_lbl', \JText::_('COM_REDSHOP_ORDER_SUBTOTAL_LBL'));
		self::checkTag($search, $replace, $content, 'total_lbl', \JText::_('COM_REDSHOP_TOTAL_LBL'));
		self::checkTag($search, $replace, $content, 'discount_type_lbl', \JText::_('COM_REDSHOP_CART_DISCOUNT_CODE_TBL'));
		self::checkTag($search, $replace, $content, 'payment_lbl', \JText::_('COM_REDSHOP_PAYMENT_METHOD'));
		self::checkTag($search, $replace, $content, 'customer_note_lbl', \JText::_('COM_REDSHOP_CUSTOMER_NOTE_LBL'));
		self::checkTag($search, $replace, $content, 'product_number_lbl', \JText::_('COM_REDSHOP_PRODUCT_NUMBER'));
		self::checkTag($search, $replace, $content, 'shopname', \Redshop::getConfig()->get('SHOP_NAME'));
		self::checkTag($search, $replace, $content, 'quotation_id_lbl', \JText::_('COM_REDSHOP_QUOTATION_ID'));
		self::checkTag($search, $replace, $content, 'quotation_number_lbl', \JText::_('COM_REDSHOP_QUOTATION_NUMBER'));
		self::checkTag($search, $replace, $content, 'quotation_date_lbl', \JText::_('COM_REDSHOP_QUOTATION_DATE'));
		self::checkTag($search, $replace, $content, 'quotation_status_lbl', \JText::_('COM_REDSHOP_QUOTATION_STATUS'));
		self::checkTag($search, $replace, $content, 'quotation_note_lbl', \JText::_('COM_REDSHOP_QUOTATION_NOTE'));
		self::checkTag($search, $replace, $content, 'quotation_information_lbl', \JText::_('COM_REDSHOP_QUOTATION_INFORMATION'));
		self::checkTag($search, $replace, $content, 'account_information_lbl', \JText::_('COM_REDSHOP_ACCOUNT_INFORMATION'));
		self::checkTag($search, $replace, $content, 'quotation_detail_lbl', \JText::_('COM_REDSHOP_QUOTATION_DETAILS'));
		self::checkTag($search, $replace, $content, 'quotation_subtotal_lbl', \JText::_('COM_REDSHOP_QUOTATION_SUBTOTAL'));
		self::checkTag($search, $replace, $content, 'quotation_discount_lbl', \JText::_('COM_REDSHOP_QUOTATION_DISCOUNT_LBL'));
		self::checkTag($search, $replace, $content, 'thirdparty_email_lbl', \JText::_('COM_REDSHOP_THIRDPARTY_EMAIL_LBL'));

		if (\Redshop::getConfig()->getBool('SHIPPING_METHOD_ENABLE'))
		{
			self::checkTag($search, $replace, $content, 'shipping_lbl', \JText::_('COM_REDSHOP_CHECKOUT_SHIPPING_LBL'));
			self::checkTag($search, $replace, $content, 'tax_with_shipping_lbl', \JText::_('COM_REDSHOP_CHECKOUT_SHIPPING_LBL'));
			self::checkTag($search, $replace, $content, 'shipping_method_lbl', \JText::_('COM_REDSHOP_SHIPPING_METHOD_LBL'));
		}
		else
		{
			self::checkTag($search, $replace, $content, 'shipping_lbl', '');
			self::checkTag($search, $replace, $content, 'tax_with_shipping_lbl', '');
			self::checkTag($search, $replace, $content, 'shipping_method_lbl', '');
		}

		return str_replace($search, $replace, $content);
	}

	/**
	 * Method for replace label in template
	 *
	 * @param   array  $search  Search array
	 * @param   array  $replace Replace array
	 * @param   string $html    Template content
	 * @param   string $tag     Tag for search
	 * @param   string $content Tag replace
	 *
	 * @return  void
	 *
	 * @since   2.1.0
	 */
	protected static function checkTag(&$search, &$replace, $html, $tag, $content)
	{
		if (strpos($html, '{' . $tag . '}') === false)
		{
			return;
		}

		$search[]  = '{' . $tag . '}';
		$replace[] = $content;
	}
}
