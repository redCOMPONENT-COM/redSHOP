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
<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_ORDER_RECEIPT_HINT'); ?></b><br /><br />
{product_loop_start} {product_loop_end} {product_name} {product_number} <br />
{product_attribute_loop_start} {product_attribute_name} {product_attribute_value} {product_attribute_value_price} {product_attribute_loop_end} {attribute_label} {product_wrapper} {product_price} {product_quantity} {product_total_price} {order_subtotal} <br />
{order_shipping} {order_total} {delivery_time} {payment_status} {print}{delivery_time_lbl}  <br />
{if discount} {discount_lbl} {order_discount} {discount_in_percentage} {discount end if} {if vat} {vat_lbl} {order_tax} {vat end if} {shipping_lbl} {shipping_method_lbl} {shipping_method} <br />
{if payment_discount} {payment_discount_lbl} {payment_order_discount} {payment_discount end if} <br />
{product_userfields} {shipping} {vat_shipping} {shipping_lbl} <br />
{download_date_list_lbl} {download_date_list} {download_counter_lbl} {download_counter} {download_date_lbl} {download_date} {download_token_lbl} {download_token} <br />
{product_subtotal} {product_subtotal_excl_vat} {shipping_excl_vat} {product_subtotal} {sub_total_vat} {discount_excl_vat} {total_excl_vat} {denotation_label} {discount_denotation} <br />
{discount_excl_vat} {shipping_denotation} {shipping_excl_vat} {payment_extrafields_lbl} {payment_extrafields} {shipping_extrafields_lbl} {shipping_extrafields}