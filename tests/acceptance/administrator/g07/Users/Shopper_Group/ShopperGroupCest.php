<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\CategoryManagerJoomla3Steps;
use AcceptanceTester\ShopperGroupManagerJoomla3Steps;

/**
 * Class ShopperGroupCest
 * @since 3.0.2
 */
class ShopperGroupCest
{
	/**
	 * @var \Faker\Generator
	 * @since 3.0.2
	 */
	protected $faker;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $shopperName;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $shopperNameSaveClose;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $shopperType;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $categoryName;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $customerType;

	/**
	 * @var int
	 * @since 3.0.2
	 */
	protected $shippingRate;

	/**
	 * @var int
	 * @since 3.0.2
	 */
	protected $shippingCheckout;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $catalog;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $showPrice;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $nameShopperEdit;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $idShopperChange;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $shipping;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $enableQuotation;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $showVat;

	/**
	 * @var string
	 * @since 3.0.2
	 */
	protected $shopperGroupPortal;

	/**
	 * ShopperGroupCest constructor
	 * @since 3.0.2
	 */
	public function __construct()
	{
		$this->faker = Faker\Factory::create();
		$this->shopperName = $this->faker->bothify('Testing shopper ##??');

		$this->shopperNameSaveClose = $this->faker->bothify('Name Close ##??');

		$this->shopperType = 'Default Private';

		$this->categoryName =  $this->faker->bothify('Category name ##??');
		$this->customerType = 'Company customer';

		$this->shippingRate = $this->faker->numberBetween(1, 100);

		$this->shippingCheckout = $this->faker->numberBetween(1, 100);

		$this->catalog = 'Yes';

		$this->showPrice = 'Yes';

		$this->nameShopperEdit = $this->shopperType . 'edit';

		$this->idShopperChange = '1';

		$this->shipping = 'no';

		$this->enableQuotation = 'yes';

		$this->showVat = 'no';

		$this->shopperGroupPortal = 'no';
	}

	/**
	 * @param AcceptanceTester $I
	 * @throws Exception
	 * @since 3.0.2
	 */
	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	/**
	 * @param CategoryManagerJoomla3Steps $I
	 * @throws Exception
	 * @since 3.0.2
	 */
	public function createCategory(CategoryManagerJoomla3Steps $I)
	{
		$I->wantTo('Test Category Save creation in Administrator');
		$I->addCategorySave($this->categoryName);
	}

	/**
	 * @param ShopperGroupManagerJoomla3Steps $I
	 * @throws Exception
	 * @since 3.0.2
	 */
	public function createShopperGroup(ShopperGroupManagerJoomla3Steps $I)
	{
		$I->wantTo('Test Shopper Group Save creation in Administrator');
		$I->wantTo('Create Shopper Group Save button');
		$I->addShopperGroups($this->shopperName, $this->shopperType, $this->customerType, $this->shopperGroupPortal,$this->categoryName, $this->shipping,$this->shippingRate, $this->shippingCheckout, $this->catalog,$this->showVat, $this->showPrice, $this->enableQuotation,'save');
		$I->addShopperGroups($this->shopperNameSaveClose, $this->shopperType, $this->customerType, $this->shopperGroupPortal,$this->categoryName,$this->shipping, $this->shippingRate, $this->shippingCheckout, $this->catalog, $this->showVat,$this->showPrice,$this->enableQuotation, 'saveclose');
	}

	/**
	 * Function change status os shopper groups is unpublish
	 * @param ShopperGroupManagerJoomla3Steps $I
	 * @since 3.0.2
	 */
	public function changeStateShopperGroup(ShopperGroupManagerJoomla3Steps $I)
	{
		$I->wantTo('Test Unpublish Shopper groups');
		$I->changeStateShopperGroup('unpublished');
		$I->changeStateShopperGroup('published');
	}

	/**
	 * Function edit name of shopper groups
	 * @param ShopperGroupManagerJoomla3Steps $I
	 * @since 3.0.2
	 */
	public function editNameShopper(ShopperGroupManagerJoomla3Steps $I)
	{
		$I->wantTo('Edit Name of Shopper groups');
		$I->editShopperGroups($this->shopperType, $this->idShopperChange, $this->nameShopperEdit);
	}

	/**
	 * @param ShopperGroupManagerJoomla3Steps $I
	 * @since 3.0.2
	 */
	public function deleteShopperGroupsNo(ShopperGroupManagerJoomla3Steps $I)
	{
		$I->wantTo('Check delete Shopper groups');
		$I->wantTo('Edit Name of Shopper groups');
		$I->deleteShopperGroupsNo();
	}

	/**
	 * @param ShopperGroupManagerJoomla3Steps $I
	 * @since 3.0.2
	 */
	public function changeStatusAllShopperGroups(ShopperGroupManagerJoomla3Steps $I)
	{
		$I->wantTo('Publish all  Shopper groups');
		$I->changStatusAllShopperGroups('unpublished');
		$I->changStatusAllShopperGroups('published');
	}

	/**
	 * @param ShopperGroupManagerJoomla3Steps $I
	 * @since 3.0.2
	 */
	public function checkButtons(ShopperGroupManagerJoomla3Steps $I)
	{
		$I->wantTo('Test to validate different buttons on Gift Card Views');
		$I->checkButtons('edit');
		$I->checkButtons('cancel');
		$I->checkButtons('publish');
		$I->checkButtons('unpublish');
		$I->checkButtons('Delete');
	}

	/**
	 * @param ShopperGroupManagerJoomla3Steps $I
	 * @since 3.0.2
	 */
	public function addShopperGroupsMissingName(ShopperGroupManagerJoomla3Steps $I)
	{
		$I->wantTo('Add shopper group missing name');
		$I->addShopperGroupsMissingName();
	}
}