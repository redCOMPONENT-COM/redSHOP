<?php
/**
 * @package     redSHOP
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;
/**
 * Class InstallJoomla2SiteConfigurationSteps
 *
 * @package  AcceptanceTester
 *
 * @since    1.4
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 */
class InstallJoomla2SiteConfigurationSteps extends InstallJoomla2Steps
{
	/**
	 * Function to Provide Site Configuration Updates
	 *
	 * @return void
	 */
	public function setupConfiguration()
	{
		$I = $this;
		$cfg = $I->getConfig();
		$this->setField('Site Name', $cfg['site_name']);
		$this->setField('Your Email', $cfg['admin_email']);
		$this->setField('Admin Username', $cfg['username']);
		$this->setField('Admin Password', $cfg['password']);
		$this->setField('Confirm Admin Password', $cfg['password']);

		if (strtolower($cfg['sample_data']) == "yes")
		{
			$this->setSampleData($cfg['sample_data_file']);
		}
		else
		{
			$this->setSampleData('Default English');
		}

		$I->click(\InstallJoomla2ManagerPage::$installSampleData);
		sleep(5);
		$I->click('Next');
		$I->waitForText(\InstallJoomla2ManagerPage::$successfulInstallation);
	}
}
