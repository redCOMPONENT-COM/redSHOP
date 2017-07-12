<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class Tableorder_detail extends JTable
{
	public $order_id = null;

	public $user_id = null;

	public $order_number = null;

	public $barcode = null;

	public $is_booked = 0;

	public $user_info_id = null;

	public $order_total = null;

	public $order_subtotal = null;

	public $order_tax = null;

	public $order_tax_details = null;

	public $order_shipping = null;

	public $order_shipping_tax = null;

	public $coupon_discount = null;

	public $order_discount = null;

	public $order_discount_vat = null;

	public $payment_discount = null;

	public $special_discount = null;

	public $special_discount_amount = null;

	public $order_status = null;

	public $order_payment_status = null;

	public $cdate = null;

	public $mdate = null;

	public $ship_method_id = null;

	public $customer_note = null;

	public $ip_address = null;

	public $encr_key = null;

	public $invoice_no = null;

	public $discount_type = null;

	public $payment_oprand = null;

	public $order_label_create = 0;

	public $analytics_status = 0;

	public $requisition_number = null;

	public $bookinvoice_number = 0;

	public $bookinvoice_date = 0;

	public $track_no = null;

	public $shop_id = null;

	public $customer_message = null;

	public $referral_code = null;

	public function __construct(& $db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'orders', 'order_id', $db);
	}

	/**
	 * Validate all table fields before saving
	 *
	 * @return  bool
	 *
	 * @since  2.0.0.4
	 */
	public function check()
	{
		if (empty($this->order_status) || $this->order_status === 0)
		{
			JFactory::getApplication()->enqueueMessage(JText::_('COM_REDSHOP_TABLE_ORDER_REDSHOP_INVALID_ORDER_STATUS'), 'warning');

			return false;
		}

		return parent::check();
	}

	public function bind($array, $ignore = '')
	{
		if (array_key_exists('params', $array) && is_array($array['params']))
		{
			$registry = new JRegistry;
			$registry->loadArray($array['params']);
			$array['params'] = $registry->toString();
		}

		return parent::bind($array, $ignore);
	}
}
