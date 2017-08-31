<?php
/**
 * Created by PhpStorm.
 * User: nhung nguyen
 * Date: 5/25/2017
 * Time: 3:51 PM
 */

namespace AcceptanceTester;


class ConfigurationManageJoomla3Steps extends AdminManagerJoomla3Steps
{
	public function featureUsedStockRoom()
	{
		$I = $this;
		$I->amOnPage(\ConfigurationManageJ3Page::$URL);
		$I->click(\ConfigurationManageJ3Page::$featureSetting);
		$I->waitForElement(\ConfigurationManageJ3Page::$ratingTab, 60);
		$I->waitForElement(\ConfigurationManageJ3Page::$stockRoomTab, 60);
		$I->click(\ConfigurationManageJ3Page::$stockRoomYes);
		$I->click(\ConfigurationManageJ3Page::$buttonSave);
	}

	public function featureOffStockRoom()
	{
		$I = $this;
		$I->amOnPage(\ConfigurationManageJ3Page::$URL);
		$I->click(\ConfigurationManageJ3Page::$featureSetting);
		$I->waitForElement(\ConfigurationManageJ3Page::$ratingTab, 60);
		$I->waitForElement(\ConfigurationManageJ3Page::$stockRoomTab, 60);
		$I->click(\ConfigurationManageJ3Page::$stockRoomNo);
		$I->click(\ConfigurationManageJ3Page::$buttonSave);
	}


	public function featureEditInLineYes()
	{
		$I = $this;
		$I->amOnPage(\ConfigurationManageJ3Page::$URL);
		$I->click(\ConfigurationManageJ3Page::$featureSetting);
		$I->waitForElement(\ConfigurationManageJ3Page::$editInline, 60);
		$I->waitForElement(\ConfigurationManageJ3Page::$stockRoomTab, 60);
		$I->click(\ConfigurationManageJ3Page::$eidtInLineYes);
		$I->click(\ConfigurationManageJ3Page::$buttonSave);
	}

	public function featureEditInLineNo()
	{
		$I = $this;
		$I->amOnPage(\ConfigurationManageJ3Page::$URL);
		$I->click(\ConfigurationManageJ3Page::$featureSetting);
		$I->waitForElement(\ConfigurationManageJ3Page::$editInline, 60);
		$I->waitForElement(\ConfigurationManageJ3Page::$stockRoomTab, 60);
		$I->click(\ConfigurationManageJ3Page::$editInLineNo);
		$I->click(\ConfigurationManageJ3Page::$buttonSave);
	}

	public function featureComparisonNo()
	{
		$I = $this;
		$I->amOnPage(\ConfigurationManageJ3Page::$URL);
		$I->click(\ConfigurationManageJ3Page::$featureSetting);
		$I->waitForElement(\ConfigurationManageJ3Page::$comparisonTab, 60);
		$I->waitForElement(\ConfigurationManageJ3Page::$stockRoomTab, 60);
		$I->click(\ConfigurationManageJ3Page::$comparisonNo);
		$I->click(\ConfigurationManageJ3Page::$buttonSave);
	}

	public function featureComparisonYes()
	{
		$I = $this;
		$I->amOnPage(\ConfigurationManageJ3Page::$URL);
		$I->click(\ConfigurationManageJ3Page::$featureSetting);
		$I->waitForElement(\ConfigurationManageJ3Page::$comparisonTab, 60);
		$I->waitForElement(\ConfigurationManageJ3Page::$stockRoomTab, 60);
		$I->click(\ConfigurationManageJ3Page::$comparisonYes);
		$I->click(\ConfigurationManageJ3Page::$buttonSave);
	}


	//Price

	public function featurePriceNo()
	{
		$I = $this;
		$I->amOnPage(\ConfigurationManageJ3Page::$URL);
		$I->click(\ConfigurationManageJ3Page::$price);
		$I->waitForElement(\ConfigurationManageJ3Page::$priceTab, 60);
		$I->click(\ConfigurationManageJ3Page::$showPriceNo);
		$I->click(\ConfigurationManageJ3Page::$buttonSave);
	}

	public function featurePriceYes()
	{
		$I = $this;
		$I->amOnPage(\ConfigurationManageJ3Page::$URL);
		$I->click(\ConfigurationManageJ3Page::$price);
		$I->waitForElement(\ConfigurationManageJ3Page::$priceTab, 60);
		$I->click(\ConfigurationManageJ3Page::$showPriceYes);
		$I->click(\ConfigurationManageJ3Page::$buttonSave);
	}

