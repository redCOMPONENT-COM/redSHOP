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
		$this->installJoomlaRemovingInstallationFolder($I);
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
		$I->wantTo('Install extension');
		$I->doAdministratorLogin();
		$I->disableStatistics();
		$I->wantTo('Install redSHOP extension');
		$I->amOnPage('/administrator/index.php?option=com_installer');
		$I->waitForText('Extensions: Install', '30', ['css' => 'H1']);
		$I->click(['link' => 'Install from URL']);
		$I->fillField(['id' => 'install_url'], $I->getConfig('redshop packages url') . '/redshop.zip');
		$I->click(['id' => 'installbutton_url']);
		$I->waitForText('installed successfully', '120', ['id' => 'system-message-container']);

		if ($I->getConfig('install demo data') == 'Yes')
		{
			$I->click(['id' => 'btn-demo-content']);
			$I->waitForText('Data Installed Successfully', 10, '#system-message-container');
		}
	}

	/**
	 * Install Joomla removing the Installation folder at the end of the execution
	 *
	 * @return  void
	 */
	private function installJoomlaRemovingInstallationFolder(AcceptanceTester $I)
	{
		
		$this->installJoomla($I);

		$I->click(['xpath' => "//input[@value='Remove installation folder']"]);

		$I->waitForJS("return jQuery('form#adminForm input[name=instDefault]').attr('disabled') == 'disabled';", 60);

		$I->see('Congratulations! Joomla! is now installed.', ['xpath' => '//h3']);
	}

	/**
	 * Installs Joomla
	 *
	 * @return  void
	 */
	private function installJoomla(AcceptanceTester $I)
	{

		// Install Joomla CMS');

		$I->amOnPage('/installation/index.php');
		$I->dontSeeElement(['id' => 'ftp']);

		// I Wait for the text Main Configuration, meaning that the page is loaded
		$I->waitForElement('#jform_language', 10);
		$I->wait(2);
		
		// Select a random language to force reloading of the lang strings after selecting English
		$I->selectOptionInChosenWithTextField('#jform_language', 'Spanish (Español)');
		$I->waitForText('Configuración principal', 60, 'h3');

		// Wait for chosen to render the field
		$I->wait(2);
		$I->selectOptionInChosenWithTextField('#jform_language', 'English (United Kingdom)');
		$I->waitForText('Main Configuration', 60, 'h3');
		$I->fillField(['id' => 'jform_site_name'], 'Joomla CMS test');
		$I->fillField(['id' => 'jform_site_metadesc'], 'Site for testing Joomla CMS');

		// I get the configuration from acceptance.suite.yml (see: tests/_support/acceptancehelper.php)
		$I->fillField(['id' => 'jform_admin_email'], $this->config['admin email']);
		$I->fillField(['id' => 'jform_admin_user'], $this->config['username']);
		$I->fillField(['id' => 'jform_admin_password'], $this->config['password']);
		$I->fillField(['id' => 'jform_admin_password2'], $this->config['password']);
		
		// ['No Site Offline']
		$I->click(['xpath' => "//fieldset[@id='jform_site_offline']/label[@for='jform_site_offline1']"]);
		$I->click(['link' => 'Next']);

		$I->waitForText('Database Configuration', 60, ['css' => 'h3']);

		$I->selectOption(['id' => 'jform_db_type'], $this->config['database type']);
		$I->fillField(['id' => 'jform_db_host'], $this->config['database host']);
		$I->fillField(['id' => 'jform_db_user'], $this->config['database user']);
		$I->fillField(['id' => 'jform_db_pass'], $this->config['database password']);
		$I->fillField(['id' => 'jform_db_name'], $this->config['database name']);
		$I->fillField(['id' => 'jform_db_prefix'], $this->config['database prefix']);
		$I->selectOptionInRadioField('Old Database Process', 'Remove');
		$I->click(['link' => 'Next']);
		$I->wait(1);
		$I->waitForElementVisible(['id' => 'jform_sample_file-lbl'], 30);

		$I->waitForText('Finalisation', 60, ['xpath' => '//h3']);

		// @todo: installation of sample data needs to be created

		// No sample data
		$I->selectOption(['id' => 'jform_sample_file'], ['id' => 'jform_sample_file0']);
		$I->click(['link' => 'Install']);

		// Wait while Joomla gets installed
		$I->waitForText('Congratulations! Joomla! is now installed.', 60, ['xpath' => '//h3']);
	}
}
