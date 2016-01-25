<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
use \AcceptanceTester;
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

		$this->fieldType = array("Check box", "Country selection box", "Date picker", "Documents", "Image", "Image with link", "Multiple select box", "Radio buttons",
			"Selection Based On Selected Conditions", "Single Select", "Text Tag Content", "Text area", "WYSIWYG");
	}

	/**
	 * Function to Test Custom Field CRUD in Backend
	 *
	 */
	public function testCustomFields(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Custom Field CRUD in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\CustomFieldManagerJoomla3Steps($scenario);

		foreach ($this->fieldType as $type)
		{
			$I->wantTo("Test $type");
			$name = $this->faker->bothify('ManageCustomFieldAdministratorCest ?##?');
			$title = 'ManageCustomFieldAdministratorCest ' . $type . ' ' . $this->faker->numberBetween(9, 99);
			$optionValue = 'ManageCustomFieldAdministratorCest Options ' . $this->faker->numberBetween(100, 1000);
			$section = 'Category';
			$newTitle = 'Updated ' . $title;
			$I->addField($name, $title, $type, $section, $optionValue);
			$I->searchField($title);
			$I->editField($title, $newTitle);
			$I->searchField($newTitle);
			$I->changeFieldState($newTitle);
			$I->verifyState('unpublished', $I->getFieldState($newTitle));
			$I->deleteCustomField($newTitle);
			$I->searchField($newTitle, 'Delete');
		}
	}
}
