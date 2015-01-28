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
		$I->amOnPage(\LoginManagerPage::$URL);
		$config = $I->getConfig();
		$I->fillField(\LoginManagerPage::$userName, $config['username']);
		$I->fillField(\LoginManagerPage::$password, $config['password']);
		$I->click('Log in');
		$I->see('Category Manager', \LoginManagerPage::$loginSuccessCheck);
	}
}
