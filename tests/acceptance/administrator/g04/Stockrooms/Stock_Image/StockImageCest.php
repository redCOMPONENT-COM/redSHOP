<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\StockImageSteps;
use AcceptanceTester\StockRoomManagerJoomla3Steps;
use Configuration\ConfigurationSteps;

/**
 * Class StockImageCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since 1.4.0
 */
class StockImageCest
{
    /**
     * @var \Faker\Generator
     * @since 1.4.0
     */
    protected $faker;

    /**
     * @var string
     * @since 1.4.0
     */
    protected $nameStockImage;

    /**
     * @var string
     * @since 1.4.0
     */
    protected $nameStockRoom;

    /**
     * @var string
     * @since 1.4.0
     */
    protected $newImageTooltip;

    /**
     * @var int
     * @since 1.4.0
     */
    protected $quantity;

    /**
     * @var int
     * @since 1.4.0
     */
    protected $quantityNew;

    /**
     * @var int
     * @since 1.4.0
     */
    protected $amountStockRoom;

    /**
     * @var string
     * @since 1.4.0
     */
    protected $amountStockImage;

    /**
     * StockImageCest constructor.
     * @since 1.4.0
     */
	public function __construct()
	{
		$this->faker = Faker\Factory::create();
		$this->nameStockImage = $this->faker->bothify('Stock Image ?##?');
		$this->nameStockRoom = $this->faker->bothify('Stock Room ?##?');
		$this->newImageTooltip = 'Updated ' . $this->nameStockImage;
		$this->quantity = $this->faker->numberBetween(1,100);
		$this->quantityNew = $this->faker->numberBetween(1,100);;
		$this->amountStockRoom = $this->faker->numberBetween(1,100);
		$this->amountStockImage = 'Higher than';
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
	 * @param AcceptanceTester $I
	 * @param                  $scenario
	 * @throws Exception
     * @since 1.4.0
	 */
	public function enableStock(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Enable StockRoom in Administrator page');
		$I = new ConfigurationSteps($scenario);
		$I -> featureUsedStockRoom();

		$I->wantTo('Test Stock Room creation in Administrator');
		$I = new StockRoomManagerJoomla3Steps($scenario);
		$I->addStockRoom($this->nameStockRoom, $this->amountStockRoom);

		$I->wantTo('Create Stock Image in Administrator page');
		$I = new StockImageSteps($scenario);
		$I->addStockImage($this->nameStockImage, $this->nameStockRoom, $this->amountStockImage, $this->quantity);

		$I->wantTo('Update Stock Image in Administrator page');
		$I->updateStockImage($this->nameStockImage, $this->newImageTooltip, $this->quantityNew);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param                  $scenario
	 * @throws Exception
	 */
	public function disableStock(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Deletion Stock Image in Administrator page');
		$I = new StockImageSteps($scenario);
		$I->deleteAllStockImage();

		$I->wantTo('Deletion of Stock Room in Administrator');
		$I = new StockRoomManagerJoomla3Steps($scenario);
		$I->deleteAllStockRoom();

		$I->wantTo('Disable StockRoom in Administrator page');
		$I = new ConfigurationSteps($scenario);
		$I->featureOffStockRoom();
	}
}
