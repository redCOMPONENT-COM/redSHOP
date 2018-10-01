<?php
/**
 * Class OnePageCheckoutWithMissingDataCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    2.4
 */

use AcceptanceTester\ProductManagerJoomla3Steps as ProductManagerSteps;
use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\OrderManagerJoomla3Steps;
use AcceptanceTester\ConfigurationSteps;
use AcceptanceTester\UserManagerJoomla3Steps;
class OnePageCheckoutWithMissingDataCest
{
    /**
     * OnePageCheckoutWithMissingDataCest constructor.
     */
    public function __construct()
    {
        $this->fake                         = Faker\Factory::create();
        $this->randomCategoryName           = 'TestingCategory' . rand(99, 999);
        $this->randomCategoryNameAssign     = 'CategoryAssign' . rand(99, 999);
        $this->randomProductName            = 'TestingProducts' . rand(99, 999);
        $this->randomDiscountName           = 'discount total order ' . rand(99, 999);
        $this->minimumPerProduct            = 2;
        $this->minimumQuantity              = 2;
        $this->maximumQuantity              = 5;
        $this->productStart                 = "2018-09-05";
        $this->productEnd                   = "2018-11-08";
        $this->discountStart                = "2018-10-04";
        $this->discountEnd                  = "2018-11-20";
        $this->randomProductNumber          = rand(999, 9999);
        $this->randomProductNumberNew       = rand(999, 9999);
        $this->randomProductPrice           = rand(9, 19);
        $this->discountPriceThanPrice       = 100;
        $this->statusProducts               = 'Product on sale';
        $this->searchCategory               = 'Category';
        $this->newProductName               = 'New-Test Product' . rand(99, 999);
        $this->priceProductForThan          = 10;
        $this->totalAmount                  = $this->fake->numberBetween(100, 999);
        $this->discountAmount               = $this->fake->numberBetween(10, 100);

        $this->userName                   = $this->fake->bothify('UserTest ?##?');
        $this->password                   = $this->fake->bothify('Password ?##?');
        $this->email                      = $this->fake->email;
        $this->shopperGroup               = 'Default Private';
        $this->group                      = 'Registered';
        $this->firstName                  = $this->fake->bothify('UserCest FN ?##?');
        $this->lastName                   = 'Last';
        $this->firstNameSave              = "FirstName";
        $this->lastNameSave               = "LastName";
        $this->emailWrong                 = "email";
        $this->userNameDelete             = $this->firstName;
        $this->searchOrder                = $this->firstName.' '.$this->lastName ;
        $this->address                    = '97 Ha Nam';
        $this->postalCode                 = '2';
        $this->city                       = 'Ha Noi';
        $this->phone                      = $this->fake->bothify('01########');


        //configuration enable one page checkout
        $this->addcart          = 'product';
        $this->allowPreOrder    = 'yes';
        $this->cartTimeOut      = $this->fake->numberBetween(100, 10000);
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

    /**
     * Method for clean data.
     *
     * @param   mixed $scenario Scenario
     *
     * @return  void
     */
    public function deleteData($scenario)
    {
        $I = new RedshopSteps($scenario);
        $I->clearAllData();
    }

    /**
     * Method run before test.
     *
     * @param   AcceptanceTester $I
     *
     * @return  void
     */
    public function _before(AcceptanceTester $I)
    {
        $I->doAdministratorLogin();
    }

    /**
     * @param AcceptanceTester $I
     * @param \Codeception\Scenario $scenario
     * @throws Exception
     */
    public function addFunction(AcceptanceTester $I, \Codeception\Scenario $scenario)
    {
        $I->wantTo('Enable PayPal');
        $I->enablePlugin('PayPal');

        $I->wantTo('Test User creation with save button in Administrator');
        $I = new UserManagerJoomla3Steps($scenario);
        $I->addUser($this->userName, $this->password, $this->email, $this->group, $this->shopperGroup, $this->firstName, $this->lastName, 'save');

        $I->wantTo('Create Category in Administrator');
        $I = new CategoryManagerJoomla3Steps($scenario);
        $I->wantTo('Create a Category');
        $I->addCategorySave($this->randomCategoryName);

        $I->wantTo('I want to add product inside the category');
        $I = new ProductManagerSteps($scenario);
        $I->createProductSave($this->randomProductName, $this->randomCategoryName, $this->randomProductNumber, $this->randomProductPrice, $this->minimumPerProduct, $this->minimumQuantity, $this->maximumQuantity, $this->productStart, $this->productEnd);
    }

    /**
     * @param AcceptanceTester $I
     * @param $scenario
     * @throws Exception
     */
    public function addProductToCartWithMissingData(AcceptanceTester $I, $scenario)
    {

        $I->wantTo('setup up one page checkout at admin');
        $I = new ConfigurationSteps($scenario);
        $I->cartSetting($this->addcart, $this->allowPreOrder, $this->enableQuation, $this->cartTimeOut, $this->enabldAjax, $this->defaultCart, $this->buttonCartLead,
            $this->onePageYes, $this->showShippingCart, $this->attributeImage, $this->quantityChange, $this->quantityInCart, $this->minimunOrder);
        $I = new OrderManagerJoomla3Steps($scenario);
        $I->wantTo('Add products in cart');
        $I->addProductToCartWithMissingData($this->randomProductName, $this->userName, $this->password, $this->address, $this->postalCode, $this->city, $this->phone, 'address');
        $I->addProductToCartWithMissingData($this->randomProductName, $this->userName, $this->password, $this->address, $this->postalCode, $this->city, $this->phone, 'postalCode');
        $I->addProductToCartWithMissingData($this->randomProductName, $this->userName, $this->password, $this->address, $this->postalCode, $this->city, $this->phone, 'city');
        $I->addProductToCartWithMissingData($this->randomProductName, $this->userName, $this->password, $this->address, $this->postalCode, $this->city, $this->phone, 'phone');
        $I = new ConfigurationSteps($scenario);
        $I->cartSetting($this->addcart, $this->allowPreOrder, $this->enableQuation, $this->cartTimeOut, $this->enabldAjax, $this->defaultCart, $this->buttonCartLead,
            $this->onePageNo, $this->showShippingCart, $this->attributeImage, $this->quantityChange, $this->quantityInCart, $this->minimunOrder);
    }

    /**
     * @param AcceptanceTester $I
     * @param $scenario
     * @throws Exception
     */
    public function clearAllData(AcceptanceTester $I, $scenario)
    {

        $I->wantTo('Deletion Product in Administrator');
        $I = new ProductManagerSteps($scenario);
        $I->deleteProduct($this->randomProductName);

        $I->wantTo('Deletion Category in Administrator');
        $I = new CategoryManagerJoomla3Steps($scenario);
        $I->deleteCategory($this->randomCategoryName);

        $I->wantTo('Deletion of User in Administrator');
        $I = new UserManagerJoomla3Steps($scenario);
        $I->deleteUser($this->userNameDelete, 'true');
    }
}
