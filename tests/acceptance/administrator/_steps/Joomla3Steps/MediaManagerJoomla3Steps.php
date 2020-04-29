<?php
/**
 * @package     redSHOP
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;

use MediaManagerPage;

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
     * @since 1.4.0
	 */
	public function addMedia()
	{
		$I = $this;
		$I->amOnPage(MediaManagerPage::$URL);
		$I->verifyNotices(false, $this->checkForNotices(), 'Media Manager Page');
		$I->click(MediaManagerPage::$buttonNew);
		$I->verifyNotices(false, $this->checkForNotices(), 'Media Manager New');
		$I->click(MediaManagerPage::$buttonCancel);
	}
}
