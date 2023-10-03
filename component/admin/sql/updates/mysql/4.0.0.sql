-- ------------------------------------------------------
-- Table '#__redshop_category'
-- ------------------------------------------------------
CALL redSHOP_Column_Update('#__redshop_product', 'short_description', 'short_description', 'LONGTEXT');

CALL redSHOP_Column_Update('#__redshop_category', 'description' 'description' 'LONGTEXT');
CALL redSHOP_Column_Update('#__redshop_category', 'more_template' 'more_template' 'VARCHAR(255)  NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_category', 'category_thumb_image' 'category_thumb_image' 'VARCHAR(250)  NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_category', 'category_full_image' 'category_full_image' 'VARCHAR(250)  NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_category', 'metakey' 'metakey' 'VARCHAR(250)  NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_category', 'name' 'name' 'VARCHAR(250)  NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_category', 'metadesc' 'metadesc' 'LONGTEXT');
CALL redSHOP_Column_Update('#__redshop_category', 'metalanguage_setting' 'metalanguage_setting' 'TEXT');
CALL redSHOP_Column_Update('#__redshop_category', 'metarobot_info' 'metarobot_info' 'TEXT');
CALL redSHOP_Column_Update('#__redshop_category', 'pagetitle' 'pagetitle' 'TEXT');
CALL redSHOP_Column_Update('#__redshop_category', 'pageheading' 'pageheading' 'LONGTEXT');
CALL redSHOP_Column_Update('#__redshop_category', 'sef_url' 'sef_url' 'TEXT');
CALL redSHOP_Column_Update('#__redshop_category', 'ordering' 'ordering' 'INT(11) NOT NULL DEFAULT "0"');
CALL redSHOP_Column_Update('#__redshop_category', 'products_per_page' 'products_per_page' 'INT(11) NOT NULL DEFAULT "0"');
CALL redSHOP_Column_Update('#__redshop_category', 'template' 'template' 'INT(11) NOT NULL DEFAULT "0"');
CALL redSHOP_Column_Update('#__redshop_category', 'published' 'published' 'TINYINT(4) NOT NULL DEFAULT "0"');
CALL redSHOP_Column_Update('#__redshop_category', 'canonical_url' 'canonical_url' 'TEXT');
CALL redSHOP_Column_Update('#__redshop_category', 'category_back_full_image' 'category_back_full_image' 'VARCHAR(250) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_category', 'compare_template_id' 'compare_template_id' 'VARCHAR(255) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_category', 'alias' 'alias' 'VARCHAR(255) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_category', 'path' 'path' 'VARCHAR(255) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_category', 'product_filter_params' 'product_filter_params' 'MEDIUMTEXT');

