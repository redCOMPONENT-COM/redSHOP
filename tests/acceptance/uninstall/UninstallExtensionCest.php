<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\AdminManagerJoomla3Steps;

/**
 * Class UninstallExtensionCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class UninstallExtensionCest
{
	/**
	 * @param AdminManagerJoomla3Steps $I
	 * @throws Exception
	 * @since 1.4.0
	 */
	public function uninstallExtension(AdminManagerJoomla3Steps $I)
	{
		$I->wantTo('Uninstall redSHOP Extensions');
		$I->doAdministratorLogin();
		$I->uninstallRedSHOP();
	}
}
