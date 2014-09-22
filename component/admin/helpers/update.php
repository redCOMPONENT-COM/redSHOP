<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2005 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Class redshop update
 *
 * @since  1.4
 */
class RedshopUpdate
{
	/**
	 * Array check engines and indexes for performance improvements in redSHOP tables
	 *
	 * @var array
	 */
	public static $tablesRelates = array(
		'#__redshop_economic_accountgroup' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_published' => 'published'
			)
		),
		'#__redshop_accessmanager' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_section_name' => 'section_name',
				'idx_gid' => 'gid',
				'idx_view' => 'view',
				'idx_add' => 'add',
				'idx_edit' => 'edit',
				'idx_delete' => 'delete'
			)
		),
		'#__redshop_quotation_accessory_item' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_quotation_item_id' => 'quotation_item_id'
			)
		),
		'#__redshop_quotation_attribute_item' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_quotation_item_id' => 'quotation_item_id',
				'idx_section' => 'section',
				'idx_parent_section_id' => 'parent_section_id',
				'idx_is_accessory_att' => 'is_accessory_att'
			)
		),
		'#__redshop_order_attribute_item' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_order_item_id' => 'order_item_id',
				'idx_section' => 'section',
				'idx_parent_section_id' => 'parent_section_id',
				'idx_is_accessory_att' => 'is_accessory_att'
			)
		),
		'#__redshop_xml_export_ipaddress' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_xmlexport_id' => 'xmlexport_id',
				'idx_access_ipaddress' => 'access_ipaddress'
			)
		),
		'#__redshop_xml_import_log' => array(
			'engine' => 'MyISAM',
			'index' => array(
				'idx_xmlimport_id' => 'xmlimport_id'
			)
		),
		'#__redshop_xml_export_log' => array(
			'engine' => 'MyISAM',
			'index' => array(
				'idx_xmlexport_id' => 'xmlexport_id',
				'idx_xmlexport_filename' => 'xmlexport_filename'
			)
		),
		'#__redshop_xml_import' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_auto_sync' => 'auto_sync',
				'idx_sync_on_request' => 'sync_on_request',
				'idx_auto_sync_interval' => 'auto_sync_interval',
				'idx_published' => 'published'
			)
		),
		'#__redshop_xml_export' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_filename' => 'filename',
				'idx_auto_sync' => 'auto_sync',
				'idx_sync_on_request' => 'sync_on_request',
				'idx_auto_sync_interval' => 'auto_sync_interval',
				'idx_published' => 'published'
			)
		),
		'#__redshop_customer_question' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_published' => 'published',
				'idx_product_id' => 'product_id',
				'idx_parent_id' => 'parent_id'
			)
		),
		'#__redshop_quotation_fields_data' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_fieldid' => 'fieldid',
				'idx_quotation_item_id' => 'quotation_item_id',
				'idx_section' => 'section'
			)
		),
		'#__redshop_quotation' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_user_id' => 'user_id',
				'idx_order_id' => 'order_id',
				'idx_quotation_status' => 'quotation_status'
			)
		),
		'#__redshop_quotation_item' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'quotation_id' => 'quotation_id'
			)
		),
		'#__redshop_tax_group' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_published' => 'published'
			)
		),
		'#__redshop_wrapper' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_wrapper_use_to_all' => 'wrapper_use_to_all',
				'idx_published' => 'published'
			)
		),
		'#__redshop_pageviewer' => array(
			'engine' => 'MyISAM',
			'index' => array(
				'idx_session_id' => 'session_id',
				'idx_section' => 'section',
				'idx_section_id' => 'section_id',
				'idx_created_date' => 'created_date'
			)
		),
		'#__redshop_mass_discount' => array(
			'engine' => 'InnoDB'
		),
		'#__redshop_giftcard' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_published' => 'published'
			)
		),
		'#__redshop_coupons_transaction' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_coupon_id' => 'coupon_id',
				'idx_coupon_code' => 'coupon_code',
				'idx_coupon_value' => 'coupon_value',
				'idx_userid' => 'userid'
			)
		),
		'#__redshop_siteviewer' => array(
			'engine' => 'MyISAM',
			'index' => array(
				'idx_session_id' => 'session_id',
				'idx_created_date' => 'created_date'
			)
		),
		'#__redshop_catalog' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_published' => 'published'
			)
		),
		'#__redshop_catalog_colour' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_sample_id' => 'sample_id'
			)
		),
		'#__redshop_catalog_request' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_block' => 'block'
			)
		),
		'#__redshop_catalog_sample' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_published' => 'published'
			)
		),
		'#__redshop_category' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_published' => 'published'
			)
		),
		'#__redshop_category_xref' => array(
			'engine' => 'InnoDB'
		),
		'#__redshop_cart' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_session_id' => 'session_id',
				'idx_product_id' => 'product_id',
				'idx_section' => 'section',
				'idx_time' => 'time'
			)
		),
		'#__redshop_container' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_published' => 'published'
			)
		),
		'#__redshop_container_product_xref' => array(
			'engine' => 'InnoDB'
		),
		'#__redshop_country' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_country_3_code' => 'country_3_code',
				'idx_country_2_code' => 'country_2_code'
			)
		),
		'#__redshop_coupons' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_coupon_code' => 'coupon_code',
				'idx_percent_or_total' => 'percent_or_total',
				'idx_start_date' => 'start_date',
				'idx_end_date' => 'end_date',
				'idx_coupon_type' => 'coupon_type',
				'idx_userid' => 'userid',
				'idx_coupon_left' => 'coupon_left',
				'idx_published' => 'published',
				'idx_subtotal' => 'subtotal',
				'idx_order_id' => 'order_id'
			)
		),
		'#__redshop_cron' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_date' => 'date',
				'idx_published' => 'published'
			)
		),
		'#__redshop_currency' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_currency_code' => 'currency_code'
			)
		),
		'#__redshop_discount' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_start_date' => 'start_date',
				'idx_end_date' => 'end_date',
				'idx_published' => 'published'
			)
		),
		'#__redshop_discount_product' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_published' => 'published',
				'idx_start_date' => 'start_date',
				'idx_end_date' => 'end_date'
			)
		),
		'#__redshop_discount_product_shoppers' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_discount_product_id' => 'discount_product_id',
				'idx_shopper_group_id' => 'shopper_group_id'
			)
		),
		'#__redshop_discount_shoppers' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_discount_id' => 'discount_id',
				'idx_shopper_group_id' => 'shopper_group_id'
			)
		),
		'#__redshop_fields' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_published' => 'published',
				'idx_field_section' => 'field_section',
				'idx_field_type' => 'field_type',
				'idx_required' => 'required',
				'idx_field_name' => 'field_name',
				'idx_field_show_in_front' => 'field_show_in_front',
				'idx_display_in_product' => 'display_in_product'
			)
		),
		'#__redshop_fields_data' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_fieldid' => 'fieldid',
				'idx_itemid' => 'itemid',
				'idx_section' => 'section'
			)
		),
		'#__redshop_fields_value' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_field_id' => 'field_id'
			)
		),
		'#__redshop_mail' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_mail_section' => 'mail_section',
				'idx_mail_order_status' => 'mail_order_status',
				'idx_published' => 'published'
			)
		),
		'#__redshop_manufacturer' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_published' => 'published'
			)
		),
		'#__redshop_media' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_section_id' => 'section_id',
				'idx_media_section' => 'media_section',
				'idx_media_type' => 'media_type',
				'idx_media_name' => 'media_name',
				'idx_published' => 'published'
			)
		),
		'#__redshop_media_download' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_media_id' => 'media_id'
			)
		),
		'#__redshop_newsletter' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_published' => 'published'
			)
		),
		'#__redshop_newsletter_subscription' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_user_id' => 'user_id',
				'idx_newsletter_id' => 'newsletter_id',
				'idx_email' => 'email',
				'idx_published' => 'published'
			)
		),
		'#__redshop_newsletter_tracker' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_newsletter_id' => 'newsletter_id',
				'idx_read' => 'read'
			)
		),
		'#__redshop_orders' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_barcode' => 'barcode',
				'idx_order_payment_status' => 'order_payment_status',
				'idx_order_status' => 'order_status',
				'vm_order_number' => 'vm_order_number'
			)
		),
		'#__redshop_stockroom_amount_image' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_stockroom_id' => 'stockroom_id',
				'idx_stock_option' => 'stock_option',
				'idx_stock_quantity' => 'stock_quantity'
			)
		),
		'#__redshop_order_item' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_order_id' => 'order_id',
				'idx_user_info_id' => 'user_info_id',
				'idx_product_id' => 'product_id',
				'idx_order_status' => 'order_status',
				'idx_cdate' => 'cdate',
				'idx_container_id' => 'container_id',
				'idx_is_giftcard' => 'is_giftcard'
			)
		),
		'#__redshop_order_acc_item' => array(
			'engine' => 'InnoDB'
		),
		'#__redshop_order_payment' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_order_id' => 'order_id',
				'idx_payment_method_id' => 'payment_method_id'
			)
		),
		'#__redshop_order_status_log' => array(
			'engine' => 'MyISAM',
			'index' => array(
				'idx_order_id' => 'order_id',
				'idx_order_status' => 'order_status'
			)
		),
		'#__redshop_order_status' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_published' => 'published'
			)
		),
		'#__redshop_order_users_info' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_order_id' => 'order_id',
				'idx_address_type' => 'address_type'
			)
		),
		'#__redshop_payment_method' => array(
			'engine' => 'InnoDB'
		),
		'#__redshop_product' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_manufacturer_id' => 'manufacturer_id',
				'idx_product_on_sale' => 'product_on_sale',
				'idx_product_special' => 'product_special',
				'idx_product_parent_id' => 'product_parent_id',
				'idx_common' => array(
					'published',
					'expired',
					'product_parent_id'
				)
			),
			'unique' => array(
				'idx_product_number' => 'product_number'
			)
		),
		'#__redshop_product_accessory' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_common' => array('product_id', 'child_product_id'),
				'idx_child_product_id' => 'child_product_id'
			)
		),
		'#__redshop_product_attribute' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_product_id' => 'product_id',
				'idx_attribute_name' => 'attribute_name',
				'idx_attribute_set_id' => 'attribute_set_id',
				'idx_attribute_published' => 'attribute_published',
				'idx_attribute_required' => 'attribute_required'
			)
		),
		'#__redshop_product_attribute_property' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_attribute_id' => 'attribute_id',
				'idx_setrequire_selected' => 'setrequire_selected',
				'idx_property_published' => 'property_published',
				'idx_property_number' => 'property_number'
			)
		),
		'#__redshop_product_category_xref' => array(
			'engine' => 'InnoDB'
		),
		'#__redshop_product_price' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_product_id' => 'product_id',
				'idx_shopper_group_id' => 'shopper_group_id',
				'idx_price_quantity_start' => 'price_quantity_start',
				'idx_price_quantity_end' => 'price_quantity_end'
			)
		),
		'#__redshop_product_compare' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_common' => array('user_id', 'product_id'),
				'idx_product_id' => 'product_id'
			)
		),
		'#__redshop_product_discount_calc' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_product_id' => 'product_id'
			)
		),
		'#__redshop_product_discount_calc_extra' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_product_id' => 'product_id'
			)
		),
		'#__redshop_product_download' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_product_id' => 'product_id',
				'idx_user_id' => 'user_id',
				'idx_order_id' => 'order_id'
			)
		),
		'#__redshop_product_download_log' => array(
			'engine' => 'MyISAM',
			'index' => array(
				'idx_download_id' => 'download_id'
			)
		),
		'#__redshop_product_rating' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_published' => 'published',
				'idx_email' => 'email'
			)
		),
		'#__redshop_product_related' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_product_id' => 'product_id'
			)
		),
		'#__redshop_product_stockroom_xref' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_stockroom_id' => 'stockroom_id'
			)
		),
		'#__redshop_product_subattribute_color' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_subattribute_id' => 'subattribute_id',
				'idx_subattribute_published' => 'subattribute_published'
			)
		),
		'#__redshop_product_tags' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_published' => 'published',
				'idx_tags_name' => 'tags_name'
			)
		),
		'#__redshop_product_tags_xref' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_product_id' => 'product_id',
				'idx_users_id' => 'users_id'
			)
		),
		'#__redshop_product_voucher' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_common' => array('voucher_code', 'published', 'start_date', 'end_date'),
				'idx_voucher_left' => 'voucher_left'
			)
		),
		'#__redshop_product_voucher_xref' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_common' => array('voucher_id', 'product_id')
			)
		),
		'#__redshop_sample_request' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_block' => 'block'
			)
		),
		'#__redshop_shipping_boxes' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_published' => 'published',
				'idx_common' => array('shipping_box_length', 'shipping_box_width', 'shipping_box_height')
			)
		),
		'#__redshop_shipping_rate' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'shipping_rate_name' => 'shipping_rate_name',
				'shipping_class' => 'shipping_class',
				'shipping_rate_zip_start' => 'shipping_rate_zip_start',
				'shipping_rate_zip_end' => 'shipping_rate_zip_end',
				'company_only' => 'company_only',
				'shipping_rate_value' => 'shipping_rate_value',
				'shipping_tax_group_id' => 'shipping_tax_group_id',
			)
		),
		'#__redshop_shopper_group' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_published' => 'published',
				'idx_parent_id' => 'parent_id'
			)
		),
		'#__redshop_state' => array(
			'engine' => 'InnoDB'
		),
		'#__redshop_stockroom' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_published' => 'published',
				'idx_min_del_time' => 'min_del_time'
			)
		),
		'#__redshop_stockroom_container_xref' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_common' => array('container_id', 'stockroom_id'),
				'idx_stockroom_id' => 'stockroom_id'
			)
		),
		'#__redshop_tax_rate' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_tax_group_id' => 'tax_group_id',
				'idx_tax_country' => 'tax_country',
				'idx_tax_state' => 'tax_state',
				'idx_is_eu_country' => 'is_eu_country'
			)
		),
		'#__redshop_template' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_template_section' => 'template_section',
				'idx_published' => 'published'
			)
		),
		'#__redshop_textlibrary' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_section' => 'section',
				'idx_published' => 'published'
			)
		),
		'#__redshop_users_info' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_common' => array('address_type', 'user_id'),
				'user_id' => 'user_id'
			)
		),
		'#__redshop_wishlist_userfielddata' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_common' => array('wishlist_id', 'product_id')
			)
		),
		'#__redshop_wishlist' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_user_id' => 'user_id'
			)
		),
		'#__redshop_product_attribute_stockroom_xref' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_stockroom_id' => 'stockroom_id',
				'idx_common' => array(
					'section_id',
					'section',
					'stockroom_id'
				)
			)
		),
		'#__redshop_product_attribute_price' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_shopper_group_id' => 'shopper_group_id',
				'idx_common' => array(
					'section_id',
					'section',
					'price_quantity_start',
					'price_quantity_end',
					'shopper_group_id'
				)
			)
		),
		'#__redshop_wishlist_product' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_wishlist_id' => 'wishlist_id',
				'idx_common' => array(
					'product_id',
					'wishlist_id'
				)
			)
		),
		'#__redshop_product_voucher_transaction' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_voucher_id' => 'voucher_id',
				'idx_voucher_code' => 'voucher_code',
				'idx_amount' => 'amount',
				'idx_user_id' => 'user_id'
			)
		),
		'#__redshop_product_subscription' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_product_id' => 'product_id'
			)
		),
		'#__redshop_subscription_renewal' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_common' => array('product_id', 'before_no_days')
			)
		),
		'#__redshop_product_subscribe_detail' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_common' => array('product_id', 'end_date'),
				'idx_order_item_id' => 'order_item_id'
			)
		),
		'#__redshop_attribute_set' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_published' => 'published'
			)
		),
		'#__redshop_product_serial_number' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_common' => array('product_id', 'is_used')
			)
		),
		'#__redshop_usercart' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_user_id' => 'user_id'
			)
		),
		'#__redshop_usercart_accessory_item' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_cart_item_id' => 'cart_item_id'
			)
		),
		'#__redshop_usercart_attribute_item' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_common' => array(
					'is_accessory_att',
					'section',
					'parent_section_id',
					'cart_item_id'
				),
				'idx_cart_item_id' => 'cart_item_id',
				'idx_parent_section_id' => 'parent_section_id'
			)
		),
		'#__redshop_usercart_item' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_cart_id' => 'cart_id'
			)
		),
		'#__redshop_orderbarcode_log' => array(
			'engine' => 'MyISAM',
			'index' => array(
				'idx_order_id' => 'order_id'
			)
		),
		'#__redshop_zipcode' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_country_code' => 'country_code',
				'idx_state_code' => 'state_code'
			)
		),
		'#__redshop_product_navigator' => array(
			'engine' => 'InnoDB',
			'index' => array(
				'idx_product_id' => 'product_id'
			)
		),
		'#__redshop_notifystock_users' => array(
			'engine' => 'InnoDB',
			'index' => array(
					'idx_common' => array(
					'product_id',
					'property_id',
					'subproperty_id',
					'notification_status',
					'user_id'
				),
				'idx_user_id' => 'user_id'
			)
		),
		'#__redshop_ordernumber_track' => array(
			'engine' => 'InnoDB'
		)
	);
}
