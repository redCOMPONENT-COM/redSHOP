<?php
/**
 * @package     RedShop
 * @subpackage  Page
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class PriceProductJoomla3Page
 *
 * @since  2.1.2
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
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
	public static $discount = ['name' => "discount_price[]"];

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $priceProduct = "#product_price_44";

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
	public static $buttonAddPrice = '//button[@class=\'btn btn-small button-new btn-success\']';

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $titlePrice = 'Price';

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $productPrice = '#product_price';

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
	public static $messageQuantity = 'Quantity End should be greater than Quantity start.';

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $messagePrice = 'Price detail saved.';

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $selectOption = '#s2id_shopper_group_id';

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $discountPrice = '#discount_price';

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $defaultPrivate = 'Default Private';

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $search = '#s2id_autogen1_search';

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $chooseDefaultPrivate = "//li[@class='select2-results-dept-0 select2-result select2-result-selectable select2-highlighted']";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $buttonAddToCart = "//span[@class='pdaddtocart_link btn btn-primary']";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $shoppingCart = 'Shopping Cart';

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $logInForm = 'Login Form';

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $buttonSubmit = "//button[@name='Submit']";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $userName = '#modlgn-username';

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $passWord = '#modlgn-passwd';
}