<?php
use AcceptanceTester\TaxRateSteps;
use AcceptanceTester\TaxGroupSteps;
use AcceptanceTester\CategoryManagerJoomla3Steps as CategoryManagerJoomla3Steps;
use AcceptanceTester\ProductCheckoutManagerJoomla3Steps as ProductCheckoutManagerJoomla3Steps;
use AcceptanceTester\ConfigurationSteps as ConfigurationSteps;
use AcceptanceTester\UserManagerJoomla3Steps as UserManagerJoomla3Steps;
use AcceptanceTester\OrderManagerJoomla3Steps as OrderManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps as ProductSteps;


class ProductAttributesVatCheckoutCest
{
    public function __construct()
    {
        $this->faker = Faker\Factory::create();
        $this->taxRateName = $this->faker->bothify('Testing Tax Rates Groups ?###?');
        $this->taxRateNameEdit = $this->taxRateName . 'Edit';
        $this->taxGroupName = $this->faker->bothify('TaxGroupsName ?###?');
        $this->taxRateValue = 0.1;
        $this->countryName = 'Denmark';
        $this->taxRateValueNegative = -1;
        $this->taxRateValueString = 'Test';
        $this->categoryName = $this->faker->bothify('CategoryNameVAT ?###?');

        $this->productName     = $this->faker->bothify('Testing ProductManagement ??####?');
        $this->productNumber   = $this->faker->numberBetween(999, 9999);
        $this->productPrice    = 100;
        $this->minimumQuantity = 1;
        $this->maximumQuantity = $this->faker->numberBetween(11, 100);

        $this->nameAttribute = $this->faker->bothify('AttributeName ??###?');

        $this->attributes      = array(
            array(
                'attributeName'  => $this->faker->bothify('AttributeValue ??###?'),
                'attributePrice' => 10
            ),
            array(
                'attributeName'  => $this->faker->bothify('AttributeValueSecond ??###?'),
                'attributePrice' => 30
            ),
        );


        // Vat setting
        $this->vatCalculation = 'Webshop';
        $this->vatAfter = 'after';
        $this->vatNumber = 0;
        $this->calculationBase = 'billing';
        $this->requiVAT = 'no';

        $this->subtotal = "DKK  240,00";
        $this->vatPrice = "DKK 60,00";
        $this->total    = "DKK 300,00";
        $this->group    = 'Registered';

        $this->customerInformation= array(
            "userName"      => $this->faker->bothify('UserName ?####?'),
            "password"      => $this->faker->bothify('Password ?##?'),
            "email"         => $this->faker->email,
            "firstName"     => $this->faker->bothify('firstNameCustomer ?####?'),
            "lastName"      => $this->faker->bothify('lastNameCustomer ?####?'),
            "address"       => "Some Place in the World",
            "postalCode"    => "23456",
            "city"          => "HCM",
            "country"       => "Denmark",
            "state"         => "Karnataka",
            "phone"         => "8787878787",
            "shopperGroup"  => 'Default Private',
        );

        $this->customerBussinesInformation = array(
            "userName"      => $this->faker->bothify('UserName ?####?'),
            "password"      => $this->faker->bothify('Password ?##?'),
            "email"         => $this->faker->email,
            "businessNumber" => 1231312,
            "firstName"      => $this->faker->bothify('firstName ?####?'),
            "lastName"       => $this->faker->bothify('lastName ?####?'),
            "address"        => "Some Place in the World",
            "postalCode"     => "23456",
            "city"           => "HCM",
            "country"        => "Denmark",
            "state"          => "Karnataka",
            "phone"          => "8787878787",
            "eanNumber"      => 1212331331231,
            "shopperGroup"   => "Default Company",
        );

        //configuration enable one page checkout
        $this->addcart          = 'product';
        $this->allowPreOrder    = 'yes';
        $this->cartTimeOut      = $this->faker->numberBetween(100, 10000);
        $this->enabldAjax       = 'no';
        $this->defaultCart      = null;
        $this->buttonCartLead   = 'Back to current view';
        $this->onePage          = 'yes';
        $this->showShippingCart = 'no';
        $this->attributeImage   = 'no';
        $this->quantityChange   = 'no';
        $this->quantityInCart   = 0;
        $this->minimunOrder     = 0;
        $this->enableQuation    = 'no';
        $this->onePageNo        = 'no';
        $this->onePageYes       = 'yes';

    }

