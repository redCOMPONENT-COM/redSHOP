<?php

/**
 * Created by PhpStorm.
 * User: nhung nguyen
 * Date: 5/25/2017
 * Time: 3:51 PM
 */
class ManageConfigurationAdministratorCest
{
	public function featureUsedStockRoom(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test used Stockroom  in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\ConfigurationManageJoomla3Steps($scenario);
		$I->wantTo('Start stook room ');
		$I->featureUsedStockRoom();
		$I->see("Configuration", '.page-title');
	}

	public function featureStockRoomNo(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test used Stockroom  in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\ConfigurationManageJoomla3Steps($scenario);
		$I->wantTo('Off stook room ');
		$I->featureOffStockRoom();
		$I->see("Configuration", '.page-title');
	}



	public function featureEditInLineYes(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Edit inline is yes  in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\ConfigurationManageJoomla3Steps($scenario);
		$I->wantTo(' Edit inline is yes ');
		$I->featureEditInLineYes();
		$I->see("Configuration", '.page-title');
	}

	public function featureEditInLineNo(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Edit inline is yes  in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\ConfigurationManageJoomla3Steps($scenario);
		$I->wantTo(' Edit inline is yes ');
		$I->featureEditInLineNo();
		$I->see("Configuration", '.page-title');
	}

	public function featureComparisonYes(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Comparison is yes  in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\ConfigurationManageJoomla3Steps($scenario);
		$I->wantTo(' Edit inline is yes ');
		$I->featureComparisonYes();
		$I->see("Configuration", '.page-title');
	}

	public function featureComparisonNo(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Comparison is No  in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\ConfigurationManageJoomla3Steps($scenario);
		$I->wantTo(' Edit inline is No ');
		$I->featureComparisonNo();
		$I->see("Configuration", '.page-title');
	}


	public function featurePriceNo(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Show Price is No  in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\ConfigurationManageJoomla3Steps($scenario);
		$I->wantTo(' Edit inline is No ');
		$I->featurePriceNo();
		$I->see("Configuration", '.page-title');
	}


	public function featurePriceYes(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Show Price is Yes  in Administrator');
		$I->doAdministratorLogin();
		$I = new AcceptanceTester\ConfigurationManageJoomla3Steps($scenario);
		$I->wantTo(' Edit inline is yes ');
		$I->featurePriceYes();
		$I->see("Configuration", '.page-title');
	}

}