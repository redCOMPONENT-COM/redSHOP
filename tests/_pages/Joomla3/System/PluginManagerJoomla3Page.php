<?php
/**
 * @package     redSHOP
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class PluginManagerJoomla3Page
 *
 * @since  1.4.0
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 */
class PluginManagerJoomla3Page extends AdminJ3Page
{
	// Include url of current page

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $URL = '/administrator/index.php?option=com_plugins';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $pluginSearch = "//input[@id='filter_search']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $searchButton = "//button[@type='submit' and @data-original-title='Search']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $searchResultRow = "//form[@id='adminForm']/div/table/tbody/tr[1]";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $pluginStatePath = "//form[@id='adminForm']/div/table/tbody/tr[1]/td[3]/a";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $checkAll = "//input[@onclick='Joomla.checkAll(this)']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $firstCheck = "//input[@id='cb0']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $pluginEnabledSuccessMessage = "Plugin successfully enabled";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $btnDisable = 'Disable';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $messageDisable = "disabled";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $vendorID = "//input[@id='jform_params_vendor_id']";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $secretWords = "//input[@id='jform_params_secret_words']";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $pluginSaveSuccessMessage = 'Plugin saved.';

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $fieldAccessId = "#jform_params_access_id" ;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $fieldTransactionID = "#jform_params_transaction_id" ;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $fieldMd5Key = "#jform_params_md5_key" ;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $fieldTestMode = "//div[@id='jform_params_is_test_chzn']/a" ;

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $optionTestModeYes= "//div[@id='jform_params_is_test_chzn']/div/ul/li[contains(text(), 'Yes')]";

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $customerID = "#jform_params_eway_customer_id";

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $advancedTag = "//a[contains(text(),'Advanced')]";

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $visa = '#jform_params_accepted_credict_card0';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $masterCard = '#jform_params_accepted_credict_card1';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $fieldPaymentOprand = "#jform_params_payment_oprand";

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $fieldPaymentPrice = "#jform_params_payment_price";

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $optionPercentage = "#jform_params_payment_discount_is_percent0";

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $optionTotal = "#jform_params_payment_discount_is_percent1";

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $merchantEmail = "#jform_params_pay_to_email";

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $merchantPassword = "#jform_params_pay_to_password";

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $fieldPublicKey = "#jform_params_paymill_public_key";

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $fieldPrivateKey = "#jform_params_paymill_private_key";

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $labelEnvironment = "Environment";

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $discountTypeTotal= '//label[@for="jform_params_payment_discount_is_percent1"]';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $secretKey = '#jform_params_secretKey';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $publishableKey = '#jform_params_publishableKey';

	/**
	 * Function to return Path for the Plugin Name to be searched for
	 *
	 * @param   String  $pluginName  Name of the Plugin
	 *
	 * @return string
	 */
	public function searchResultPluginName($pluginName)
	{
		$path = "//form[@id='adminForm']/div/table/tbody/tr[1]/td[4]/a[contains(text(), '" . $pluginName . "')]";

		return $path;
	}

	/**
	 * @var string
	 * @since 2.1.4
	 */
	public static $fieldMerchantID = '#jform_params_merchantId';

	/**
	 * @var string
	 * @since 2.1.4
	 */
	public static $fieldSharedSecretKey = '#jform_params_sharedSecret';
}
