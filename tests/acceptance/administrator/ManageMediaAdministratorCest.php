<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class ManageMediaAdministratorCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class ManageMediaAdministratorCest
{
	/**
	 * Function to Test Media Creation in Backend
	 *
	 */

    public function _before(AcceptanceTester $I)
    {
        $I->doAdministratorLogin();
    }

	public function createMedia(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Media creation in Administrator');
		$I = new AcceptanceTester\MediaManagerJoomla3Steps($scenario);
		$I->addMedia();
	}
}
