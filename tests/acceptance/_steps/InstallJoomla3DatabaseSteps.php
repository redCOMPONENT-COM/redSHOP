<?php
/**
 * @package     redSHOP
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;
/**
 * Class InstallJoomla3DatabaseSteps
 *
 * @package  AcceptanceTester
 *
 * @since    1.4
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 */
class InstallJoomla3DatabaseSteps extends InstallJoomla3Steps
{
	/**
	 * Function to setup database connection on Joomla3
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
		$this->click(\InstallJoomla3ManagerPage::$removeOldDatabase);

		$I->click('Next');

		$I->waitForText(\InstallJoomla3ManagerPage::$finalisationPage);
	}
}
