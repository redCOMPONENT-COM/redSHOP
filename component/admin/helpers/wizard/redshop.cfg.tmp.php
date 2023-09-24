<?php

global $temparray;
$temparray                                          = array();
$temparray["currency_symbol"]                       = Redshop::getConfig()->get('REDCURRENCY_SYMBOL');
$temparray["price_seperator"]                       = Redshop::getConfig()->get('PRICE_SEPERATOR');
$temparray["thousand_seperator"]                    = Redshop::getConfig()->get('THOUSAND_SEPERATOR');
$temparray["currency_symbol_position"]              = Redshop::getConfig()->get('CURRENCY_SYMBOL_POSITION');
$temparray["price_decimal"]                         = Redshop::getConfig()->get('PRICE_DECIMAL');
$temparray["show_price"]                            = Redshop::getConfig()->get('SHOW_PRICE');
$temparray["use_tax_exempt"]                        = Redshop::getConfig()->get('USE_TAX_EXEMPT');
$temparray["tax_exempt_apply_vat"]                  = Redshop::getConfig()->get('TAX_EXEMPT_APPLY_VAT');
$temparray["use_as_catalog"]                        = Redshop::getConfig()->get('PRE_USE_AS_CATALOG');
$temparray["price_replacement"]                     = Redshop::getConfig()->get('PRICE_REPLACE');
$temparray["price_replacement_url"]                 = Redshop::getConfig()->get('PRICE_REPLACE_URL');
$temparray["zero_price_replacement"]                = Redshop::getConfig()->get('ZERO_PRICE_REPLACE');
$temparray["zero_price_replacement_url"]            = Redshop::getConfig()->get('ZERO_PRICE_REPLACE_URL');
$temparray["discount_mail_send"]                    = Redshop::getConfig()->get('DISCOUNT_MAIL_SEND');
$temparray["discount_type"]                         = Redshop::getConfig()->get('DISCOUNT_TYPE');
$temparray["discount_enable"]                       = Redshop::getConfig()->get('DISCOUNT_ENABLE');
$temparray["coupons_enable"]                        = Redshop::getConfig()->get('COUPONS_ENABLE');
$temparray["vouchers_enable"]                       = Redshop::getConfig()->get('VOUCHERS_ENABLE');
$temparray["apply_voucher_coupon_already_discount"] = Redshop::getConfig()->get(
    'APPLY_VOUCHER_COUPON_ALREADY_DISCOUNT'
);
$temparray["attribute_as_product_in_economic"]      = Redshop::getConfig()->get('ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC');
$temparray["economic_book_invoice_number"]          = Redshop::getConfig()->get('ECONOMIC_BOOK_INVOICE_NUMBER');
$temparray["days_mail1"]                            = Redshop::getConfig()->get('DAYS_MAIL1');
$temparray["days_mail2"]                            = Redshop::getConfig()->get('DAYS_MAIL2');
$temparray["days_mail3"]                            = Redshop::getConfig()->get('DAYS_MAIL3');
$temparray["vat_rate_after_discount"]               = Redshop::getConfig()->get('VAT_RATE_AFTER_DISCOUNT');
$temparray["discoupon_duration"]                    = Redshop::getConfig()->get('DISCOUPON_DURATION');
$temparray["shipping_after"]                        = Redshop::getConfig()->get('SHIPPING_AFTER');
$temparray["discoupon_percent_or_total"]            = Redshop::getConfig()->get('DISCOUPON_PERCENT_OR_TOTAL');
$temparray["discoupon_value"]                       = Redshop::getConfig()->get('DISCOUPON_VALUE');
$temparray["default_vat_country"]                   = Redshop::getConfig()->get('DEFAULT_VAT_COUNTRY');
$temparray["default_vat_group"]                     = Redshop::getConfig()->get('DEFAULT_VAT_GROUP');
$temparray["vat_based_on"]                          = Redshop::getConfig()->get('VAT_BASED_ON');
$temparray["apply_vat_on_discount"]                 = Redshop::getConfig()->get('APPLY_VAT_ON_DISCOUNT');
$temparray["calculate_vat_on"]                      = Redshop::getConfig()->get('CALCULATE_VAT_ON');
$temparray["vat_introtext"]                         = Redshop::getConfig()->get('VAT_INTROTEXT');
$temparray["with_vat_text_info"]                    = Redshop::getConfig()->get('WITH_VAT_TEXT_INFO');
$temparray["without_vat_text_info"]                 = Redshop::getConfig()->get('WITHOUT_VAT_TEXT_INFO');
$temparray["rating_msg"]                            = Redshop::getConfig()->get('RATING_MSG');
$temparray['rating_review_login_required']          = Redshop::getConfig()->get('RATING_REVIEW_LOGIN_REQUIRED');
$temparray["register_method"]                       = Redshop::getConfig()->get('REGISTER_METHOD');
$temparray["show_email_verification"]               = Redshop::getConfig()->get('SHOW_EMAIL_VERIFICATION');
$temparray["new_customer_selection"]                = Redshop::getConfig()->get('NEW_CUSTOMER_SELECTION');
$temparray["terms_article"]                         = Redshop::getConfig()->get('TERMS_ARTICLE_ID');
$temparray["terms_article_id"]                      = Redshop::getConfig()->get('TERMS_ARTICLE_ID');
$temparray["allow_customer_register_type"]          = Redshop::getConfig()->get('ALLOW_CUSTOMER_REGISTER_TYPE');
$temparray["default_customer_register_type"]        = Redshop::getConfig()->get('DEFAULT_CUSTOMER_REGISTER_TYPE');
$temparray["welcomepage_introtext"]                 = Redshop::getConfig()->get('WELCOMEPAGE_INTROTEXT');
$temparray["registration_introtext"]                = Redshop::getConfig()->get('REGISTRATION_INTROTEXT');
$temparray["registration_comp_introtext"]           = Redshop::getConfig()->get('REGISTRATION_COMPANY_INTROTEXT');
$temparray["portal_shop"]                           = Redshop::getConfig()->get('PORTAL_SHOP');
$temparray["portal_login_itemid"]                   = Redshop::getConfig()->get('PORTAL_LOGIN_ITEMID');
$temparray["portal_logout_itemid"]                  = Redshop::getConfig()->get('PORTAL_LOGOUT_ITEMID');
$temparray["default_portal_name"]                   = Redshop::getConfig()->get('DEFAULT_PORTAL_NAME');
$temparray["shopper_group_default_private"]         = Redshop::getConfig()->get('SHOPPER_GROUP_DEFAULT_PRIVATE');
$temparray["shopper_group_default_company"]         = Redshop::getConfig()->get('SHOPPER_GROUP_DEFAULT_COMPANY');
$temparray["new_shopper_group_get_value_from"]      = Redshop::getConfig()->get('NEW_SHOPPER_GROUP_GET_VALUE_FROM');
$temparray["default_portal_logo"]                   = Redshop::getConfig()->get('DEFAULT_PORTAL_LOGO');
$temparray["default_portal_logo_tmp"]               = Redshop::getConfig()->get('DEFAULT_PORTAL_LOGO');
$temparray["administrator_email"]                   = Redshop::getConfig()->get('ADMINISTRATOR_EMAIL');
$temparray["table_prefix"]                          = Redshop::getConfig()->get('TABLE_PREFIX');
$temparray["shop_country"]                          = Redshop::getConfig()->get('SHOP_COUNTRY');
$temparray["default_shipping_country"]              = Redshop::getConfig()->get('DEFAULT_SHIPPING_COUNTRY');
$temparray["welcome_msg"]                           = Redshop::getConfig()->get('WELCOME_MSG');
$temparray["shop_name"]                             = Redshop::getConfig()->get('SHOP_NAME');
$temparray["default_dateformat"]                    = Redshop::getConfig()->get('DEFAULT_DATEFORMAT');
$temparray["invoice_mail_enable"]                   = Redshop::getConfig()->get('INVOICE_MAIL_ENABLE');
$temparray["invoice_mail_send_option"]              = Redshop::getConfig()->get('INVOICE_MAIL_SEND_OPTION');
$temparray["country_list"]                          = Redshop::getConfig()->get('COUNTRY_LIST');
$temparray["currency_code"]                         = Redshop::getConfig()->get('CURRENCY_CODE');
$temparray["default_vat_state"]                     = Redshop::getConfig()->get('DEFAULT_VAT_STATE');
$temparray["noof_thumb_for_scroller"]               = Redshop::getConfig()->get('NOOF_THUMB_FOR_SCROLLER');
$temparray["watermark_product_additional_image"]    = Redshop::getConfig()->get('WATERMARK_PRODUCT_ADDITIONAL_IMAGE');
$temparray["accessory_as_product_in_cart_enable"]   = Redshop::getConfig()->get('ACCESSORY_AS_PRODUCT_IN_CART_ENABLE');
$temparray["attribute_scroller_thumb_width"]        = Redshop::getConfig()->get('ATTRIBUTE_SCROLLER_THUMB_WIDTH');
$temparray["attribute_scroller_thumb_height"]       = Redshop::getConfig()->get('ATTRIBUTE_SCROLLER_THUMB_HEIGHT');
$temparray["use_encoding"]                          = Redshop::getConfig()->get('USE_ENCODING');
$temparray["requestquote_image"]                    = Redshop::getConfig()->get('REQUESTQUOTE_IMAGE');
$temparray["requestquote_background"]               = Redshop::getConfig()->get('REQUESTQUOTE_BACKGROUND');
$temparray["addtocart_delete"]                      = Redshop::getConfig()->get('ADDTOCART_DELETE');
$temparray["addtocart_update"]                      = Redshop::getConfig()->get('ADDTOCART_UPDATE');
$temparray["shopper_group_default_unregistered"]    = Redshop::getConfig()->get('SHOPPER_GROUP_DEFAULT_UNREGISTERED');
$temparray["required_vat_number"]                   = Redshop::getConfig()->get('REQUIRED_VAT_NUMBER');
$temparray["default_stockamount_thumb_width"]       = Redshop::getConfig()->get('DEFAULT_STOCKAMOUNT_THUMB_WIDTH');
$temparray["default_stockamount_thumb_height"]      = Redshop::getConfig()->get('DEFAULT_STOCKAMOUNT_THUMB_HEIGHT');
$temparray["show_terms_and_conditions"]             = Redshop::getConfig()->get('SHOW_TERMS_AND_CONDITIONS');
