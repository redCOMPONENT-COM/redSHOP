<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
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

	public static $installSuccessMessage = "Installing component was successful.";

	public static $installDemoContent = "//input[@value='Install Demo Content']";

	public static $demoDataInstallSuccessMessage = "Sample Data Installed Successfully";

	public static $extensionSearchJ3 = "//input[@id='filter_search']";

	public static $extensionSearchJ2 = "//input[@id='filters_search']";

	public static $checkAll = "//input[@onclick='Joomla.checkAll(this)']";

	public static $firstCheck = "//input[@id='cb0']";

	public static $extensionNameLink = "//a[contains(text(),'Name')]";

	public static $extensionTable = "//form[@id='adminForm']/div/table/tbody/tr[1]/td[2]/label/span";

	public static $extensionTableJ2 = "//form[@id='adminForm']/table/tbody/tr[1]/td[2]/span";

	public static $uninstallSuccessMessageJ3 = "//p[contains(text(),'successful')]";

	public static $uninstallSuccessMessageJ2 = "//li[contains(text(),'successful')]";

	public static $uninstallComponentSuccessMessageJ2 = "//li[contains(text(),'Uninstalling component was successful')]";

	public static $uninstallComponentSuccessMessageJ3 = "//p[contains(text(),'Uninstalling component was successful')]";

	public static $noExtensionMessageJ3 = "//p[contains(text(),'There are no extensions installed matching your query')]";

	public static $noExtensionMessageJ2 = "//li[contains(text(),'There are no extensions installed matching your query')]";

	public static $searchResultSpan = "//form[@id='adminForm']/div/table/tbody/tr[1]/td[2]/span";

	public static $searchButtonJ3 = "//button[@type='submit' and @data-original-title='Search']";

	public static $searchButtonJ2 = "//button[@class='btn' and @type='submit' and contains(text(),'Search')]";
}