	/**
	 * @param $country
	 * @param $state
	 * @param $vatDefault
	 * @param $vatCalculation
	 * @param $vatAfter
	 * @param $calculationBase
	 * @param $vatNumber
	 */
	public function setupVAT($country, $state, $vatDefault, $vatCalculation, $vatAfter, $vatNumber, $calculationBase, $requiVAT)
	{
		$I = $this;
		$I->amOnPage(\ConfigurationManageJ3Page::$URL);
		$I->click(\ConfigurationManageJ3Page::$price);

		$I->click(\ConfigurationManageJ3Page::$countryPrice);
		$I->waitForElement(\ConfigurationManageJ3Page::$countrySearchPrice, 5);
		$I->fillField(\ConfigurationManageJ3Page::$countrySearchPrice, $country);
		$userConfigurationPage = new \ConfigurationManageJ3Page();
		$I->waitForElement($userConfigurationPage->returnChoice($country));
		$I->click($userConfigurationPage->returnChoice($country));

		//get state
		$I->click(\ConfigurationManageJ3Page::$statePrice);
		$I->waitForElement(\ConfigurationManageJ3Page::$stateSearchPrice, 5);
		$I->fillField(\ConfigurationManageJ3Page::$stateSearchPrice, $state);
		$I->waitForElement($userConfigurationPage->returnChoice($state));
		$I->click($userConfigurationPage->returnChoice($state));

		//get default vat
		$I->click(\ConfigurationManageJ3Page::$vatGroup);
		$I->waitForElement(\ConfigurationManageJ3Page::$vatSearchGroup, 5);
		$I->fillField(\ConfigurationManageJ3Page::$vatSearchGroup, $vatDefault);
		$I->waitForElement($userConfigurationPage->returnChoice($vatDefault));
		$I->pressKey(\ConfigurationManageJ3Page::$vatGroup, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);

		//get vat base on
		$I->click(\ConfigurationManageJ3Page::$vatDefaultBase);
		$I->waitForElement(\ConfigurationManageJ3Page::$vatSearchDefaultBase, 5);
		$I->fillField(\ConfigurationManageJ3Page::$vatSearchDefaultBase, $vatCalculation);
		$I->waitForElement($userConfigurationPage->returnChoice($vatCalculation));
		$I->pressKey(\ConfigurationManageJ3Page::$vatDefaultBase, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);

		//apply vat on discount
		switch ($vatAfter) {
			case 'after':
				$I->click(\ConfigurationManageJ3Page::$applyDiscountAfter);
				break;
			case 'before':
				$I->click(\ConfigurationManageJ3Page::$applyDiscountBefore);
				break;
		}

		// value after discount
		$I->fillField(\ConfigurationManageJ3Page::$vatAfterDiscount, $vatNumber);

		//get value calculation based on
		switch ($calculationBase) {
			case 'billing':
				$I->click(\ConfigurationManageJ3Page::$calculationBaseBilling);
				break;
			case 'shipping':
				$I->click(\ConfigurationManageJ3Page::$calculationBaseShipping);
				break;
		}

		//get requi vat yesno

		switch ($requiVAT) {
			case 'yes':
				$I->click(\ConfigurationManageJ3Page::$vatNumberYes);
				break;
			case 'no':
				$I->click(\ConfigurationManageJ3Page::$vatNumberNo);
				break;
		}

		$I->click(\ConfigurationManageJ3Page::$buttonSave);
		$I->see(\ConfigurationManageJ3Page::$namePage, \ConfigurationManageJ3Page::$selectorPageTitle);

	}

