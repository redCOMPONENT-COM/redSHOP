<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class FrontEndLoginManagerJoomla3Page
 *
 * @since  1.4
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 */
class FrontEndLoginManagerJoomla3Page
{
	public static $frontEndLoginURL = "/index.php?option=com_users&view=login";

	public static $frontEndUserName = "//input[@id='username']";

	public static $frontEndPassword = "//input[@id='password']";

	public static $frontEndLoginButton = "//div[@class='login']/form/fieldset/div[4]/div/button";

	public static $frontEndHomeButton = "//a[text()='Home']";

	public static $frontEndLogoutButton = "//input[@value='Log out']";

	public static $frontEndLoginSuccess = "//form[@id='member-profile']";
}
