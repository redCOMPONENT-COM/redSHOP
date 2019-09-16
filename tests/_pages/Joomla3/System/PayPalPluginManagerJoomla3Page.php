<?php
/**
 * @package     redSHOP
 * @subpackage  Page
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class PayPalPluginManagerJoomla3Page
 *
 * @since  2.1.2
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 */
class PayPalPluginManagerJoomla3Page extends FrontEndProductManagerJoomla3Page
{
	/**
	 * @var string
	 * @since  2.1.2
	 */
	public static $payPalPaymentOptionSelectOnCheckout = "//input[@value='rs_payment_paypal']";

	/**
	 * @var string
	 * @since  2.1.2
	 */
	public static $payWithPayPalAccountOption = "//a[@ng-click='logWebviewLoginClickWrapper(); redirect()']";

	/**
	 * @var string
	 * @since  2.1.2
	 */
	public static $payPalLoginEmailField = '#email';

	/**
	 * @var string
	 * @since  2.1.2
	 */
	public static $payPalPasswordField = '#password';

	/**
	 * @var string
	 * @since  2.1.2
	 */
	public static $submitLoginField = '#btnLogin';

	/**
	 * @var string
	 * @since  2.1.2
	 */
	public static $payNowField = "#confirmButtonTop";

	/**
	 * @var string
	 * @since  2.1.2
	 */
	public static $paymentCompletionSuccessMessage = '#merchant-text';

	/**
	 * @var string
	 * @since  2.1.2
	 */
	public static $successMessage = "You paid";

	/**
	 * @var string
	 * @since  2.1.2
	 */
	public static $merchantReturnBtn = '#merchantReturnBtn';
}