-- ------------------------------------------------------
-- Table '#__redshop_product'
-- ------------------------------------------------------
CALL redSHOP_Column_Update('#__redshop_product', 'product_parent_id' 'product_parent_id' 'INT(11) NOT NULL DEFAULT "0"');
CALL redSHOP_Column_Update('#__redshop_product', 'manufacturer_id' 'manufacturer_id' 'INT(11) NOT NULL DEFAULT "0"');
CALL redSHOP_Column_Update('#__redshop_product', 'supplier_id' 'supplier_id' 'INT(11) NOT NULL DEFAULT "0"');
CALL redSHOP_Column_Update('#__redshop_product', 'product_on_sale' 'product_on_sale' 'TINYINT(4) NOT NULL DEFAULT "0"');
CALL redSHOP_Column_Update('#__redshop_product', 'product_special' 'product_special' 'TINYINT(4) NOT NULL DEFAULT "0"');
CALL redSHOP_Column_Update('#__redshop_product', 'product_download' 'product_download' 'TINYINT(4) NOT NULL DEFAULT "0"');
CALL redSHOP_Column_Update('#__redshop_product', 'product_template' 'product_template' 'INT(11) NOT NULL DEFAULT "0"');
CALL redSHOP_Column_Update('#__redshop_product', 'product_name' 'product_name' 'VARCHAR(250) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_product', 'product_price' 'product_price' 'DOUBLE NOT NULL DEFAULT "0"');
CALL redSHOP_Column_Update('#__redshop_product', 'discount_price' 'discount_price' 'DOUBLE NOT NULL DEFAULT "0"');
CALL redSHOP_Column_Update('#__redshop_product', 'discount_stratdate' 'discount_stratdate' 'INT(11) NOT NULL DEFAULT "0"');
CALL redSHOP_Column_Update('#__redshop_product', 'discount_enddate' 'discount_enddate' 'INT(11) NOT NULL DEFAULT "0"');
CALL redSHOP_Column_Update('#__redshop_product', 'product_number' 'product_number' 'VARCHAR(250) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_product', 'product_type' 'product_type' 'VARCHAR(20) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_product', 'product_s_desc' 'product_s_desc' 'LONGTEXT');
CALL redSHOP_Column_Update('#__redshop_product', 'product_desc' 'product_desc' 'LONGTEXT');
CALL redSHOP_Column_Update('#__redshop_product', 'product_volume' 'product_volume' 'DOUBLE NOT NULL DEFAULT "0"');
CALL redSHOP_Column_Update('#__redshop_product', 'product_tax_id' 'product_tax_id' 'INT(11) NOT NULL DEFAULT "0"');
CALL redSHOP_Column_Update('#__redshop_product', 'published' 'published' 'TINYINT(4) NOT NULL DEFAULT "0"');
CALL redSHOP_Column_Update('#__redshop_product', 'product_thumb_image' 'product_thumb_image' 'VARCHAR(250) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_product', 'product_full_image' 'product_full_image' 'VARCHAR(250) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_product', 'publish_date' 'publish_date' 'DATETIME NOT NULL DEFAULT "0000-00-00 00:00:00"');
CALL redSHOP_Column_Update('#__redshop_product', 'update_date' 'update_date' 'TIMESTAMP NOT NULL DEFAULT "0000-00-00 00:00:00"');
CALL redSHOP_Column_Update('#__redshop_product', 'visited' 'visited' 'INT(11) NOT NULL DEFAULT "0"');
CALL redSHOP_Column_Update('#__redshop_product', 'metakey' 'metakey' 'TEXT');
CALL redSHOP_Column_Update('#__redshop_product', 'metadesc' 'metadesc' 'TEXT');
CALL redSHOP_Column_Update('#__redshop_product', 'metalanguage_setting' 'metalanguage_setting' 'TEXT');
CALL redSHOP_Column_Update('#__redshop_product', 'metarobot_info' 'metarobot_info' 'TEXT');
CALL redSHOP_Column_Update('#__redshop_product', 'pagetitle' 'pagetitle' 'TEXT');
CALL redSHOP_Column_Update('#__redshop_product', 'pageheading' 'pageheading' 'TEXT');
CALL redSHOP_Column_Update('#__redshop_product', 'sef_url' 'sef_url' 'TEXT');
CALL redSHOP_Column_Update('#__redshop_product', 'cat_in_sefurl' 'cat_in_sefurl' 'INT(11) NOT NULL DEFAULT "0"');
CALL redSHOP_Column_Update('#__redshop_product', 'weight', 'weight', 'FLOAT(10,3) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_product', 'expired' 'expired' 'TINYINT(4) NOT NULL DEFAULT "0"');
CALL redSHOP_Column_Update('#__redshop_product', 'not_for_sale' 'not_for_sale' 'TINYINT(4) NOT NULL DEFAULT "0"');
CALL redSHOP_Column_Update('#__redshop_product', 'use_discount_calc' 'use_discount_calc' 'TINYINT(4) NOT NULL DEFAULT "0"');
CALL redSHOP_Column_Update('#__redshop_product', 'discount_calc_method' 'discount_calc_method' 'VARCHAR(255) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_product', 'min_order_product_quantity' 'min_order_product_quantity' 'INT(11) NOT NULL DEFAULT "0"');
CALL redSHOP_Column_Update('#__redshop_product', 'attribute_set_id' 'attribute_set_id' 'INT(11) NOT NULL DEFAULT "0"');
CALL redSHOP_Column_Update('#__redshop_product', 'product_length', 'product_length', 'DECIMAL(10,2) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_product', 'product_height' 'product_height' 'DECIMAL(10,2) NOT NULL DEFAULT "0"');
CALL redSHOP_Column_Update('#__redshop_product', 'product_width' 'product_width' 'DECIMAL(10,2) NOT NULL DEFAULT "0"');
CALL redSHOP_Column_Update('#__redshop_product', 'product_diameter' 'product_diameter' 'DECIMAL(10,2) NOT NULL DEFAULT "0"');
CALL redSHOP_Column_Update('#__redshop_product', 'product_availability_date' 'product_availability_date' 'INT(11) NOT NULL DEFAULT "0"');
CALL redSHOP_Column_Update('#__redshop_product', 'use_range' 'use_range' 'TINYINT(4) NOT NULL DEFAULT "0"');
CALL redSHOP_Column_Update('#__redshop_product', 'product_tax_group_id' 'product_tax_group_id' 'INT(11) NOT NULL DEFAULT "0"');
CALL redSHOP_Column_Update('#__redshop_product', 'product_download_days' 'product_download_days' 'INT(11) NOT NULL DEFAULT "0"');
CALL redSHOP_Column_Update('#__redshop_product', 'product_download_limit' 'product_download_limit' 'INT(11) NOT NULL DEFAULT "0"');
CALL redSHOP_Column_Update('#__redshop_product', 'product_download_clock' 'product_download_clock' 'INT(11) NOT NULL DEFAULT "0"');
CALL redSHOP_Column_Update('#__redshop_product', 'product_download_clock_min' 'product_download_clock_min' 'INT(11) NOT NULL DEFAULT "0"');
CALL redSHOP_Column_Update('#__redshop_product', 'accountgroup_id' 'accountgroup_id' 'INT(11) NOT NULL DEFAULT "0"');
CALL redSHOP_Column_Update('#__redshop_product', 'canonical_url' 'canonical_url' 'TEXT');
CALL redSHOP_Column_Update('#__redshop_product', 'minimum_per_product_total' 'minimum_per_product_total' 'INT(11) NOT NULL DEFAULT "0"');
CALL redSHOP_Column_Update('#__redshop_product', 'allow_decimal_piece' 'allow_decimal_piece' 'INT(4) NOT NULL DEFAULT "0"');
CALL redSHOP_Column_Update('#__redshop_product', 'quantity_selectbox_value' 'quantity_selectbox_value' 'VARCHAR(255) NULL DEFAULT NULL');
CALL redSHOP_Column_Update('#__redshop_product', 'checked_out' 'checked_out' 'INT(11) NOT NULL DEFAULT "0"');
CALL redSHOP_Column_Update('#__redshop_product', 'checked_out_time' 'checked_out_time' 'DATETIME NOT NULL DEFAULT "0000-00-00 00:00:00"');
CALL redSHOP_Column_Update('#__redshop_product', 'max_order_product_quantity' 'max_order_product_quantity' 'INT(11) NOT NULL DEFAULT "0"');
CALL redSHOP_Column_Update('#__redshop_product', 'product_download_infinite' 'product_download_infinite' 'TINYINT(4) NOT NULL DEFAULT "0"');
CALL redSHOP_Column_Update('#__redshop_product', 'product_back_full_image' 'product_back_full_image' 'VARCHAR(250) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_product', 'product_back_thumb_image' 'product_back_thumb_image' 'VARCHAR(250) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_product', 'product_preview_image' 'product_preview_image' 'VARCHAR(250) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_product', 'product_preview_back_image' 'product_preview_back_image' 'VARCHAR(250) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_product', 'preorder' 'preorder' 'VARCHAR(255) NOT NULL DEFAULT ""');

