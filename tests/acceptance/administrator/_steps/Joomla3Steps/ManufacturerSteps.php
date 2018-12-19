<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Step\AbstractStep;

/**
 * Class ManufacturerSteps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class ManufacturerSteps extends AbstractStep
{
	use Step\Traits\CheckIn, Step\Traits\Delete, Step\Traits\Publish;

	/**
	 * @return void
	 */
	public function checkCancelButton()
	{
		$client = $this;
		$client->amOnPage(\ManufacturerPage::$url);
		$client->click(\ManufacturerPage::$buttonNew);
		$client->waitForElement(\ManufacturerPage::$fieldName, 30);
		$client->click(\ManufacturerPage::$buttonCancel);
	}

	/**
	 * Bad case: Missing name
	 *
	 * @return  void
	 */
	public function addManufacturerMissingName()
	{
		$client = $this;
		$client->amOnPage(\ManufacturerPage::$url);
		$client->click(\ManufacturerPage::$buttonNew);
		$client->waitForElement(\ManufacturerPage::$fieldName, 30);
		$client->fillField(\ManufacturerPage::$fieldName, '');
		$client->click(\ManufacturerPage::$buttonSave);
		$client->waitForText(\ManufacturerPage::$fieldMissing, 60, \ManufacturerPage::$selectorMissing);
		$client->waitForElement(\ManufacturerPage::$fieldName, 30);
		$client->click(\ManufacturerPage::$buttonCancel);
	}

	/**
	 * Bad case: Wrong email format
	 *
	 * @param   string $name  Name
	 * @param   string $email Email
	 *
	 * @return void
	 */
	public function addManufacturerWrongEmail($name = '', $email = '')
	{
		$client = $this;
		$client->amOnPage(\ManufacturerPage::$url);
		$client->click(\ManufacturerPage::$buttonNew);
		$client->waitForElement(\ManufacturerPage::$fieldName, 30);
		$client->fillField(\ManufacturerPage::$fieldName, $name);
		$client->fillField(\ManufacturerPage::$fieldEmail, $email);
		$client->click(\ManufacturerPage::$buttonSave);
		$client->waitForText(\ManufacturerPage::$fieldEmailInvalid, 60, \ManufacturerPage::$selectorMissing);
		$client->waitForElement(\ManufacturerPage::$fieldName, 30);
		$client->click(\ManufacturerPage::$buttonCancel);
	}
}
