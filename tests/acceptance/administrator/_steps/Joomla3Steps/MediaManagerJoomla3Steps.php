<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace AcceptanceTester;
/**
 * Class MediaManagerJoomla3Steps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class MediaManagerJoomla3Steps extends AdminManagerJoomla3Steps
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
		$I->click(\MediaManagerPage::$newButton);
//		$I->verifyNotices(false, $this->checkForNotices(), 'Media Manager New');
        $I->click(\MediaManagerPage::$cancelButton);
	}
}
