<?php

/**
 * Shipping Page for Shipping Management and shipping rate
 */
class ShippingPage extends AdminJ3Page
{
    public static $shippingManagementUrl = '/administrator/index.php?option=com_redshop&view=shipping';

    public static $standShipping = "redSHOP - Standard Shipping";

    public static $shippingRate = "#toolbar-redshop_shipping_rates32";

    public static $shippingName = "#shipping_rate_name";

    public static $shippingRateValue = "#shipping_rate_value";

    public static $weightStart = "#shipping_rate_weight_start";

    public static $weightEnd = "#shipping_rate_weight_end";

    public static $volumeStart = "#shipping_rate_volume_start";

    public static $volumeEnd = "#shipping_rate_volume_end";

    public static $shippingRateLenghtStart = "#shipping_rate_length_start";

    public static $shippingRateLegnhtEnd = "#shipping_rate_length_end";

    public static $shippingRateWidthStart = "#shipping_rate_width_start";

    public static $shippingRateWidthEnd = "#shipping_rate_width_end";


    public static $shippingRateHeightEnd = "#shipping_rate_height_end";

    public static $shippingRateHeightStart = "#shipping_rate_height_start";

    public static $orderTotalStart = "#shipping_rate_ordertotal_start";

    public static $orderTotalEnd = "#shipping_rate_ordertotal_end";

    public static $zipCodeStart = "#shipping_rate_zip_start";

    public static $zipCodeEnd = "#shipping_rate_zip_start";

    public static $country = "#s2id_shipping_rate_country";

    public static $shippingRateProduct = "#s2id_container_product";

    public static $shippingCategory = "#s2id_shipping_rate_on_category";

	public static $shipingCategorySearch = "#s2id_autogen4";

    public static $shippingShopperGroups = "#s2id_shipping_rate_on_shopper_group";

    public static $shippingPriority = "#shipping_rate_priority";

    public static $shippingRateFor = "#s2id_company_only";

    public static $shippingRateForSearch = "#s2id_autogen6_search";

    public static $shippingVATGroups = "#s2id_shipping_tax_group_id";

    public static $shippingVATGroupsSearh = "#s2id_autogen7_search";

    public static $pickupAthThePost = "#deliver_type0-lbl";

    public static $regularDelivery = "#deliver_type1-lbl";

    public static $scrollDown = "window.scrollTo(200,2000)";

}