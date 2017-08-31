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

	public static $userTab=['xpath'=>'//form[@id=\'adminForm\']/div/div[1]/div/div/ul/li[2]/a'];

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

	//user tab registration
	public static $registrationId=['id'=>'s2id_register_method'];

	public static $registraionSearch=['id'=>'s2id_autogen6_search'];

	public static $createUserYes=['id'=>'create_account_checkbox1-lbl'];

	public static $createUserNo=['id'=>'create_account_checkbox0-lbl'];

	public static $emailVerifyYes=['id'=>'show_email_verification1-lbl'];

	public static $emailVerifyNo=['id'=>'show_email_verification0-lbl'];

	public static $newCustomerPreselectedYes=['id'=>'new_customer_selection1-lbl'];

	public static $newCustomerPreselectedNo=['id'=>'new_customer_selection0-lbl'];

	public static $termsShowPerOrder=['id'=>'show_terms_and_conditions0-lbl'];

	public static $termShowPerUser=['id'=>'show_terms_and_conditions1-lbl'];

	public static $whoCanRegister=['id'=>'s2id_allow_customer_register_type'];

	public static $whoCanRegisterSearch=['id'=>'s2id_autogen7_search'];

	public static $defaultCustomer=['id'=>'s2id_default_customer_register_type'];

	public static $defaultCustomerSearch=['id'=>'s2id_autogen8_search'];

	public static $checkoutLogin=['id'=>'s2id_checkout_login_register_switcher'];

	public static $checkoutLoginSearch=['id'=>'s2id_autogen9_search'];

	//user shopper groups
	public static $portalShopNo=['id'=>'portal_shop0-lbl'];

	public static $portalYes=['id'=>'portal_shop1-lbl'];

	public static $privateShopperGroup=['id'=>'s2id_shopper_group_default_private'];

	public static $privateShopperGroupSearch=['id'=>'s2id_autogen12_search'];

	public static $companyShopperGroup=['id'=>'s2id_shopper_group_default_company'];

	public static $companyShopperGroupSearch=['id'=>'s2id_autogen13_search'];

	public static $shopperUnregistered =['id'=>'s2id_shopper_group_default_unregistered'];

	public static $shopperUnregisteredSearch =['id'=>'s2id_autogen14_search'];

	public static $newShopperGroups=['id'=>'s2id_new_shopper_group_get_value_from'];

	public static $newShopperGroupSearch =['id'=>'s2id_autogen15_search'];





}