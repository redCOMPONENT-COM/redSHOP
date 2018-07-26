<?php

/**
 *
 * Configuration Page at frontend
 *
 * @since  2.4
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 */
class ConfigurationPage extends AdminJ3Page
{
	//name page
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

	public static $countryPrice = "#s2id_default_vat_country";

	public static $countrySearchPrice = "#s2id_autogen35_search";

	public static $statePrice = "#s2id_default_vat_state";

	public static $stateSearchPrice = "#s2id_autogen36_search";

	public static $vatGroup = "#s2id_default_vat_group";

	public static $vatSearchGroup = "#s2id_autogen37_search";

	public static $varFirstResults = "//ul[@id=\'select2-results-37\']";

	public static $vatDefaultBase = "#s2id_vat_based_on";

	public static $vatSearchDefaultBase = "#'s2id_autogen38_search";

	public static $searchDefaultFirstResult = "//ul[@id=\'select2-results-38\']";

	public static $applyDiscountAfter = "#apply_vat_on_discount0-lbl";

	public static $applyDiscountBefore = "#apply_vat_on_discount1-lbl";

	public static $vatAfterDiscount = "#vat_rate_after_discount";

	public static $calculationBaseBilling = "#calculate_vat_onBT-lbl";

	public static $calculationBaseShipping = "#calculate_vat_onST-lbl";

	public static $vatNumberNo = "#required_vat_number0-lbl";

	public static $vatNumberYes = "#required_vat_number1-lbl";

	//xPath feature
	public static $priceTab = "//h3[text()='Main Price Settings']";

	public static $comparisonTab = "//h3[text()='Comparison']";

	public static $stockRoomTab = "//h3[text()='Stockroom']";

	public static $editInline = "//h3[text()='Inline Edit']";

	public static $ratingTab = "//h3[text()='Rating']";

	//button
	public static $featureSetting = "Feature Settings";

	public static $price = "Price";

	public static $cartCheckout = "Cart / Checkout";

	// cart checkout cart setting

	public static $addCartProduct = "#individual_add_to_cart_enable0-lbl";

	public static $addCartAttibute = "#individual_add_to_cart_enable1-lbl";

	public static $allowPreorderNo = "#allow_pre_order0-lbl";

	public static $allowPreOrOderYes = "#allow_pre_order1-lbl";

	public static $enableQuotationNo = "#default_quotation_mode0-lbl";

	public static $enableQuotationYes = "#default_quotation_mode1-lbl";

	public static $cartTimeOut = "#cart_timeout";

	public static $enableAjaxNo = "#ajax_cart_box0-lbl";

	public static $enableAjaxYes = "#ajax_cart_box1-lbl";

	public static $defaultCart = "#s2id_default_cart_checkout_itemid";

	public static $defaultCartSearch = "#s2id_autogen42_search";

	public static $buttonCartLead = "#s2id_addtocart_behaviour";

	public static $buttonCartSearch = "#s2id_autogen43_search";

	public static $firstCartSearch = "//ul[@id=\'select2-results-43\']";

	public static $onePageNo = "#onestep_checkout_enable0-lbl";

	public static $onePageYes = "#onestep_checkout_enable1-lbl";

	public static $showShippingCartNo = "show_shipping_in_cart0-lbl";

	public static $showShippingCartYes = "#show_shipping_in_cart1-lbl";

	public static $attributeImageInCartYes = "#wanttoshowattributeimage1-lbl";

	public static $attributeImageInCartNo = "#wanttoshowattributeimage0-lbl";

	public static $quantityChangeInCartNo = "#quantity_text_display0-lbl";

	public static $quantityChangeInCartYes = "#quantity_text_display1-lbl";

	public static $quantityInCart = "#default_quantity";

	public static $defaultproductQuantity = "#default_quantity_selectbox_value";

	public static $minimunOrderTotal = "#minimum_order_total";

	// price tab and discount

	public static $enableDiscountNo = "#discount_enable0-lbl";

	public static $enableDiscountYes = "#discount_enable1-lbl";

	public static $allowedDiscountId = "#s2id_discount_type";

	public static $allowDiscountSearch = "#s2id_autogen39_search";

	public static $enableCouponYes = "#coupons_enable1-lbl";

	public static $enableCouponNo = "#coupons_enable0-lbl";

	public static $enableCouponInfoYes = "#couponinfo1-lbl";

	public static $enableCouponInfoNo = "#couponinfo0-lbl";

	public static $enableVoucherYes = "#vouchers_enable1-lbl";

	public static $enableVoucherNo = "#vouchers_enable0-lbl";

	public static $spendTimeDiscountYes = "#special_discount_mail_send1-lbl";

	public static $spendTimeDiscountNo = "#special_discount_mail_send0-lbl";

	public static $applyDiscountForProductAlreadyDiscountYes = "#apply_voucher_coupon_already_discount1-lbl";

	public static $applyDiscountForProductAlreadyDiscountNo = "apply_voucher_coupon_already_discount0-lbl";

	public static $calculateShippingBasedTotal = "#shipping_aftertotal-lbl";

	public static $calculateShippingBasedSubTotal = "#shipping_aftersubtotal-lbl";

	public static $valueDiscountCouponId = "#s2id_discoupon_percent_or_total";

	public static $valueDiscountCouponSearch = "#s2id_autogen40_search";

	public static $discountVoucherCoupon = 'Discount/voucher/coupon';

	public static $discountAndVoucherOrCoupon = 'Discount + voucher/coupon';

	public static $discountVoucherSingleCouponSingle = 'Discount + voucher (single) + coupon (single)';

	public static $discountVoucherMultipleCouponMultiple = 'Discount + voucher (multiple) + coupon (multiple)';

	public static $messageSaveSuccess = 'Configuration Saved';
}