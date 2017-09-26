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
	case 'accessory':
	?>
		<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_ATTRIBUTE_WITH_CART_HINT'); ?></b><br /><br />
		{if accessory_main} {accessory_main end if} {accessory_mainproduct_price} {accessory_main_image} {accessory_main_title} {accessory_main_short_desc} {accessory_main_readmore} {accessory_main_image_3} {accessory_main_image_2} <br />
		{accessory_product_start} {accessory_product_end} {accessory_title} {accessory_image} {accessory_price} {accessory_price_saving} {accessory_main_price} <br />
		{accessory_short_desc} {accessory_quantity} {product_number} {accessory_readmore} {accessory_image_3} {accessory_image_2} <br />
		{manufacturer_name} {manufacturer_link} {without_vat} {accessory_readmore_link} {accessory_add_chkbox_lbl} {accessory_quantity_lbl} {accessory_preview_image}<br />
		{selected_accessory_price} {accessory_add_chkbox} {attribute_template:attributes} {stock_status}
		<?php
		break;
	case 'account':
		?>
		<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_ACCOUNT_HINT'); ?></b><br /><br />
		{welcome_introtext} {account_image} {account_title} {fullname_lbl} {fullname} {vatnumber_lbl} {vatnumber} {email_lbl} {email} {address_lbl} {address} {city_lbl} {city} <br />
		{zipcode_lbl} {zipcode} {state_lbl} {state} {country_lbl} {country} {phone_lbl} {phone}{phone_optional} {company_name_lbl} {company_name} {requesting_tax_exempt_lbl} {requesting_tax_exempt} {edit_account_link} {customer_custom_fields} <br />
		{more_orders} {order_image} {order_title} {order_loop_start} {order_index} {order_id} {order_detail_link} {order_loop_end} <br />
		{coupon_image} {coupon_title} {coupon_loop_start} {coupon_code_lbl} {coupon_code} {coupon_value_lbl} {coupon_value} {coupon_loop_end} <br />
		{shipping_image} {shipping_title} {edit_shipping_link} <br />
		{quotation_image} {quotation_title} {quotation_loop_start} {quotation_index} {quotation_id} {quotation_detail_link} {quotation_loop_end} <br />
		{tag_image} {tag_title} {edit_tag_link} <br />
		{wishlist_image} {wishlist_title} {edit_wishlist_link} <br />
		{compare_image} {compare_title} {edit_compare_link} {logout_link} {newsletter_signup_chk} {newsletter_signup_lbl} {reserve_discount} {reserve_discount_lbl} <br />
		{product_serial_loop_start} {product_serial_image} {product_serial_title} {product_name} {product_serial_number} {product_serial_loop_end}
		<?php
		break;
	case 'add_to_cart':
		?>
		<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_ADD_TO_CART_HINT'); ?></b><br /><br />
		{addtocart_quantity_increase_decrease} {addtocart_link} {addtocart_quantity} {addtocart_image} {addtocart_image_aslink} {addtocart_button} {addtocart_tooltip} {quantity_lbl}
		<?php
		break;
	case 'ajax_cart_box':
		?>
		<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_AJAX_CART_BOX_HINT'); ?></b><br /><br />
		{ajax_cart_box_title} {show_cart_text} {show_cart_button} {continue_shopping_button}
		<?php
		break;
	case 'ajax_product':
		?>
		<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_AJAX_PRODUCT_HINT'); ?></b><br /><br />
		{product_name} {product_price} {product_image} {attribute_template:attributes} {accessory_template:templatename} {if product_userfield} {product_userfield end if}
		<?php
		break;
	case 'ask_question':
		?>
		<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_CATEGORY_HINT'); ?></b><br /><br />
		{user_email_lbl} {user_email} {user_name_lbl} {user_name} {user_question_lbl} {user_question} {user_telephone_lbl} {user_telephone} {user_address_lbl} {user_address} {send_button} {captcha_lbl} {captcha}
		<?php
		break;
	case 'attribute':
		?>
		<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_ATTRIBUTE_HINT'); ?></b><br /><br />
		{attribute_title} {attribute_tooltip} {property_dropdown} {property_image_without_scroller} {property_image_scroller} <br />
		{subproperty_start} {property_title} {subproperty_dropdown} {subproperty_image_without_scroller} {subproperty_image_scroller} {subproperty_end}
		<?php
		break;
	case 'attribute_with_cart':
		?>
		<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_ATTRIBUTE_WITH_CART_HINT'); ?></b><br /><br />
		{attribute_title} {property_image_lbl} {virtual_number_lbl} {property_name_lbl} {property_price_lbl} {property_stock_lbl} {add_to_cart_lbl} {property_start} {property_image} {virtual_number} {property_name} {property_price} {without_vat} {property_stock} {property_end}
		<?php
		break;
	case 'billing':
		?>
		<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_BILLING_HINT'); ?></b><br /><br />
		<b><?php echo JText::_('COM_REDSHOP_REQUIRED_TAG'); ?></b><br /><br />
		{account_creation_start}<br />
		{username_lbl}{username}
		{password_lbl}{password}
		{confirm_password_lbl}{confirm_password}<br/>
		{newsletter_signup_chk}{newsletter_signup_lbl}<br/>
		{account_creation_end}<br/>
		{required_lbl}<br/>
		{shipping_same_as_billing_lbl} {shipping_same_as_billing}
		<?php
		break;
	case 'cart':
		?>
		<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_CART_HINT'); ?></b><br /><br />
		{cart_lbl} {product_price} {product_loop_start} {product_total_price} {product_name} {product_number} <br />
		{product_attribute} {product_attribute_loop_start} {product_attribute_name} {product_attribute_value} {product_attribute_value_price} {product_attribute_calculated_price} {product_attribute_loop_end} {attribute_label} {attribute_change} {product_accessory} {product_old_price} {product_customfields_lbl} {product_customfields} {product_subscription_lbl} {product_subscription} {product_wrapper} {discount_rule} <br />
		{update} {discount_form_lbl} {discount_form} {product_userfields} {coupon_code_lbl} {shipping_lbl} {total_lbl} {product_name_lbl} {price_lbl} {quantity_lbl} {total_price_lbl} {print} {product_thumb_image} {product_price_excl_vat} {product_total_price_excl_vat} {product_loop_end} {attribute_price_with_vat} {attribute_price_without_vat} {shipping} <br />
		{vat_info} {vat_shipping} {shipping_lbl} {product_subtotal} {shipping_excl_vat} {sub_total_vat} {discount_excl_vat} {total_excl_vat} {denotation_label} {discount_denotation} {discount_excl_vat} <br />
		{shipping_denotation} {shipping_excl_vat} {product_subtotal_lbl} {product_subtotal_excl_vat_lbl} {shipping_with_vat_lbl} {shipping_excl_vat_lbl} {product_price_excl_lbl} {product_name_nolink} {product_attribute_number} {tax_with_shipping_lbl} {product_subtotal} {product_subtotal_excl_vat} {total} <br />
		{update_cart} {quantity_increase_decrease} {remove_product} {empty_cart} {if discount} {discount_lbl} {order_discount} {discount_in_percentage} {discount end if} {totalpurchase_lbl} <br />
		{if vat} {vat_lbl} {tax} {vat end if} {checkout_button} {shop_more}
		<?php
		break;
	case 'catalogue':
		?>
		<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_CATALOGUE_HINT'); ?></b><br /><br />
		{name_lbl} {name} {email_lbl} {email_address} {submit_button_catalog} {catalog_select}
		<?php
		break;
	case 'catalogue_cart':
		?>
		<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_CATALOGUE_CART_HINT'); ?></b><br /><br />
		{product_name_lbl} {quantity_lbl} {print} {product_loop_start} {product_name} {product_attribute} {product_accessory} {product_wrapper} {product_userfields} {product_thumb_image} {update_cart} {remove_product} {product_loop_end} {update} {empty_cart} {coupon_code_lbl} {discount_form} {checkout_button} {shop_more}
		<?php
		break;
	case 'catalogue_order_detail':
		?>
		<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_CATALOGUE_ORDER_DETAIL_HINT'); ?></b><br /><br />
		{discount_type_lbl} {discount_type} {order_information_lbl} {print} {order_id_lbl} {order_id} {order_number_lbl} {order_number} {order_date_lbl} {order_date} {order_status_lbl} {order_status} {billing_address_information_lbl} {billing_address} {shipping_address_info_lbl} {shipping_address} {order_detail_lbl} {product_name_lbl} {note_lbl} {quantity_lbl} {product_loop_start} {product_name} {product_userfields} {customer_note} {product_quantity} {product_loop_end}
		<?php
		break;
	case 'catalogue_order_receipt':
		?>
		<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_CATALOGUE_ORDER_RECEIPT_HINT'); ?></b><br /><br />
		{product_name_lbl} {print} {quantity_lbl} {product_loop_start} {product_name} {product_userfields} {product_thumb_image} {product_quantity} {product_loop_end} {order_number_lbl} {order_number} {delivery_time_lbl} {delivery_time} {order_id} {order_id_lbl} {print}
		<?php
		break;
	case 'category':
		?>
		<h3><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_CATEGORY_HINT'); ?></h3>
		{if subcats} {category_main_description} {category_main_short_desc} {category_main_name} {category_main_thumb_image} {subcats end if} <br />
		{category_loop_start} {category_loop_end} {category_name} {category_description} {category_short_desc} {category_thumb_image} <br />
		{product_price_lbl} {product_price_slider} {include_product_in_sub_cat} {product_loop_start} {product_loop_end} {product_name} {product_price} {product_rating_summary} {product_s_desc} <br />
		{pagination} {perpagelimit:X} {product_display_limit} {show_all_products_in_category} {order_by} {if product_on_sale} {product_on_sale end if} {price_excluding_vat} <br />
		{more_documents} {product_id_lbl} {product_id} {product_number_lbl} {product_number} {product_discount_price} {product_old_price} {product_price_saving} {product_price_saving_percentage} {with_vat} {without_vat} {filter_by} <br />
		{template_selector_category_lbl} {template_selector_category} {manufacturer_link} {manufacturer_name} {stock_status:class for available stock : class for out of stock: class for pre order} {shopname} {read_more} {category_readmore} {category_main_thumb_image_2} {category_main_thumb_image_3} {category_thumb_image_2} {category_thumb_image_3} <br />
		{product_stock_amount_image} {product_price_table} {product_thumb_image_3} {product_thumb_image_2} {discount_start_date} {discount_end_date} {compare_products_button} {compare_product_div} {front_img_link} {back_img_link} {product_preview_img} {read_more_link} {product_name_nolink} {category_product_link} <br />
		{order_by_lbl} {filter_by_lbl} {returntocategory_link} {returntocategory_name} {returntocategory} {category_total_product_lbl} {category_total_product} {product_delivery_time} {delivery_time_lbl} {product_length} {product_width} {product_height} {front_preview_img_link} {back_preview_img_link} {if product_userfield} {product_userfield end if}
		<?php
		break;
	case 'category_product':
		?>
		<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_CATEGORY_PRODUCT_HINT'); ?></b><br /><br />
		{print} {filter_by_lbl} {filter_by} {order_by_lbl} {order_by} {category_frontpage_introtext} {template_selector_category} {template_selector_category_lbl} {category_loop_start} {category_thumb_image} {category_name} {category_description} {category_thumb_image_2} {category_thumb_image_3} {category_readmore} {category_short_desc} {category_total_product_lbl} {category_total_product} {product_loop_start} {product_thumb_image} {product_name} {product_price} {read_more} {attribute_template:attributes} {form_addtocart:add_to_cart1} {product_id_lbl} {product_id} {product_thumb_image_3} {product_thumb_image_2} {product_number_lbl} {product_number} {product_size} {product_length} {product_width} {product_height} {read_more_link} {product_s_desc} {product_desc} {product_rating_summary} {manufacturer_link} {manufacturer_name} {manufacturer_product_link} {if product_userfield} {product_userfield end if} {product_loop_end} {category_loop_end} {pagination}
		<?php
		break;
	case 'change_cart_attribute':
		?>
		<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_CHANGE_CART_ATTRIBUTE_HINT'); ?></b><br /><br />
		{apply_button} {cancel_button}
		<?php
		break;
	case 'checkout':
		?>
		<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_CHECKOUT_HINT'); ?></b><br /><br />
		{product_name} {product_number} {product_thumb_image} {checkout_button} {terms_and_conditions} {product_subtotal} {product_subtotal_excl_vat} {total} {shop_more} {shipping} <br />
		{product_price} {update_cart} {product_total_price} {product_loop_start} {product_attribute} {product_attribute_loop_start} {product_attribute_name} {product_attribute_value} {product_attribute_value_price} {product_attribute_loop_end} {attribute_label} {product_accessory} {product_wrapper} {product_userfields} {coupon_code_lbl} {if discount} {discount_lbl} {order_discount} {discount_in_percentage} {discount end if} {totalpurchase_lbl} <br />
		{if vat} {vat_lbl} {tax} {vat end if} {shipping_lbl} {total_lbl} {product_name_lbl} {price_lbl} {quantity_lbl} {total_price_lbl} {product_loop_end} {product_thumb_image} {product_price_excl_vat} {product_total_price_excl_vat} <br />
		{attribute_price_with_vat} {attribute_price_without_vat} {vat_shipping} {shipping_lbl} {customer_note_lbl} {customer_note} {requisition_number_lbl} {requisition_number} {shipping_excl_vat} {sub_total_vat} {discount_excl_vat} {total_excl_vat} {denotation_label} {discount_denotation} {discount_excl_vat} {shipping_denotation} {shipping_excl_vat} {checkout_back_button} {product_attribute_number} {thirdparty_email} {thirdparty_email_lbl} {quotation_request}
		<?php
		break;
	case 'clicktell_sms_message':
		?>
		<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_CLICKATELL_SMS_MESSAGE_HINT'); ?></b><br /><br />
		{order_id} {order_status} {customer_name} {payment_status}
		<?php
		break;
	case 'company_billing':
		?>
		<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_COMPANY_BILLING_HINT'); ?></b><br /><br />
		<b><?php echo JText::_('COM_REDSHOP_REQUIRED_TAG'); ?></b><br /><br />
		{email_lbl}{email}
		{retype_email_lbl}{retype_email}<br/>
		{company_name_lbl}{company_name}
		{vat_number_lbl}{vat_number}
		{firstname_lbl}{firstname}
		{lastname_lbl}{lastname}
		{address_lbl}{address}
		{zipcode_lbl}{zipcode}
		{city_lbl}{city}
		{country_lbl}{country}
		{state_lbl}{state}
		{phone_lbl}{phone}{phone_optional}
		{tax_exempt_lbl}{tax_exempt}<br/><br/>
		<b><?php echo JText::_('COM_REDSHOP_OPTION_TAG'); ?></b><br /><br />
		{ean_number_lbl}{ean_number}<br/>
		{company_extrafield}
		<?php
		break;
	case 'compare_product':
		?>
		<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_COMPARE_PRODUCT_HINT'); ?></b><br /><br />
		{print} {compare_product_heading} {returntocategory_name} {returntocategory_link} {remove_all} {expand_collapse} <br />
		{product_name} {product_image} {manufacturer_name} {discount_start_date} {discount_end_date} {product_price} <br />
		{product_s_desc} {product_desc} {product_rating_summary} {product_delivery_time} {product_number} <br />
		{products_in_stock} {product_stock_amount_image} {product_weight} {product_length} {product_height} {product_width} <br />
		{product_availability_date} {product_volume} {product_category} {remove} {add_to_cart} {product_field}
		<?php
		break;
	case 'frontpage_category':
		?>
		<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_FRONTPAGE_CATEGORY_HINT'); ?></b><br /><br />
		{category_frontpage_introtext} {category_frontpage_loop_start} {category_thumb_image} {category_name} {category_frontpage_loop_end} {pagination} {print} {category_thumb_image_2} {category_thumb_image_3} {category_readmore} {category_description} {category_short_desc} {category_total_product} {category_total_product_lbl}
		<?php
		break;
	case 'giftcard':
		?>
		<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_GIFTCARD_HINT'); ?></b><br /><br />
		{customer_quantity_lbl} {customer_quantity} {customer_amount_lbl} {customer_amount} {giftcard_name} {giftcard_desc} {giftcard_image} <br />
		{giftcard_price_lbl} {giftcard_price} {giftcard_value_lbl} {giftcard_value} <br />
		{giftcard_validity} {giftcard_validity_from} {giftcard_validity_to} {giftcard_reciver_name_lbl} {giftcard_reciver_name} {giftcard_reciver_email_lbl} {giftcard_reciver_email} {form_addtocart:cart_templatename} <br />
		{if giftcard_userfield} {giftcard_userfield end if}
		<?php
		break;
	case 'giftcard_list':
		?>
		<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_GIFTCARD_LIST_HINT'); ?></b><br /><br />
		{giftcard_loop_start} {giftcard_name} {giftcard_desc} {giftcard_readmore} {giftcard_value_lbl} {giftcard_value} {giftcard_price_lbl} {giftcard_price} {giftcard_validity} {giftcard_loop_end}
		<?php
		break;
	case 'manufacturer':
		?>
		<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_MANUFACTURER_HINT'); ?></b><br /><br />
		{order_by} {manufacturer_loop_start} {manufacturer_loop_end} {manufacturer_name} {manufacturer_image} {manufacturer_description} {manufacturer_link} {manufacturer_allproductslink} {print} {pagination}
		<?php
		break;
	case 'manufacturer_detail':
		?>
		<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_MANUFACTURER_DETAIL_HINT'); ?></b><br /><br />
		{manufacturer_name} {manufacturer_image} {manufacturer_description} {category_name} {category_thumb_image} {category_desc} {category_name_with_link} {category_loop_start} {category_loop_end} {manufacturer_url} {manufacturer_allproductslink_lbl} {manufacturer_allproductslink} {manufacturer_extra_fields}
		<?php
		break;
	case 'manufacturer_products':
		?>
		<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_MANUFACTURER_PRODUCTS_HINT'); ?></b><br /><br />
		{order_by} {filter_by} <br />
		{product_loop_start} {category_heading_start} {category_name} {category_heading_end} {product_name} {product_thumb_image} {product_thumb_image_2} {product_thumb_image_3} {product_price} {product_s_desc} <br />
		{product_old_price} {product_price_saving} {product_price_saving_percentage} {product_loop_end} {manufacturer_product_link} {form_addtocart:add_to_cart1} {print} {product_id_lbl} {product_id} {product_number} {product_number_lbl} {product_desc} {read_more} {read_more_link} {manufacturer_image} {manufacturer_name} {manufacturer_description} {manufacturer_extra_fields} {manufacturer_link} {pagination}
		<?php
		break;
	case 'newsletter':
		?>
		<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_NEWSLETTER_HINT'); ?></b><br /><br />
		{data} {username} {email} {unsubscribe_link}
		<?php
		break;
	case 'newsletter_product':
		?>
		<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_NEWSLETTER_PRODUCTS_HINT'); ?></b><br /><br />
		{product_name} {product_price} {product_thumb_image} {product_s_desc} {product_desc} {unsubscribe_link}
		<?php
		break;
	case 'onestep_checkout':
		?>
		<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_ONESTEP_CHECKOUT_HINT'); ?></b><br /><br />
		{billing_template} {billing_address_information_lbl} {edit_billing_address} {billing_address} {shipping_address_information_lbl} {shipping_address}
		<?php
		break;
	case 'order_detail':
		?>
		<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_ORDER_DETAIL_HINT'); ?></b><br /><br />
		{order_id} {order_number} {order_date} {order_status} {order_status_log} {order_status_order_only_lbl} {order_status_payment_only_lbl} {order_status_order_only} {order_status_payment_only} {tracking_number_lbl} {tracking_number} {tracking_url} <br />
		{billing_address} {shipping_address} {product_name} {product_number} {product_wrapper} <br />
		{product_price} {product_attribute_loop_start} {product_attribute_name} {product_attribute_value} {product_attribute_value_price} {product_attribute_calculated_price} {product_attribute_loop_end} {attribute_label} {product_quantity} {product_total_price} {order_subtotal} {order_total} <br />
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
		{payment_extrafields_lbl} {payment_extrafields} {shipping_extrafields_lbl} {shipping_extrafields} {product_gift}
		<?php
		break;
	case 'order_print':
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
		<?php
		break;
	case 'order_receipt':
		?>
		<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_ORDER_RECEIPT_HINT'); ?></b><br /><br />
		{product_loop_start} {product_loop_end} {product_name} {product_number} <br />
		{product_attribute_loop_start} {product_attribute_name} {product_attribute_value} {product_attribute_value_price} {product_attribute_calculated_price} {product_attribute_loop_end} {attribute_label} {product_wrapper} {product_price} {product_quantity} {product_total_price} {order_subtotal}  {tracking_number_lbl} {tracking_number} {tracking_url} <br />
		{order_id} {order_number} {order_shipping} {order_total} {delivery_time} {payment_status} {print}{delivery_time_lbl}  <br />
		{if discount} {discount_lbl} {order_discount} {discount_in_percentage} {discount end if} {if vat} {vat_lbl} {order_tax} {vat end if} {shipping_lbl} {shipping_method_lbl} {shipping_method} <br />
		{if payment_discount} {payment_discount_lbl} {payment_order_discount} {payment_discount end if} <br />
		{product_userfields} {shipping} {vat_shipping} {shipping_lbl} <br />
		{download_date_list_lbl} {download_date_list} {download_counter_lbl} {download_counter} {download_date_lbl} {download_date} {download_token_lbl} {download_token} <br />
		{product_subtotal} {product_subtotal_excl_vat} {shipping_excl_vat} {product_subtotal} {sub_total_vat} {discount_excl_vat} {total_excl_vat} {denotation_label} {discount_denotation} <br />
		{discount_excl_vat} {shipping_denotation} {shipping_excl_vat} {payment_extrafields_lbl} {payment_extrafields} {shipping_extrafields_lbl} {shipping_extrafields} {product_gift}
		<?php
		break;
	case 'orderlist':
		?>
		<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_ORDERLIST_HINT'); ?></b><br /><br />
		{order_id_lbl} {product_name_lbl} {total_price_lbl} {order_date_lbl} {order_status_lbl} {order_detail_lbl} {product_loop_start} {order_id} {order_products} {order_total} {order_date} {order_status} {order_detail_link} {reorder_link} {product_loop_end} {pagination} {print} {order_number} {pagination_limit}
		<?php
		break;
	case 'private_billing':
		?>
		<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_PRIVATE_BILLING_HINT'); ?></b><br /><br />
		<b><?php echo JText::_('COM_REDSHOP_REQUIRED_TAG'); ?></b><br /><br />
		{email_lbl}{email}
		{firstname_lbl}{firstname}
		{lastname_lbl}{lastname}
		{address_lbl}{address}
		{zipcode_lbl}{zipcode}
		{city_lbl}{city}
		{country_lbl}{country}
		{state_lbl}{state}
		{phone_lbl}{phone}{phone_optional}
		{private_extrafield}<br/>
		{retype_email_lbl}{retype_email}
		<?php
		break;
	case 'product':
		?>
		<h4><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_PRODUCT_HINT'); ?></h4>
		{product_name} {manufacturer_name} {supplier_name} {publish_date} {update_date} <br />
		{discount_start_date} {discount_end_date} {product_discount_price} {product_old_price} {product_price_saving} {product_price_saving_percentage} {if product_on_sale} <br />
		{product_on_sale end if} {product_price_lbl} {if product_special} {product_special end if} {product_name} {product_price} {lowest_price} {highest_price} <br />
		{product_thumb_image} {product_thumb_image_2} {product_thumb_image_3} {product_s_desc} {product_rating} {more_images} {more_documents} {more_videos} <br />
		{product_desc} {bookmark} {send_to_friend} {ask_question_about_product} {ask_question_about_product_without_lightbox} {manufacturer_product_link} {product_rating_summary} <br />
		{product_delivery_time} {manufacturer_link} {form_rating} {form_rating_without_lightbox} {product_id_lbl} {product_id} <br />
		{product_number_lbl} {product_number} {product_price_table} {products_in_stock} {if product_userfield} {product_userfield end if} <br />
		{delivery_time_lbl} {with_vat} {without_vat} {discount_calculator} {facebook_like_button} {googleplus1} <br />
		{returntocategory_name} {returntocategory_link} {product_size} <br />
		{component_heading} {returntocategory} {navigation_link_right} {product_weight} {product_weight_lbl} <br />
		{child_products} {product_volume} {product_volume_lbl} {product_price_novat} {price_excluding_vat} <br />
		{more_images_3} {more_images_2} {product_stock_amount_image} <br />
		{wishlist_button} {wishlist_link} {compare_products_button} {compare_product_div} {my_tags_button} {subscription} {ajaxdetail_template:templatename} {accessory_template:templatename} <br />
		{question_loop_start} {question} {question_owner} {question_date} {answer_loop_start} {answer} {answer_owner} {answer_date} {answer_loop_end} {question_loop_end} <br />
		{product_length} {product_width} {product_height} <br />
		{front_img_link} {back_img_link} {category_product_img} {category_front_img_link} {category_back_img_link} {product_preview_img} {diameter} {product_diameter_lbl} {manufacturer_image} <br />
		{back_link} {product_length_lbl} {product_width_lbl} {product_height_lbl} {min_order_product_quantity} {print} {product_category_list} {stock_notify_flag} {product_availability_date}  <br />
		{stock_status} {product_gift_table}
		<?php
		break;
	case 'product_content':
		?>
		<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_PRODUCT_CONTENT_HINT'); ?></b><br /><br />
		{product_thumb_image} {product_thumb_image_2} {product_thumb_image_3} {product_name} {product_desc} {product_price} {read_more} {attribute_template:attributes} {form_addtocart:add_to_cart1}
		<?php
		break;
	case 'product_sample':
		?>
		<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_PRODUCT_SAMPLE_HINT'); ?></b><br /><br />
		{name_lbl} {name} {email_lbl} {email_address} {address_fields} {submit_button_sample} {product_samples}
		<?php
		break;
	case 'quotation_cart':
		?>
		<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_QUOTATION_CART_HINT'); ?></b><br /><br />
		{cart_lbl} {print} {product_name_lbl} {quantity_lbl} {product_loop_start} {product_name} {product_attribute} {product_accessory} {product_wrapper} {product_userfields} {product_thumb_image} {update_cart} {remove_product} {product_loop_end} {update} {empty_cart} {checkout_button} {shop_more}
		<?php
		break;
	case 'quotation_detail':
		?>
		<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_QUOTATION_DETAIL_HINT'); ?></b><br /><br />
		{print} {quotation_information_lbl} {quotation_id_lbl} {quotaion_id} {quotation_number_lbl} {quotation_number} {quotation_date_lbl} {quotation_date} {quotation_status_lbl} {quotation_status} {quotation_note_lbl} {quotation_note} {account_information_lbl} {account_information} {quotation_detail_lbl} {product_name_lbl} {note_lbl} {price_lbl} {quantity_lbl} {total_price_lbl} {product_loop_start} {product_name} {product_attribute} {product_accessory} {product_number_lbl} {product_number} {product_price} {product_quantity} {product_total_price} {product_loop_end} {product_userfields} {quotation_subtotal_lbl} {quotation_subtotal} {total_lbl} {quotation_total} {quotation_discount_lbl} {quotation_discount}
		<?php
		break;
	case 'quotation_request':
		?>
		<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_QUOTATION_REQUEST_HINT'); ?></b><br /><br />
		{order_detail_lbl} {product_name_lbl} {quantity_lbl} {product_loop_start} {product_name} {product_attribute} {product_accessory} {product_userfields} {update_cart} {product_wrapper} {product_loop_end} {customer_note_lbl} {customer_note} {billing_address_information_lbl} {billing_address} {cancel_btn} {request_quotation_btn}
		<?php
		break;
	case 'redproductfinder':
		?>
		<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_REDPRODUCTFINDER_HINT'); ?></b><br /><br />
		{search_tag_display} {product_loop_start} {product_loop_end} {product_name} {product_price} {product_thumb_image} <br />
		{product_s_desc} {read_more} {product_id_lbl} {product_id} {product_number_lbl} {product_number} {manufacturer_link} {manufacturer_name} {order_by} {pagination} <br />
		{perpagelimit:X} {product_display_limit} {attribute_template:attributes}
		<?php
		break;
	case 'redshop_payment':
		?>
		<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_PAYMENT_METHOD_HINT'); ?></b><br /><br />
		{payment_heading} {payment_loop_start} {payment_method_name} {creditcard_information} {payment_loop_end}
		<?php
		break;
	case 'redshop_shipping':
		?>
		<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_SHIPPING_METHOD_HINT'); ?></b><br /><br />
		{shipping_heading} {shipping_method_loop_start} {shipping_method_title} {shipping_rate_loop_start} {shipping_rate_name} {shipping_rate} {shipping_location} {shipping_rate_loop_end} {shipping_method_loop_end}
		<?php
		break;
	case 'related_product':
		?>
		<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_RELATED_PRODUCT_HINT'); ?></b><br /><br />
		{relproduct_link} {relproduct_image} {relproduct_name} {relproduct_price} {relproduct_number} {relproduct_number_lbl} {read_more} {relproduct_old_price} {relproduct_price_table} {relproduct_price_saving} <br />
		{related_product_start} {related_product_end} {relproduct_s_desc} {relproduct_price_novat} {relproduct_old_price_lbl} {relproduct_image_2} {relproduct_image_3} <br />
		{attribute_template:templatename} {form_addtocart:templatename} {producttag:rs_field} <br />
		{stock_status} {relproduct_attribute_pricelist} {manufacturer_name} {manufacturer_link} {read_more_link}
		<?php
		break;
	case 'review':
		?>
		<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_REVIEW_HINT'); ?></b><br /><br />
		{product_loop_start} {product_title} <br />
		{review_loop_start} {fullname} {title} {comment} {stars} {reviewdate} {review_loop_end} {product_loop_end}
		<?php
		break;
	case 'shipment_invoice':
		?>
		<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_SHIPMENT_INVOICE_HINT'); ?></b><br /><br />
		{order_information_lbl} {order_id_lbl} {order_id} {order_number_lbl} {order_number} {order_date_lbl} {order_date} {order_status_lbl} {order_status} {customer_note_lbl} {customer_note} {billing_address_information_lbl} {billing_address} {shipping_address_information_lbl} {shipping_address} {order_detail_lbl} <br />
		{product_loop_start} {product_name_lbl} {note_lbl} {price_lbl} {quantity_lbl} {total_price_lbl} {product_name} {product_number} {product_userfields} {product_attribute} {product_accessory} {product_wrapper} {product_price} {product_quantity} {product_total_price} {product_loop_end} <br />
		{if vat} {vat_lbl} {order_tax} {vat end if} <br />
		{if discount} {discount_lbl} {order_discount} {discount end if} <br />
		{order_subtotal_lbl} {product_subtotal} {shipping_lbl} {shipping_excl_vat} {total_lbl} {order_total} {order_payment_status} {shipping_method_lbl} {shipping_method} {order_detail_link}
		<?php
		break;
	case 'shipping':
		?>
		<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_SHIPPING_HINT'); ?></b><br /><br />
		<b><?php echo JText::_('COM_REDSHOP_REQUIRED_TAG'); ?></b><br /><br />
		{firstname_st_lbl}{firstname_st}<br/>
		{lastname_st_lbl}{lastname_st}<br/>
		{address_st_lbl}{address_st}<br/>
		{zipcode_st_lbl}{zipcode_st}<br/>
		{city_st_lbl}{city_st}<br/>
		{country_st_lbl}{country_st}<br/>
		{state_st_lbl}{state_st}<br/>
		{phone_st_lbl}{phone_st}<br/><br/>

		<b><?php echo JText::_('COM_REDSHOP_OPTION_TAG'); ?></b><br /><br />
		{extra_field_st_start}{extra_field_st}{extra_field_st_end}
		<?php
		break;
	case 'shipping_box':
		?>
		<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_SHIPPING_BOX_HINT'); ?></b><br /><br />
		{shipping_box_heading} {shipping_box_list}
		<?php
		break;
	case 'shipping_pdf':
		?>
		<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_SHIPPING_PDF_HINT'); ?></b><br /><br />
		{order_information_lbl} {order_id_lbl} {order_id} {order_number_lbl} {order_number} {order_date_lbl} {order_date} <br />
		{order_status_lbl} {order_status} {shipping_address_information_lbl} {shipping_firstname_lbl} {shipping_firstname} {shipping_lastname_lbl} {shipping_lastname} <br />
		{shipping_address_lbl} {shipping_address} {shipping_zip_lbl} {shipping_zip} {shipping_city_lbl} {shipping_city} {shipping_country_lbl} {shipping_state_lbl} <br />
		{shipping_phone_lbl} {company_name_lbl} {company_name} {shipping_country} {shipping_phone} {shipping_state}
		<?php
		break;
	case 'stock_note':
		?>
		<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_STOCK_NOTE_HINT'); ?></b><br /><br />
		{order_id_lbl} {order_id} {order_date_lbl} {order_date} {product_name_lbl} {product_number_lbl} {product_quantity_lbl} {product_name} <br />
		{product_attribute} {product_number} {product_quantity} {requisition_number} {requisition_number_lbl}
		<?php
		break;
	case 'wishlist':
		?>
		<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_WISHLIST_HINT'); ?></b><br /><br />
		{if product_userfield} {product_userfield end if} {all_cart} {attribute_template:attributes} {product_loop_start} {product_loop_end} {mail_link} {product_price} {remove_product_link} <br />
		{product_thumb_image} {product_name} {back_link} {product_thumb_image_2} {product_thumb_image_3} <br />
		{price_excluding_vat} {product_price_table} {product_old_price} {product_price_saving} {product_price_saving_percentage} {accessory_template:templatename} {product_s_desc} {read_more} {read_more_link}
		<?php
		break;
	case 'wishlist_mail':
		?>
		<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_WISHLIST_MAIL_HINT'); ?></b><br /><br />
		{email_to_friend} {emailto_lbl} {emailto} {sender_lbl} {sender} <br />
		{mail_lbl} {mail} {subject_lbl} {subject} {cancel_button} {send_button}
		<?php
		break;
	case 'wrapper':
		?>
		<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_WRAPPER_HINT'); ?></b><br /><br />
		{wrapper_dropdown} {wrapper_image} {wrapper_add_checkbox} {wrapper_price}
		<?php
		break;
}
