<?php
/**
 * @package     RedSHOP
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;
/**
 * Class InstallJoomla2DatabaseSteps
 *
 * @package  AcceptanceTester
 *
 * @since    1.4
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 */
class InstallJoomla2DatabaseSteps extends InstallJoomla2Steps
{
	/**
	 * Function to setUp Database Connection Details
	 *
	 * @return void
	 */
	public function setupDatabaseConnection()
	{
		$I = $this;
		$cfg = $I->getConfig();
		$this->setDatabaseType($cfg['db_type']);
		$this->setField('Host Name', $cfg['db_host']);
		$this->setField('Username', $cfg['db_user']);
		$this->setField('Password', $cfg['db_pass']);
		$this->setField('Database Name', $cfg['db_name']);
		$this->setField('Table Prefix', $cfg['db_prefix']);
		$this->checkOption(\InstallJoomla2ManagerPage::$removeOldDatabase);

		$I->click('Next');

		$I->waitForText(\InstallJoomla2ManagerPage::$ftpConfigurationPage);


		$I->click('Next');

		$I->waitForText(\InstallJoomla2ManagerPage::$mainConfigurationPage);
	}
}
