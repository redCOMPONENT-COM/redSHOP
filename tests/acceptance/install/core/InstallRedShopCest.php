<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\AdminManagerJoomla3Steps as AdminManagerJoomla3Steps;
use Administrator\System\SystemSteps;

/**
 * Class InstallRedShopCest
 * @since 1.4.0
 */
class InstallRedShopCest
{
	/**
	 * Test to Install Joomla
	 *
	 * @param   AcceptanceTester  $I  Actor Class Object
	 *
	 * @return void
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function testInstallJoomlaAndRedSHOP(AdminManagerJoomla3Steps $I, $scenario)
	{
		$I->wantTo('Execute Joomla Installation');
		$I->installJoomlaRemovingInstallationFolder();
		$I->doAdministratorLogin(null, null, false);
		$I->installRedShopExtension();

		$I = new SystemSteps($scenario);
		$I->wantTo('Execute Joomla Installation');
		$I->disableSEOSettings();
	}
}
