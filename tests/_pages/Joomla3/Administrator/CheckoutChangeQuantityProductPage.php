<?php
/**
 * @package     redSHOP
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
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
	 * @var string
	 * @since 2.1.0
	 */
	public static $submitButton = "Log in";

	/**
	 * @var array
	 * @since 2.1.0
	 */
	public static $quantityField = "#quantitybox0";

	/**
	 * @var string
	 * @since 2.1.0
	 */
	public static $bankTransfer = "#rs_payment_banktransfer";

	/**
	 * @var array
	 * @since 2.1.0
	 */
	public static $updateCartButton = "//img[@class='update_cart']";
}
