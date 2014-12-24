<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class GlobalConfigurationManagerJoomla3Page
 *
 * @since  1.4
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 */
class GlobalConfigurationManagerJoomla3Page
{
	// Include url of current page
	public static $URL = '/administrator/index.php?option=com_config';

	public static $errorReportingDropDown = "//div[@id='jform_error_reporting_chzn']/a";

	public static $pageTitle = "Global Configuration";

	public static $successMessage = "Configuration successfully saved.";

	public static $serverLink = "Server";

	public static $errorReporting = "//div[@id='jform_error_reporting_chzn']/div/ul/li[contains(text(), 'Development')]";
}
