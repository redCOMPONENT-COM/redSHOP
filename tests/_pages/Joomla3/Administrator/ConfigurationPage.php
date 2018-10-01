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

	/**
	 * @var string
	 */
	public static $namePage = "Configuration";

	/**
	 * @var string
	 */
	public static $URL = '/administrator/index.php?option=com_redshop&view=configuration';

	/**
	 * @var string
	 */
	public static $stockRoomYes = "#use_stockroom_1-lbl";

	/**
	 * @var string
	 */
	public static $stockRoomNo = "#use_stockroom_0-lbl";

	/**
	 * @var string
	 */
	public static $eidtInLineYes = "#inline_editing_1-lbl";

	/**
	 * @var string
	 */
	public static $editInLineNo = "#inline_editing_0-lbl";

	/**
	 * @var string
	 */
	public static $comparisonNo = "#compare_products_0-lbl";

	/**
	 * @var string
	 */
	public static $comparisonYes = "#compare_products_1-lbl";

	//Price

	/**
	 * @var string
	 */
	public static $showPriceYes = "#show_price_1-lbl";

	/**
	 * @var string
	 */
	public static $showPriceNo = "#show_price_0-lbl";

	/**
	 * @var string
	 */
	public static $countryPrice = "#s2id_default_vat_country";

	/**
	 * @var string
	 */
	public static $countrySearchPrice = "#s2id_autogen35_search";

	/**
	 * @var string
	 */
	public static $statePrice = "#s2id_default_vat_state";

	/**
	 * @var string
	 */
	public static $stateSearchPrice = "#s2id_autogen36_search";

	/**
	 * @var string
	 */
	public static $vatGroup = "#s2id_default_vat_group";

	/**
	 * @var string
	 */
	public static $vatSearchGroup = "#s2id_autogen37_search";

	/**
	 * @var string
	 */
	public static $varFirstResults = "//ul[@id='select2-results-37']";

	/**
	 * @var string
	 */
	public static $vatDefaultBase = "#s2id_vat_based_on";

	/**
	 * @var string
	 */
	public static $vatSearchDefaultBase = "#s2id_autogen38_search";

	/**
	 * @var string
	 */
	public static $searchDefaultFirstResult = "//ul[@id='select2-results-38']";

	/**
	 * @var string
	 */
	public static $applyDiscountAfter = "#apply_vat_on_discount_0-lbl";

	/**
	 * @var string
	 */
	public static $applyDiscountBefore = "#apply_vat_on_discount_1-lbl";

	/**
	 * @var string
	 */
	public static $vatAfterDiscount = "#vat_rate_after_discount";

	/**
	 * @var string
	 */
	public static $calculationBaseBilling = "#calculate_vat_on_BT-lbl";

	/**
	 * @var string
	 */
	public static $calculationBaseShipping = "#calculate_vat_on_ST-lbl";

	/**
	 * @var string
	 */
	public static $vatNumberNo = "#required_vat_number_0-lbl";

	/**
	 * @var string
	 */
	public static $vatNumberYes = "#required_vat_number_1-lbl";

	//xPath feature

	/**
	 * @var string
	 */
	public static $priceTab = "//h3[text()='Main Price Settings']";

	/**
	 * @var string
	 */
	public static $comparisonTab = "//h3[text()='Comparison']";

	/**
	 * @var string
	 */
	public static $stockRoomTab = "//h3[text()='Stockroom']";

	/**
	 * @var string
	 */
	public static $editInline = "//h3[text()='Inline Edit']";

	/**
	 * @var string
	 */
	public static $ratingTab = "//h3[text()='Rating']";

	//button

	/**
	 * @var string
	 */
	public static $featureSetting = "Feature Settings";

	/**
	 * @var string
	 */
	public static $price = "Price";

	/**
	 * @var string
	 */
	public static $cartCheckout = "Cart / Checkout";

	// cart checkout cart setting

	/**
	 * @var string
	 */
	public static $addCartProduct = "//label[@id='individual_add_to_cart_enable_0-lbl']";

	/**
	 * @var string
	 */
	public static $addCartAttibute = "//label[@id='individual_add_to_cart_enable_1-lbl']";

	/**
	 * @var string
	 */
	public static $allowPreorderNo = "#allow_pre_order_0-lbl";

	/**
	 * @var string
	 */
	public static $allowPreOrOderYes = "#allow_pre_order_1-lbl";

	/**
	 * @var string
	 */
	public static $enableQuotationNo = "#default_quotation_mode_0-lbl";

	/**
	 * @var string
	 */
	public static $enableQuotationYes = "#default_quotation_mode_1-lbl";

	/**
	 * @var string
	 */
	public static $cartTimeOut = "#cart_timeout";

	/**
	 * @var string
	 */
	public static $enableAjaxNo = "#ajax_cart_box_0-lbl";

	/**
	 * @var string
	 */
	public static $enableAjaxYes = "#ajax_cart_box_1-lbl";

	/**
	 * @var string
	 */
	public static $defaultCart = "#s2id_default_cart_checkout_itemid";

	/**
	 * @var string
	 */
	public static $defaultCartSearch = "#s2id_autogen42_search";

	/**
	 * @var string
	 */
	public static $buttonCartLead = "#s2id_addtocart_behaviour";

	/**
	 * @var string
	 */
	public static $buttonCartSearch = "#s2id_autogen43_search";

	/**
	 * @var string
	 */
	public static $firstCartSearch = "//ul[@id='select2-results-43']";
	/**
	 * @var string
	 */
	public static $onePageNo = "#onestep_checkout_enable_0-lbl";

	/**
	 * @var string
	 */
	public static $onePageYes = "#onestep_checkout_enable_1-lbl";

	/**
	 * @var string
	 */
	public static $showShippingCartNo = "#show_shipping_in_cart_0-lbl";

	/**
	 * @var string
	 */
	public static $showShippingCartYes = "#show_shipping_in_cart_1-lbl";

	/**
	 * @var string
	 */
	public static $attributeImageInCartYes = "#wanttoshowattributeimage_0-lbl";

	/**
	 * @var string
	 */
	public static $attributeImageInCartNo = "#wanttoshowattributeimage_1-lbl";

	/**
	 * @var string
	 */
	public static $quantityChangeInCartNo = "#quantity_text_display_0-lbl";

	/**
	 * @var string
	 */
	public static $quantityChangeInCartYes = "#quantity_text_display_1-lbl";

	/**
	 * @var string
	 */
	public static $quantityInCart = "#default_quantity";

	/**
	 * @var string
	 */
	public static $defaultproductQuantity = "#default_quantity_selectbox_value";

	/**
	 * @var string
	 */
	public static $minimunOrderTotal = "#minimum_order_total";

	/**
	 * @var string
	 */
	public static $showSameAddressForBillingYes = "#optional_shipping_address_1-lbl";

	/**
	 * @var string
	 */
	public static $showSameAddressForBillingNo = "#optional_shipping_address_0-lbl";

	// price tab and discount

	/**
	 * @var string
	 */
	public static $enableDiscountNo = "#discount_enable_0-lbl";

	/**
	 * @var string
	 */
	public static $enableDiscountYes = "#discount_enable_1-lbl";

	/**
	 * @var string
	 */
	public static $allowedDiscountId = "#s2id_discount_type";

	/**
	 * @var string
	 */
	public static $allowDiscountSearch = "#s2id_autogen39_search";

	/**
	 * @var string
	 */
	public static $enableCouponYes = "#coupons_enable_1-lbl";

	/**
	 * @var string
	 */
	public static $enableCouponNo = "#coupons_enable_0-lbl";

	/**
	 * @var string
	 */
	public static $enableCouponInfoYes = "#couponinfo_1-lbl";

	/**
	 * @var string
	 */
	public static $enableCouponInfoNo = "#couponinfo_0-lbl";

	/**
	 * @var string
	 */
	public static $enableVoucherYes = "#vouchers_enable_1-lbl";

	/**
	 * @var string
	 */
	public static $enableVoucherNo = "#vouchers_enable_0-lbl";

	/**
	 * @var string
	 */
	public static $spendTimeDiscountYes = "#special_discount_mail_send_1-lbl";

	/**
	 * @var string
	 */
	public static $spendTimeDiscountNo = "#special_discount_mail_send_0-lbl";

	/**
	 * @var string
	 */
	public static $applyDiscountForProductAlreadyDiscountYes = "#apply_voucher_coupon_already_discount_1-lbl";

	/**
	 * @var string
	 */
	public static $applyDiscountForProductAlreadyDiscountNo = "apply_voucher_coupon_already_discount_0-lbl";

	/**
	 * @var string
	 */
	public static $calculateShippingBasedTotal = "#shipping_after_total-lbl";

	/**
	 * @var string
	 */
	public static $calculateShippingBasedSubTotal = "#shipping_after_subtotal-lbl";

	/**
	 * @var string
	 */
	public static $valueDiscountCouponId = "#s2id_discoupon_percent_or_total";

	/**
	 * @var string
	 */
	public static $valueDiscountCouponSearch = "#s2id_autogen40_search";

	/**
	 * @var string
	 */
	public static $discountVoucherCoupon = 'Discount/voucher/coupon';

	/**
	 * @var string
	 */
	public static $discountAndVoucherOrCoupon = 'Discount + voucher/coupon';

	/**
	 * @var string
	 */
	public static $discountVoucherSingleCouponSingle = 'Discount + voucher (single) + coupon (single)';

	/**
	 * @var string
	 */
	public static $discountVoucherMultipleCouponMultiple = 'Discount + voucher (multiple) + coupon (multiple)';

	/**
	 * @var string
	 */
	public static $messageSaveSuccess = 'Configuration Saved';

	/**
	 * @var string
	 */
	public static $currencySymbol = '#currency_symbol';

	/**
	 * @var string
	 */
	public static $decimalSeparator = '#price_seperator';

	/**
	 * @var string
	 */
	public static $numberOfPriceDecimals = '#price_decimal';
}