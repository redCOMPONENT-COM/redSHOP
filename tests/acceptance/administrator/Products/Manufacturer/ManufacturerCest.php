<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Cest\AbstractCest;
use Codeception\Scenario;

/**
 * Class ManufacturerCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class ManufacturerCest extends AbstractCest
{
	use Cest\Traits\CheckIn, Cest\Traits\Publish, Cest\Traits\Delete;

	/**
	 * Name field, which is use for search
	 *
	 * @var string
	 */
	public $nameField = 'name';

	/**
	 * Method for set new data.
	 *
	 * @return  array
	 */
	protected function prepareNewData()
	{
		return array(
			'name'             => $this->faker->bothify('Testing Currency ?##?'),
			'email'            => $this->faker->email,
			'template_id'      => 'manufacturer_products',
			'product_per_page' => 12
		);
	}

    /**
     * Abstract method for run after complete create item.
     *
     * @param   \AcceptanceTester     $tester    Tester
     * @param   Codeception\Scenario  $scenario  Scenario
     *
     * @return  void
     *
     * @depends testItemCreate
     */
    public function deleteDataSave(\AcceptanceTester $tester, Codeception\Scenario $scenario)
    {
        $tester->wantTo('Run after create item with save button ');
        $stepClass = $this->stepClass;

        /** @var ManufacturerSteps $tester */
        $tester = new $stepClass($scenario);
        $tester->deleteItem('New ' . $this->dataNew['name']);

    }

    /**
     * Abstract method for run after complete create item.
     *
     * @param   \AcceptanceTester     $tester    Tester
     * @param   Codeception\Scenario  $scenario  Scenario
     *
     * @return  void
     *
     * @depends testItemCreateSaveClose
     */
    public function deleteDataSaveClose(\AcceptanceTester $tester, Codeception\Scenario $scenario)
    {
        $tester->wantTo('Run after create item with save button ');
        $stepClass = $this->stepClass;

        /** @var ManufacturerSteps $tester */
        $tester = new $stepClass($scenario);
        $tester->deleteItem('New ' . $this->dataNew['name']);

    }
	/**
	 * Method for set new data.
	 *
	 * @return  array
	 */
	protected function prepareEditData()
	{
		return array(
			'name'             => 'New ' . $this->dataNew['name'],
			'email'            => $this->faker->email,
			'template_id'      => 'manufacturer_products',
			'product_per_page' => 12
		);
	}

	/**
	 * Method check cancel button at detail page
	 *
	 * @param   AcceptanceTester $client   Tester
	 * @param   Scenario         $scenario Scenario
	 *
	 * @return  void
	 */
	public function checkCancelButton(AcceptanceTester $client, Scenario $scenario)
	{
		$client->wantTo('Test manufacture check Close button in Administrator');
		$stepClass = $this->stepClass;

		/** @var ManufacturerSteps $step */
		$step = new $stepClass($scenario);
		$step->checkCancelButton();
	}

	/**
	 * Function add manufacturer when missing name
	 *
	 * @param   AcceptanceTester $tester   Tester
	 * @param   Scenario         $scenario Scenario
	 *
	 * @return  void
	 */
	public function badCaseMissingName(AcceptanceTester $tester, Scenario $scenario)
	{
		$tester->wantTo('Test Manufacturer Missing Name creation in Administrator');
		$stepClass = $this->stepClass;

		/** @var ManufacturerSteps $step */
		$step = new $stepClass($scenario);
		$step->addManufacturerMissingName();
	}

	/**
	 * Function add manufacturer when email wrong format
	 *
	 * @param   AcceptanceTester $tester   Tester
	 * @param   Scenario         $scenario Scenario
	 *
	 * @return  void
	 */
	public function badCaseWrongEmailFormat(AcceptanceTester $tester, Scenario $scenario)
	{
		$tester->wantTo('Test Manufacturer Missing Name creation in Administrator');
		$stepClass = $this->stepClass;

		/** @var ManufacturerSteps $step */
		$step = new $stepClass($scenario);
		$step->addManufacturerWrongEmail($this->dataNew['name'], 'email_in_wrong_format');
	}
}