-- ------------------------------------------------------
-- Table '#__redshop_manufacturer'
-- ------------------------------------------------------
CALL redSHOP_Column_Update('#__redshop_manufacturer', 'description' 'description' 'TEXT');
CALL redSHOP_Column_Update('#__redshop_manufacturer', 'template_id' 'template_id' 'INT(11) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_manufacturer', 'metakey' 'metakey' 'TEXT');
CALL redSHOP_Column_Update('#__redshop_manufacturer', 'metadesc' 'metadesc' 'TEXT');
CALL redSHOP_Column_Update('#__redshop_manufacturer', 'metalanguage_setting' 'metalanguage_setting' 'TEXT');
CALL redSHOP_Column_Update('#__redshop_manufacturer', 'metarobot_info' 'metarobot_info' 'TEXT');
CALL redSHOP_Column_Update('#__redshop_manufacturer', 'pagetitle' 'pagetitle' 'TEXT');
CALL redSHOP_Column_Update('#__redshop_manufacturer', 'pageheading' 'pageheading' 'TEXT');
CALL redSHOP_Column_Update('#__redshop_manufacturer', 'sef_url' 'sef_url' 'TEXT');
CALL redSHOP_Column_Update('#__redshop_manufacturer', 'excluding_category_list' 'excluding_category_list' 'TEXT');

