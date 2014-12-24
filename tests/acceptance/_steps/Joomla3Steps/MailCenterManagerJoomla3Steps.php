<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace AcceptanceTester;
/**
 * Class MailCenterManagerJoomla3Steps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class MailCenterManagerJoomla3Steps extends AdminManagerJoomla3Steps
{
	/**
	 * Function to add a new Mail to the Mail Center
	 *
	 * @return void
	 */
	public function addMail()
	{
		$I = $this;
		$I->amOnPage(\MailCenterManagerPage::$URL);
		$I->verifyNotices(false, $this->checkForNotices(), 'Mail Center Manager Page');
		$I->click('New');
		$I->verifyNotices(false, $this->checkForNotices(), 'Mail Center Manager New');
		$I->click('Cancel');
	}
}
