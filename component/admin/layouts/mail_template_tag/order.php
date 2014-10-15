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
<b><?php echo JText::_('COM_REDSHOP_MAIL_TEMPLATE_TAG_ORDER_HINT'); ?></b><br /><br />
{order_mail_intro_text_title} {order_mail_intro_text} {order_information_lbl} {order_id_lbl} {order_id} {order_number_lbl} {order_number} <br />
{order_date_lbl} {order_date} {order_status_lbl} {order_status} {billing_address_information_lbl} <br />
{billing_address} {shipping_address_information_lbl} {shipping_address} {order_detail_lbl} {product_name_lbl} <br />
{product_name} {product_s_desc} {product_number} {note_lbl} {product_wrapper} {price_lbl} <br />
{product_price} {product_sku} {quantity_lbl} {product_quantity} {total_price_lbl} {product_total_price} {order_subtotal_lbl} {order_subtotal} {if discount} {order_discount} {discount_in_percentage} {discount end if} {if vat} {order_tax} {vat end if} {total_lbl} <br />
{order_total} {order_detail_link} {payment_lbl} {payment_method} {shipping_lbl} <br />
{order_shipping} {shipping_method_lbl} {shipping_method} {customer_note_lbl} {customer_note} {requisition_number_lbl} {requisition_number} {if payment_discount} {payment_discount_lbl} {payment_order_discount} {payment_discount end if} {discount_type_lbl} {discount_type} {product_userfields} <br />
{fullname} {firstname} {lastname} {shipping} {shipping_excl_vat} {product_subtotal} {product_subtotal_excl_vat} {sub_total_vat} {discount_excl_vat} {total_excl_vat} <br />
{denotation_label} {discount_denotation} {discount_excl_vat} {shipping_denotation} {shipping_excl_vat} {discount_excl_vat} {payment_extrainfo} {product_attribute_number} <br />
{order_detail_link_lbl} {product_attribute} {product_accessory} {special_discount} {special_discount_amount} {download_token} {download_token_lbl} {referral_code} {product_subtotal_lbl} {product_subtotal_excl_vat_lbl} <br />
{shipping_with_vat_lbl} {shipping_excl_vat_lbl} {product_price_excl_lbl} {shipping_rate_name} {order_shipping_shop_location} {order_payment_status} {transaction_id_label} {transaction_id} {payment_extrafields_lbl} {payment_extrafields} {shipping_extrafields_lbl} {shipping_extrafields} {order_transfee_label} {order_transfee} {order_total_incl_transfee}