<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\TemplateSteps;
use Codeception\Scenario;

/**
 * Class TemplateCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class TemplateCest
{
	/**
	 * @var  string
	 */
	public $faker;

	/**
	 * @var string
	 */
	public $name = '';

	/**
	 * @var string
	 */
	public $section = '';

	/**
	 * @var string
	 */
	public $newName = '';

	/**
	 * TemplateCest constructor.
	 */
	public function __construct()
	{
		$this->faker = Faker\Factory::create();
		$this->name = $this->faker->bothify('managetemplateadministratorcest_?##?');
		$this->section = 'Add to cart';
		$this->newName = 'updated' . $this->name;
	}

	/**
	 * Function to Test Template Creation in Backend
	 *
	 * @param   AcceptanceTester  $I         Current user state.
	 * @param   Scenario          $scenario  Scenario for test.
	 *
	 * @return  void
	 */
	public function createTemplate(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Template creation in Administrator');
		$I->doAdministratorLogin();
		$I = new TemplateSteps($scenario);
		$I->addTemplate($this->name, $this->section);
	}

	/**
	 * Function to Test Template Update in the Administrator
	 *
	 * @param   AcceptanceTester  $I         Current user state.
	 * @param   Scenario          $scenario  Scenario for test.
	 *
	 * @return  void
	 *
	 * @depends createTemplate
	 */
	public function updateTemplate(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test if Template gets updated in Administrator');
		$I->doAdministratorLogin();
		$I = new TemplateSteps($scenario);
		$I->editTemplate($this->name, $this->newName);
		$I->searchTemplate($this->newName);
	}

	/**
	 * Test for State Change in Template Administrator
	 *
	 * @param   AcceptanceTester  $I         Current user state.
	 * @param   Scenario          $scenario  Scenario for test.
	 *
	 * @return  void
	 *
	 * @depends updateTemplate
	 */
	public function changeTemplateState(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test if State of a Template gets Updated in Administrator');
		$I->doAdministratorLogin();
		$I = new TemplateSteps($scenario);
		$I->changeTemplateState($this->newName);
		$I->verifyState('unpublished', $I->getTemplateState($this->newName));
	}

	/**
	 * Function to Test Template Deletion
	 *
	 * @param   AcceptanceTester  $I         Current user state.
	 * @param   Scenario          $scenario  Scenario for test.
	 *
	 * @return  void
	 *
	 * @depends changeTemplateState
	 */
	public function deleteTemplate(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Deletion of Template in Administrator');
		$I->doAdministratorLogin();
		$I = new TemplateSteps($scenario);
		$I->deleteTemplate($this->newName);
	}
}
