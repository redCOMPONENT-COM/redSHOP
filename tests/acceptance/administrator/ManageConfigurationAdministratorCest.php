<?php

/**
 *
 * Configuration function
 *
 */
class ManageConfigurationAdministratorCest
{
	public function __construct()
	{
		$this->faker = Faker\Factory::create();
		//setup VAT for system

		$this->country = 'United States';
		$this->state = 'Alabam';
		$this->vatDefault = 'Default';
		$this->vatCalculation = 'Webshop';
		$this->vatAfter = 'after';
		$this->vatNumber = $this->faker->numberBetween(1, 10);
		$this->calculationBase = 'billing';
		$this->requiVAT = 'no';

		//Setup Discount
		$this->enableDiscount = 'yes';
		$this->allowedDiscount = "Discount + voucher (multiple) + coupon (multiple)";
		$this->coupon = 'yes';
		$this->couponInfor = 'no';
		$this->voucher = 'yes';
		$this->sendEmail = 'no';
		$this->apply = 'no';
		$this->calculate = 'total';
		$this->value = 'total';
		$this->amount = 10;

    }


	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	public function featureUsedStockRoom(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test used Stockroom  in Administrator');
		$I = new AcceptanceTester\ConfigurationManageJoomla3Steps($scenario);
		$I->wantTo('Start stook room ');
		$I->featureUsedStockRoom();
		$I->see(\ConfigurationManageJ3Page::$namePage, \ConfigurationManageJ3Page::$selectorPageTitle);
	}

	public function featureStockRoomNo(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test used Stockroom  in Administrator');
		$I = new AcceptanceTester\ConfigurationManageJoomla3Steps($scenario);
		$I->wantTo('Off stook room ');
		$I->featureOffStockRoom();
		$I->see(\ConfigurationManageJ3Page::$namePage, \ConfigurationManageJ3Page::$selectorPageTitle);
	}


	public function featureEditInLineYes(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Edit inline is yes  in Administrator');
		$I = new AcceptanceTester\ConfigurationManageJoomla3Steps($scenario);
		$I->wantTo(' Edit inline is yes ');
		$I->featureEditInLineYes();
		$I->see(\ConfigurationManageJ3Page::$namePage, \ConfigurationManageJ3Page::$selectorPageTitle);
	}

	public function featureEditInLineNo(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Edit inline is yes  in Administrator');
		$I = new AcceptanceTester\ConfigurationManageJoomla3Steps($scenario);
		$I->wantTo(' Edit inline is yes ');
		$I->featureEditInLineNo();
		$I->see(\ConfigurationManageJ3Page::$namePage, \ConfigurationManageJ3Page::$selectorPageTitle);
	}

	public function featureComparisonYes(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Comparison is yes  in Administrator');
		$I = new AcceptanceTester\ConfigurationManageJoomla3Steps($scenario);
		$I->wantTo(' Edit inline is yes ');
		$I->featureComparisonYes();
		$I->see(\ConfigurationManageJ3Page::$namePage, \ConfigurationManageJ3Page::$selectorPageTitle);
	}

	public function featureComparisonNo(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Comparison is No  in Administrator');
		$I = new AcceptanceTester\ConfigurationManageJoomla3Steps($scenario);
		$I->wantTo(' Edit inline is No ');
		$I->featureComparisonNo();
		$I->see(\ConfigurationManageJ3Page::$namePage, \ConfigurationManageJ3Page::$selectorPageTitle);
	}


	public function featurePriceNo(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Show Price is No  in Administrator');
		$I = new AcceptanceTester\ConfigurationManageJoomla3Steps($scenario);
		$I->wantTo(' Edit inline is No ');
		$I->featurePriceNo();
		$I->see(\ConfigurationManageJ3Page::$namePage, \ConfigurationManageJ3Page::$selectorPageTitle);
	}


	public function featurePriceYes(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Show Price is Yes  in Administrator');
		$I = new AcceptanceTester\ConfigurationManageJoomla3Steps($scenario);
		$I->wantTo(' Edit inline is yes ');
		$I->featurePriceYes();
		$I->see(\ConfigurationManageJ3Page::$namePage, \ConfigurationManageJ3Page::$selectorPageTitle);
	}

	/**
	 *
	 * Function setup vat for system
	 *
	 * @param AcceptanceTester $I
	 * @param $scenario
	 */
	public function setupVAT(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('setup VAT at admin');
		$I = new AcceptanceTester\ConfigurationManageJoomla3Steps($scenario);
		$I->wantTo(' Edit inline is yes ');
		$I->setupVAT($this->country, $this->state, $this->vatDefault, $this->vatCalculation, $this->vatAfter, $this->vatNumber, $this->calculationBase, $this->requiVAT);
	}

	/**
	 *
	 * Function setup discount
	 *
	 * @param AcceptanceTester $I
	 * @param $scenario
	 */
	public function setupDiscount(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('setup Discount at admin');
		$I = new AcceptanceTester\ConfigurationManageJoomla3Steps($scenario);
		$I->wantTo(' Setup discount ');
		$I->setupDiscount($this->enableDiscount, $this->allowedDiscount, $this->coupon, $this->couponInfor, $this->voucher, $this->sendEmail, $this->apply, $this->calculate,$this->value,$this->amount);
	}

}