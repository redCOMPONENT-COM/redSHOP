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
		$this->acceptanceTester = $I;
		$I->amOnPage(\FrontEndLoginManagerJoomla3Page::$frontEndLoginURL);
		$config = $I->getConfig();
		$I->waitForElement(\FrontEndLoginManagerJoomla3Page::$frontEndUserName);
		$I->fillField(\FrontEndLoginManagerJoomla3Page::$frontEndUserName, $config['username']);
		$I->fillField(\FrontEndLoginManagerJoomla3Page::$frontEndPassword, $config['password']);
		$I->click(\FrontEndLoginManagerJoomla3Page::$frontEndLoginButton);
		$I->waitForElement(\FrontEndLoginManagerJoomla3Page::$frontEndLoginSuccess, 30);
		$I->seeElement(\FrontEndLoginManagerJoomla3Page::$frontEndLoginSuccess);
	}
}
