<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;
/**
 * Class LoginJoomla3Steps
 *
 * @package  AcceptanceTester
 *
 * @since    1.4
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 */
class LoginJoomla3Steps extends \AcceptanceTester
{
	/**
	 * Function to execute an Admin Login for Joomla3.x
	 *
	 * @return void
	 */
	public function doAdminLogin()
	{
		$I = $this;
		$this->acceptanceTester = $I;
		$I->amOnPage(\LoginManagerJoomla3Page::$URL);
		$config = $I->getConfig();
		$I->fillField(\LoginManagerJoomla3Page::$userName, $config['username']);
		$I->fillField(\LoginManagerJoomla3Page::$password, $config['password']);
		$I->click('Log in');
		$I->see('Category Manager', \LoginManagerJoomla3Page::$loginSuccessCheck);
	}
}
