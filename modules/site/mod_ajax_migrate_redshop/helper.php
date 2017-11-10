<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_ajax_migrate_redshop
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Helper for mod_ajax_migrate_redshop
 *
 * @package     Joomla.Site
 * @subpackage  mod_ajax_migrate_redshop
 * @since       1.5
 */
class ModAjaxMigrateRedshopHelper
{
	/**
	 * Migrate redSHOP Order from 1.x.x to 2.x.x
	 *
	 * @return  string
	 *
	 * @link index.php?option=com_ajax&module=ajax_migrate_redshop&format=debug&method=migrateOrder&limit=100&start=0&prefix=j34
	 */
	public static function migrateOrderAjax()
	{
		$app   = JFactory::getApplication();
		$input = $app->input;
		$limit = $input->get('limit');
		$start = $input->get('start');
		$prefix = $input->getString('prefix', '');

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn($prefix . '_redshop_orders'));

		$data = $db->setQuery($query)->loadAssocList();
		$count = count($data);

		if ($start == 0)
		{
			self::deleleRecord('orders');
		}

		foreach ($data as $key => $value)
		{
			$columns = array(
				'order_id', 
				'user_id', 
				'order_number',
				'invoice_number', 
				'user_info_id', 
				'order_total', 
				'order_subtotal', 
				'order_shipping', 
				'order_shipping_tax', 
				'order_tax', 
				'order_discount', 
				'coupon_discount', 
				'special_discount_amount', 
				'payment_discount', 
				'order_status', 
				'order_payment_status', 
				'cdate', 
				'mdate', 
				'ship_method_id',
				'ip_address',
				'encr_key',
				'order_discount_vat',
				'invoice_no',
				'customer_note', 
				'track_no',
				'bookinvoice_number',
				'bookinvoice_date'
			);

			$values = array(
				$db->q($value['order_id']), 
				$db->q($value['user_id']), 
				$db->q($value['order_number']),
				$db->q($value['invoice_number']), 
				$db->q($value['user_info_id']), 
				$db->q($value['order_total']), 
				$db->q($value['order_subtotal']), 
				$db->q($value['order_shipping']), 
				$db->q($value['order_shipping_tax']), 
				$db->q($value['order_tax']), 
				$db->q($value['order_discount']), 
				$db->q($value['coupon_discount']), 
				$db->q($value['special_discount_amount']), 
				$db->q($value['payment_discount']), 
				$db->q($value['order_status']), 
				$db->q($value['order_payment_status']), 
				$db->q($value['cdate']), 
				$db->q($value['mdate']), 
				$db->q($value['ship_method_id']), 
				$db->q($value['ip_address']),
				$db->q($value['encr_key']),
				$db->q($value['order_discount_vat']), 
				$db->q($value['invoice_no']), 
				$db->q($value['customer_note']), 
				$db->q($value['track_no']),
				$db->q($value['bookinvoice_number']),
				$db->q($value['bookinvoice_date'])
			);

			self::insertRecord('orders', $columns, $values);
			$start++;
		}

