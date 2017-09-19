<?php
/**
 * Shipping Page for Shipping Management and shipping rate
 */

class ShippingPage extends AdminJ3Page
{
	public static $shippingManagementUrl='/administrator/index.php?option=com_redshop&view=shipping';

	public static $standShipping="redSHOP - Standard Shipping";

	public static $shippingRate =['id'=>'toolbar-redshop_shipping_rates32'];

	public static $shippingName=['id'=>'shipping_rate_name'];

	public static $shippingRateValue=['id'=>'shipping_rate_value'];

	public static $weightStart=['id'=>'shipping_rate_weight_start'];

	public static $weightEnd=['id'=>'shipping_rate_weight_end'];

	public static $volumeStart=['id'=>'shipping_rate_volume_start'];

	public static $volumeEnd=['id'=>'shipping_rate_volume_end'];

	public static $shippingRateLenghtStart=['id'=>'shipping_rate_length_start'];

	public static $shippingRateLegnhtEnd=['id'=>'shipping_rate_length_end'];

	public static $shippingRateWidthStart=['id'=>'shipping_rate_width_start'];

	public static $shippingRateWidthEnd=['id'=>'shipping_rate_width_end'];


	public static $shippingRateHeightEnd=['id'=>'shipping_rate_height_end'];

	public static $shippingRateHeightStart=['id'=>'shipping_rate_height_start'];

	public static $orderTotalStart=['id'=>'shipping_rate_ordertotal_start'];

	public static $orderTotalEnd=['id'=>'shipping_rate_ordertotal_end'];

	public static $zipCodeStart=['id'=>'shipping_rate_zip_start'];

	public static $zipCodeEnd=['id'=>'shipping_rate_zip_start'];

	public static $country=['id'=>'s2id_shipping_rate_country'];

	public static $shippingRateProduct =['id'=>'s2id_container_product'];

	public static $shippingCategory = ['id'=>'s2id_shipping_rate_on_category'];

	public static $shippingShopperGroups =['id'=>'s2id_shipping_rate_on_shopper_group'];

	public static $shippingPriority=['id'=>'shipping_rate_priority'];

	public static $shippingRateFor=['id'=>'s2id_company_only'];

	public static $shippingRateForSearch=['id'=>'s2id_autogen6_search'];

	public static $shippingVATGroups=['id'=>'s2id_shipping_tax_group_id'];

	public static $shippingVATGroupsSearh=['id'=>'s2id_autogen7_search'];

	public static $pickupAthThePost=['id'=>'deliver_type0-lbl'];

	public static $regularDelivery=['id'=>'deliver_type1-lbl'];

	public static $scrollDown="window.scrollTo(200,2000)";

}