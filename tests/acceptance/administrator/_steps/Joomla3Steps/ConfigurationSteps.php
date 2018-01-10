<?php
/**
 * Created by PhpStorm.
 * User: nhung nguyen
 * Date: 5/25/2017
 * Time: 3:51 PM
 */

namespace AcceptanceTester;

class ConfigurationSteps extends AdminManagerJoomla3Steps
{
	public function featureUsedStockRoom()
	{
		$I = $this;
		$I->amOnPage(\ConfigurationPage::$URL);
		$I->click(\ConfigurationPage::$featureSetting);
		$I->waitForElement(\ConfigurationPage::$ratingTab, 60);
		$I->waitForElement(\ConfigurationPage::$stockRoomTab, 60);
		$I->click(\ConfigurationPage::$stockRoomYes);
		$I->click(\ConfigurationPage::$buttonSave);
		$I->waitForElement(\ConfigurationPage::$selectorPageTitle, 60);
		$I->assertSystemMessageContains(\ConfigurationPage::$messageSaveSuccess);
	}

	public function featureOffStockRoom()
	{
		$I = $this;
		$I->amOnPage(\ConfigurationPage::$URL);
		$I->click(\ConfigurationPage::$featureSetting);
		$I->waitForElement(\ConfigurationPage::$ratingTab, 60);
		$I->waitForElement(\ConfigurationPage::$stockRoomTab, 60);
		$I->click(\ConfigurationPage::$stockRoomNo);
		$I->click(\ConfigurationPage::$buttonSave);
		$I->waitForElement(\ConfigurationPage::$selectorPageTitle, 60);
		$I->assertSystemMessageContains(\ConfigurationPage::$messageSaveSuccess);
	}


	public function featureEditInLineYes()
	{
		$I = $this;
		$I->amOnPage(\ConfigurationPage::$URL);
		$I->click(\ConfigurationPage::$featureSetting);
		$I->waitForElement(\ConfigurationPage::$editInline, 60);
		$I->waitForElement(\ConfigurationPage::$stockRoomTab, 60);
		$I->click(\ConfigurationPage::$eidtInLineYes);
		$I->click(\ConfigurationPage::$buttonSave);
		$I->waitForElement(\ConfigurationPage::$selectorPageTitle, 60);
		$I->assertSystemMessageContains(\ConfigurationPage::$messageSaveSuccess);
	}

	public function featureEditInLineNo()
	{
		$I = $this;
		$I->amOnPage(\ConfigurationPage::$URL);
		$I->click(\ConfigurationPage::$featureSetting);
		$I->waitForElement(\ConfigurationPage::$editInline, 60);
		$I->waitForElement(\ConfigurationPage::$stockRoomTab, 60);
		$I->click(\ConfigurationPage::$editInLineNo);
		$I->click(\ConfigurationPage::$buttonSave);
		$I->waitForElement(\ConfigurationPage::$selectorPageTitle, 60);
		$I->assertSystemMessageContains(\ConfigurationPage::$messageSaveSuccess);
	}

	public function featureComparisonNo()
	{
		$I = $this;
		$I->amOnPage(\ConfigurationPage::$URL);
		$I->click(\ConfigurationPage::$featureSetting);
		$I->waitForElement(\ConfigurationPage::$comparisonTab, 60);
		$I->waitForElement(\ConfigurationPage::$stockRoomTab, 60);
		$I->click(\ConfigurationPage::$comparisonNo);
		$I->click(\ConfigurationPage::$buttonSave);
		$I->waitForElement(\ConfigurationPage::$selectorPageTitle, 60);
		$I->assertSystemMessageContains(\ConfigurationPage::$messageSaveSuccess);
	}

	public function featureComparisonYes()
	{
		$I = $this;
		$I->amOnPage(\ConfigurationPage::$URL);
		$I->click(\ConfigurationPage::$featureSetting);
		$I->waitForElement(\ConfigurationPage::$comparisonTab, 60);
		$I->waitForElement(\ConfigurationPage::$stockRoomTab, 60);
		$I->click(\ConfigurationPage::$comparisonYes);
		$I->click(\ConfigurationPage::$buttonSave);
		$I->waitForElement(\ConfigurationPage::$selectorPageTitle, 60);
		$I->assertSystemMessageContains(\ConfigurationPage::$messageSaveSuccess);
	}

