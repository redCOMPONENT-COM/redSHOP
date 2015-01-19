<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace AcceptanceTester;
/**
 * Class OrderManagerJoomla2Steps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class OrderManagerJoomla2Steps extends AdminManagerJoomla2Steps
{
	/**
	 * Function to Add a new Order
	 *
	 * @return void
	 */
	public function addOrder()
	{
		$I = $this;
		$I->amOnPage(\OrderManagerPage::$URL);
		$I->verifyNotices(false, $this->checkForNotices(), 'Order Manager Page');
		$I->click('New');
		$I->verifyNotices(false, $this->checkForNotices(), 'Order Manager New');
		$I->click('Cancel');
	}
}