-- ------------------------------------------------------
-- Table '#__redshop_mass_discount'
-- ------------------------------------------------------
CALL redSHOP_Column_Update('#__redshop_mass_discount', 'discount_product' 'discount_product' 'LONGTEXT');
CALL redSHOP_Column_Update('#__redshop_mass_discount', 'category_id' 'category_id' 'LONGTEXT');
CALL redSHOP_Column_Update('#__redshop_mass_discount', 'manufacturer_id' 'manufacturer_id' 'LONGTEXT');
CALL redSHOP_Column_Update('#__redshop_mass_discount', 'type' 'type' 'TINYINT(4) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_mass_discount', 'amount' 'amount' 'DOUBLE(10,2) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_mass_discount', 'start_date' 'start_date' 'INT(11) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_mass_discount', 'end_date' 'end_date' 'INT(11) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_mass_discount', 'name' 'name' 'VARCHAR(255) NOT NULL DEFAULT ""');

-- ------------------------------------------------------
-- Table '#__redshop_supplier'
-- ------------------------------------------------------
CALL redSHOP_Column_Update('#__redshop_supplier', 'description' 'description' 'TEXT');

-- ------------------------------------------------------
-- Table '#__redshop_textlibrary'
-- ------------------------------------------------------
CALL redSHOP_Column_Update('#__redshop_textlibrary', 'content' 'content' 'TEXT');
CALL redSHOP_Column_Update('#__redshop_textlibrary', 'section' 'section' 'VARCHAR(255) NOT NULL DEFAULT ""');

-- ------------------------------------------------------
-- Table '#__redshop_users_info'
-- ------------------------------------------------------
CALL redSHOP_Column_Update('#__redshop_users_info', 'user_id' 'user_id' 'INT(11) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_users_info', 'user_email' 'user_email' 'VARCHAR(255) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_users_info', 'address_type' 'address_type' 'VARCHAR(11) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_users_info', 'firstname' 'firstname' 'VARCHAR(250) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_users_info', 'lastname' 'lastname' 'VARCHAR(250) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_users_info', 'vat_number' 'vat_number' 'VARCHAR(250) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_users_info', 'tax_exempt' 'tax_exempt' 'TINYINT(4) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_users_info', 'shopper_group_id' 'shopper_group_id' 'INT(11) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_users_info', 'country_code' 'country_code' 'VARCHAR(11) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_users_info', 'address' 'address' 'VARCHAR(255) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_users_info', 'city' 'city' 'VARCHAR(50) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_users_info', 'state_code' 'state_code' 'VARCHAR(11) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_users_info', 'zipcode' 'zipcode' 'VARCHAR(255) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_users_info', 'phone' 'phone' 'VARCHAR(50) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_users_info', 'tax_exempt_approved' 'tax_exempt_approved' 'TINYINT(1) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_users_info', 'approved' 'approved' 'TINYINT(1) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_users_info', 'is_company' 'is_company' 'TINYINT(4) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_users_info', 'ean_number' 'ean_number' 'VARCHAR(250) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_users_info', 'braintree_vault_number' 'braintree_vault_number' 'VARCHAR(255) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_users_info', 'veis_vat_number' 'veis_vat_number' 'VARCHAR(255) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_users_info', 'veis_status' 'veis_status' 'VARCHAR(255) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_users_info', 'company_name' 'company_name' 'VARCHAR(255) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_users_info', 'requesting_tax_exempt' 'requesting_tax_exempt' 'TINYINT(4) NOT NULL DEFAULT "0"');
CALL redSHOP_Column_Update('#__redshop_users_info', 'accept_terms_conditions' 'accept_terms_conditions' 'TINYINT(4) NOT NULL DEFAULT 0');

