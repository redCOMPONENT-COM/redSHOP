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

	//price Discount

	public static $enableDiscountYes = ['id' => 'discount_enable1-lbl'];

	public static $enableDiscountNo = ['id' => 'discount_enable0-lbl'];

	public static $discountType = ['id' => 's2id_discount_type'];

	public static $discountSearch = ['id' => 's2id_autogen39_search'];

	public static $enableCouponNo = ['id' => 'coupons_enable0-lbl'];

	public static $enableCouponYes = ['id' => 'coupons_enable1-lbl'];

	public static $couponInforYes = ['id' => 'couponinfo1-lbl'];

	public static $couponInforNo = ['id' => 'couponinfo0-lbl'];

	public static $enableVoucherNo = ['id' => 'vouchers_enable0-lbl'];

	public static $enableVoucherYes = ['id' => 'vouchers_enable1-lbl'];

	public static $enableDiscountMailNo = ['id' => 'special_discount_mail_send0-lbl'];

	public static $enableDiscountMailYes = ['id' => 'special_discount_mail_send1-lbl'];

	public static $applyDiscountNo = ['id' => 'apply_voucher_coupon_already_discount0-lbl'];

	public static $applyDiscountYes = ['id' => 'apply_voucher_coupon_already_discount1-lbl'];

	public static $shippingBaseOnTotal = ['id' => 'shipping_aftertotal-lbl'];

	public static $shippingBaseOnSubtotal = ['id' => 'shipping_aftersubtotal-lbl'];

	public static $valueDiscountCouponTotal = ['id' => 's2id_discoupon_percent_or_total'];

	public static $valueDiscountCouponPercentager = ['id' => 's2id_autogen40_search'];

	public static $discountAmount = ['id' => 'discoupon_value'];


	//xPath feature
	public static $priceTab = ['xpath' => "//h3[text()='Main Price Settings']"];

	public static $comparisonTab = ['xpath' => "//h3[text()='Comparison']"];

	public static $stockRoomTab = ['xpath' => "//h3[text()='Stockroom']"];

	public static $editInline = ['xpath' => "//h3[text()='Inline Edit']"];

	public static $ratingTab = ['xpath' => "//h3[text()='Rating']"];

	//button
	public static $featureSetting = "Feature Settings";

	public static $price = "Price";


}