	public function cartSetting($addcart, $allowPreOrder, $enableQuation, $cartTimeOut, $enabldAjax, $defaultCart, $buttonCartLead, $onePage, $showShippingCart, $attributeImage, $quantityChange, $quantityInCart, $minimunOrder)
	{
		$I = $this;
		$I->amOnPage(\ConfigurationManageJ3Page::$URL);
		$I->click(\ConfigurationManageJ3Page::$cartCheckout);
		$userConfiguration = new \ConfigurationManageJ3Page();
		switch ($addcart) {
			case 'product':
				$I->click(\ConfigurationManageJ3Page::$addCartProduct);
				break;
			case 'attribute':
				$I->click(\ConfigurationManageJ3Page::$addCartAttibute);
				break;
		}
		switch ($allowPreOrder) {
			case 'yes':
				$I->click(\ConfigurationManageJ3Page::$allowPreOrOderYes);
				break;

			case 'no':
				$I->click(\ConfigurationManageJ3Page::$allowPreorderNo);
				break;
		}
		switch ($enableQuation) {
			case 'yes':
				$I->click(\ConfigurationManageJ3Page::$enableQuotationYes);
				break;
			case 'no':
				$I->click(\ConfigurationManageJ3Page::$enableQuotationNo);
				break;
		}

		$I->fillField(\ConfigurationManageJ3Page::$cartTimeOut, $cartTimeOut);

		switch ($enabldAjax) {
			case 'yes':
				$I->click(\ConfigurationManageJ3Page::$enableAjaxYes);
				break;
			case 'no':
				$I->click(\ConfigurationManageJ3Page::$enableAjaxNo);
				break;
		}
		//choice default cart/checkout item ID
		if ($defaultCart != null) {
			$I->click(\ConfigurationManageJ3Page::$defaultCart);
			$I->waitForElement(\ConfigurationManageJ3Page::$defaultCartSearch, 5);
			$I->fillField(\ConfigurationManageJ3Page::$defaultCartSearch, $defaultCart);
			$I->waitForElement($userConfiguration->returnchoice($defaultCart));
			$I->pressKey(\ConfigurationManageJ3Page::$defaultCart, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);
		}

		//Choice add to cart button lead
		$I->click(\ConfigurationManageJ3Page::$buttonCartLead);
		$I->waitForElement(\ConfigurationManageJ3Page::$buttonCartSearch);
		$I->fillField(\ConfigurationManageJ3Page::$buttonCartSearch, $buttonCartLead);
		$I->waitForElement($userConfiguration->returnchoice($buttonCartLead));
		$I->pressKey(\ConfigurationManageJ3Page::$buttonCartLead, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);

		switch ($onePage) {
			case 'yes':
				$I->click(\ConfigurationManageJ3Page::$onePageYes);
				break;
			case 'no':
				$I->click(\ConfigurationManageJ3Page::$onePageNo);
				break;
		}
		switch ($showShippingCart) {
			case 'yes':
				$I->click(\ConfigurationManageJ3Page::$showShippingCartYes);
				break;
			case 'no':
				$I->click(\ConfigurationManageJ3Page::$showShippingCartNo);
				break;
		}

		switch ($attributeImage) {
			case 'yes':
				$I->click(\ConfigurationManageJ3Page::$attributeImageInCartYes);
				break;
			case 'no':
				$I->click(\ConfigurationManageJ3Page::$attributeImageInCartNo);
				break;
		}
		switch ($quantityChange) {
			case 'yes':
				$I->click(\ConfigurationManageJ3Page::$quantityChangeInCartYes);
				break;
			case 'no':
				$I->click(\ConfigurationManageJ3Page::$quantityChangeInCartNo);
				break;
		}
		$I->fillField(\ConfigurationManageJ3Page::$quantityInCart, $quantityInCart);

		$I->fillField(\ConfigurationManageJ3Page::$minimunOrderTotal, $minimunOrder);
		$I->click(\ConfigurationManageJ3Page::$buttonSave);
		$I->see(\ConfigurationManageJ3Page::$namePage, \ConfigurationManageJ3Page::$selectorPageTitle);
	}

