<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class Tableorder_detail extends JTable
{
	var $order_id = null;
	var $user_id = null;
	var $order_number = null;
	var $barcode = null;
	var $is_booked = 0;
	var $user_info_id = null;
	var $order_total = null;
	var $order_subtotal = null;
	var $order_tax = null;
	var $order_tax_details = null;
	var $order_shipping = null;
	var $order_shipping_tax = null;
	var $coupon_discount = null;
	var $order_discount = null;
	var $order_discount_vat = null;
	var $payment_discount = null;
	var $special_discount = null;
	var $special_discount_amount = null;
	var $order_status = null;
	var $order_payment_status = null;
	var $cdate = null;
	var $mdate = null;
	var $ship_method_id = null;
	var $customer_note = null;
	var $ip_address = null;
	var $encr_key = null;
	var $split_payment = null;
	var $invoice_no = null;
	var $discount_type = null;
	var $payment_oprand = null;
	var $order_label_create = 0;
	var $analytics_status = 0;
	var $requisition_number = null;
	var $bookinvoice_number = 0;
	var $bookinvoice_date = 0;
	var $track_no = null;
	var $shop_id = null;
	var $customer_message = null;
	var $referral_code = null;


	function Tableorder_detail(& $db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'orders', 'order_id', $db);
	}

	function bind($array, $ignore = '')
	{
		if (key_exists('params', $array) && is_array($array['params']))
		{
			$registry = new JRegistry();
			$registry->loadArray($array['params']);
			$array['params'] = $registry->toString();
		}
		return parent::bind($array, $ignore);
	}

}

?>