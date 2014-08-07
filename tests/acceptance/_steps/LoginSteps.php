<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;
/**
 * Class LoginSteps
 *
 * @package  AcceptanceTester
 *
 * @since    1.4
 */
class LoginSteps extends \AcceptanceTester
{
	// Include url of current page
	public static $URL = '/administrator/index.php';

	/**
	 * @var AcceptanceTester;
	 */
	protected $acceptanceTester;

	/**
	 * Basic route example for your current URL
	 * You can append any additional parameter to URL
	 * and use it in tests like: EditPage::route('/123-post');
	 *
	 * @return  void
	 */
	public static function route($param = "")
	{
		return static::$URL . $param;
	}

	/**
	 * Function to execute an Admin Login for Joomla2.5
	 *
	 * @return void
	 */
	public function doAdminLogin()
	{
		$I = $this;
		$this->acceptanceTester = $I;
		$I->amOnPage($this->route());
		$config = $I->getConfig();
		$I->fillField('username', $config['username']);
		$I->fillField('passwd', $config['password']);
		$I->click('Log in');
		$I->see('Joomla');
	}
}
