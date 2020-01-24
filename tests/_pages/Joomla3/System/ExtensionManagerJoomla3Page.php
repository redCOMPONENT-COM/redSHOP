<?php
/**
 * @package     redSHOP
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
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
	 * @since 2.9.5
	 */
	public static $URL = '/administrator/index.php?option=com_installer';

	/**
	 * @var string
	 * @since 2.9.5
	 */
	public static $extensionDirectoryPath = "#install_directory";

	/**
	 * @var string
	 * @since 2.9.5
	 */
	public static $installButton = "//input[contains(@onclick,'Joomla.submitbutton3()')]";

	/**
	 * @var string
	 * @since 2.9.5
	 */
	public static $installSuccessMessage = "Installation of the component was successful.";

	/**
	 * @var string
	 * @since 2.9.5
	 */
	public static $installDemoContent = "//input[@value='Install Demo Content']";

	/**
	 * @var string
	 * @since 2.9.5
	 */
	public static $demoDataInstallSuccessMessage = "Sample Data Installed Successfully";

	/**
	 * @var string
	 */
	public static $extensionSearchJ3 = "//input[@id='filter_search']";

	/**
	 * @var string
	 * @since 2.9.5
	 */
	public static $extensionSearchJ2 = "//input[@id='filters_search']";

	/**
	 * @var string
	 * @since 2.9.5
	 */
	public static $checkAll = "//input[@onclick='Joomla.checkAll(this)']";

	/**
	 * @var string
	 * @since 2.9.5
	 */
	public static $firstCheck = "//input[@id='cb0']";

	/**
	 * @var string
	 * @since 2.9.5
	 */
	public static $extensionNameLink = "//a[contains(text(),'Name')]";

	/**
	 * @var string
	 * @since 2.9.5
	 */
	public static $extensionTable = "//form[@id='adminForm']/div/table/tbody/tr[1]/td[2]/label/span";

	/**
	 * @var string
	 * @since 2.9.5
	 */
	public static $extensionTableJ2 = "//form[@id='adminForm']/table/tbody/tr[1]/td[2]/span";

	/**
	 * @var string
	 * @since 2.9.5
	 */
	public static $uninstallSuccessMessageJ3 = "//p[contains(text(),'successful')]";

	/**
	 * @var string
	 * @since 2.9.5
	 */
	public static $uninstallSuccessMessageJ2 = "//li[contains(text(),'successful')]";

	/**
	 * @var string
	 * @since 2.9.5
	 */
	public static $uninstallComponentSuccessMessageJ2 = "//li[contains(text(),'Uninstalling component was successful')]";

	/**
	 * @var string
	 * @since 2.9.5
	 */
	public static $uninstallComponentSuccessMessageJ3 = "//p[contains(text(),'Uninstalling component was successful')]";

	/**
	 * @var string
	 * @since 2.9.5
	 */
	public static $noExtensionMessageJ3 = "//p[contains(text(),'There are no extensions installed matching your query')]";

	/**
	 * @var string
	 * @since 2.9.5
	 */
	public static $noExtensionMessageJ2 = "//li[contains(text(),'There are no extensions installed matching your query')]";

	/**
	 * @var string
	 * @since 2.9.5
	 */
	public static $searchResultSpan = "//form[@id='adminForm']/div/table/tbody/tr[1]/td[2]/span";

	/**
	 * @var string
	 * @since 2.9.5
	 */
	public static $searchButtonJ3 = "//button[@type='submit' and @data-original-title='Search']";

	/**
	 * @var string
	 * @since 2.9.5
	 */
	public static $searchButtonJ2 = "//button[@class='btn' and @type='submit' and contains(text(),'Search')]";

	/**
	 * @var string
	 * @since 2.9.5
	 */
	public static $searchTools = "//button[@class='btn hasTooltip js-stools-btn-filter']";

	/**
	 * @var string
	 * @since 2.9.5
	 */
	 public static $buttonClear = 'Clear';
}
