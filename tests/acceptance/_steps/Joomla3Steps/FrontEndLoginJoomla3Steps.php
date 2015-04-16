<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;
/**
 * Class FrontEndLoginJoomla3Steps
 *
 * @package  AcceptanceTester
 *
 * @since    1.4
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 */
class FrontEndLoginJoomla3Steps extends \AcceptanceTester
{
	/**
	 * Function to execute an Frontend Login for Joomla3.x
	 *
	 * @return void
	 */
	public function doFrontEndLogin()
	{
		$I = $this;
		$frontEndLoginUrl = '/index.php?option=com_users&view=login';
		$I->amOnPage($frontEndLoginUrl);
		$config = $I->getConfig();
		$I->waitForText("Username", 10, '.control-label');
		$I->fillField("Username", $config['username']);
		$I->fillField("Password", $config['password']);
		$I->click('Log in');
		$I->waitForElement('#member-profile', 30);
		$I->seeElement('#member-profile');
	}
}
