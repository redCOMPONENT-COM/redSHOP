<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\AdminManagerJoomla3Steps;
use Administrator\plugins\PluginPaymentManagerJoomla;
use AcceptanceTester\ProductManagerJoomla3Steps;
use Configuration\ConfigurationSteps;
use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\ShippingSteps;

class ProductCheckoutWithGLSCest
{
    /**
     * @var \Faker\Generator
     * @since 2.1.3
     */
    public $faker;

    /**
     * @var string
     * @since 2.1.3
     */
    public $categoryName;

    /**
     * @var string
     * @since 2.1.3
     */
    public $productName;

    /**
     * @var string
     * @since 2.1.3
     */
    public $productNumber;

    /**
     * @var string
     * @since 2.1.3
     */
    public $productPrice;

    /**
     * @var string
     * @since 2.1.3
     */
    public $extensionURL;

    /**
     * @var string
     * @since 2.1.3
     */
    public $pluginName;

    /**
     * @var string
     * @since 2.1.3
     */
    public $pluginURL;

    /**
     * @var string
     * @since 2.1.3
     */
    public $package;

    public function __construct()
    {
        $this->faker            = Faker\Factory::create();
        $this->categoryName     = $this->faker->bothify('CategoryName ?###?');
        $this->productName      = $this->faker->bothify('Testing Product ??####?');
        $this->productNumber    = $this->faker->numberBetween(999, 9999);
        $this->productPrice     = 100;

        $this->extensionURL     = 'extension url';
        $this->pluginName       = 'default GLS';
        $this->pluginURL        = 'paid-extensions/tests/releases/plugins/';
        $this->package          = 'plg_redshop_shipping_default_shipping_gls-v2.0.zip';

        //configuration enable one page checkout
        $this->cartSetting = array(
            "addcart"           => 'product',
            "allowPreOrder"     => 'yes',
            "cartTimeOut"       => $this->faker->numberBetween(100, 10000),
            "enabldAjax"        => 'no',
            "defaultCart"       => null,
            "buttonCartLead"    => 'Back to current view',
            "onePage"           => 'yes',
            "showShippingCart"  => 'no',
            "attributeImage"    => 'no',
            "quantityChange"    => 'no',
            "quantityInCart"    => 0,
            "minimunOrder"      => 0,
            "enableQuation"     => 'no',
            "onePageNo"         => 'no',
            "onePageYes"        => 'yes'
        );

        $this->shipping       = array(
            'shippingName' => $this->faker->bothify('TestingShippingRate ?##?'),
            'shippingRate' => 10
        );
    }

    /**
     * @param AcceptanceTester $I
     * @throws Exception
     * @since    2.1.3
     */
    public function _before(AcceptanceTester $I)
    {
        $I->doAdministratorLogin();
    }

    /**
     * @param AdminManagerJoomla3Steps $I
     * @throws Exception
     * @since    2.1.3
     */
    public function installPlugin(AdminManagerJoomla3Steps $I, $scenario)
    {
        $I->wantTo("install plugin payment Bank Transfer Discount");
//        $I->installExtensionPackageFromURL($this->extensionURL, $this->pluginURL, $this->package);
//        $I->waitForText(AdminJ3Page:: $messageInstallPluginSuccess, 120, AdminJ3Page::$idInstallSuccess);
        $I->wantTo('Enable Plugin Bank Transfer Discount Payments in Administrator');
        $I->enablePlugin($this->pluginName);

        $I = new PluginPaymentManagerJoomla($scenario);
        $I->configShippingGLSPlugin($this->pluginName);
    }

    /**
     * @param ConfigurationSteps $I
     * @param $scenario
     * @throws Exception
     * @since 2.1.3
     */
    public function testBankTransferDiscountPaymentPlugin( ConfigurationSteps $I, $scenario)
    {
        $I->cartSetting($this->cartSetting["addcart"], $this->cartSetting["allowPreOrder"], $this->cartSetting["enableQuation"],$this->cartSetting["cartTimeOut"], $this->cartSetting["enabldAjax"], $this->cartSetting["defaultCart"],
            $this->cartSetting["buttonCartLead"], $this->cartSetting["onePageYes"], $this->cartSetting["showShippingCart"], $this->cartSetting["attributeImage"], $this->cartSetting["quantityChange"], $this->cartSetting["quantityInCart"], $this->cartSetting["minimunOrder"]);

        $I->wantTo('Create Category in Administrator');
        $I = new CategoryManagerJoomla3Steps($scenario);
        $I->addCategorySave($this->categoryName);

        $I = new ProductManagerJoomla3Steps($scenario);
        $I->wantTo('I Want to add product inside the category');
        $I->createProductSaveClose($this->productName, $this->categoryName, $this->productNumber, $this->productPrice);

        $I->wantToTest("Create shipping rate");
        $I = new ShippingSteps($scenario);
        $I->createShippingRateStandard($this->pluginName, $this->shipping, 'save');

        $I->wantTo('checkout with '.$this->pluginName);

        $I->wantTo('Check Order');
    }

    /**
     * @param AcceptanceTester $I
     * @param $scenario
     * @throws Exception
     * @since    2.1.3
     */
    public function clearAllData(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Delete product');
        $I = new ProductManagerJoomla3Steps($scenario);
        $I->deleteProduct($this->productName);

        $I->wantTo('Delete Category');
        $I = new CategoryManagerJoomla3Steps($scenario);
        $I->deleteCategory($this->categoryName);

        $I->wantTo('Delete shipping rate');
        $I = new ShippingSteps($scenario);
        $I->deleteShippingRate($this->pluginName, $this->shipping['shippingName']);

        $I->wantTo("Disable Plugin");
        $I->disablePlugin($this->pluginName);
    }
}