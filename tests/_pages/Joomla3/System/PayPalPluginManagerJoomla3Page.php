<?php
/**
 * @package     redSHOP
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class PayPalPluginManagerJoomla3Page
 *
 * @since  1.4.0
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 */
class PayPalPluginManagerJoomla3Page
{
	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $payPalUseField = "//fieldset[@id='jform_params_sandbox']/label[2]";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $payPalBusinessAccountEmail = "//input[@id='jform_params_merchant_email']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $pluginSuccessSavedMessage = "Plugin successfully saved";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $payPalPaymentOptionSelectOnCheckout = "//input[@id='rs_payment_paypal1']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $payPalAccountLoginPage = "//h2[@id='loginPageTitle']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $payWithPayPalAccountOption = "//input[@id='loadLogin']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $payPalLoginEmailField = "//input[@id='login_email']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $payPalPasswordField = "//input[@id='login_password']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $privateComputerField = "//input[@id='privateDeviceCheckbox']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $submitLoginField = "//input[@id='submitLogin']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $payNowField = "//input[@id='continue_abovefold']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $paymentCompletionSuccessMessage = "//span[@title='You just completed your payment.']";
}
