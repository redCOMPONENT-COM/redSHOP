<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class ManageCustomFieldAdministratorCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class ManageCustomFieldAdministratorCest
{
	public function __construct()
	{
		$this->faker = Faker\Factory::create();

		$this->fieldType = [
			"Check box",
			"Country selection box",
			"Date picker",
			"Documents",
			"Image",
			"Image with link",
			"Multiple select box",
			"Radio buttons",
			"Selection Based On Selected Conditions",
			"Single Select",
			"Text Tag Content",
			"Text area",
			"WYSIWYG"
		];
	}

	/**
	 * Function to Test Custom Field CRUD in Backend
	 */
	public function testCustomFields(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Custom Field CRUD in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\CustomFieldManagerJoomla3Steps($scenario);

		foreach ($this->fieldType as $type)
		{
			$I->wantTo("Test $type");
			$name = (string) $this->faker->bothify('ManageCustomFieldAdministratorCest ?##?');
			$title = (string) $this->faker->bothify("ManageCustomFieldAdministratorCest $type ?##?");
			$optionValue = (string) $this->faker->numberBetween(100, 1000);
			$section = 'Category';
			$newTitle = 'Updated ' . $title;
			$I->addField($name, $title, $type, $section, $optionValue);

			// @todo: the following order should be placed in a search function able to search an element in a list. See REDSHOP-2809
			$I->comment('I Look for the created field');
			$I->waitForElement(['id' => 'filter'], 60);
			$I->fillField(['id' => 'filter'], $title);
			$I->pressKey(['id' => 'filter'], WebDriverKeys::ENTER);
			$I->waitForElement(['link' => $title],60);
			// the following function does not use strict locator due to a bug in Codeception 2.0, see https://github.com/Codeception/Codeception/issues/2839
			$I->see($title, ['css' => 'a']);
			$I->wait(1);

			$I->editField($title, $newTitle);
			$I->waitForElement(['id' => 'redSHOPAdminContainer'], 60);
			$I->click(['xpath' => "//button[contains(normalize-space(), 'Reset')]"]);
			$I->wait(2);
			$I->fillField(['xpath' => "//input[@id='filter']"], $newTitle);
			$I->pressKey(['id' => 'filter'], WebDriverKeys::ENTER);
			$I->waitForElement(['link' => $newTitle],60);
			$I->dontSee($title, ['css' => 'a']);
			$I->see($newTitle, ['css' => 'a']);

			$I->changeFieldState($newTitle);
			$I->verifyState('unpublished', $I->getFieldState($newTitle));
			$I->deleteCustomField($newTitle);
			$I->searchField($newTitle, 'Delete');
		}
	}
}
