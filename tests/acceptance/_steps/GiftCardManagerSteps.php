<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace AcceptanceTester;
/**
 * Class GiftCardManagerSteps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class GiftCardManagerSteps extends AdminManagerSteps
{
	/**
	 * Function to add a New Gift Card
	 *
	 * @return void
	 */
	public function addCard()
	{
		$I = $this;
		$I->amOnPage(\GiftCardManagerPage::$URL);
		$I->verifyNotices(false, $this->checkForNotices(), 'Gift Card Manager Page');
		$I->click('New');
		$I->verifyNotices(false, $this->checkForNotices(), 'Gift Card Manager New');
		$I->click('Cancel');
	}
}
