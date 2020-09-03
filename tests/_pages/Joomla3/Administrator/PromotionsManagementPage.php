<?php
/**
 * @package     redSHOP
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class PromotionsManagementPage
 * @since 3.0.3
 */
class PromotionsManagementPage extends AdminJ3Page
{
	/**
	 * @var string
	 * @since 3.0.3
	 */
	public static $url = "/administrator/index.php?option=com_redshop&view=promotions";

	/**
	 * @var string
	 * @since 3.0.3
	 */
	public static $titlePage = "Promotions Management";

	/**
	 * @var string
	 * @since 3.0.3
	 */
	public static $titlePageNew = "Promotion [ New ]";

	/**
	 * @var string
	 * @since 3.0.3
	 */
	public static $titlePageEdit = "Promotion [ Edit ]";

	/**
	 * @var string
	 * @since 3.0.3
	 */
	public static $selectPromotionType = "//div[@id='s2id_promotion_type']";

	/**
	 * @var string
	 * @since 3.0.3
	 */
	public static $searchPromotionType = "//input[@id='s2id_autogen2_search']";

	/**
	 * @var string
	 * @since 3.0.3
	 */
	public static $selectManufacturer = "#s2id_manufacturer";

	/**
	 * @var string
	 * @since 3.0.3
	 */
	public static $inputManufacturer = "#s2id_autogen3";

	/**
	 * @var string
	 * @since 3.0.3
	 */
	public static $selectCategory = "#s2id_category";

	/**
	 * @var string
	 * @since 3.0.3
	 */
	public static $inputCategory = "#s2id_autogen4";

	/**
	 * @var string
	 * @since 3.0.3
	 */
	public static $selectProduct = "#s2id_product";

	/**
	 * @var string
	 * @since 3.0.3
	 */
	public static $inputProduct = "#s2id_autogen5";

	/**
	 * @var string
	 * @since 3.0.3
	 */
	public static $inputConditionAmount = "#condition_amount";

	/**
	 * @var string
	 * @since 3.0.3
	 */
	public static $inputFromDate = "#from_date";

	/**
	 * @var string
	 * @since 3.0.3
	 */
	public static $inputToDate = "#to_date";

	/**
	 * @var string
	 * @since 3.0.3
	 */
	public static $inputOrderVolume = "#order_volume";

	/**
	 * @var string
	 * @since 3.0.3
	 */
	public static $selectProductAwards = "#s2id_product_award";

	/**
	 * @var string
	 * @since 3.0.3
	 */
	public static $searchProductAwards = "#s2id_autogen6_search";

	/**
	 * @var string
	 * @since 3.0.3
	 */
	public static $searchProductAwards2 = "#s2id_autogen3_search";

	/**
	 * @var string
	 * @since 3.0.3
	 */
	public static $inputAwardAmount = "#award_amount";

	/**
	 * @var string
	 * @since 3.0.3
	 */
	public static $selectFreeShipping = "#s2id_free_shipping";

	/**
	 * @var string
	 * @since 3.0.3
	 */
	public static $searchFreeShipping = "#s2id_autogen7_search";

	/**
	 * @var string
	 * @since 3.0.3
	 */
	public static $searchFreeShipping2 = "#s2id_autogen4_search";

	/**
	 * @var string
	 * @since 3.0.3
	 */
	public static $textareDescription = "#jform_desc";
}