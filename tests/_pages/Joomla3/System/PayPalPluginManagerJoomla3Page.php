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
class PayPalPluginManagerJoomla3Page
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
	public static $payPalAccountLoginPage = "//h2[@id='loginPageTitle']";

	/**
	 * @var string
	 * @since  2.1.2
	 */
	public static $payWithPayPalAccountOption = "//a[@ng-click='logWebviewLoginClickWrapper(); redirect()']";

	/**
	 * @var string
	 * @since  2.1.2
	 */
	public static $payPalLoginEmailField = "//input[@id='email']";

	/**
	 * @var string
	 * @since  2.1.2
	 */
	public static $payPalPasswordField = "//input[@id='password']";

	/**
	 * @var string
	 * @since  2.1.2
	 */
	public static $privateComputerField = "//input[@id='privateDeviceCheckbox']";

	/**
	 * @var string
	 * @since  2.1.2
	 */
	public static $submitLoginField = "//button[@id='btnLogin']";

	/**
	 * @var string
	 * @since  2.1.2
	 */
	public static $continueField = '//button[@ng-click="continue()"]';

	/**
	 * @var string
	 * @since  2.1.2
	 */
	public static $payNowField = "//input[@id='confirmButtonTop']";

	/**
	 * @var string
	 * @since  2.1.2
	 */
	public static $paymentCompletionSuccessMessage = "//div[@id='merchant-text']";

	/**
	 * @var string
	 * @since  2.1.2
	 */
	public static $textPaypalSteps1 = "Pay with debit or credit card";

	/**
	 * @var string
	 * @since  2.1.2
	 */
	public static $textPaypalSteps2 = "Pay with PayPal";

	/**
	 * @var string
	 * @since  2.1.2
	 */
	public static $textPaypalSteps3 = "Ship to";
}
