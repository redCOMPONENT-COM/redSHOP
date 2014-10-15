<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
?>
{order_information_lbl} {order_id_lbl} {order_id} {order_number_lbl} {order_number} {order_date_lbl} {order_date} {order_status_lbl} {order_status} {customer_note_lbl} {customer_note} {billing_address_information_lbl} {billing_address} {shipping_address_information_lbl} {shipping_address} {order_detail_lbl} <br />
{product_loop_start} {product_name_lbl} {note_lbl} {price_lbl} {quantity_lbl} {total_price_lbl} {product_name} {product_number} {product_userfields} {product_attribute} {product_accessory} {product_wrapper} {product_price} {product_quantity} {product_total_price} {product_loop_end} <br />
{if vat} {vat_lbl} {order_tax} {vat end if} <br />
{if discount} {discount_lbl} {order_discount} {discount end if} <br />
{order_subtotal_lbl} {product_subtotal} {shipping_lbl} {shipping_excl_vat} {total_lbl} {order_total} {order_payment_status} {shipping_method_lbl} {shipping_method} {order_detail_link}