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
<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_ORDER_PRINT_HINT'); ?></b><br /><br />
{order_id} {order_number} {order_date} {order_status} {order_status_order_only_lbl}{order_status_payment_only_lbl} {order_status_order_only} {order_status_payment_only}   <br />
{billing_address} {shipping_address} {product_name} {product_number} {product_attribute_loop_start} {product_attribute_name} {product_attribute_value} {product_attribute_value_price} {product_attribute_loop_end} {attribute_label} {product_wrapper} <br />
{product_price} {product_quantity} {product_total_price} {order_subtotal} {order_total} <br />
{order_information_lbl} {order_id_lbl} {order_number_lbl} {order_date_lbl} {order_status_lbl} {billing_address_information_lbl} {shipping_address_information_lbl} <br />
{order_detail_lbl} {product_name_lbl} {note_lbl} {price_lbl} {quantity_lbl} {total_price_lbl} {order_subtotal_lbl} {if discount} {discount_lbl} {order_discount} {discount_in_percentage} {discount end if} {if vat} {vat_lbl} <br />
{order_tax} {vat end if} {shipping_lbl} {total_lbl} {payment_lbl} {payment_method} {customer_note_lbl} {customer_note} {requisition_number_lbl} {requisition_number} <br />
{shipping_method_lbl} {shipping_method} {if payment_discount} {payment_discount_lbl} {payment_order_discount} {payment_discount end if} <br />
{product_userfields} {print} {special_discount} {special_discount_amount}