<?php
/**
 * @package     redSHOP
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;
/**
 * Class InstallJoomla3FinalisationSteps
 *
 * @package  AcceptanceTester
 *
 * @since    1.4
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 */
class InstallJoomla3FinalisationSteps extends InstallJoomla3Steps
{
	/**
	 * Function to Finalize the Joomla3 Installation along with Sample Data
	 *
	 * @return void
	 */
	public function setupSampleData()
	{
		$I = $this;
		$cfg = $I->getConfig();

		if (strtolower($cfg['sample_data']) == "yes")
		{
			$this->setSampleData($cfg['sample_data_file']);
		}
		else
		{
			$this->setSampleData('None');
		}

		$I->click('Install');
		$I->waitForElement(\InstallJoomla3ManagerPage::$removeInstallationFolder, 60);
	}
}
