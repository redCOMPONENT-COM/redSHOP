<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace AcceptanceTester;
/**
 * Class QuotationManagerJoomla2Steps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class QuotationManagerJoomla2Steps extends AdminManagerJoomla2Steps
{
	/**
	 * Function to add a New Quotation
	 *
	 * @return void
	 */
	public function addQuotation()
	{
		$I = $this;
		$I->amOnPage(\QuotationManagerPage::$URL);
		$I->verifyNotices(false, $this->checkForNotices(), 'Quotation Manager Page');
		$I->click('New');
		$I->verifyNotices(false, $this->checkForNotices(), 'Quotation Manager New');
		$I->click('Cancel');
	}
}