-- ------------------------------------------------------
-- Table '#__redshop_users_info'
-- ------------------------------------------------------
CALL redSHOP_Column_Update('#__redshop_product_attribute_property', 'property_name' 'property_name' 'VARCHAR(255) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_product_attribute_property', 'property_price' 'property_price' 'DOUBLE NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_product_attribute_property', 'property_image' 'property_image' 'VARCHAR(255) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_product_attribute_property', 'property_main_image' 'property_main_image' 'VARCHAR(255) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_product_attribute_property', 'ordering' 'ordering' 'INT(11) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_product_attribute_property', 'setdefault_selected' 'setdefault_selected' 'TINYINT(4) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_product_attribute_property', 'setrequire_selected' 'setrequire_selected' 'TINYINT(3) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_product_attribute_property', 'setmulti_selected' 'setmulti_selected' 'TINYINT(4) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_product_attribute_property', 'setdisplay_type' 'setdisplay_type' 'VARCHAR(255) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_product_attribute_property', 'extra_field' 'extra_field' 'VARCHAR(250) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_product_attribute_property', 'property_number' 'property_number' 'VARCHAR(255) NOT NULL DEFAULT ""');

-- ------------------------------------------------------
-- Table '#__redshop_product_subattribute_color'
-- ------------------------------------------------------
CALL redSHOP_Column_Update('#__redshop_product_subattribute_color', 'subattribute_color_name' 'subattribute_color_name' 'VARCHAR(255) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_product_subattribute_color', 'subattribute_color_price' 'subattribute_color_price' 'DOUBLE NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_product_subattribute_color', 'oprand' 'oprand' 'CHAR(1) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_product_subattribute_color', 'subattribute_color_image' 'subattribute_color_image' 'VARCHAR(255) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_product_subattribute_color', 'subattribute_id' 'subattribute_id' 'INT(11) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_product_subattribute_color', 'ordering' 'ordering' 'INT(11) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_product_subattribute_color', 'setdefault_selected' 'setdefault_selected' 'TINYINT(4) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_product_subattribute_color', 'extra_field' 'extra_field' 'VARCHAR(250) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_product_subattribute_color', 'subattribute_color_number' 'subattribute_color_number' 'VARCHAR(255) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_product_subattribute_color', 'subattribute_color_title' 'subattribute_color_title' 'VARCHAR(255) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_product_subattribute_color', 'subattribute_color_main_image' 'subattribute_color_main_image' 'VARCHAR(255) NULL DEFAULT ""');

-- ------------------------------------------------------
-- Table '#__redshop_media'
-- ------------------------------------------------------
CALL redSHOP_Column_Update('#__redshop_media', 'media_name' 'media_name' 'VARCHAR(250) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_media', 'media_alternate_text' 'media_alternate_text' 'VARCHAR(255) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_media', 'media_section' 'media_section' 'VARCHAR(20) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_media', 'section_id' 'section_id' 'INT(11) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_media', 'media_type' 'media_type' 'VARCHAR(250) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_media', 'media_mimetype' 'media_mimetype' 'VARCHAR(20) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_media', 'published' 'published' 'TINYINT(4) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_media', 'ordering' 'ordering' 'INT(11) NOT NULL DEFAULT "0"');

