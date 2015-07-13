<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace AcceptanceTester;
/**
 * Class ProductManagerJoomla3Steps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class ProductManagerJoomla3Steps extends AdminManagerJoomla3Steps
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
