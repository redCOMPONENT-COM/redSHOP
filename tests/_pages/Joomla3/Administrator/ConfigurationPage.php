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
	public static $stockRoomYes = "#use_stockroom1-lbl";

    /**
     * @var string
     */
	public static $stockRoomNo = "#use_stockroom0-lbl";

    /**
     * @var string
     */
	public static $eidtInLineYes = "#inline_editing1-lbl";

    /**
     * @var string
     */
	public static $editInLineNo = "#inline_editing0-lbl";

    /**
     * @var string
     */
	public static $comparisonNo = "#compare_products0-lbl";

    /**
     * @var string
     */
	public static $comparisonYes = "#compare_products1-lbl";

	//Price

    /**
     * @var string
     */
	public static $showPriceYes = "#show_price1-lbl";

    /**
     * @var string
     */
	public static $showPriceNo = "#show_price0-lbl";

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
	public static $applyDiscountAfter = "#apply_vat_on_discount0-lbl";

    /**
     * @var string
     */
	public static $applyDiscountBefore = "#apply_vat_on_discount1-lbl";

    /**
     * @var string
     */
	public static $vatAfterDiscount = "#vat_rate_after_discount";

    /**
     * @var string
     */
	public static $calculationBaseBilling = "#calculate_vat_onBT-lbl";

    /**
     * @var string
     */
	public static $calculationBaseShipping = "#calculate_vat_onST-lbl";

    /**
     * @var string
     */
	public static $vatNumberNo = "#required_vat_number0-lbl";

    /**
     * @var string
     */
	public static $vatNumberYes = "#required_vat_number1-lbl";

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
	public static $addCartProduct = "#individual_add_to_cart_enable0-lbl";

    /**
     * @var string
     */
	public static $addCartAttibute = "#individual_add_to_cart_enable1-lbl";

    /**
     * @var string
     */
	public static $allowPreorderNo = "#allow_pre_order0-lbl";

    /**
     * @var string
     */
	public static $allowPreOrOderYes = "#allow_pre_order1-lbl";

    /**
     * @var string
     */
	public static $enableQuotationNo = "#default_quotation_mode0-lbl";

    /**
     * @var string
     */
	public static $enableQuotationYes = "#default_quotation_mode1-lbl";

    /**
     * @var string
     */
	public static $cartTimeOut = "#cart_timeout";

    /**
     * @var string
     */
	public static $enableAjaxNo = "#ajax_cart_box0-lbl";

    /**
     * @var string
     */
	public static $enableAjaxYes = "#ajax_cart_box1-lbl";

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
	public static $onePageNo = "#onestep_checkout_enable0-lbl";

    /**
     * @var string
     */
	public static $onePageYes = "#onestep_checkout_enable1-lbl";

    /**
     * @var string
     */
	public static $showShippingCartNo = "#show_shipping_in_cart0-lbl";

    /**
     * @var string
     */
	public static $showShippingCartYes = "#show_shipping_in_cart1-lbl";

    /**
     * @var string
     */
	public static $attributeImageInCartYes = "#wanttoshowattributeimage1-lbl";

    /**
     * @var string
     */
	public static $attributeImageInCartNo = "#wanttoshowattributeimage0-lbl";

    /**
     * @var string
     */
	public static $quantityChangeInCartNo = "#quantity_text_display0-lbl";

    /**
     * @var string
     */
	public static $quantityChangeInCartYes = "#quantity_text_display1-lbl";

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

	// price tab and discount

    /**
     * @var string
     */
	public static $enableDiscountNo = "#discount_enable0-lbl";

    /**
     * @var string
     */
	public static $enableDiscountYes = "#discount_enable1-lbl";

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
	public static $enableCouponYes = "#coupons_enable1-lbl";

    /**
     * @var string
     */
	public static $enableCouponNo = "#coupons_enable0-lbl";

    /**
     * @var string
     */
	public static $enableCouponInfoYes = "#couponinfo1-lbl";

    /**
     * @var string
     */
	public static $enableCouponInfoNo = "#couponinfo0-lbl";

    /**
     * @var string
     */
	public static $enableVoucherYes = "#vouchers_enable1-lbl";

    /**
     * @var string
     */
	public static $enableVoucherNo = "#vouchers_enable0-lbl";

    /**
     * @var string
     */
	public static $spendTimeDiscountYes = "#special_discount_mail_send1-lbl";

    /**
     * @var string
     */
	public static $spendTimeDiscountNo = "#special_discount_mail_send0-lbl";

    /**
     * @var string
     */
	public static $applyDiscountForProductAlreadyDiscountYes = "#apply_voucher_coupon_already_discount1-lbl";

    /**
     * @var string
     */
	public static $applyDiscountForProductAlreadyDiscountNo = "apply_voucher_coupon_already_discount0-lbl";

    /**
     * @var string
     */
	public static $calculateShippingBasedTotal = "#shipping_aftertotal-lbl";

    /**
     * @var string
     */
	public static $calculateShippingBasedSubTotal = "#shipping_aftersubtotal-lbl";

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
}