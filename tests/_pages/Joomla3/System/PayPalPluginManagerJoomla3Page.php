<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class PayPalPluginManagerJoomla3Page
 *
 * @since  1.4
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 */
class PayPalPluginManagerJoomla3Page
{
	public static $payPalUseField = "//fieldset[@id='jform_params_sandbox']/label[2]";

	public static $payPalBusinessAccountEmail = "//input[@id='jform_params_merchant_email']";

	public static $pluginSuccessSavedMessage = "Plugin successfully saved";

	public static $payPalPaymentOptionSelectOnCheckout = "//input[@id='rs_payment_paypal1']";

	public static $payPalAccountLoginPage = "//h2[@id='loginPageTitle']";

	public static $payWithPayPalAccountOption = "//input[@id='loadLogin']";

	public static $payPalLoginEmailField = "//input[@id='login_email']";

	public static $payPalPasswordField = "//input[@id='login_password']";

	public static $privateComputerField = "//input[@id='privateDeviceCheckbox']";

	public static $submitLoginField = "//input[@id='submitLogin']";

	public static $payNowField = "//input[@id='continue_abovefold']";

	public static $paymentCompletionSuccessMessage = "//span[@title='You just completed your payment.']";
}
