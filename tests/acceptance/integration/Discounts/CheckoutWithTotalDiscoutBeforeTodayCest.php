<?php
/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 7/26/2018
 * Time: 11:12 AM
 */
use AcceptanceTester\ProductManagerJoomla3Steps as ProductManagerSteps;
use AcceptanceTester\DiscountSteps;
use AcceptanceTester\OrderManagerJoomla3Steps;
use AcceptanceTester\OrderBackendSteps;
use AcceptanceTester\UserManagerJoomla3Steps;
class CheckoutWithTotalDiscoutBeforeTodayCest
{
    /**
     * CheckoutWithMassDiscoutBeforeTodayCest constructor.
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
        $this->discountStart                = "2018-04-04";
        $this->discountEnd                  = "2018-04-20";
        $this->randomProductNumber          = rand(999, 9999);
        $this->randomProductNumberNew       = rand(999, 9999);
        $this->randomProductAttributeNumber = rand(999, 9999);
        $this->randomProductNameAttribute   = 'Testing Attribute' . rand(99, 999);
        $this->randomProductPrice           = rand(9, 19);
        $this->discountPriceThanPrice       = 100;
        $this->statusProducts               = 'Product on sale';
        $this->searchCategory               = 'Category';
        $this->newProductName               = 'New-Test Product' . rand(99, 999);
        $this->nameAttribute                = 'Size';
        $this->valueAttribute               = "Z";
        $this->priceAttribute               = 12;
        $this->nameProductAccessories       = "redFORM";
        $this->nameRelatedProduct           = "redITEM";
        $this->quantityStock                = 4;
        $this->PreorderStock                = 2;
        $this->priceProductForThan          = 10;
        $this->totalAmount                  = $this->fake->numberBetween(100, 999);
        $this->discountAmount               = $this->fake->numberBetween(10, 100);

        $this->product                    = array();
        $this->product['name']            = $this->newProductName;
        $this->product['number']          = $this->randomProductNumber;
        $this->product['category']        = $this->randomCategoryName;
        $this->product['price']           = $this->randomProductPrice;
        $this->product['discountStart']   = $this->discountStart;
        $this->product['discountEnd']     = $this->discountEnd;
        $this->product['discountPrice']   = $this->fake->numberBetween(100, 1000);
        $this->product['maximumQuantity'] = $this->maximumQuantity;
        $this->product['minimumQuantity'] = $this->minimumQuantity;

        $this->userName                   = $this->fake->bothify('ManageUserAdministratorCest ?##?');
        $this->password                   = $this->fake->bothify('Password ?##?');
        $this->email                      = $this->fake->email;
        $this->emailsave                  = $this->fake->email;
        $this->shopperGroup               = 'Default Private';
        $this->group                      = 'Super Users';
        $this->firstName                  = $this->fake->bothify('ManageUserAdministratorCest FN ?##?');
        $this->lastName                   = 'Last';
        $this->firstNameSave              = "FirstName";
        $this->lastNameSave               = "LastName";
        $this->emailWrong                 = "email";
        $this->userNameDelete             = $this->firstName;
        $this->searchOrder                = $this->firstName.$this->lastName ;
    }
    /**
     * @param ProductManagerSteps $I
     */
    public function _before(ProductManagerSteps $I)
    {
        $I->doAdministratorLogin();
    }
    /**
     * @param UserManagerJoomla3Steps $I
     */
    public function addUser(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Test User creation with save button in Administrator');
        $I = new AcceptanceTester\UserManagerJoomla3Steps($scenario);
        $I->addUser($this->userName, $this->password, $this->emailsave, $this->group, $this->shopperGroup, $this->firstName, $this->lastName, 'saveclose');
    }
    /**
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function addCategory(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Create Category in Administrator');
        $I = new AcceptanceTester\CategoryManagerJoomla3Steps($scenario);
        $I->wantTo('Create a Category');
        $I->addCategorySave($this->randomCategoryName);
    }
    /**
     * @param ProductManagerSteps $I
     * @throws Exception
     */
    public function createProductSave(ProductManagerSteps $I)
    {
        $I->wantTo('Test Product Save Manager in Administrator');
        $I->wantTo('I Want to add product inside the category');
        $I->createProductSave($this->randomProductName, $this->randomCategoryName, $this->randomProductNumber, $this->randomProductPrice, $this->minimumPerProduct, $this->minimumQuantity, $this->maximumQuantity);
    }
    /**
     * @param DiscountSteps $I
     * @throws Exception
     */
//    public function createMassDiscountSave(DiscountSteps $I)
//    {
//        $I->wantTo('Create Mass Discount in Administrator');
//        $I->addMassDiscoutnAndSave($this->randomDiscountName, '10', 'Total', '2018-07-01', '2018-07-20', $this->randomProductName, $this->randomCategoryName);
//    }
    /**
     * @param DiscountSteps $I
     * @throws Exception
     */
    public function createTotalDiscountSaveClose(DiscountSteps $I)
    {
        $I->wantTo('Create Total Discount in Administrator');
        $I->addTotalDiscountSaveClose($this->randomDiscountName,  $this->totalAmount, 'Higher', 'Total', $this->discountAmount, $this->discountStart, $this->discountEnd, $this->shopperGroup);
    }
    /**
     * @param ProductManagerSteps $I
     * @throws Exception
     */
    public function addProductToCart(OrderManagerJoomla3Steps $I)
    {
        $I->wantTo('Add products in cart');
        $I->addProductToCart($this->randomProductName, $this->userName, $this->password );
    }
    /**
     * @param OrderBackendSteps $I
     * @throws Exception
     */
    public function checkOrder(OrderBackendSteps $I)
    {
       $I->wantTo('Check Order');
       $I->totalProductCustomer($this->searchOrder, $this->firstName, $this->lastName, $this->randomProductName, $this->randomCategoryName, $this->randomProductPrice);
    }
    /**
     * @param AcceptanceTester $I
     * @param $scenario
     * @throws Exception
     */
    public function deleteProduct(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Deletion Product in Administrator');
        $I = new AcceptanceTester\ProductManagerJoomla3Steps($scenario);
        $I->wantTo('I Want to add product inside the category');
        $I->deleteProduct($this->randomProductName);
    }
    /**
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function deleteCategory(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Deletion Category in Administrator');
        $I = new AcceptanceTester\CategoryManagerJoomla3Steps($scenario);
        $I->wantTo('Delete a Category');
        $I->deleteCategory($this->randomCategoryName);
    }
    /**
     * @param AcceptanceTester $I
     * @param $scenario
     */
    public function deleteUser(AcceptanceTester $I, $scenario)
    {
        $I->wantTo('Deletion of User in Administrator');
        $I = new AcceptanceTester\UserManagerJoomla3Steps($scenario);
        $I->deleteUserWithAccept($this->userNameDelete);
    }
    /**
     * @param DiscountSteps $I
     */
    public function deleteTotalDiscount(DiscountSteps $I)
    {
        $I->wantTo('Deletion of Total Discount in Administrator');
        $I->deleteDiscount($this->randomDiscountName);
    }
    /**
     * @param OrderManagerJoomla3Steps $I
     * @throws Exception
     */
    public function deleteOrderTotalDiscount(OrderManagerJoomla3Steps $I)
    {
        $I->wantTo('Deletion of Order Total Discount in Administrator');
        $I->deleteOrder( $this->searchOrder);
    }
}