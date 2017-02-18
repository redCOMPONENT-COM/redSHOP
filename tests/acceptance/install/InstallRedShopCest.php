<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class InstallRedShopCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class InstallRedShopCest
{
	/**
	 * Test to Install Joomla
	 *
	 * @param   AcceptanceTester  $I  Actor Class Object
	 *
	 * @return void
	 */
	public function testInstallJoomla(AcceptanceTester $I)
	{
		$I->wantTo('Execute Joomla Installation');
		$I->installJoomlaRemovingInstallationFolder();
		$I->doAdministratorLogin();
		$I->setErrorReportingtoDevelopment();
	}

	public function disableTemplateFloatingToolbars(AcceptanceTester $I)
	{
		$I->am('administrator');
		$I->wantTo('disable the floating template toolbars');
		$I->doAdministratorLogin();
		$I->waitForText('Control Panel', 60, ['css' => 'h1']);
		$I->click(['link' => 'Extensions']);
		$I->waitForElement(['link' => 'Templates'],60);
		$I->click(['link' => 'Templates']);
		$I->waitForText('Templates: Styles', 60, ['css' => 'h1']);
		$I->selectOptionInChosen('#client_id', 'Administrator');
		$I->waitForText('Templates: Styles (Administrator)', 60, ['css' => 'h1']);
		$I->click(['link' => 'isis - Default']);
		$I->waitForText('Templates: Edit Style', 60, ['css' => 'h1']);
		$I->click(['link' => 'Advanced']);
		$I->waitForElement(['css' => "label[data-original-title='Status Module Position']"], 60);
		$I->executeJS("window.scrollTo(0, document.body.scrollHeight);");
		$I->selectOptionInChosen('Status Module Position', 'Top');
		$I->selectOptionInRadioField('Pinned Toolbar', 'No');
		$I->click('Save & Close');
		$I->waitForText('Style successfully saved.', 60, ['id' => 'system-message-container']);
		$I->see('Style successfully saved.', ['id' => 'system-message-container']);
	}

	/**
	 * Test to Install redSHOP Extension on Joomla
	 *
	 * @param   AcceptanceTester  $I  Actor Class Object
	 *
	 * @return void
	 */
	public function testInstallRedShopExtension(AcceptanceTester $I)
	{
		$I->wantTo('Install Extension');
		$I->doAdministratorLogin();
		$I->disableStatistics();
		$I->wantTo('Install redSHOP');
		// $I->installExtensionFromFolder($I->getConfig('repo folder'));
		$I->amOnPage('/administrator/index.php?option=com_installer');
		$I->waitForText('Extensions: Install', '30', ['css' => 'H1']);
		$I->click(['link' => 'Install from Folder']);
		$I->debug('I enter the Path');
		$I->fillField(['id' => 'install_directory'], $I->getConfig('repo folder'));
		$I->click(['id' => 'installbutton_directory']);
		$I->waitForText('was successful', '120', ['id' => 'system-message-container']);
		$I->debug("redSHOP successfully installed from " . $I->getConfig('repo folder'));

		if ($I->getConfig('install demo data') == 'Yes')
		{
			$I->click(['id' => 'btn-demo-content']);
			$I->waitForText('Data Installed Successfully', 10, '#system-message-container');
		}
	}
}
