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

		$this->fieldType = array(
			"Check box",
			"Country selection box");
	}

	/**
	 * Function to Test Custom Field CRUD in Backend
	 *
	 */
	public function testCustomFields(AcceptanceTester $I, $scenario)
	{
		//$scenario->skip('@fixme: skiping test due to bug REDSHOP-2864');
		$I->wantTo('Test Custom Field CRUD in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\CustomFieldManagerJoomla3Steps($scenario);

		foreach ($this->fieldType as $type)
		{
			
			$I->wantTo("Test $type");
			//$this->faker = Faker\Factory::create();
			$name = (string) $this->name = 'Custom' .$this->faker->numberBetween(100, 1000);
		    $title = (string) $this->title = $type .$this->faker->numberBetween(100, 1000);
			$optionValue =  (string) $this->faker->numberBetween(100, 1000);
			$section = 'Category';
			$newTitle = 'Updated ' . $title;
			$I->addField($name, $title, $type, $section, $optionValue);
			$I->filterListBySearching($title);
			$I->seeElement(['link' => $title]);
			$I->editField($title, $newTitle);
			$I->filterListBySearching($newTitle);
			$I->seeElement(['link' => $newTitle]);
			$I->changeFieldState($newTitle);
			//$I->verifyState('unpublished', $I->getFieldState($newTitle));
			$I->deleteCustomField($newTitle);
			
		}
	}
}
