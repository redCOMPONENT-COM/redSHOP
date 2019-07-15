<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class PluginManagerJoomla3Page
 *
 * @since  2.4
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 */
class PluginManagerJoomla3Page extends AdminJ3Page
{
	// Include url of current page

	/**
	 * @var string
	 */
	public static $URL = '/administrator/index.php?option=com_plugins';

	/**
	 * @var string
	 */
	public static $pluginSearch = "//input[@id='filter_search']";

	/**
	 * @var string
	 */
	public static $searchButton = "//button[@type='submit' and @data-original-title='Search']";

	/**
	 * @var string
	 */
	public static $searchResultRow = "//form[@id='adminForm']/div/table/tbody/tr[1]";

	/**
	 * @var string
	 */
	public static $pluginStatePath = "//form[@id='adminForm']/div/table/tbody/tr[1]/td[3]/a";

	/**
	 * @var string
	 */
	public static $checkAll = "//input[@onclick='Joomla.checkAll(this)']";

	/**
	 * @var string
	 */
	public static $firstCheck = "//input[@id='cb0']";

	/**
	 * @var string
	 */
	public static $pluginEnabledSuccessMessage = "Plugin successfully enabled";

	/**
	 * @var string
	 */
	public static $btnDisable = 'Disable';

	/**
	 * @var string
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
	public static $merchantID = '//input[@id="jform_params_merchant_id"]';

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $keyMD5 = '//input[@id="jform_params_epay_paymentkey"]';

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
}
