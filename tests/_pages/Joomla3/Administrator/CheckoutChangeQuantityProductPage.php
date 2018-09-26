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
	 * @var string
	 */
	public static $submitButton = "Log in";

	/**
	 * @var array
	 */
	public static $quantityField = "#quantitybox0";

	/**
	 * @var string
	 */
	public static $bankTransfer = "#rs_payment_banktransfer";

    /**
     * @var string
     */
	public static $term = "#termscondition";

    /**
     * @var string
     */
	public static $finalStep = "#checkout_final";

	/**
	 * @var array
	 */
	public static $updateCartButton = "//img[@class='update_cart']";
}
