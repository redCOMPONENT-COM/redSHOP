<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Cest\AbstractCest;
use AcceptanceTester\ProductManagerJoomla3Steps as ProductManagerSteps;

/**
 * Category cest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class CategoryCest extends AbstractCest
{
	use  Cest\Traits\CheckIn, Cest\Traits\Publish, Cest\Traits\Delete;

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
			'name'        => $this->faker->bothify('Category Name ?##?'),
			'type'        => 'Total',
			'value'       => '100',
			'effect'      => 'Global',
			'amount_left' => '10'
		);
	}

	/**
	 * @return array
	 */
	protected function prepareEditData()
	{
		return array(
			'name'        => 'New' . $this->dataNew['name'],
			'type'        => 'Total',
			'value'       => '100',
			'effect'      => 'Global',
			'amount_left' => '10'
		);
	}

	/**
	 * Clean up data.
	 *
	 * @param   \Codeception\Scenario  $scenario  Scenario
	 *
	 * @return  void
	 */
	public function deleteData($scenario)
	{
		$tester = new RedshopSteps;
		$tester->clearAllData();
	}

	/**
	 * Abstract method for run after complete create item.
	 *
	 * @param   \AcceptanceTester      $tester    Tester
	 * @param   \Codeception\Scenario  $scenario  Scenario
	 *
	 * @return  void
	 *
	 * @depends testItemCreate
	 */
	public function deleteDataSave(\AcceptanceTester $tester, \Codeception\Scenario $scenario)
	{
		$tester->wantTo(__METHOD__);
		$stepClass = $this->stepClass;

		/** @var CategorySteps $tester */
		$tester = new $stepClass($scenario);
		$tester->deleteItem('New' . $this->dataNew['name']);

	}

	/**
	 * Abstract method for run after complete create item.
	 *
	 * @param   \AcceptanceTester      $tester    Tester
	 * @param   \Codeception\Scenario  $scenario  Scenario
	 *
	 * @return  void
	 *
	 * @depends testItemCreateSaveClose
	 */
	public function deleteDataSaveClose(\AcceptanceTester $tester, \Codeception\Scenario $scenario)
	{
		$tester->wantTo(__METHOD__);
		$stepClass = $this->stepClass;

		/** @var CategorySteps $tester */
		$tester = new $stepClass($scenario);
		$tester->deleteItem('New' . $this->dataNew['name']);

	}

	/**
	 * Abstract method for run after complete create item.
	 *
	 * @param   \AcceptanceTester      $tester    Tester
	 * @param   \Codeception\Scenario  $scenario  Scenario
	 *
	 * @return  void
	 * @throws  Exception
	 *
	 * @depends testItemCreateSaveNew
	 */
	public function afterTestItemCreate(\AcceptanceTester $tester, \Codeception\Scenario $scenario)
	{
		$tester->wantTo(__METHOD__);
		$stepClass = $this->stepClass;

		/** @var CategorySteps $tester */
		$tester            = new $stepClass($scenario);
		$nameCategoryChild = $this->faker->bothify('CategiryChild ?##? ');
		$productName       = $this->faker->bothify('ProductCategory ?##?');
		$productNameSecond = $this->faker->bothify('Product ?##?');
		$productNumber     = $this->faker->numberBetween(1, 10000);
		$price             = $this->faker->numberBetween(1, 100);

		$tester->addCategoryChild('New' . $this->dataNew['name'], $nameCategoryChild, 3);

		$tester = new ProductManagerSteps($scenario);
		$tester->createProductSaveClose($productName, 'New' . $this->dataNew['name'], $productNumber, $price);
		$tester->createProductSaveClose($productNameSecond, $nameCategoryChild, $productNameSecond, $price);

		$stepClass = $this->stepClass;

		/** @var CategorySteps $tester */
		$tester = new $stepClass($scenario);
		$tester->addCategoryAccessories($this->dataNew['name'], 4, $productNameSecond);

		$tester = new ProductManagerSteps($scenario);
		$tester->deleteProduct($productName);
		$tester->deleteProduct($productNameSecond);

		$stepClass = $this->stepClass;

		/** @var CategorySteps $tester */
		$tester = new $stepClass($scenario);
		$tester->deleteItem($nameCategoryChild);
	}
}
