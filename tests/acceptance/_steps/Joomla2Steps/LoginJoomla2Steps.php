<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;
/**
 * Class LoginJoomla2Steps
 *
 * @package  AcceptanceTester
 *
 * @since    1.4
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 */
class LoginJoomla2Steps extends \AcceptanceTester
{
	/**
	 * Function to execute an Admin Login for Joomla2.5
	 *
	 * @return void
	 */
	public function doAdminLogin()
	{
		$I = $this;
		$this->acceptanceTester = $I;
		$I->amOnPage(\LoginManagerJoomla2Page::$URL);
		$config = $I->getConfig();
		$I->fillField(\LoginManagerJoomla2Page::$userName, $config['username']);
		$I->fillField(\LoginManagerJoomla2Page::$password, $config['password']);
		$I->click('Log in');
		$I->see('Category Manager', \LoginManagerJoomla2Page::$loginSuccessCheck);
	}
}
