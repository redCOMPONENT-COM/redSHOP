<?php
use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;

/**
 * Class PlaceOrderManagerCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    2.1.2
 */
class PlaceOrderManagerCest
{
	public function __construct()
	{
		$this->faker                        = Faker\Factory::create();
		$this->userName                     = $this->faker->bothify('ManageUserAdministratorCest ?##?');
		$this->password                     = $this->faker->bothify('Password ?##?');
		$this->email                        = $this->faker->email;
		$this->emailMissingUser             = $this->faker->email;
		$this->emailsave                    = $this->faker->email;
		$this->shopperGroup                 = 'Default Private';
		$this->group                        = 'Public';
		$this->firstName                    = $this->faker->bothify('ManageUserAdministratorCest FN ?##?');
		$this->updateFirstName              = 'Updating ' . $this->firstName;
		$this->lastName                     = 'Last';

		$this->categoryName                 = $this->faker->bothify('CategoryTesting ??####?');
		$this->productName                  = $this->faker->bothify('TestingProductManagement ??####?');
		$this->randomProductNumber          = $this->faker->numberBetween(999, 9999);
		$this->randomProductPrice           = rand(9, 19);
		$this->quantity                     = 1;
	}

	/**
	 * @param AcceptanceTester $I
	 */
	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 */
	public function createProduct(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Create Category in Administrator');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->addCategorySave($this->categoryName);

		$I->wantTo('I Want to add product inside the category');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->createProductSaveClose($this->productName, $this->categoryName, $this->randomProductNumber, $this->randomProductPrice);
	}

	/**
	 *
	 * Function add user with save and save &c lose button
	 *
	 * @param AcceptanceTester $I
	 * @param $scenario
	 */
	public function createOder(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test User creation with save button in Administrator');
		$I = new AcceptanceTester\UserManagerJoomla3Steps($scenario);
		$I->addUser($this->userName, $this->password, $this->email, $this->group, $this->shopperGroup, $this->firstName, $this->lastName, 'saveclose');

		$I->wantTo('Create Oder');
		$I->checkPlaceOder($this->firstName,$this->productName,$this->quantity);
	}

	/**
	 * @param AcceptanceTester $I
	 * @param $scenario
	 * @throws Exception
	 */
	public function clearAllData(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Delete Oder');
		$I = new AcceptanceTester\OrderManagerJoomla3Steps($scenario);
		$I->wantTo('Test Order delete by user  in Administrator');
		$I->deleteOrder($this->firstName);

		$I->wantTo('Deletion Product in Administrator');
		$I = new ProductManagerJoomla3Steps($scenario);
		$I->deleteProduct($this->productName);

		$I->wantTo('Deletion Category in Administrator');
		$I = new CategoryManagerJoomla3Steps($scenario);
		$I->deleteCategory($this->categoryName);

		$I->wantTo('Deletion of User in Administrator');
		$I = new AcceptanceTester\UserManagerJoomla3Steps($scenario);
		$I->deleteUser($this->firstName, 'true');
	}
}