-- ------------------------------------------------------
-- Table '#__redshop_orders'
-- ------------------------------------------------------
CALL redSHOP_Column_Update('#__redshop_orders', 'invoice_number_chrono' 'invoice_number_chrono' 'INT(11) NOT NULL DEFAULT 0 COMMENT "Order invoice number in chronological order"');
CALL redSHOP_Column_Update('#__redshop_orders', 'invoice_number' 'invoice_number' 'VARCHAR(255) NOT NULL DEFAULT "" COMMENT "Formatted Order Invoice for final use"');
CALL redSHOP_Column_Update('#__redshop_orders', 'barcode' 'barcode' 'VARCHAR(13) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_orders', 'order_tax_details' 'order_tax_details' 'TEXT');
CALL redSHOP_Column_Update('#__redshop_orders', 'special_discount_amount' 'special_discount_amount' 'DECIMAL(12,2) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_orders', 'payment_dicount' 'payment_dicount' 'DECIMAL(12,2) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_orders', 'order_payment_status' 'order_payment_status' 'VARCHAR(25) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_orders', 'customer_note' 'customer_note' 'TEXT');
CALL redSHOP_Column_Update('#__redshop_orders', 'encr_key' 'encr_key' 'VARCHAR(255) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_orders', 'invoice_no' 'invoice_no' 'VARCHAR(255) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_orders', 'mail1_status' 'mail1_status' 'TINYINT(1) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_orders', 'mail2_status' 'mail2_status' 'TINYINT(1) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_orders', 'mail3_status' 'mail3_status' 'TINYINT(1) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_orders', 'special_discount' 'special_discount' 'DECIMAL(10,2) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_orders', 'payment_discount' 'payment_discount' 'DECIMAL(10,2) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_orders', 'is_booked' 'is_booked' 'TINYINT(1) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_orders', 'order_label_create' 'order_label_create' 'TINYINT(1) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_orders', 'vm_order_number' 'vm_order_number' 'VARCHAR(32) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_orders', 'requisition_number' 'requisition_number' 'VARCHAR(255) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_orders', 'bookinvoice_number' 'bookinvoice_number' 'INT(11) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_orders', 'bookinvoice_date' 'bookinvoice_date' 'INT(11) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_orders', 'referral_code' 'referral_code' 'VARCHAR(50) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_orders', 'customer_message' 'customer_message' 'VARCHAR(255) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_orders', 'shop_id' 'shop_id' 'VARCHAR(255) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_orders', 'order_discount_vat' 'order_discount_vat' 'DECIMAL(10,3) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_orders', 'track_no' 'track_no' 'VARCHAR(250) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_orders', 'payment_oprand' 'payment_oprand' 'VARCHAR(50) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_orders', 'discount_type' 'discount_type' 'VARCHAR(255) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_orders', 'analytics_status' 'analytics_status' 'INT(1) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_orders', 'tax_after_discount' 'tax_after_discount' 'DECIMAL(10,3) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_orders', 'recuuring_subcription_id' 'recuuring_subcription_id' 'VARCHAR(500) NOT NULL DEFAULT ""');

-- ------------------------------------------------------
-- Table '#__redshop_order_item'
-- ------------------------------------------------------
CALL redSHOP_Column_Update('#__redshop_order_item', 'order_item_name' 'order_item_name' 'VARCHAR(255) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_order_item', 'customer_note' 'customer_note' 'TEXT');
CALL redSHOP_Column_Update('#__redshop_order_item', 'product_accessory' 'product_accessory' 'TEXT');
CALL redSHOP_Column_Update('#__redshop_order_item', 'delivery_time' 'delivery_time' 'INT(11) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_order_item', 'stockroom_id' 'stockroom_id' 'VARCHAR(255) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_order_item', 'stockroom_quantity' 'stockroom_quantity' 'VARCHAR(255) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_order_item', 'is_split' 'is_split' 'TINYINT(1) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_order_item', 'attribute_image' 'attribute_image' 'TEXT');
CALL redSHOP_Column_Update('#__redshop_order_item', 'is_giftcard' 'is_giftcard' 'TINYINT(4) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_order_item', 'wrapper_id' 'wrapper_id' 'INT(11) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_order_item', 'wrapper_price' 'wrapper_price' 'DECIMAL(10,2) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_order_item', 'giftcard_user_name' 'giftcard_user_name' 'VARCHAR(255) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_order_item', 'giftcard_user_email' 'giftcard_user_email' 'VARCHAR(255) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_order_item', 'product_item_old_price' 'product_item_old_price' 'DECIMAL(10,4) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_order_item', 'product_purchase_price' 'product_purchase_price' 'DECIMAL(10,4) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_order_item', 'discount_calc_data' 'discount_calc_data' 'TEXT');

