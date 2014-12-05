<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class ExtensionManagerPage
 *
 * @since  1.4
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 */
class ExtensionManagerPage
{
	// Include url of current page
	public static $URL = '/administrator/index.php?option=com_installer';

	public static $extensionDirectoryPath = "#install_directory";

	public static $installButton = "//input[contains(@onclick,'Joomla.submitbutton3()')]";

	public static $installSuccessMessage = "//li[contains(text(),'Installing component was successful')]";

	public static $installDemoContent = "//input[@onclick=\"submitWizard('content');\" and @value='Install Demo Content']";

	public static $demoDataInstallSuccessMessage = "//li[contains(text(),'Sample Data Installed Successfully')]";

	public static $extensionSearch = "//input[@id='filters_search']";

	public static $checkAll = "//input[@onclick='Joomla.checkAll(this)']";

	public static $firstCheck = "//input[@id='cb0']";

	public static $extensionNameLink = "//a[contains(text(),'Name')]";

	public static $extensionTable = "//form[@id='adminForm']/table/tbody/tr[1]/td[2]//span";

	public static $uninstallSuccessMessage = "//li[contains(text(),'successful')]";

	public static $uninstallComponentSuccessMessage = "//li[contains(text(),'Uninstalling component was successful')]";

	public static $noExtensionMessage = "//li[contains(text(),'There are no extensions installed matching your query')]";

	public static $searchResultSpan = "//form[@id='adminForm']/table/tbody/tr[1]/td[2]/span";

	public static $searchButton = "//button[@class='btn' and @type='submit' and contains(text(),'Search')]";
}
