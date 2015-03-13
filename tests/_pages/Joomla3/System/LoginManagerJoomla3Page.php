<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class LoginManagerJoomla3Page
 *
 * @since  1.4
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 */
class LoginManagerJoomla3Page
{
	// Include url of current page
	public static $URL = '/administrator/index.php';

	public static $userName = "username";

	public static $password = "passwd";

	public static $loginSuccessCheck = "//a//span[text() = 'Category Manager']";

	public static $logoutUserToggleButton = "//ul[@class='nav nav-user pull-right']//li//a[@class='dropdown-toggle']";

	public static $logoutLink = "//a[text() = 'Logout']";
}
