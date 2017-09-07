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

	public function manufactureSetting($manufactureDefault,$manufactureSorting,$manufactureDefaultSorting,$titleDescription,$titleSuffix,$enableMailManufacture,$enableMailSupplier){
		$I = $this;
		$I->amOnPage(\ConfigurationManageJ3Page::$URL);
		$I->click(\ConfigurationManageJ3Page::$manufactureTab);

		if ($manufactureDefault==""){

		}else{
			$I->click(\ConfigurationManageJ3Page::$manufactureDefault);
			$I->waitForElement(\ConfigurationManageJ3Page::$manufactureDefaultSearch,30);
			$I->fillField(\ConfigurationManageJ3Page::$manufactureDefaultSearch,$manufactureDefault);
			$I->pressKey(\ConfigurationManageJ3Page::$manufactureDefault, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);
		}


		$I->click(\ConfigurationManageJ3Page::$manufactureSorting);
		$I->waitForElement(\ConfigurationManageJ3Page::$manufactureSortingSearch,30);
		$I->fillField(\ConfigurationManageJ3Page::$manufactureSortingSearch,$manufactureSorting);
		$I->pressKey(\ConfigurationManageJ3Page::$manufactureSorting, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);


		$I->click(\ConfigurationManageJ3Page::$manufactureDefaultSorting);
		$I->waitForElement(\ConfigurationManageJ3Page::$manufactureDefaultSortingSearch,30);
		$I->fillField(\ConfigurationManageJ3Page::$manufactureDefaultSortingSearch,$manufactureDefaultSorting);
		$I->pressKey(\ConfigurationManageJ3Page::$manufactureDefaultSorting, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);


		$I->fillField(\ConfigurationManageJ3Page::$titleDescription,$titleDescription);
		$I->fillField(\ConfigurationManageJ3Page::$titleSuffix,$titleSuffix);

		if ($enableMailManufacture=='yes'){
			$I->click(\ConfigurationManageJ3Page::$enableMailManufactureYes);
		}else{
			$I->click(\ConfigurationManageJ3Page::$enableMailManufactureNo);
		}

		if ($enableMailSupplier=='yes'){
			$I->click(\ConfigurationManageJ3Page::$enableMailSupplierYes);
		}else{
			$I->click(\ConfigurationManageJ3Page::$enableMailSupplierNo);
		}

		$I->click(\ConfigurationManageJ3Page::$buttonSave);
		$I->see(\ConfigurationManageJ3Page::$namePage, \ConfigurationManageJ3Page::$selectorPageTitle);
	}


	public function manufactureImage($enableWatermark, $enableWatermarkProduct, $manufactureThumbWeight,$manufactureThumbHeight,$manufactureThumbTwoWeight,$manufactureThumbTwoHeight
	,$manufactureThumbThreeWeight, $manufactureThumbThreeHeight,$manufactureThumbProductWeight,$manufactureThumbProductHeight){
		$I = $this;
		$I->amOnPage(\ConfigurationManageJ3Page::$URL);
		$I->click(\ConfigurationManageJ3Page::$manufactureTab);

		if ($enableWatermark=='yes'){
			$I->click(\ConfigurationManageJ3Page::$watermarkYes);
		}else{
			$I->click(\ConfigurationManageJ3Page::$watermarkNo);
		}

		if ($enableWatermarkProduct=='yes'){
			$I->click(\ConfigurationManageJ3Page::$watermarkThumbYes);
		}else{
			$I->click(\ConfigurationManageJ3Page::$watermarkThumbNo);
		}

		$I->fillField(\ConfigurationManageJ3Page::$manufactureThumbWeight,$manufactureThumbWeight);
		$I->fillField(\ConfigurationManageJ3Page::$manufactureThumbHeight,$manufactureThumbHeight);
		$I->fillField(\ConfigurationManageJ3Page::$manufactureThumbTwoWeight,$manufactureThumbTwoWeight);
		$I->fillField(\ConfigurationManageJ3Page::$manufactureThumbTwoHeight,$manufactureThumbTwoHeight);
		$I->fillField(\ConfigurationManageJ3Page::$manufactureThumbThreeWeight,$manufactureThumbThreeWeight);
		$I->fillField(\ConfigurationManageJ3Page::$manufactureThumbThreeHeight,$manufactureThumbThreeHeight);
		$I->fillField(\ConfigurationManageJ3Page::$manufactureThumbProductWeight,$manufactureThumbProductWeight);
		$I->fillField(\ConfigurationManageJ3Page::$manufactureThumbProductHeight,$manufactureThumbProductHeight);

		$I->click(\ConfigurationManageJ3Page::$buttonSave);
		$I->see(\ConfigurationManageJ3Page::$namePage, \ConfigurationManageJ3Page::$selectorPageTitle);

	}

}