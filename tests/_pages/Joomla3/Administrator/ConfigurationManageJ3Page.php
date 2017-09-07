<?php

/**
 *
 */
class ConfigurationManageJ3Page extends AdminJ3Page
{
	//nam page
	public static $namePage = "Configuration";

	public static $URL = '/administrator/index.php?option=com_redshop&view=configuration';

	public static $stockRoomYes = "#use_stockroom1-lbl";

	public static $stockRoomNo = "#use_stockroom0-lbl";

	public static $eidtInLineYes = "#inline_editing1-lbl";

	public static $editInLineNo = "#inline_editing0-lbl";

	public static $comparisonNo = "#compare_products0-lbl";

	public static $comparisonYes = "#compare_products1-lbl";

	//Price
	public static $showPriceYes = "#show_price1-lbl";

	public static $showPriceNo = "#show_price0-lbl";

	public static $countryPrice = ['id' => 's2id_default_vat_country'];

	public static $countrySearchPrice = ['id' => 's2id_autogen35_search'];

	public static $statePrice = ['id' => 's2id_default_vat_state'];

	public static $stateSearchPrice = ['id' => 's2id_autogen36_search'];

	public static $vatGroup = ['id' => 's2id_default_vat_group'];

	public static $vatSearchGroup = ['id' => 's2id_autogen37_search'];

	public static $vatDefaultBase = ['id' => 's2id_vat_based_on'];

	public static $vatSearchDefaultBase = ['id' => 's2id_autogen38_search'];

	public static $applyDiscountAfter = ['id' => 'apply_vat_on_discount0-lbl'];

	public static $applyDiscountBefore = ['id' => 'apply_vat_on_discount1-lbl'];

	public static $vatAfterDiscount = ['id' => 'vat_rate_after_discount'];

	public static $calculationBaseBilling = ['id' => 'calculate_vat_onBT-lbl'];

	public static $calculationBaseShipping = ['id' => 'calculate_vat_onST-lbl'];

	public static $vatNumberNo = ['id' => 'required_vat_number0-lbl'];

	public static $vatNumberYes = ['id' => 'required_vat_number1-lbl'];

	//xPath feature
	public static $priceTab = ['xpath' => "//h3[text()='Main Price Settings']"];

	public static $comparisonTab = ['xpath' => "//h3[text()='Comparison']"];

	public static $stockRoomTab = ['xpath' => "//h3[text()='Stockroom']"];

	public static $editInline = ['xpath' => "//h3[text()='Inline Edit']"];

	public static $ratingTab = ['xpath' => "//h3[text()='Rating']"];

	//button
	public static $featureSetting = "Feature Settings";

	public static $price = "Price";

	public static $cartCheckout = "Cart / Checkout";

	// cart checkout cart setting

	public static $addCartProduct = ['id' => 'individual_add_to_cart_enable0-lbl'];

	public static $addCartAttibute = ['id' => 'individual_add_to_cart_enable1-lbl'];

	public static $allowPreorderNo = ['id' => 'allow_pre_order0-lbl'];

	public static $allowPreOrOderYes = ['id' => 'allow_pre_order1-lbl'];

	public static $enableQuotationNo = ['id' => 'default_quotation_mode0-lbl'];

	public static $enableQuotationYes = ['id' => 'default_quotation_mode1-lbl'];

	public static $cartTimeOut = ['id' => 'cart_timeout'];

	public static $enableAjaxNo = ['id' => 'ajax_cart_box0-lbl'];

	public static $enableAjaxYes = ['id' => 'ajax_cart_box1-lbl'];

	public static $defaultCart = ['id' => 's2id_default_cart_checkout_itemid'];

	public static $defaultCartSearch = ['id' => 's2id_autogen42_search'];

	public static $buttonCartLead = ['id' => 's2id_addtocart_behaviour'];

	public static $buttonCartSearch = ['id' => 's2id_autogen43_search'];

	public static $onePageNo = ['id' => 'onestep_checkout_enable0-lbl'];

	public static $onePageYes = ['id' => 'onestep_checkout_enable1-lbl'];

	public static $showShippingCartNo = ['id' => 'show_shipping_in_cart0-lbl'];

	public static $showShippingCartYes = ['id' => 'show_shipping_in_cart1-lbl'];

	public static $attributeImageInCartYes = ['id' => 'wanttoshowattributeimage1-lbl'];

	public static $attributeImageInCartNo = ['id' => 'wanttoshowattributeimage0-lbl'];

	public static $quantityChangeInCartNo = ['id' => 'quantity_text_display0-lbl'];

	public static $quantityChangeInCartYes = ['id' => 'quantity_text_display1-lbl'];

	public static $quantityInCart = ['id' => 'default_quantity'];

	public static $defaultproductQuantity = ['id' => 'default_quantity_selectbox_value'];

	public static $minimunOrderTotal = ['id' => 'minimum_order_total'];



	//manufacture setting
	public static $manufactureTab=['xpath'=>'//form[@id=\'adminForm\']/div/div[1]/div/div/ul/li[4]/a'];

	public static $manufactureDefault=['id'=>'s2id_default_manufacturer_template'];

	public static $manufactureDefaultSearch=['id'=>'s2id_autogen20_search'];

	public static $manufactureSorting=['id'=>'s2id_default_manufacturer_ordering_method'];

	public static $manufactureSortingSearch=['id'=>'s2id_autogen21_search'];

	public static $manufactureDefaultSorting=['id'=>'s2id_default_manufacturer_product_ordering_method'];

	public static $manufactureDefaultSortingSearch=['id'=>'s2id_autogen22_search'];

	public static $titleDescription=['id'=>'manufacturer_title_max_chars'];

	public static $titleSuffix=['id'=>'manufacturer_title_end_suffix'];

	public static $enableMailManufactureYes=['id'=>'manufacturer_mail_enable1-lbl'];

	public static $enableMailManufactureNo=['id'=>'manufacturer_mail_enable0-lbl'];

	public static $enableMailSupplierYes=['id'=>'supplier_mail_enable1-lbl'];

	public static $enableMailSupplierNo=['id'=>'supplier_mail_enable0-lbl'];

	//manufacture image setting

	public static $watermarkNo=['id'=>'watermark_manufacturer_image0-lbl'];

	public static $watermarkYes=['id'=>'watermark_manufacturer_image1-lbl'];

	public static $watermarkThumbNo=['id'=>'watermark_manufacturer_thumb_image0-lbl'];

	public static $watermarkThumbYes=['id'=>'watermark_manufacturer_thumb_image1-lbl'];

	public static $manufactureThumbWeight=['id'=>'manufacturer_thumb_width'];

	public static $manufactureThumbHeight=['id'=>'manufacturer_thumb_height'];

	public static $manufactureThumbTwoWeight=['id'=>'manufacturer_product_thumb_width_2'];

	public static $manufactureThumbTwoHeight=['id'=>'manufacturer_product_thumb_height_2'];

	public static $manufactureThumbThreeWeight=['id'=>'manufacturer_product_thumb_width_3'];

	public static $manufactureThumbThreeHeight=['id'=>'manufacturer_product_thumb_height_3'];

	public static $manufactureThumbProductWeight=['id'=>'manufacturer_product_thumb_width'];

	public static $manufactureThumbProductHeight=['id'=>'manufacturer_product_thumb_height'];


}