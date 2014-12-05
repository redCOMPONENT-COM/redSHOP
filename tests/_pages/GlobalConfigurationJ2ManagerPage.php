<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class GlobalConfigurationJ2ManagerPage
 *
 * @since  1.4
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 */
class GlobalConfigurationJ2ManagerPage
{
	// Include url of current page
	public static $URL = '/administrator/index.php?option=com_config';

	public static $errorReporting = "//select[@id='jform_error_reporting']";

	public static $pageTitle = "Global Configuration";

	public static $successMessage = "Configuration successfully saved.";
}
