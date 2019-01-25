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
 * @since  2.4
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 */
class PayPalPluginManagerJoomla3Page
{
    /**
     * @var string
     */
	public static $payPalUseField = "//fieldset[@id='jform_params_sandbox']/label[2]";

    /**
     * @var string
     */
	public static $payPalBusinessAccountEmail = "//input[@id='jform_params_merchant_email']";

    /**
     * @var string
     */
	public static $pluginSuccessSavedMessage = "Plugin successfully saved";

    /**
     * @var string
     */
	public static $payPalPaymentOptionSelectOnCheckout = "//input[@id='rs_payment_paypal1']";

    /**
     * @var string
     */
	public static $payPalAccountLoginPage = "//h2[@id='loginPageTitle']";

    /**
     * @var string
     */
	public static $payWithPayPalAccountOption = "//input[@id='loadLogin']";

    /**
     * @var string
     */
	public static $payPalLoginEmailField = "//input[@id='login_email']";

    /**
     * @var string
     */
	public static $payPalPasswordField = "//input[@id='login_password']";

    /**
     * @var string
     */
	public static $privateComputerField = "//input[@id='privateDeviceCheckbox']";

    /**
     * @var string
     */
	public static $submitLoginField = "//input[@id='submitLogin']";

    /**
     * @var string
     */
	public static $payNowField = "//input[@id='continue_abovefold']";

    /**
     * @var string
     */
	public static $paymentCompletionSuccessMessage = "//span[@title='You just completed your payment.']";
}
