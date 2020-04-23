<?php
/**
 * @package     redSHOP
 * @subpackage  Page ModuleCheckout
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 *
 * Configuration Page at frontend
 *
 * @since  1.4.0
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 */
class ConfigurationPage extends AdminJ3Page
{
	//name page

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $namePage = "Configuration";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $URL = '/administrator/index.php?option=com_redshop&view=configuration';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $stockRoomYes = "#use_stockroom_1-lbl";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $stockRoomNo = "#use_stockroom_0-lbl";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $eidtInLineYes = "#inline_editing_1-lbl";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $editInLineNo = "#inline_editing_0-lbl";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $comparisonNo = "#compare_products_0-lbl";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $comparisonYes = "#compare_products_1-lbl";

	//Price

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $showPriceYes = "#show_price_1-lbl";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $showPriceNo = "#show_price_0-lbl";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $countryPrice = "#s2id_default_vat_country";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $countrySearchPrice = "#s2id_autogen35_search";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $statePrice = "#s2id_default_vat_state";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $stateSearchPrice = "#s2id_autogen36_search";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $vatGroup = "#s2id_default_vat_group";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $vatSearchGroup = "#s2id_autogen37_search";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $varFirstResults = "//ul[@id='select2-results-37']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $vatDefaultBase = "#s2id_vat_based_on";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $vatSearchDefaultBase = "#s2id_autogen38_search";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $searchDefaultFirstResult = "//ul[@id='select2-results-38']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $applyDiscountAfter = "#apply_vat_on_discount_0-lbl";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $applyDiscountBefore = "#apply_vat_on_discount_1-lbl";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $vatAfterDiscount = "#vat_rate_after_discount";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $calculationBaseBilling = "#calculate_vat_on_BT-lbl";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $calculationBaseShipping = "#calculate_vat_on_ST-lbl";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $vatNumberNo = "#required_vat_number_0-lbl";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $vatNumberYes = "#required_vat_number_1-lbl";

	//xPath feature

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $priceTab = "//h3[text()='Main Price Settings']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $comparisonTab = "//h3[text()='Comparison']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $stockRoomTab = "//h3[text()='Stockroom']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $editInline = "//h3[text()='Inline Edit']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $ratingTab = "//h3[text()='Rating']";

	//WishList

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $wishListTab = "//h3[text()='Wishlist']";

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $wishListNo = '#my_wishlist_0-lbl';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $wishListYes = '#my_wishlist_1-lbl';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $loginRequireNo = '#wishlist_login_required_0-lbl';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $loginRequireYes = '#wishlist_login_required_1-lbl';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $wishListListNo = '#wishlist_list_0-lbl';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $wishListListYes = '#wishlist_list_1-lbl';

	//button

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $featureSetting = "Feature Settings";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $price = "Price";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $cartCheckout = "Cart / Checkout";

	// cart checkout cart setting

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $addCartProduct = "//label[@id='individual_add_to_cart_enable_0-lbl']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $addCartAttibute = "//label[@id='individual_add_to_cart_enable_1-lbl']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $allowPreorderNo = "#allow_pre_order_0-lbl";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $allowPreOrOderYes = "#allow_pre_order_1-lbl";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $enableQuotationNo = "#default_quotation_mode_0-lbl";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $enableQuotationYes = "#default_quotation_mode_1-lbl";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $cartTimeOut = "#cart_timeout";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $enableAjaxNo = "#ajax_cart_box_0-lbl";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $enableAjaxYes = "#ajax_cart_box_1-lbl";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $defaultCart = "#s2id_default_cart_checkout_itemid";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $defaultCartSearch = "#s2id_autogen42_search";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $buttonCartLead = "#s2id_addtocart_behaviour";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $buttonCartSearch = "#s2id_autogen43_search";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $firstCartSearch = "//ul[@id='select2-results-43']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $onePageNo = "#onestep_checkout_enable_0-lbl";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $onePageYes = "#onestep_checkout_enable_1-lbl";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $showShippingCartNo = "#show_shipping_in_cart_0-lbl";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $showShippingCartYes = "#show_shipping_in_cart_1-lbl";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $attributeImageInCartYes = "#wanttoshowattributeimage_0-lbl";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $attributeImageInCartNo = "#wanttoshowattributeimage_1-lbl";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $quantityChangeInCartNo = "#quantity_text_display_0-lbl";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $quantityChangeInCartYes = "#quantity_text_display_1-lbl";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $quantityInCart = "#default_quantity";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $defaultproductQuantity = "#default_quantity_selectbox_value";

	/**
	 * @since 1.4.0
	 * @var string
	 */
	public static $minimunOrderTotal = "#minimum_order_total";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $showSameAddressForBillingYes = "#optional_shipping_address_1-lbl";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $showSameAddressForBillingNo = "#optional_shipping_address_0-lbl";