	public function featurePriceNo()
	{
		$I = $this;
		$I->amOnPage(\ConfigurationPage::$URL);
		$I->click(\ConfigurationPage::$price);
		$I->waitForElement(\ConfigurationPage::$priceTab, 60);
		$I->click(\ConfigurationPage::$showPriceNo);
		$I->click(\ConfigurationPage::$buttonSave);
		$I->waitForElement(\ConfigurationPage::$selectorPageTitle, 60);
		$I->assertSystemMessageContains(\ConfigurationPage::$messageSaveSuccess);
	}

	public function featurePriceYes()
	{
		$I = $this;
		$I->amOnPage(\ConfigurationPage::$URL);
		$I->click(\ConfigurationPage::$price);
		$I->waitForElement(\ConfigurationPage::$priceTab, 60);
		$I->click(\ConfigurationPage::$showPriceYes);
		$I->click(\ConfigurationPage::$buttonSave);
		$I->waitForElement(\ConfigurationPage::$selectorPageTitle, 60);
		$I->assertSystemMessageContains(\ConfigurationPage::$messageSaveSuccess);
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
		$I->amOnPage(\ConfigurationPage::$URL);
		$I->click(\ConfigurationPage::$price);

		$I->click(\ConfigurationPage::$countryPrice);
		$I->waitForElement(\ConfigurationPage::$countrySearchPrice, 5);
		$I->fillField(\ConfigurationPage::$countrySearchPrice, $country);
		$userConfigurationPage = new \ConfigurationPage();
		$I->waitForElement($userConfigurationPage->returnChoice($country));
		$I->click($userConfigurationPage->returnChoice($country));

		//get state
		$I->click(\ConfigurationPage::$statePrice);
		$I->waitForElement(\ConfigurationPage::$stateSearchPrice, 5);
		$I->fillField(\ConfigurationPage::$stateSearchPrice, $state);
		$I->waitForElement($userConfigurationPage->returnChoice($state));
		$I->click($userConfigurationPage->returnChoice($state));

		//get default vat
		$I->click(\ConfigurationPage::$vatGroup);
		$I->waitForElement(\ConfigurationPage::$vatSearchGroup, 5);
		$I->fillField(\ConfigurationPage::$vatSearchGroup, $vatDefault);
		$I->waitForElement($userConfigurationPage->returnChoice($vatDefault));
		$I->click(\ConfigurationPage::$varFirstResults);

		//get vat base on
		$I->click(\ConfigurationPage::$vatDefaultBase);
		$I->waitForElement(\ConfigurationPage::$vatSearchDefaultBase, 5);
		$I->fillField(\ConfigurationPage::$vatSearchDefaultBase, $vatCalculation);
		$I->waitForElement($userConfigurationPage->returnChoice($vatCalculation));
		$I->click(\ConfigurationPage::$searchDefaultFirstResult);

		//apply vat on discount
		switch ($vatAfter)
		{
			case 'after':
				$I->click(\ConfigurationPage::$applyDiscountAfter);
				break;
			case 'before':
				$I->click(\ConfigurationPage::$applyDiscountBefore);
				break;
		}

		// value after discount
		$I->fillField(\ConfigurationPage::$vatAfterDiscount, $vatNumber);

		//get value calculation based on
		switch ($calculationBase)
		{
			case 'billing':
				$I->click(\ConfigurationPage::$calculationBaseBilling);
				break;
			case 'shipping':
				$I->click(\ConfigurationPage::$calculationBaseShipping);
				break;
		}

		//get requi vat yesno

		switch ($requiVAT)
		{
			case 'yes':
				$I->click(\ConfigurationPage::$vatNumberYes);
				break;
			case 'no':
				$I->click(\ConfigurationPage::$vatNumberNo);
				break;
		}

		$I->click(\ConfigurationPage::$buttonSave);
		$I->waitForElement(\ConfigurationPage::$selectorPageTitle, 60);
		$I->assertSystemMessageContains(\ConfigurationPage::$messageSaveSuccess);
	}

