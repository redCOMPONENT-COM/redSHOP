<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace AcceptanceTester;
/**
 * Class MediaManagerJoomla2Steps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class MediaManagerJoomla2Steps extends AdminManagerJoomla2Steps
{
	/**
	 * Function to add a new Media File
	 *
	 * @return void
	 */
	public function addMedia()
	{
		$I = $this;
		$I->amOnPage(\MediaManagerPage::$URL);
		$I->verifyNotices(false, $this->checkForNotices(), 'Media Manager Page');
		$I->click('New');
		$I->verifyNotices(false, $this->checkForNotices(), 'Media Manager New');
		$I->click('Cancel');
	}
}
