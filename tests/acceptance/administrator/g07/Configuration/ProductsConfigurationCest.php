<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
use Faker\Factory;
use AcceptanceTester\CategoryManagerJoomla3Steps;
use Configuration\ConfigurationSteps;
use Configuration\ProductsConfigurationSteps;
use AcceptanceTester\ProductManagerJoomla3Steps;
/**
 * Class ProductsConfigurationCest
 */
class ProductsConfigurationCest
{
    /**
     * @var string
     * since 2.1.2
     */
    protected $randomProductNameAttribute;
    /**
     * @var string
     * since 2.1.2
     */
    protected $randomCategoryName;
    /**
     * @var int
     * since 2.1.2
     */
    protected $randomProductAttributeNumber;
    /**
     * @var int
     * since 2.1.2
     */
    protected $randomProductPrice;
    /**
     * @var string
     * since 2.1.2
     */
    protected $nameAttribute;
    /**
     * @var string
     * since 2.1.2
     */
    protected $valueAttribute;
    /**
     * @var string
     * since 2.1.2
     */
    protected $priceAttribute;
    /**
     * ProductsConfigurationCest constructor.
     * since 2.1.2
     */
    public function __construct()
    {
        $this->faker = Faker\Factory::create();
        $this->randomProductNameAttribute   = $this->faker->bothify('Testing Attribute_?##?');
        $this->randomCategoryName           = $this->faker->bothify('Category Demo_?##?');
        $this->randomProductAttributeNumber = $this->faker->numberBetween(999, 9999);
        $this->randomProductPrice           = $this->faker->numberBetween(99, 199);
        $this->nameAttribute                = 'Testing Attribute' . rand(99, 999);
        $this->valueAttribute               = '10';
        $this->priceAttribute               = '10';
    }
    /**
     * @param AcceptanceTester $I
     * @throws Exception
     * since 2.1.2
     */
    public function _before(AcceptanceTester $I)
    {
        $I->doAdministratorLogin();
    }
    /**
     * @param AcceptanceTester $I
     * @param $scenario
     * @throws Exception
     * since 2.1.2
     */
    public function checkDisplayOutOfStockAttibute(AcceptanceTester $I,$scenario )
    {
        $I->wantTo('Test enable Stockroom in Administrator');
        $I = new ConfigurationSteps($scenario);
        $I->featureUsedStockRoom();

        $I->wantTo('Create Category in Administrator');
        $I = new CategoryManagerJoomla3Steps($scenario);
        $I->wantTo('Create a Category');
        $I->addCategorySave($this->randomCategoryName);

        $I->wantTo('Test create Product with attribute Save Manager in Administrator');
        $I = new ProductsConfigurationSteps($scenario);
        $I->wantTo('I Want to add product inside the category');
        $I->createProductWithAttributeStockRoom($this->randomProductNameAttribute, $this->randomCategoryName, $this->randomProductAttributeNumber, $this->randomProductPrice, $this->nameAttribute, $this->valueAttribute, $this->priceAttribute);

        $I->wantTo('I Want to check Product With Attribute StockRoom');
        $I = new ProductsConfigurationSteps($scenario);
        $I->wantTo('I Want to check Product With Attribute StockRoom');
        $I->checkProductWithAttributeStockRoom($this->randomCategoryName,$this->randomProductNameAttribute);

        $I->wantTo('I Want to delete product');
        $I = new ProductManagerJoomla3Steps($scenario);
        $I->deleteProduct($this->randomProductNameAttribute);

        $I->wantTo('I Want to delete category');
        $I = new CategoryManagerJoomla3Steps($scenario);
        $I->deleteCategory($this->randomCategoryName);
    }
}