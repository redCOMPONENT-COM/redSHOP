<?php
/**
 * @package     redSHOP
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Shipping Page for Shipping Management and shipping rate Page
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  1.4.0
 */
class ShippingPage extends AdminJ3Page
{
	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $shippingManagementUrl = 'administrator/index.php?option=com_redshop&view=shipping_methods';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $standShipping = "redSHOP - Standard Shipping";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $shippingRate = "#toolbar-redshop_shipping_rates32";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $shippingName = "#shipping_rate_name";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $shippingRateValue = "#shipping_rate_value";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $weightStart = "#shipping_rate_weight_start";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $weightEnd = "#shipping_rate_weight_end";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $volumeStart = "#shipping_rate_volume_start";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $volumeEnd = "#shipping_rate_volume_end";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $shippingRateLenghtStart = "#shipping_rate_length_start";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $shippingRateLegnhtEnd = "#shipping_rate_length_end";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $shippingRateWidthStart = "#shipping_rate_width_start";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $shippingRateWidthEnd = "#shipping_rate_width_end";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $shippingRateHeightEnd = "#shipping_rate_height_end";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $shippingRateHeightStart = "#shipping_rate_height_start";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $orderTotalStart = "#shipping_rate_ordertotal_start";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $orderTotalEnd = "#shipping_rate_ordertotal_end";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $zipCodeStart = "#shipping_rate_zip_start";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $zipCodeEnd = "#shipping_rate_zip_end";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $country = "#s2id_shipping_rate_country";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $shippingRateProduct = "#s2id_container_product";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $shippingCategory = "#s2id_shipping_rate_on_category";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $shipingCategorySearch = "#s2id_autogen4";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $shippingShopperGroups = "#s2id_autogen5";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $shippingPriority = "#shipping_rate_priority";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $shippingRateFor = "#s2id_company_only";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $shippingRateForSearch = "#s2id_autogen6_search";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $shippingVATGroups = "#s2id_shipping_tax_group_id";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $shippingVATGroupsSearh = "#s2id_autogen7_search";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $pickupAthThePost = "#deliver_type0-lbl";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $regularDelivery = "#deliver_type1-lbl";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $scrollDown = "window.scrollTo(200,2000)";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $editShipping = "//a[@title='Edit Shipping']";
}