-- ------------------------------------------------------
-- Table '#__redshop_product_accessory'
-- ------------------------------------------------------
CALL redSHOP_Column_Update('#__redshop_product_accessory', 'accessory_price' 'accessory_price' 'DOUBLE NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_product_accessory', 'oprand' 'oprand' 'CHAR(1) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_product_accessory', 'setdefault_selected' 'setdefault_selected' 'TINYINT(4) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_product_accessory', 'ordering' 'ordering' 'INT(11) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_product_accessory', 'category_id' 'category_id' 'INT(11) NOT NULL DEFAULT 0');

-- ------------------------------------------------------
-- Table '#__redshop_fields'
-- ------------------------------------------------------
CALL redSHOP_Column_Update('#__redshop_fields', 'title' 'title' 'VARCHAR(250) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_fields', 'name' 'name' 'VARCHAR(250) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_fields', 'desc' 'desc' 'LONGTEXT');
CALL redSHOP_Column_Update('#__redshop_fields', 'class' 'class' 'VARCHAR(20) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_fields', 'section' 'section' 'VARCHAR(20) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_fields', 'maxlength' 'maxlength' 'INT(11) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_fields', 'cols' 'cols' 'INT(11) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_fields', 'rows' 'rows' 'INT(11) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_fields', 'size' 'size' 'TINYINT(4) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_fields', 'show_in_front' 'show_in_front' 'TINYINT(4) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_fields', 'required' 'required' 'TINYINT(4) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_fields', 'published' 'published' 'TINYINT(4) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_fields', 'display_in_product' 'display_in_product' 'TINYINT(4) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_fields', 'ordering' 'ordering' 'INT(11) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_fields', 'display_in_checkout' 'display_in_checkout' 'TINYINT(4) NOT NULL DEFAULT 0');

-- ------------------------------------------------------
-- Table '#__redshop_fields_data'
-- ------------------------------------------------------
CALL redSHOP_Column_Update('#__redshop_fields_data', 'alt_text' 'alt_text' 'VARCHAR(255) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_fields_data', 'image_link' 'image_link' 'VARCHAR(255) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_fields_data', 'user_email' 'user_email' 'VARCHAR(255) NOT NULL DEFAULT ""');

-- ------------------------------------------------------
-- Table '#__redshop_product_rating'
-- ------------------------------------------------------
CALL redSHOP_Column_Update('#__redshop_product_rating', 'favoured' 'favoured' 'TINYINT(4) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_product_rating', 'published' 'published' 'TINYINT(4) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_product_rating', 'email' 'email' 'VARCHAR(200) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_product_rating', 'username' 'username' 'VARCHAR(255) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_product_rating', 'company_name' 'company_name' 'VARCHAR(255) NOT NULL DEFAULT ""');

-- ------------------------------------------------------
-- Table '#__redshop_tax_rate'
-- ------------------------------------------------------
CALL redSHOP_Column_Update('#__redshop_tax_rate', 'tax_group_id' 'tax_group_id' 'INT(11) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_tax_rate', 'shopper_group_id' 'shopper_group_id' 'INT(11) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_tax_rate', 'is_eu_country' 'is_eu_country' 'TINYINT(4) NOT NULL DEFAULT 0');

-- ------------------------------------------------------
-- Table '#__redshop_order_payment'
-- ------------------------------------------------------
CALL redSHOP_Column_Update('#__redshop_order_payment', 'order_payment_cardname' 'order_payment_cardname' 'BLOB NULL DEFAULT NULL');
CALL redSHOP_Column_Update('#__redshop_order_payment', 'order_payment_ccv' 'order_payment_ccv' 'BLOB NULL DEFAULT NULL');
CALL redSHOP_Column_Update('#__redshop_order_payment', 'order_payment_amount' 'order_payment_amount' 'DOUBLE(10,2) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_order_payment', 'order_payment_trans_id' 'order_payment_trans_id' 'TEXT');
CALL redSHOP_Column_Update('#__redshop_order_payment', 'order_transfee' 'order_transfee' 'DOUBLE(10,2) NOT NULL DEFAULT 0');