    public function _before(AcceptanceTester $I)
    {
        $I->doAdministratorLogin();
    }

    /**
     * Create VAT Group with
     *
     * @param   AcceptanceTester $client Current user state.
     * @param   \Codeception\Scenario $scenario Scenario for test.
     *
     * @return  void
     * @throws  Exception
     */
    public function createVATGroupSave(AcceptanceTester $client, $scenario)
    {

//        $client->wantTo('VAT Groups - Save creation in Administrator');
//        $client = new TaxGroupSteps($scenario);
//        $client->addVATGroupsSave($this->taxGroupName);
//
//        $client->wantTo('Test TAX Rates Save creation in Administrator');
//        $client = new TaxRateSteps($scenario);
//        $client->addTAXRatesSave($this->taxRateName, $this->taxGroupName, $this->taxRateValue, $this->countryName, null);
//
        $client->wantTo('Create new category ');
        $client = new CategoryManagerJoomla3Steps($scenario);
        $client->addCategorySave($this->categoryName);

        $client->wantTo('Create new product');
        $client = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
        $client->wantTo('I Want to add product inside the category');

        $client->wantTo('Create new product with attribute');
        (new ProductSteps($scenario))->productMultiAttributeValue($this->productName, $this->categoryName,
            $this->productNumber, $this->productPrice,$this->nameAttribute, $this->attributes
        );

//        $client->wantTo('Configuration for apply VAT');
//        $client = new ConfigurationSteps($scenario);
//        $client->setupVAT(
//            $this->countryName, null, $this->taxGroupName, $this->vatCalculation, $this->vatAfter, $this->vatNumber,
//            $this->calculationBase, $this->requiVAT
//        );
//        $client->cartSetting($this->addcart, $this->allowPreOrder, $this->enableQuation, $this->cartTimeOut, $this->enabldAjax, $this->defaultCart, $this->buttonCartLead,
//            $this->onePageYes, $this->showShippingCart, $this->attributeImage, $this->quantityChange, $this->quantityInCart, $this->minimunOrder);
//
//        $client->wantTo('Create user for checkout');
//        $client = new UserManagerJoomla3Steps($scenario);
//        $client->addUser(
//            $this->customerInformation["userName"], $this->customerInformation["password"], $this->customerInformation["email"], $this->group,$this->customerInformation["shopperGroup"], $this->customerInformation["firstName"], $this->customerInformation["lastName"], 'saveclose'
//        );
//
//        $client->addUser(
//            $this->customerBussinesInformation["userName"], $this->customerBussinesInformation["password"], $this->customerBussinesInformation["email"], $this->group,$this->customerBussinesInformation["shopperGroup"], $this->customerBussinesInformation["firstName"], $this->customerBussinesInformation["lastName"], 'saveclose'
//        );


        $client = new ProductCheckoutManagerJoomla3Steps($scenario);
        $client->testProductAttributeWithVatCheckout(
            "admin", "admin", $this->productName, $this->nameAttribute, $this->attributes, $this->categoryName, $this->subtotal, $this->vatPrice, $this->total
        );

//        $client = new ConfigurationSteps($scenario);
//        $client->cartSetting($this->addcart, $this->allowPreOrder, $this->enableQuation, $this->cartTimeOut, $this->enabldAjax, $this->defaultCart, $this->buttonCartLead,
//            $this->onePageNo, $this->showShippingCart, $this->attributeImage, $this->quantityChange, $this->quantityInCart, $this->minimunOrder);
    }
}