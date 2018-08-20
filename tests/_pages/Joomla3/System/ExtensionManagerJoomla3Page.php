<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class ExtensionManagerJoomla3Page
 *
 * @since  2.4
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 */
class ExtensionManagerJoomla3Page
{
	// Include url of current page

    /**
     * @var string
     */
	public static $URL = '/administrator/index.php?option=com_installer';

    /**
     * @var string
     */
	public static $extensionDirectoryPath = "#install_directory";

    /**
     * @var string
     */
	public static $installButton = "//input[contains(@onclick,'Joomla.submitbutton3()')]";

    /**
     * @var string
     */
	public static $installSuccessMessage = "Installation of the component was successful.";

    /**
     * @var string
     */
	public static $installDemoContent = "//input[@value='Install Demo Content']";

    /**
     * @var string
     */
	public static $demoDataInstallSuccessMessage = "Sample Data Installed Successfully";

    /**
     * @var string
     */
	public static $extensionSearchJ3 = "//input[@id='filter_search']";

    /**
     * @var string
     */
	public static $extensionSearchJ2 = "//input[@id='filters_search']";

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
	public static $extensionNameLink = "//a[contains(text(),'Name')]";

    /**
     * @var string
     */
	public static $extensionTable = "//form[@id='adminForm']/div/table/tbody/tr[1]/td[2]/label/span";

    /**
     * @var string
     */
	public static $extensionTableJ2 = "//form[@id='adminForm']/table/tbody/tr[1]/td[2]/span";

    /**
     * @var string
     */
	public static $uninstallSuccessMessageJ3 = "//p[contains(text(),'successful')]";

    /**
     * @var string
     */
	public static $uninstallSuccessMessageJ2 = "//li[contains(text(),'successful')]";

    /**
     * @var string
     */
	public static $uninstallComponentSuccessMessageJ2 = "//li[contains(text(),'Uninstalling component was successful')]";

    /**
     * @var string
     */
	public static $uninstallComponentSuccessMessageJ3 = "//p[contains(text(),'Uninstalling component was successful')]";

    /**
     * @var string
     */
	public static $noExtensionMessageJ3 = "//p[contains(text(),'There are no extensions installed matching your query')]";

    /**
     * @var string
     */
	public static $noExtensionMessageJ2 = "//li[contains(text(),'There are no extensions installed matching your query')]";

    /**
     * @var string
     */
	public static $searchResultSpan = "//form[@id='adminForm']/div/table/tbody/tr[1]/td[2]/span";

    /**
     * @var string
     */
	public static $searchButtonJ3 = "//button[@type='submit' and @data-original-title='Search']";

    /**
     * @var string
     */
	public static $searchButtonJ2 = "//button[@class='btn' and @type='submit' and contains(text(),'Search')]";

    /**
     * @var string
     */
	public static $searchTools = "//button[@class='btn hasTooltip js-stools-btn-filter']";
}
