<?php

/**
 * Shipping Page for Shipping Management and shipping rate Page
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  2.4
 */
class ShippingPage extends AdminJ3Page
{
    /**
     * @var string
     */
    public static $shippingManagementUrl = '/administrator/index.php?option=com_redshop&view=shipping';

    /**
     * @var string
     */
    public static $standShipping = "redSHOP - Standard Shipping";

    /**
     * @var string
     */
    public static $shippingRate = "#toolbar-redshop_shipping_rates32";

    /**
     * @var string
     */
    public static $shippingName = "#shipping_rate_name";

    /**
     * @var string
     */
    public static $shippingRateValue = "#shipping_rate_value";

    /**
     * @var string
     */
    public static $weightStart = "#shipping_rate_weight_start";

    /**
     * @var string
     */
    public static $weightEnd = "#shipping_rate_weight_end";

    /**
     * @var string
     */
    public static $volumeStart = "#shipping_rate_volume_start";

    /**
     * @var string
     */
    public static $volumeEnd = "#shipping_rate_volume_end";

    /**
     * @var string
     */
    public static $shippingRateLenghtStart = "#shipping_rate_length_start";

    /**
     * @var string
     */
    public static $shippingRateLegnhtEnd = "#shipping_rate_length_end";

    /**
     * @var string
     */
    public static $shippingRateWidthStart = "#shipping_rate_width_start";

    /**
     * @var string
     */
    public static $shippingRateWidthEnd = "#shipping_rate_width_end";

    /**
     * @var string
     */
    public static $shippingRateHeightEnd = "#shipping_rate_height_end";

    /**
     * @var string
     */
    public static $shippingRateHeightStart = "#shipping_rate_height_start";

    /**
     * @var string
     */
    public static $orderTotalStart = "#shipping_rate_ordertotal_start";

    /**
     * @var string
     */
    public static $orderTotalEnd = "#shipping_rate_ordertotal_end";

    /**
     * @var string
     */
    public static $zipCodeStart = "#shipping_rate_zip_start";

    /**
     * @var string
     */
    public static $zipCodeEnd = "#shipping_rate_zip_start";

    /**
     * @var string
     */
    public static $country = "#s2id_shipping_rate_country";

    /**
     * @var string
     */
    public static $shippingRateProduct = "#s2id_container_product";

    /**
     * @var string
     */
    public static $shippingCategory = "#s2id_shipping_rate_on_category";

    /**
     * @var string
     */
	public static $shipingCategorySearch = "#s2id_autogen4";

    /**
     * @var string
     */
    public static $shippingShopperGroups = "#s2id_shipping_rate_on_shopper_group";

    /**
     * @var string
     */
    public static $shippingPriority = "#shipping_rate_priority";

    /**
     * @var string
     */
    public static $shippingRateFor = "#s2id_company_only";

    /**
     * @var string
     */
    public static $shippingRateForSearch = "#s2id_autogen6_search";

    /**
     * @var string
     */
    public static $shippingVATGroups = "#s2id_shipping_tax_group_id";

    /**
     * @var string
     */
    public static $shippingVATGroupsSearh = "#s2id_autogen7_search";

    /**
     * @var string
     */
    public static $pickupAthThePost = "#deliver_type0-lbl";

    /**
     * @var string
     */
    public static $regularDelivery = "#deliver_type1-lbl";

    /**
     * @var string
     */
    public static $scrollDown = "window.scrollTo(200,2000)";

}