	public function cartSetting($addcart, $allowPreOrder, $enableQuation, $cartTimeOut, $enabldAjax, $defaultCart, $buttonCartLead, $onePage, $showShippingCart, $attributeImage, $quantityChange, $quantityInCart, $minimunOrder)
	{
		$I = $this;
		$I->amOnPage(\ConfigurationPage::$URL);
		$I->click(\ConfigurationPage::$cartCheckout);
		$userConfiguration = new \ConfigurationPage();
		switch ($addcart)
		{
			case 'product':
				$I->click(\ConfigurationPage::$addCartProduct);
				break;
			case 'attribute':
				$I->click(\ConfigurationPage::$addCartAttibute);
				break;
		}
		switch ($allowPreOrder)
		{
			case 'yes':
				$I->click(\ConfigurationPage::$allowPreOrOderYes);
				break;

			case 'no':
				$I->click(\ConfigurationPage::$allowPreorderNo);
				break;
		}
		switch ($enableQuation)
		{
			case 'yes':
				$I->click(\ConfigurationPage::$enableQuotationYes);
				break;
			case 'no':
				$I->click(\ConfigurationPage::$enableQuotationNo);
				break;
		}

		$I->fillField(\ConfigurationPage::$cartTimeOut, $cartTimeOut);

		switch ($enabldAjax)
		{
			case 'yes':
				$I->click(\ConfigurationPage::$enableAjaxYes);
				break;
			case 'no':
				$I->click(\ConfigurationPage::$enableAjaxNo);
				break;
		}
		//choice default cart/checkout item ID
		if ($defaultCart != null)
		{
			$I->click(\ConfigurationPage::$defaultCart);
			$I->waitForElement(\ConfigurationPage::$defaultCartSearch, 5);
			$I->fillField(\ConfigurationPage::$defaultCartSearch, $defaultCart);
			$I->waitForElement($userConfiguration->returnChoice($defaultCart));
			$I->click($userConfiguration->returnChoice($defaultCart));
		}

		//Choice add to cart button lead
		$I->click(\ConfigurationPage::$buttonCartLead);
		$I->waitForElement(\ConfigurationPage::$buttonCartSearch);
		$I->fillField(\ConfigurationPage::$buttonCartSearch, $buttonCartLead);
		$I->waitForElement($userConfiguration->returnChoice($buttonCartLead));
		$I->click(\ConfigurationPage::$firstCartSearch);

		switch ($onePage)
		{
			case 'yes':
				$I->click(\ConfigurationPage::$onePageYes);
				break;
			case 'no':
				$I->click(\ConfigurationPage::$onePageNo);
				break;
		}

		switch ($showShippingCart)
		{
			case 'yes':
				$I->click(\ConfigurationPage::$showShippingCartYes);
				break;
			case 'no':
				$I->click(\ConfigurationPage::$showShippingCartNo);
				break;
		}

		switch ($attributeImage)
		{
			case 'yes':
				$I->click(\ConfigurationPage::$attributeImageInCartYes);
				break;
			case 'no':
				$I->click(\ConfigurationPage::$attributeImageInCartNo);
				break;
		}

		switch ($quantityChange)
		{
			case 'yes':
				$I->click(\ConfigurationPage::$quantityChangeInCartYes);
				break;
			case 'no':
				$I->click(\ConfigurationPage::$quantityChangeInCartNo);
				break;
		}
		$I->fillField(\ConfigurationPage::$quantityInCart, $quantityInCart);

		$I->fillField(\ConfigurationPage::$minimunOrderTotal, $minimunOrder);
		$I->click(\ConfigurationPage::$buttonSave);
		$I->waitForElement(\ConfigurationPage::$selectorPageTitle, 60);
		$I->assertSystemMessageContains(\ConfigurationPage::$messageSaveSuccess);
	}
}