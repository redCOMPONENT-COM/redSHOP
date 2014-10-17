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
<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_CATEGORY_HINT'); ?></b><br /><br />
{order_id} {order_number} {order_date} {order_status} {order_status_order_only_lbl} {order_status_payment_only_lbl} {order_status_order_only} {order_status_payment_only} <br />
{billing_address} {shipping_address} {product_name} {product_number} {product_wrapper} <br />
{product_price} {product_attribute_loop_start} {product_attribute_name} {product_attribute_value} {product_attribute_value_price} {product_attribute_loop_end} {attribute_label} {product_quantity} {product_total_price} {order_subtotal} {order_total} <br />
{order_information_lbl} {order_id_lbl} {order_number_lbl} {order_date_lbl} {order_status_lbl} <br />
{billing_address_information_lbl} {shipping_address_information_lbl} {order_detail_lbl} {product_name_lbl} {note_lbl} {price_lbl} <br />
{quantity_lbl} {total_price_lbl} {order_subtotal_lbl} {if discount} {discount_lbl} {order_discount} {discount_in_percentage} {discount end if} {if vat} {vat_lbl} <br />
{order_tax} {vat end if} {shipping_lbl} {total_lbl} {payment_lbl} {payment_method} {customer_note_lbl} {customer_note} {requisition_number_lbl} <br />
{requisition_number} {shipping_method_lbl} {shipping_method} {if payment_discount} {payment_discount_lbl} {payment_order_discount} {payment_discount end if} {product_userfields} <br />
{print} {product_attribute} {product_accessory} {product_number_lbl} {product_subscription_lbl} {product_subscription} <br />
{product_price_excl_vat} {product_total_price_excl_vat} {product_subtotal_excl_vat} {order_subtotal_excl_vat} <br />
{tax} {reorder_button} {shipping} {vat_shipping} {shipping_lbl} <br />
{download_date_list_lbl} {download_date_list} {download_counter_lbl} {download_counter} {download_date_lbl} {download_date} {download_token_lbl} {download_token} <br />
{product_subtotal} {shipping_excl_vat} {product_subtotal} {sub_total_vat} {discount_excl_vat} {total_excl_vat} {denotation_label} {discount_denotation} {discount_excl_vat} <br />
{shipping_denotation} {shipping_excl_vat} {product_s_desc} {product_thumb_image} {product_old_price} {special_discount} {special_discount_amount} <br />
{payment_extrafields_lbl} {payment_extrafields} {shipping_extrafields_lbl} {shipping_extrafields}