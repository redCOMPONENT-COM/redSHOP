<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class ManageQuotationAdministratorCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class ManageQuotationAdministratorCest
{
	/**
	 * Function to Test Quotation Creation in Backend
	 *
	 */

    public function _before(AcceptanceTester $I)
    {
        $I->doAdministratorLogin();
    }
	public function createQuotation(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Quotation creation in Administrator');
		$I = new AcceptanceTester\QuotationManagerJoomla3Steps($scenario);
		$I->addQuotation();
	}
}
