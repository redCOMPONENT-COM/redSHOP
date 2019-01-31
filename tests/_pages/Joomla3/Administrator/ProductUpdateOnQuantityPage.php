<?php

/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class ProductUpdateOnQuantityPage
 *
 * @since  2.4
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 */
class ProductUpdateOnQuantityPage extends AdminJ3Page
{

	/**
	 * @var string
	 */
	public static $menuItemURL = '/administrator/index.php?option=com_menus&view=menus';

	/**
	 * @var string
	 */
	public static $menuTitle   = 'Menus';

	/**
	 * @var string
	 */
	public static $menuItemsTitle   = 'Menus: Items';

	/**
	 * @var string
	 */
	public static $menuNewItemTitle   = 'Menus: New Item';

	/**
	 * Menu item title
	 * @var string
	 */
	public static $menItemTitle = "#jform_title";

	/**
	 * @var   string
	 */
	public static $buttonSelect = "//button[@title='Select']";

	/**
	 * @var string
	 */
	public static $messageMenuItemSuccess = 'Menu item saved';

	/**
	 * Menu Type Modal
	 * @var string
	 */
	public static $menuTypeModal = "#menuTypeModal";

	/**
	 * @var string
	 */
	public static $messageAddToCartSuccess = "Product has been added to your cart.";

	/**
	 * @param $menuCategory
	 * @return array
	 */
	public static function getMenuCategory($menuCategory)
	{
		$menuCate = ["link" => $menuCategory];

		return $menuCate;
	}

	/**
	 * @param $menuItem
	 * @return string
	 * @since  5.11.0
	 */
	public static function returnMenuItem($menuItem)
	{
		$path = "//a[contains(text()[normalize-space()], '$menuItem')]";
		return $path;
	}

	/**
	 * Menu item save message
	 * @var string
	 * @since  5.11.0
	 */
	public static $message = '#system-message-container';
}

