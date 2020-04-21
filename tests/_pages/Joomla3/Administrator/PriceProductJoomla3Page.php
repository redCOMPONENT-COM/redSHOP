<?php
/**
 * @package     redSHOP
 * @subpackage  page
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class PriceProductJoomla3Page
 * @since 2.1.2
 */
class PriceProductJoomla3Page extends AdminJ3Page
{
	/**
	 * @var string
     * @since 2.1.2
	 */
	public static $URL = '/administrator/index.php?option=com_redshop&view=product&layout=listing';

	/**
	 * @var array
     * @since 2.1.2
	 */
	public static $discount = '//input[@name="discount_price[]"]';

	/**
	 * @var string
     * @since 2.1.2
	 */
	public static $priceProduct = '//input[@name="price[]"]';

	/**
	 * @var string
     * @since 2.1.2
	 */
	public static $quantityStart = "//*[@id=\"price_quantity_start\"]";

	/**
	 * @var string
     * @since 2.1.2
	 */
	public static $quantityEnd = "#price_quantity_end";

	/**
	 * @var array
     * @since 2.1.2
	 */
	public static $priceDefault = ['name' => "price[]"];

	/**
	 * @var string
     * @since 2.1.2
	 */
	public static $saveButton = "//a[contains(@href,'savediscountprice')]";

	/**
	 * @var string
	 * @since 3.0.2
	 */
	public static $savePrice = "//a[contains(@href,'saveprice')]";

	/**
	 * @var string
     * @since 2.1.2
	 */
	public static $quantityStartPopup = "//td[contains(text(), 'Default Private')]/../td[2]/input";

	/**
	 * @var string
     * @since 2.1.2
	 */
	public static $quantityEndPopup = "//td[contains(text(), 'Default Private')]/../td[3]/input";

	/**
	 * @var string
     * @since 2.1.2
	 */
	public static $namePage = "Product Price Management";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $startDate = '#discount_start_date';

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $endDate = '#discount_end_date';

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $savePriceSuccess = 'Price detail saved';

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $discountPrice = '#discount_price';
}