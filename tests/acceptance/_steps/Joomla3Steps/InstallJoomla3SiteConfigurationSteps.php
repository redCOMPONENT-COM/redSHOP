<?php
/**
 * @package     redSHOP
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;
/**
 * Class InstallJoomla3SiteConfigurationSteps
 *
 * @package  AcceptanceTester
 *
 * @since    1.4
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 */

class InstallJoomla3SiteConfigurationSteps extends InstallJoomla3Steps
{
	/**
	 * Function to setup the Site Configuration Detail during Joomla3 Installation
	 *
	 * @return void
	 */
	public function setupConfiguration()
	{
		$I = $this;
		$cfg = $I->getConfig();
		$I->amOnPage(\InstallJoomla3ManagerPage::$URL);
		$I->waitForText(\InstallJoomla3ManagerPage::$mainConfigurationPage);
		$this->setField('Site Name', $cfg['site_name']);
		$this->setField('Your Email', $cfg['admin_email']);
		$this->setField('Admin Username', $cfg['username']);
		$this->setField('Admin Password', $cfg['password']);
		$this->setField('Confirm Admin Password', $cfg['password']);

		$I->click('Next');

		$I->waitForText(\InstallJoomla3ManagerPage::$databaseConfigurationPage);
	}
}
