<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class ManageWrapperAdministratorCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class ManageWrapperAdministratorCest
{
	public function __construct()
	{
		$this->faker = Faker\Factory::create();
		$this->name = $this->faker->bothify('ManageWrapperAdministratorCest ?##?');
		$this->price = $this->faker->numberBetween(100, 1000);
		$this->category = 'Events and Forms';
		$this->newName = 'Updated ' . $this->name;
	}

	/**
	 * Function to Test Wrapper Creation in Backend
	 *
	 */
	public function createWrapper(\AcceptanceTester\AdminManagerJoomla3Steps $I, $scenario)
	{
		$I->wantTo('Test Wrapper creation in Administrator');
		$I->doAdministratorLogin();
		$I->amOnPage('/administrator/index.php?option=com_redshop&view=wrapper');
		$I->checkForPhpNoticesOrWarnings();
		$I->click('New');
		$I->waitForElement(['xpath' => "//input[@id='wrapper_name']"],60);
		$I->checkForPhpNoticesOrWarnings();
		$I->fillField(['xpath' => "//input[@id='wrapper_name']"], $this->name);
		$I->fillField(['xpath' => "//input[@id='wrapper_price']"], $this->price);
		$I->click('//*[@id="s2id_categoryid"]/ul');
		$I->click('//*[@id="select2-drop"]/ul/li[1]');
		$I->fillField(['id' => 's2id_autogen1'], 'Product 1');
		$I->waitForElement(['css' => 'span.select2-match'], 60);
		$I->click(['css' => 'span.select2-match']);
		$I->click('Save & Close');
		$I->waitForText("Wrapping detail saved", 60, ['id' => 'system-message-container']);
		$I->see("Wrapping detail saved", ['id' => 'system-message-container']);
		$I->filterListBySearching4($this->name);
		$I->seeElement(['link' => $this->name]);
	}

	/**
	 * Function to Test Wrapper Update in the Administrator
	 *
	 * @depends createWrapper
	 */
	public function updateWrapper(\AcceptanceTester\AdminManagerJoomla3Steps $I, $scenario)
	{
		$I->wantTo('Test if Wrapper gets updated in Administrator');
		$I->doAdministratorLogin();
		$I->amOnPage('/administrator/index.php?option=com_redshop&view=wrapper');
		$I->filterListBySearching4($this->name);
		$I->click(['link' => $this->name]);
		$I->waitForElement(['xpath' => "//input[@id='wrapper_name']"],60);
		$I->fillField(['xpath' => "//input[@id='wrapper_name']"], $this->newName);
		$I->click('Save & Close');
		$I->waitForText("Wrapping detail saved", 60, ['id' => 'system-message-container']);
		$I->filterListBySearching4($this->newName);
		$I->dontSeeElement(['link' => $this->name]);
		$I->seeElement(['link' => $this->newName]);
	}

	/**
	 * Test for State Change in Wrapper Administrator
	 *
	 * @depends updateWrapper
	 */
	public function changeWrapperState(\AcceptanceTester\AdminManagerJoomla3Steps $I, $scenario)
	{
		
		$I->doAdministratorLogin();
		$I->amOnPage('/administrator/index.php?option=com_redshop&view=wrapper');
		$I->checkForPhpNoticesOrWarnings();
		$I->filterListBySearching4($this->newName);
		$I->click('//tbody/tr[1]/td[7]/a/span');
		$I->waitForText('Wrapping unpublished successfully',60, ['id' => 'system-message-container']);
		$I->see('Wrapping unpublished successfully', ['id' => 'system-message-container']);
		$I->click('//tbody/tr[1]/td[7]/a/span');
		$I->waitForText('Wrapping published successfully',60, ['id' => 'system-message-container']);
		$I->see('Wrapping published successfully', ['id' => 'system-message-container']);
	}

	/**
	 * Function to Test Wrapper Deletion
	 *
	 * @depends changeWrapperState
	 */
	public function deleteWrapper(\AcceptanceTester\AdminManagerJoomla3Steps $I, $scenario)
	{
		$I->am('administrator');
		$I->wantTo('Deletion of Wrapper in Administrator');
		$I->doAdministratorLogin();
		$I->amOnPage('/administrator/index.php?option=com_redshop&view=wrapper');
		$I->checkForPhpNoticesOrWarnings();
		$I->filterListBySearching4($this->newName);
		$I->click('//tbody/tr[1]/td[2]/div');
		$I->click('Delete');
		$I->waitForText('Wrapping detail deleted successfully',60, ['id' => 'system-message-container']);
		$I->see('Wrapping detail deleted successfully', ['id' => 'system-message-container']);
		$I->dontSeeElement(['link' => $this->newName]);
	}
}
