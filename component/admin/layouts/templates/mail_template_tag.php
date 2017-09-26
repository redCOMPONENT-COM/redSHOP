<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$name = isset($displayData['name']) ? $displayData['name'] : '';

switch ($name)
{
	case 'ask_question':
		?>
		<b><?php echo JText::_('COM_REDSHOP_MAIL_TEMPLATE_TAG_ASK_QUESTION_HINT'); ?></b><br /><br />
		{product_name} {product_link} {user_question} {answer} {user_telephone_lbl} {user_telephone} {user_address_lbl} {user_address}
		<?php
		break;
	case 'catalog_coupon_reminder':
		?>
		<b><?php echo JText::_('COM_REDSHOP_MAIL_TEMPLATE_TAG_CATALOG_COUPON_REMINDER_HINT'); ?></b><br /><br />
		{name} {discount} {coupon_code}
		<?php
		break;
	case 'catalog_first_reminder':
		?>
		<b><?php echo JText::_('COM_REDSHOP_MAIL_TEMPLATE_TAG_CATALOG_FIRST_REMINDER_HINT'); ?></b><br /><br />
		{name} {discount}
		<?php
		break;
	case 'catalog_order':
		?>
		{order_information_lbl} {order_id_lbl} {order_id} {order_number_lbl} {order_number} {order_date_lbl} {order_date} <br />
		{order_status_lbl} {order_status} {billing_address_information_lbl} {billing_address} <br />
		{shipping_address_information_lbl} {shipping_address} {order_detail_lbl} {product_name_lbl} <br />
		{note_lbl} {quantity_lbl} {product_loop_start} {product_name} {product_userfields} {product_wrapper} {product_quantity} {product_loop_end} <br />
		{order_payment_status} {shipping_method_lbl} {shipping_method} {shipping_rate_name} {order_detail_link}
		<?php
		break;
	case 'catalog_sample_coupon_reminder':
		?>
		<b><?php echo JText::_('COM_REDSHOP_MAIL_TEMPLATE_TAG_CATALOG_SAMPLE_COUPON_REMINDER_HINT'); ?></b><br /><br />
		{name} {discount} {days} {coupon_code}
		<?php
		break;
	case 'catalog_sample_first_reminder':
		?><b><?php echo JText::_('COM_REDSHOP_MAIL_TEMPLATE_TAG_CATALOG_SAMPLE_FIRST_REMINDER_HINT'); ?></b><br /><br />
		{name}
		<?php
		break;
	case 'catalog_sample_second_reminder':
		?>
		<b><?php echo JText::_('COM_REDSHOP_MAIL_TEMPLATE_TAG_CATALOG_SAMPLE_SECOND_REMINDER_HINT'); ?></b><br /><br />
		{name}
		<?php
		break;
	case 'catalog_sample_third_reminder':
		?>
		<b><?php echo JText::_('COM_REDSHOP_MAIL_TEMPLATE_TAG_CATALOG_SAMPLE_THIRD_REMINDER_HINT'); ?></b><br /><br />
		{name} {discount} {days} {coupon_code}
		<?php
		break;
	case 'catalog_second_reminder':
		?>
		<b><?php echo JText::_('COM_REDSHOP_MAIL_TEMPLATE_TAG_CATALOG_SECOND_REMINDER_HINT'); ?></b><br /><br />
		{name} {discount} {days} {coupon_code}
		<?php
		break;
	case 'catalog_send':
		?>
		<b><?php echo JText::_('COM_REDSHOP_MAIL_TEMPLATE_TAG_CATALOG_SEND_HINT'); ?></b><br /><br />
		{name}
		<?php
		break;
	case 'downloable_product':
		?>
		<b><?php echo JText::_('COM_REDSHOP_MAIL_TEMPLATE_TAG_DOWNLOADABLE_PRODUCT_HINT'); ?></b><br /><br />
		{fullname} {order_id} {order_number} {order_date} {product_serial_loop_start} {token} {product_serial_number} {product_name} {product_serial_loop_end}
		<?php
		break;
	case 'economic_invoice':
		?>
		<b><?php echo JText::_('COM_REDSHOP_MAIL_TEMPLATE_TAG_ECONOMIC_INVOICE_HINT'); ?></b><br /><br />
		{name} {order_id} {order_number} {order_date} {order_comment}
		<?php
		break;
	case 'first_after_order_purchased':
		?>
		{name} {url} {coupon_amount} {coupon_code} {coupon_duration}
		<?php
		break;
	case 'giftcard':
		?>
		{giftcard_price_lbl} {giftcard_reciver_name_lbl} {giftcard_reciver_email_lbl} {giftcard_price} {giftcard_reciver_name} {giftcard_reciver_email} {giftcard_desc} {giftcard_price} <br />
		{giftcard_validity_from} {giftcard_validity_to} {giftcard_image} {giftcard_validity} {giftcard_code_lbl} {giftcard_code}
		<?php
		break;
	case 'invoice':
		?>
		<b><?php echo JText::_('COM_REDSHOP_MAIL_TEMPLATE_TAG_INVOICE_HINT'); ?></b><br /><br />
		{invoice_number}
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
		<?php
		break;
	case 'newsletter':
		?>
		<b><?php echo JText::_('COM_REDSHOP_MAIL_TEMPLATE_TAG_NEWSLETTER_FIXED_TAGS_HINT'); ?></b> <br /><br />
		{username} {email}
		<?php
		break;
	case 'newsletter_confirmation':
		?>
		{shopname} {link} {name}
		<?php
		break;
	case 'order':
		?>
		<b><?php echo JText::_('COM_REDSHOP_MAIL_TEMPLATE_TAG_ORDER_HINT'); ?></b><br /><br />
		{order_mail_intro_text_title} {order_mail_intro_text} {order_information_lbl} {order_id_lbl} {order_id} {order_number_lbl} {order_number} <br />
		{order_date_lbl} {order_date} {order_status_lbl} {order_status} {billing_address_information_lbl} <br />
		{billing_address} {shipping_address_information_lbl} {shipping_address} {order_detail_lbl} {product_name_lbl} {stock_status} {tracking_number_lbl} {tracking_number} {tracking_url} <br />
		{product_name} {product_s_desc} {product_number} {note_lbl} {product_wrapper} {price_lbl} <br />
		{product_price} {product_sku} {quantity_lbl} {product_quantity} {total_price_lbl} {product_total_price} {order_subtotal_lbl} {order_subtotal} {if discount} {order_discount} {discount_in_percentage} {discount end if} {if vat} {order_tax} {vat end if} {total_lbl} <br />
		{order_total} {order_detail_link} {payment_lbl} {payment_method} {shipping_lbl} <br />
		{order_shipping} {shipping_method_lbl} {shipping_method} {customer_note_lbl} {customer_note} {requisition_number_lbl} {requisition_number} {if payment_discount} {payment_discount_lbl} {payment_order_discount} {payment_discount end if} {discount_type_lbl} {discount_type} {product_userfields} <br />
		{fullname} {firstname} {lastname} {shipping} {shipping_excl_vat} {product_subtotal} {product_subtotal_excl_vat} {sub_total_vat} {discount_excl_vat} {total_excl_vat} <br />
		{denotation_label} {discount_denotation} {discount_excl_vat} {shipping_denotation} {shipping_excl_vat} {discount_excl_vat} {payment_extrainfo} {product_attribute_number} <br />
		{order_detail_link_lbl} {product_attribute} {product_attribute_loop_start} {product_attribute_name} {product_attribute_value} {product_attribute_value_price} {product_attribute_calculated_price} {product_attribute_loop_end} {product_accessory} {special_discount} {special_discount_amount} {download_token} {download_token_lbl} {referral_code} {product_subtotal_lbl} {product_subtotal_excl_vat_lbl} <br />
		{shipping_with_vat_lbl} {shipping_excl_vat_lbl} {product_price_excl_lbl} {shipping_rate_name} {order_shipping_shop_location} {order_payment_status} {transaction_id_label} {transaction_id} {payment_extrafields_lbl} {payment_extrafields} {shipping_extrafields_lbl} {shipping_extrafields} {order_transfee_label} {order_transfee} {order_total_incl_transfee}
		<?php
		break;
	case 'order_status':
		?>
		<b><?php echo JText::_('COM_REDSHOP_MAIL_TEMPLATE_TAG_ORDER_STATUS_HINT'); ?></b><br /><br />
		{fullname} {email} {order_id} {order_number} {order_date} {customer_note_lbl} {customer_note} {order_detail_link_lbl} {order_detail_link} {customer_id} {order_track_url}
		<?php
		break;
	case 'product_subscription':
		?>
		{firstname} {lastname} {product_name} {subsciption_enddate} {subscription_period} {subscription_price} {product_link}
		<?php
		break;
	case 'quotation':
		?>
		<b><?php echo JText::_('COM_REDSHOP_MAIL_TEMPLATE_TAG_QUOTATION_HINT'); ?></b><br /><br />
		{quotation_information_lbl} {quotation_id_lbl} {quotation_id} {quotation_number_lbl} {quotation_number} {quotation_date_lbl} {quotation_date} <br />
		{quotation_status_lbl} {quotation_status} {quotation_note_lbl} {quotation_note} {quotation_customer_note_lbl} {quotation_customer_note} <br />
		{billing_address_information_lbl} {billing_address} {quotation_detail_lbl} {product_name_lbl} {product_name} {product_number_lbl} {product_number} {product_s_desc} <br />
		{note_lbl} {product_wrapper} {price_lbl} {product_price} {quantity_lbl} {product_quantity} {total_price_lbl} {product_total_price} {quotation_subtotal_lbl} {quotation_subtotal} <br />
		{quotation_vat_lbl} {quotation_vat} {total_lbl} {quotation_total} {quotation_discount_lbl} {quotation_discount} {quotation_detail_link}
		<?php
		break;
	case 'quotation_registration':
		?>
		{username} {password} {link}
		<?php
		break;
	case 'registration':
		?>
		<b><?php echo JText::_('COM_REDSHOP_MAIL_TEMPLATE_TAG_REGISTRATION_HINT'); ?></b><br /><br />
		{shopname} {fullname} {firstname} {lastname} {name} {username} {password} {email} {account_link}
		<?php
		break;
	case 'request_tax_exempt':
		?>
		{vat_number} {username} {company_name} {country} {state} {phone} {zipcode} {address} {city}
		<?php
		break;
	case 'review_product':
		?>
		<b><?php echo JText::_('COM_REDSHOP_MAIL_TEMPLATE_TAG_REVIEW_PRODUCT_HINT'); ?></b><br /><br />
		{username} {product_name} {product_link} {title} {comment}
		<?php
		break;
	case 'send_friend':
		?>
		{friend_name} {your_name} {product_name} {product_desc} {product_url}
		<?php
		break;
	case 'tax_exempt_approval_disapproval':
		?>
		{username} {shopname} {name} {company_name} {address} {city} {zipcode} {country} {phone}
		<?php
		break;
	case 'wishlist':
		?>
		{name} {product_loop_start} {product_thumb_image} {product_name} {product_price} {product_loop_end} {from_name}
		<?php
		break;
}
