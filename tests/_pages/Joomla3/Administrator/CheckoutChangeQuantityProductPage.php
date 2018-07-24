<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2018 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
/**
 * Class Checkout Product Change Quantity Page
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  2.1
 */
class CheckoutChangeQuantityProductPage extends AdminJ3Page
{
	/**
	 * @var array
	 */
	public static $categoryTitle = ".category_front_inside";
	public static $fillUserName = "#modlgn-username";
	public static $fillPassWord = "#modlgn-passwd";
	public static $submitButton = "Log in";
	/**
	 * @var array
	 */
	public static $quantityField = "#quantitybox0";
	/**
	 * @var array
	 */
	public static $updateCartButton = "//img[@onclick=\"document.update_cart0.task.value='update';document.update_cart0.submit();\"]";
}