	// price tab and discount

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $enableDiscountNo = "#discount_enable_0-lbl";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $enableDiscountYes = "#discount_enable_1-lbl";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $allowedDiscountId = "#s2id_discount_type";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $allowDiscountSearch = "#s2id_autogen39_search";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $enableCouponYes = "#coupons_enable_1-lbl";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $enableCouponNo = "#coupons_enable_0-lbl";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $enableCouponInfoYes = "#couponinfo_1-lbl";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $enableCouponInfoNo = "#couponinfo_0-lbl";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $enableVoucherYes = "#vouchers_enable_1-lbl";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $enableVoucherNo = "#vouchers_enable_0-lbl";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $spendTimeDiscountYes = "#special_discount_mail_send_1-lbl";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $spendTimeDiscountNo = "#special_discount_mail_send_0-lbl";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $applyDiscountForProductAlreadyDiscountYes = "#apply_voucher_coupon_already_discount_1-lbl";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $applyDiscountForProductAlreadyDiscountNo = "apply_voucher_coupon_already_discount_0-lbl";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $calculateShippingBasedTotal = "#shipping_after_total-lbl";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $calculateShippingBasedSubTotal = "#shipping_after_subtotal-lbl";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $valueDiscountCouponId = "#s2id_discoupon_percent_or_total";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $valueDiscountCouponSearch = "#s2id_autogen40_search";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $discountVoucherCoupon = 'Discount/voucher/coupon';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $discountAndVoucherOrCoupon = 'Discount + voucher/coupon';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $discountVoucherSingleCouponSingle = 'Discount + voucher (single) + coupon (single)';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $discountVoucherMultipleCouponMultiple = 'Discount + voucher (multiple) + coupon (multiple)';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $messageSaveSuccess = 'Configuration Saved';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $currencySymbol = '#currency_symbol';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $decimalSeparator = '#price_seperator';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $numberOfPriceDecimals = '#price_decimal';

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $productTab = '//a[@href="#producttab"]';

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $stockRoomAttributeYes = '//label[@id="display_out_of_stock_attribute_data_1-lbl"]';

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $stockRoomAttributeNo = '//label[@id="display_out_of_stock_attribute_data_0-lbl"]';

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $messOutOfStockRoom = 'Out of Stock.';

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $productAccessory = '//a[@href="#accessory"]';

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $relatedProductTab = '//a[@href="#related"]';

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $enableAccessoryYes = '//label[@id="accessory_as_product_in_cart_enable_1-lbl"]';

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $twoWayRelatedYes = '//label[@id="twoway_related_product_1-lbl"]';

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $enableAccessoryNo = '//label[@id="accessory_as_product_in_cart_enable_0-lbl"]';

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $twoWayRelatedNo = '//label[@id="twoway_related_product_0-lbl"]';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $ordersTab = '//a[@href="#ordertab"]';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $resetOderId = '//a[@title="Order ID Reset"]';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $sendOrderEmail = '//div[@id="s2id_order_mail_after"]';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $inputOderEmail = '//input[@id="s2id_autogen44_search"]';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $enableInvoiceEmailYes = '//label[@id="invoice_mail_enable_1-lbl"]';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $enableInvoiceEmailNo = '//label[@id="invoice_mail_enable_0-lbl"]';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $sendMailToCustomerInOrderYes = '//label[@id="send_mail_to_customer_1-lbl"]';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $sendMailToCustomerInOrderNo = '//label[@id="send_mail_to_customer_0-lbl"]';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $afterPayment = '//ul[@id="select2-results-44"]/li[3]';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $noneButton = '//label[@id="invoice_mail_send_option_0-lbl"]';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $administratorButton = '//label[@id="invoice_mail_send_option_1-lbl"]';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $customerButton = '//label[@id="invoice_mail_send_option_2-lbl"]';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $bothButton = '//label[@id="invoice_mail_send_option_3-lbl"]';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $messagePopup = 'Successfully reset order id';

	/**
	 * @var string
	 * @since 2.1.4
	 */
	public static $purchaseParentYes = '#purchase_parent_with_child_1-lbl';

	/**
	 * @var string
	 * @since 2.1.4
	 */
	public static $purchaseParentNo = '#purchase_parent_with_child_0-lbl';

	/**
	 * @var string
	 * @since 3.0.2
	 */
	public static $showDiscontinuedProductsYes = '#show_discontinued_products_1-lbl';

	/**
	 * @var string
	 * @since 3.0.2
	 */
	public static $showDiscontinuedProductsNo = '#show_discontinued_products_0-lbl';
}
