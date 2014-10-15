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
<b><?php echo JText::_('COM_REDSHOP_MAIL_TEMPLATE_TAG_INVOICE_HINT'); ?></b><br /><br />
{order_information_lbl} {order_id_lbl} {order_id} {order_number_lbl} {order_number} {order_date_lbl} {order_date} {order_status_lbl} {order_status} <br />
{billing_address_information_lbl} {billing_address} {shipping_address_information_lbl} {shipping_address} {order_detail_lbl} {product_name_lbl} <br />
product_name} {product_number} {note_lbl} {product_wrapper} {price_lbl} <br />
{product_price} {product_sku} {quantity_lbl} {product_quantity} {total_price_lbl} <br />
{product_total_price} {order_subtotal_lbl} {order_subtotal} {if discount} {order_discount} {discount_in_percentage} {discount end if} {if vat} {order_tax} {vat end if} <br />
{total_lbl} {order_total} {order_detail_link} {payment_lbl} {payment_method} <br />
{shipping_lbl} {order_shipping} {shipping_method_lbl} {shipping_method} {customer_note_lbl} {customer_note} {requisition_number_lbl} {requisition_number} {if payment_discount} {payment_discount_lbl} {payment_order_discount} {payment_discount end if} <br />
{discount_type_lbl} {discount_type} {product_userfields} {shipping} {shipping_excl_vat} {product_subtotal} {product_subtotal_excl_vat} {sub_total_vat} {discount_excl_vat} {total_excl_vat} <br />
{denotation_label} {discount_denotation} {discount_excl_vat} {shipping_denotation} {shipping_excl_vat} {discount_excl_vat} {product_attribute_number} {order_detail_link_lbl} <br />
{product_attribute} {product_accessory} {special_discount} {special_discount_amount} {download_token} {download_token_lbl} {referral_code} <br />
{product_subtotal_lbl} {product_subtotal_excl_vat_lbl} {shipping_with_vat_lbl} {shipping_excl_vat_lbl} {product_price_excl_lbl} {shipping_rate_name} <br />
{order_shipping_shop_location} {order_payment_status} {payment_extrainfo} {payment_extrafields_lbl} {payment_extrafields} {shipping_extrafields_lbl} {shipping_extrafields}