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
class CheckoutProductChangeQuantityPage extends AdminJ3Page
{
	/**
	 * @var string
	 */
	public static $URL = "/administrator/index.php?option=com_redshop&view=configuration";

	/**
	 * @var string
	 */
	public static $Cart = "Cart / Checkout";

	/**
	 * @var array
	 */
	public static $disableQuantity = ['id' => 'quantity_text_display0-lbl'];

	/**
	 * @var array
	 */
	public static $enableQuantity = ['id' => 'quantity_text_display1-lbl'];

	/**
	 * @var array
	 */
	public static $categoryTitle = ['class' => 'category_front_inside'];

	/**
	 * @var array
	 */
	public static $quantityField = ['id' => 'quantitybox0'];

    /**
     * @var array
     */
	public static $Term = ['id' => 'termscondition'];

	/**
	 * @var array
	 */
	public static $updateCartButton = ['xpath' => "//img[@onclick=\"document.update_cart0.task.value='update';document.update_cart0.submit();\"]"];
}