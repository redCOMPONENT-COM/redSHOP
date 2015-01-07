<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace AcceptanceTester;
/**
 * Class DiscountManagerJoomla2Steps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class DiscountManagerJoomla2Steps extends AdminManagerJoomla2Steps
{
	/**
	 * Function to Add a New Discount
	 *
	 * @param   string  $amount          Discount Amount
	 * @param   string  $discountAmount  Amount on the Discount
	 * @param   string  $shopperGroup    Group for the Shopper
	 * @param   string  $discountType    Type of Discount
	 *
	 * @return void
	 */
	public function addDiscount($amount = '100', $discountAmount = '100', $shopperGroup = 'Default Private', $discountType = 'Total')
	{
		$I = $this;
		$I->amOnPage(\DiscountManagerPage::$URL);
		$I->verifyNotices(false, $this->checkForNotices(), 'Discount Manager Page');
		$I->click('New');
		$I->verifyNotices(false, $this->checkForNotices(), 'Discount Manager New');
		$I->click('Cancel');
	}
}