-- ------------------------------------------------------
-- Table '#__redshop_order_users_info'
-- ------------------------------------------------------
CALL redSHOP_Column_Update('#__redshop_order_users_info', 'users_info_id' 'users_info_id' 'INT(11) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_order_users_info', 'user_id' 'user_id' 'INT(11) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_order_users_info', 'firstname' 'firstname' 'VARCHAR(250) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_order_users_info', 'lastname' 'lastname' 'VARCHAR(250) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_order_users_info', 'address_type' 'address_type' 'VARCHAR(255) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_order_users_info', 'vat_number' 'vat_number' 'VARCHAR(250) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_order_users_info', 'tax_exempt' 'tax_exempt' 'TINYINT(4) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_order_users_info', 'shopper_group_id' 'shopper_group_id' 'INT(11) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_order_users_info', 'address' 'address' 'VARCHAR(255) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_order_users_info', 'city' 'city' 'VARCHAR(255) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_order_users_info', 'country_code' 'country_code' 'VARCHAR(11) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_order_users_info', 'state_code' 'state_code' 'VARCHAR(11) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_order_users_info', 'zipcode' 'zipcode' 'VARCHAR(255) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_order_users_info', 'phone' 'phone' 'VARCHAR(50) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_order_users_info', 'tax_exempt_approved' 'tax_exempt_approved' 'TINYINT(1) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_order_users_info', 'approved' 'approved' 'TINYINT(1) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_order_users_info', 'is_company' 'is_company' 'TINYINT(4) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_order_users_info', 'user_email' 'user_email' 'VARCHAR(255) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_order_users_info', 'company_name' 'company_name' 'VARCHAR(255) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_order_users_info', 'ean_number' 'ean_number' 'VARCHAR(250) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_order_users_info', 'requesting_tax_exempt' 'requesting_tax_exempt' 'TINYINT(4) NOT NULL DEFAULT 0');
CALL redSHOP_Column_Update('#__redshop_order_users_info', 'thirdparty_email' 'thirdparty_email' 'VARCHAR(255) NOT NULL DEFAULT ""');

-- ------------------------------------------------------
-- Table '#__redshop_shipping_rate'
-- ------------------------------------------------------
CALL redSHOP_Column_Update('#__redshop_shipping_rate', 'deliver_type' 'deliver_type' 'INT(11) NOT NULL DEFAULT "0"');

-- ------------------------------------------------------
-- Table '#__redshop_order_attribute_item'
-- ------------------------------------------------------
CALL redSHOP_Column_Update('#__redshop_order_attribute_item', 'section_price' 'section_price' 'DECIMAL(15,4) NOT NULL DEFAULT "0.0000"');
CALL redSHOP_Column_Update('#__redshop_order_attribute_item', 'section_vat' 'section_vat' 'DECIMAL(15,4) NOT NULL DEFAULT "0.0000"');
CALL redSHOP_Column_Update('#__redshop_order_attribute_item', 'section_oprand' 'section_oprand' 'CHAR(1) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_order_attribute_item', 'stockroom_id' 'stockroom_id' 'VARCHAR(255) NOT NULL DEFAULT ""');
CALL redSHOP_Column_Update('#__redshop_order_attribute_item', 'stockroom_quantity' 'stockroom_quantity' 'VARCHAR(255) NOT NULL DEFAULT ""');

-- ------------------------------------------------------
-- Table '#__redshop_product_discount_calc_extra'
-- ------------------------------------------------------
CALL redSHOP_Column_Update('#__redshop_product_discount_calc_extra', 'oprand' 'oprand' 'CHAR(1) NOT NULL DEFAULT ""');

-- ------------------------------------------------------
-- Table '#__redshop_quotation_attribute_item'
-- ------------------------------------------------------
CALL redSHOP_Column_Update('#__redshop_quotation_attribute_item', 'section_price' 'section_price' 'DECIMAL(15,4) NOT NULL DEFAULT "0.0000"');
CALL redSHOP_Column_Update('#__redshop_quotation_attribute_item', 'section_vat' 'section_vat' 'DECIMAL(15,4) NOT NULL DEFAULT 0.0000');
CALL redSHOP_Column_Update('#__redshop_quotation_attribute_item', 'section_oprand' 'section_oprand' 'CHAR(1) NOT NULL DEFAULT ""');