	public function registration($registerMethod, $createNewUser,$emailVerify,$showTerm,$whoCan,$defaultCustomer,$checkoutLogin){
		$I=$this;
		$I->amOnPage(\ConfigurationManageJ3Page::$URL);
		$I->click(\ConfigurationManageJ3Page::$userTab);
		$I->waitForElement(\ConfigurationManageJ3Page::$portalShopNo,30);
		$I->click(\ConfigurationManageJ3Page::$registrationId);
		$I->waitForElement(\ConfigurationManageJ3Page::$registraionSearch,10);
		$I->fillField(\ConfigurationManageJ3Page::$registraionSearch,$registerMethod);
		$I->pressKey(\ConfigurationManageJ3Page::$registrationId, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);
		switch ($createNewUser){
			case 'no':
				$I->click(\ConfigurationManageJ3Page::$createUserNo);
				break;
			case 'yes':
				$I->click(\ConfigurationManageJ3Page::$createUserYes);
				break;
			default: break;
		}

		switch ($emailVerify){
			case 'yes':
				$I->click(\ConfigurationManageJ3Page::$emailVerifyYes);
				break;

			case 'no':
				$I->click(\ConfigurationManageJ3Page::$emailVerifyNo);
				break;
			default:
				break;
		}

		switch ($showTerm){
			case 'perOrder':
				$I->click(\ConfigurationManageJ3Page::$termsShowPerOrder);
				break;
			case 'perUser':
				$I->click(\ConfigurationManageJ3Page::$termShowPerUser);
				break;
			default:
				break;
		}

		$I->click(\ConfigurationManageJ3Page::$whoCanRegister);
		$I->waitForElement(\ConfigurationManageJ3Page::$whoCanRegisterSearch,10);
		$I->fillField(\ConfigurationManageJ3Page::$whoCanRegisterSearch,$whoCan);
		$I->pressKey(\ConfigurationManageJ3Page::$whoCanRegister, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);

		$I->click(\ConfigurationManageJ3Page::$defaultCustomer);
		$I->waitForElement(\ConfigurationManageJ3Page::$defaultCustomerSearch,10);
		$I->fillField(\ConfigurationManageJ3Page::$defaultCustomerSearch,$defaultCustomer);
		$I->pressKey(\ConfigurationManageJ3Page::$defaultCustomer,\Facebook\WebDriver\WebDriverKeys::ARROW_DOWN,\Facebook\WebDriver\WebDriverKeys::ENTER);

		$I->click(\ConfigurationManageJ3Page::$checkoutLogin);
		$I->waitForElement(\ConfigurationManageJ3Page::$checkoutLoginSearch,10);
		$I->fillField(\ConfigurationManageJ3Page::$checkoutLoginSearch,$checkoutLogin);
		$I->pressKey(\ConfigurationManageJ3Page::$checkoutLogin,\Facebook\WebDriver\WebDriverKeys::ARROW_DOWN,\Facebook\WebDriver\WebDriverKeys::ENTER);
		$I->click(\ConfigurationManageJ3Page::$buttonSave);
		$I->see(\ConfigurationManageJ3Page::$namePage, \ConfigurationManageJ3Page::$selectorPageTitle); 
	}

	public function shopperGroups($portal,$privateGroup,$companyGroups,$shopperGroupsUnregistered,$newGroupsInherit){
		$I=$this;
		$I->amOnPage(\ConfigurationManageJ3Page::$URL);
		$I->click(\ConfigurationManageJ3Page::$userTab);
		$I->waitForElement(\ConfigurationManageJ3Page::$portalShopNo,30);
		switch ($portal){
			case 'no':
				$I->click(\ConfigurationManageJ3Page::$portalShopNo);
				break;
			case 'yes':
				$I->click(\ConfigurationManageJ3Page::$portalYes);
				break;
			default:
				break;
		}

		$I->click(\ConfigurationManageJ3Page::$privateShopperGroup);
		$I->waitForElement(\ConfigurationManageJ3Page::$privateShopperGroupSearch,10);
		$I->fillField(\ConfigurationManageJ3Page::$privateShopperGroupSearch,$privateGroup);
		$I->pressKey(\ConfigurationManageJ3Page::$privateShopperGroup,\Facebook\WebDriver\WebDriverKeys::ARROW_DOWN,\Facebook\WebDriver\WebDriverKeys::ENTER);

		$I->click(\ConfigurationManageJ3Page::$companyShopperGroup);
		$I->waitForElement(\ConfigurationManageJ3Page::$companyShopperGroupSearch,10);
		$I->fillField(\ConfigurationManageJ3Page::$companyShopperGroupSearch,$companyGroups);
		$I->pressKey(\ConfigurationManageJ3Page::$companyShopperGroup,\Facebook\WebDriver\WebDriverKeys::ARROW_DOWN,\Facebook\WebDriver\WebDriverKeys::ENTER);

		$I->click(\ConfigurationManageJ3Page::$shopperUnregistered);
		$I->waitForElement(\ConfigurationManageJ3Page::$shopperUnregisteredSearch,10);
		$I->fillField(\ConfigurationManageJ3Page::$shopperUnregisteredSearch,$shopperGroupsUnregistered);
		$I->pressKey(\ConfigurationManageJ3Page::$shopperUnregistered,\Facebook\WebDriver\WebDriverKeys::ARROW_DOWN,\Facebook\WebDriver\WebDriverKeys::ENTER);

		$I->click(\ConfigurationManageJ3Page::$newShopperGroups);
		$I->waitForElement(\ConfigurationManageJ3Page::$newShopperGroupSearch,10);
		$I->fillField(\ConfigurationManageJ3Page::$newShopperGroupSearch,$newGroupsInherit);
		$I->pressKey(\ConfigurationManageJ3Page::$newShopperGroups,\Facebook\WebDriver\WebDriverKeys::ARROW_DOWN,\Facebook\WebDriver\WebDriverKeys::ENTER);

		$I->click(\ConfigurationManageJ3Page::$buttonSave);
		$I->see(\ConfigurationManageJ3Page::$namePage, \ConfigurationManageJ3Page::$selectorPageTitle);
	}
}