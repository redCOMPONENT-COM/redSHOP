<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;
/**
 * Class InstallredSHOP1Steps
 *
 * @package  AcceptanceTester
 *
 * @since    1.4
 */
class InstallredSHOP1Steps extends \AcceptanceTester
{
	// Include url of current page
	public static $URL = '/administrator/index.php?option=com_installer';

	/**
	 * Basic route example for your current URL
	 * You can append any additional parameter to URL
	 * and use it in tests like: EditPage::route('/123-post');
	 *
	 * @return  void
	 */
	public static function route($param = "")
	{
		return static::$URL . $param;
	}

	/**
	 * Function to Install RedShop1, inside Joomla 2.5
	 *
	 * @param   string  $sampleData  Sample Data Needs to be INstalled
	 *
	 * @return void
	 */
	public function installredShop1($sampleData = 'Sample Data')
	{
		$I = $this;
		$this->acceptanceTester = $I;
		$I->amOnPage($this->route());
		$config = $I->getConfig();
		$I->fillField("#install_directory", $config['folder']);
		$I->click("//input[contains(@onclick,'Joomla.submitbutton3()')]");
		$I->waitForElement("//li[contains(text(),'Installing component was successful')]", 60);


		if ($sampleData == 'Sample Data')
		{
			$I->click("//input[@onclick=\"submitWizard('content');\" and @value='install Demo Content']");
			$I->waitForElement("//li[contains(text(),'Sample Data Installed Successfully')]", 30);
		}
	}
}