		if ($start >= $count)
		{
			return 'Successfull';
		}
		else
		{
			return $app->redirect('index.php?option=com_ajax&module=ajax_migrate_redshop&format=debug&method=migrateOrder&limit=' . $limit . '&start=' . $start . '&prefix=' . $prefix);
		}
	}

	/**
	 * Migrate redSHOP Order Item from 1.x.x to 2.x.x
	 *
	 * @return  string
	 *
	 * @link index.php?option=com_ajax&module=ajax_migrate_redshop&format=debug&method=migrateOrderItem&limit=100&start=0&prefix=j34
	 */
	public static function migrateOrderItemAjax()
	{
		$app   = JFactory::getApplication();
		$input = $app->input;
		$limit = $input->get('limit');
		$start = $input->get('start');
		$prefix = $input->get('prefix', '');

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn($prefix . '_redshop_order_item'));

		$data = $db->setQuery($query)->loadAssocList();
		$count = count($data);

		if ($start == 0)
		{
			self::deleleRecord('order_item');
		}

		foreach ($data as $key => $value)
		{
			$columns = array(
				'order_item_id', 
				'order_id', 
				'user_info_id',
				'supplier_id', 
				'product_id', 
				'order_item_sku', 
				'order_item_name', 
				'product_quantity', 
				'product_item_price', 
				'product_item_price_excl_vat', 
				'product_final_price', 
				'order_item_currency', 
				'order_status', 
				'customer_note',
				'cdate', 
				'mdate', 
				'product_attribute',
				'delivery_time',
				'stockroom_id',
				'is_split',
				'wrapper_id',
				'is_giftcard', 
				'product_purchase_price',
				'product_item_old_price',
				'stockroom_quantity',
				'discount_calc_data'
			);

			$values = array(
				$db->q($value['order_item_id']), 
				$db->q($value['order_id']), 
				$db->q($value['user_info_id']),
				$db->q($value['supplier_id']), 
				$db->q($value['product_id']), 
				$db->q($value['order_item_sku']), 
				$db->q($value['order_item_name']), 
				$db->q($value['product_quantity']), 
				$db->q($value['product_item_price']), 
				$db->q($value['product_item_price_excl_vat']), 
				$db->q($value['product_final_price']), 
				$db->q($value['order_item_currency']), 
				$db->q($value['order_status']), 
				$db->q($value['customer_note']),
				$db->q($value['cdate']), 
				$db->q($value['mdate']), 
				$db->q($value['product_attribute']), 
				$db->q($value['delivery_time']),
				$db->q($value['stockroom_id']),
				$db->q($value['is_split']), 
				$db->q($value['wrapper_id']), 
				$db->q($value['is_giftcard']), 
				$db->q($value['product_purchase_price']), 
				$db->q($value['product_item_old_price']),
				$db->q($value['stockroom_quantity']),
				$db->q($value['discount_calc_data'])
			);

			self::insertRecord('order_item', $columns, $values);
			$start++;
		}

		if ($start >= $count)
		{
			return 'Successfull';
		}
		else
		{
			return $app->redirect('index.php?option=com_ajax&module=ajax_migrate_redshop&format=debug&method=migrateOrderItem&limit=' . $limit . '&start=' . $start . '&prefix=' . $prefix);
		}
	}

	/**
	 * Migrate redSHOP Order Accessory from 1.x.x to 2.x.x
	 *
	 * @return  string
	 *
	 * @link index.php?option=com_ajax&module=ajax_migrate_redshop&format=debug&method=migrateOrderAccessory&limit=100&start=0&prefix=j34
	 */
	public static function migrateOrderAccessoryAjax()
	{
		$app   = JFactory::getApplication();
		$input = $app->input;
		$limit = $input->get('limit');
		$start = $input->get('start');
		$prefix = $input->get('prefix', '');

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn($prefix . '_redshop_order_acc_item'));

		$data = $db->setQuery($query)->loadAssocList();
		$count = count($data);

		if ($start == 0)
		{
			self::deleleRecord('order_acc_item');
		}

		foreach ($data as $key => $value)
		{
			$columns = array(
				'order_item_acc_id', 
				'order_item_id', 
				'product_id',
				'order_acc_item_sku', 
				'order_acc_item_name', 
				'order_acc_price', 
				'order_acc_vat', 
				'product_quantity', 
				'product_acc_item_price', 
				'product_acc_final_price', 
				'product_attribute'
			);

			$values = array(
				$db->q($value['order_item_acc_id']), 
				$db->q($value['order_item_id']), 
				$db->q($value['product_id']),
				$db->q($value['order_acc_item_sku']), 
				$db->q($value['order_acc_item_name']), 
				$db->q($value['order_acc_price']), 
				$db->q($value['order_acc_vat']), 
				$db->q($value['product_quantity']), 
				$db->q($value['product_acc_item_price']), 
				$db->q($value['product_acc_final_price']), 
				$db->q($value['product_attribute'])
			);

			self::insertRecord('order_acc_item', $columns, $values);
			$start++;
		}

		if ($start >= $count)
		{
			return 'Successfull';
		}
		else
		{
			return $app->redirect('index.php?option=com_ajax&module=ajax_migrate_redshop&format=debug&method=migrateOrderAccessory&limit=' . $limit . '&start=' . $start . '&prefix=' . $prefix);
		}
	}

	/**
	 * Migrate redSHOP Order Attribute from 1.x.x to 2.x.x
	 *
	 * @return  string
	 *
	 * @link index.php?option=com_ajax&module=ajax_migrate_redshop&format=debug&method=migrateOrderAttribute&limit=100&start=0&prefix=j34
	 */
	public static function migrateOrderAttributeAjax()
	{
		$app   = JFactory::getApplication();
		$input = $app->input;
		$limit = $input->get('limit');
		$start = $input->get('start');
		$prefix = $input->get('prefix', '');

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn($prefix . '_redshop_order_attribute_item'));

		$data = $db->setQuery($query)->loadAssocList();
		$count = count($data);

		if ($start == 0)
		{
			self::deleleRecord('order_attribute_item');
		}

		foreach ($data as $key => $value)
		{
			$columns = array(
				'order_att_item_id', 
				'order_item_id', 
				'section_id',
				'section', 
				'parent_section_id', 
				'section_name', 
				'section_price', 
				'section_vat', 
				'section_oprand', 
				'is_accessory_att'
			);

			$values = array(
				$db->q($value['order_att_item_id']), 
				$db->q($value['order_item_id']), 
				$db->q($value['section_id']),
				$db->q($value['section']), 
				$db->q($value['parent_section_id']), 
				$db->q($value['section_name']), 
				$db->q($value['section_price']), 
				$db->q($value['section_vat']), 
				$db->q($value['section_oprand']), 
				$db->q($value['is_accessory_att'])
			);

			self::insertRecord('order_attribute_item', $columns, $values);
			$start++;
		}

		if ($start >= $count)
		{
			return 'Successfull';
		}
		else
		{
			return $app->redirect('index.php?option=com_ajax&module=ajax_migrate_redshop&format=debug&method=migrateOrderAttribute&limit=' . $limit . '&start=' . $start . '&prefix=' . $prefix);
		}
	}

	/**
	 * Migrate redSHOP Order Payment from 1.x.x to 2.x.x
	 *
	 * @return  string
	 *
	 * @link index.php?option=com_ajax&module=ajax_migrate_redshop&format=debug&method=migrateOrderPayment&limit=100&start=0&prefix=j34
	 */
	public static function migrateOrderPaymentAjax()
	{
		$app   = JFactory::getApplication();
		$input = $app->input;
		$limit = $input->get('limit');
		$start = $input->get('start');
		$prefix = $input->get('prefix', '');

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn($prefix . '_redshop_order_payment'));

		$data = $db->setQuery($query)->loadAssocList();
		$count = count($data);

		if ($start == 0)
		{
			self::deleleRecord('order_payment');
		}

		foreach ($data as $key => $value)
		{
			$columns = array(
				'payment_order_id', 
				'order_id', 
				'payment_method_id',
				'order_payment_code', 
				'order_payment_cardname', 
				'order_payment_number', 
				'order_payment_ccv', 
				'order_payment_amount', 
				'order_payment_expire', 
				'order_payment_name',
				'order_payment_trans_id',
				'payment_method_class',
				'authorize_status',
				'order_transfee'
			);

			$values = array(
				$db->q($value['payment_order_id']), 
				$db->q($value['order_id']), 
				$db->q($value['payment_method_id']),
				$db->q($value['order_payment_code']), 
				$db->q($value['order_payment_cardname']), 
				$db->q($value['order_payment_number']), 
				$db->q($value['order_payment_ccv']), 
				$db->q($value['order_payment_amount']), 
				$db->q($value['order_payment_expire']), 
				$db->q($value['order_payment_name']),
				$db->q($value['order_payment_trans_id']),
				$db->q($value['payment_method_class']),
				$db->q($value['authorize_status']),
				$db->q($value['order_transfee'])
			);

			self::insertRecord('order_payment', $columns, $values);
			$start++;
		}

		if ($start >= $count)
		{
			return 'Successfull';
		}
		else
		{
			return $app->redirect('index.php?option=com_ajax&module=ajax_migrate_redshop&format=debug&method=migrateOrderPayment&limit=' . $limit . '&start=' . $start . '&prefix=' . $prefix);
		}
	}

	/**
	 * Migrate redSHOP Order Status Log from 1.x.x to 2.x.x
	 *
	 * @return  string
	 *
	 * @link index.php?option=com_ajax&module=ajax_migrate_redshop&format=debug&method=migrateOrderStatusLog&limit=100&start=0&prefix=j34
	 */
	public static function migrateOrderStatusLogAjax()
	{
		$app   = JFactory::getApplication();
		$input = $app->input;
		$limit = $input->get('limit');
		$start = $input->get('start');
		$prefix = $input->get('prefix', '');

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn($prefix . '_redshop_order_status_log'));

		$data = $db->setQuery($query)->loadAssocList();
		$count = count($data);

		if ($start == 0)
		{
			self::deleleRecord('order_status_log');
		}

		foreach ($data as $key => $value)
		{
			$columns = array(
				'order_status_log_id', 
				'order_id', 
				'order_status',
				'order_payment_status', 
				'date_changed', 
				'customer_note'
			);

			$values = array(
				$db->q($value['order_status_log_id']), 
				$db->q($value['order_id']), 
				$db->q($value['order_status']),
				$db->q($value['order_payment_status']), 
				$db->q($value['date_changed']), 
				$db->q($value['customer_note'])
			);

			self::insertRecord('order_status_log', $columns, $values);
			$start++;
		}

		if ($start >= $count)
		{
			return 'Successfull';
		}
		else
		{
			return $app->redirect('index.php?option=com_ajax&module=ajax_migrate_redshop&format=debug&method=migrateOrderStatusLog&limit=' . $limit . '&start=' . $start . '&prefix=' . $prefix);
		}
	}

	/**
	 * Migrate redSHOP Order from 1.x.x to 2.x.x
	 *
	 * @return  string
	 *
	 * @link index.php?option=com_ajax&module=ajax_migrate_redshop&format=debug&method=migrateOrderUsersInfo&limit=100&start=0&prefix=j34
	 */
	public static function migrateOrderUsersInfoAjax()
	{
		$app   = JFactory::getApplication();
		$input = $app->input;
		$limit = $input->get('limit');
		$start = $input->get('start');
		$prefix = $input->get('prefix', '');

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn($prefix . '_redshop_order_users_info'));

		$data = $db->setQuery($query)->loadAssocList();
		$count = count($data);

		if ($start == 0)
		{
			self::deleleRecord('order_users_info');
		}

		foreach ($data as $key => $value)
		{
			$columns = array(
				'order_info_id', 
				'users_info_id', 
				'order_id',
				'user_id', 
				'firstname', 
				'lastname',
				'address_type',
				'vat_number',
				'tax_exempt',
				'shopper_group_id',
				'address',
				'city',
				'country_code',
				'state_code',
				'zipcode',
				'phone',
				'tax_exempt_approved',
				'approved',
				'is_company',
				'user_email',
				'company_name',
				'ean_number',
				'requesting_tax_exempt'
			);

			$values = array(
				$db->q($value['order_info_id']), 
				$db->q($value['users_info_id']), 
				$db->q($value['order_id']),
				$db->q($value['user_id']), 
				$db->q($value['firstname']), 
				$db->q($value['lastname']),
				$db->q($value['address_type']),
				$db->q($value['vat_number']),
				$db->q($value['tax_exempt']),
				$db->q($value['shopper_group_id']),
				$db->q($value['address']),
				$db->q($value['city']),
				$db->q($value['country_code']),
				$db->q($value['state_code']),
				$db->q($value['zipcode']),
				$db->q($value['phone']),
				$db->q($value['tax_exempt_approved']),
				$db->q($value['approved']),
				$db->q($value['is_company']),
				$db->q($value['user_email']),
				$db->q($value['company_name']),
				$db->q($value['ean_number']),
				$db->q($value['requesting_tax_exempt']),
			);

			self::insertRecord('order_users_info', $columns, $values);
			$start++;
		}

		if ($start >= $count)
		{
			return 'Successfull';
		}
		else
		{
			return $app->redirect('index.php?option=com_ajax&module=ajax_migrate_redshop&format=debug&method=migrateOrderUsersInfo&limit=' . $limit . '&start=' . $start . '&prefix=' . $prefix);
		}
	}

	/**
	 * Migrate redSHOP Users Info from 1.x.x to 2.x.x
	 *
	 * @return  string
	 *
	 * @link index.php?option=com_ajax&module=ajax_migrate_redshop&format=debug&method=migrateUsersInfo&limit=100&start=0&prefix=j34
	 */
	public static function migrateUsersInfoAjax()
	{
		$app   = JFactory::getApplication();
		$input = $app->input;
		$limit = $input->get('limit');
		$start = $input->get('start');
		$prefix = $input->get('prefix', '');

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn($prefix . '_redshop_users_info'));

		$data = $db->setQuery($query)->loadAssocList();
		$count = count($data);

		if ($start == 0)
		{
			self::deleleRecord('users_info');
		}

		foreach ($data as $key => $value)
		{
			$columns = array(
				'users_info_id', 
				'user_id', 
				'user_email',
				'address_type', 
				'firstname', 
				'lastname',
				'accept_terms_conditions',
				'vat_number',
				'tax_exempt',
				'shopper_group_id',
				'address',
				'city',
				'country_code',
				'state_code',
				'zipcode',
				'phone',
				'tax_exempt_approved',
				'approved',
				'is_company',
				'braintree_vault_number',
				'company_name',
				'ean_number',
				'requesting_tax_exempt',
				'veis_status',
				'veis_vat_number'
			);

			$values = array(
				$db->q($value['users_info_id']), 
				$db->q($value['user_id']), 
				$db->q($value['user_email']),
				$db->q($value['address_type']), 
				$db->q($value['firstname']), 
				$db->q($value['lastname']),
				$db->q($value['accept_terms_conditions']),
				$db->q($value['vat_number']),
				$db->q($value['tax_exempt']),
				$db->q($value['shopper_group_id']),
				$db->q($value['address']),
				$db->q($value['city']),
				$db->q($value['country_code']),
				$db->q($value['state_code']),
				$db->q($value['zipcode']),
				$db->q($value['phone']),
				$db->q($value['tax_exempt_approved']),
				$db->q($value['approved']),
				$db->q($value['is_company']),
				$db->q($value['braintree_vault_number']),
				$db->q($value['company_name']),
				$db->q($value['ean_number']),
				$db->q($value['requesting_tax_exempt']),
				$db->q($value['veis_status']),
				$db->q($value['veis_vat_number'])
			);

			self::insertRecord('users_info', $columns, $values);
			$start++;
		}

		if ($start >= $count)
		{
			return 'Successfull';
		}
		else
		{
			return $app->redirect('index.php?option=com_ajax&module=ajax_migrate_redshop&format=debug&method=migrateUsersInfo&limit=' . $limit . '&start=' . $start . '&prefix=' . $prefix);
		}
	}

	/**
	 * Function delete all records in table
	 *
	 * @param   string  $table  redSHOP table
	 *
	 * @return  boolean
	 */
	public static function deleleRecord($table)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->delete($db->qn('#__redshop_' . $table));

		return $db->setQuery($query)->execute();
	}

	/**
	 * Function insert records in table
	 *
	 * @param   string  $table    redSHOP table
	 * @param   array   $columns  Table columns
	 * @param   array   $values   Values that insert to table
	 *
	 * @return  boolean
	 */
	public static function insertRecord($table, $columns, $values)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
				->insert($db->qn('#__redshop_' . $table))
				->columns($db->qn($columns))
				->values(implode(',', $values));

		return $db->setQuery($query)->execute();
	}
}
