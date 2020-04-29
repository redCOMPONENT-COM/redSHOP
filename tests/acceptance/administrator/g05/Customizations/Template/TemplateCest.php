<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
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
 * @since    1.4.0
 */
class TemplateCest
{
	/**
	 * @var  string
	 * @since 1.4.0
	 */
	public $faker;

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public $name = '';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public $section = '';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public $newName = '';

	/**
	 * TemplateCest constructor.
	 * @since 1.4.0
	 */
	public function __construct()
	{
		$this->faker = Faker\Factory::create();
		$this->name = $this->faker->bothify('managetemplateadministratorcest_?##?');
		$this->section = 'Add to cart';
		$this->newName = 'updated' . $this->name;
	}

	/**
	 * @param AcceptanceTester $I
	 * @throws Exception
	 * @since 1.4.0
	 */
	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * Function to Test Template Creation in Backend
	 *
	 * @param   AcceptanceTester  $I         Current user state.
	 * @param   Scenario          $scenario  Scenario for test.
	 *
	 * @return  void
	 * @since 1.4.0
	 */
	public function createTemplate(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Template creation in Administrator');
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
	 * @since 1.4.0
	 */
	public function updateTemplate(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test if Template gets updated in Administrator');
		$I = new TemplateSteps($scenario);
		$I->editTemplate($this->name, $this->newName);
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
	 * @since 1.4.0
	 */
	public function changeTemplateStateUnpublish(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test if update status to unpublish of Template gets Updated in Administrator');
		$I = new TemplateSteps($scenario);
		$I->changeTemplateState($this->newName);
		$I->verifyState('unpublished', $I->getTemplateState($this->newName));
	}

	/**
	 * Test for State Change in Template Administrator
	 *
	 * @param   AcceptanceTester  $I         Current user state.
	 * @param   Scenario          $scenario  Scenario for test.
	 *
	 * @return  void
	 *
	 * @depends changeTemplateStateUnpublish
	 * @since 1.4.0
	 */
	public function changeTemplateStatePublish(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test change publish Template gets Updated in Administrator');
		$I = new TemplateSteps($scenario);
		$I->changeTemplateState($this->newName);
		$I->verifyState('published', $I->getTemplateState($this->newName));
	}

	/**
	 * Function to Test Template Deletion
	 *
	 * @param   AcceptanceTester  $I         Current user state.
	 * @param   Scenario          $scenario  Scenario for test.
	 *
	 * @return  void
	 *
	 * @depends changeTemplateStatePublish
	 * @since 1.4.0
	 */
	public function deleteTemplate(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Deletion of Template in Administrator');
		$I = new TemplateSteps($scenario);
		$I->deleteTemplate($this->newName);
	}
}
