<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class PluginManagerJoomla3Page
 *
 * @since  2.4
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 */
class PluginManagerJoomla3Page
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
