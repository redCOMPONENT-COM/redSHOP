<?php

use Step\AbstractStep;

/**
 * Class Field_Group Steps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    2.1.0
 */
class Field_GroupSteps extends AbstractStep
{
	use Step\Traits\CheckIn, Step\Traits\Publish, Step\Traits\Delete;

	/**
	 * Create field group with missing name
	 *
	 * @return  void
	 * @throws Exception
	 */
	public function missingName()
	{
		$test = $this;
		$test->amOnPage(Field_GroupPage::$url);
		$test->see(Field_GroupPage::$namePage);
		$test->click(Field_GroupPage::$buttonNew);
		$test->waitForElement(Field_GroupPage::$descriptionField, 30);
		$test->fillField(Field_GroupPage::$descriptionField, 'Checking');
		$test->click(Field_GroupPage::$buttonSave);
		$test->see(Field_GroupPage::$missingName);
	}
}