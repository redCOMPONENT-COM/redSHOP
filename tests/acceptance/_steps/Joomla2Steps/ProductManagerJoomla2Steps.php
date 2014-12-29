<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace AcceptanceTester;
/**
 * Class ProductManagerJoomla2Steps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class ProductManagerJoomla2Steps extends AdminManagerJoomla2Steps
{
	/**
	 * Function to add a Product
	 *
	 * @return void
	 */
	public function addProduct()
	{
		$I = $this;
		$I->amOnPage(\ProductManagerPage::$URL);
		$I->verifyNotices(false, $this->checkForNotices(), 'Product Manager Page');
		$I->click('New');
		$I->verifyNotices(false, $this->checkForNotices(), 'Product Manager New');
		$I->click('Cancel');
	}
}
