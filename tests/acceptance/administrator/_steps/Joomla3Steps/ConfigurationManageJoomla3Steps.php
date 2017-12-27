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
		$I->waitForElement($userConfigurationPage->returnChoice($country), 30);
		$I->click($userConfigurationPage->returnChoice($country));

		//get state
		$I->click(\ConfigurationManageJ3Page::$statePrice);
		$I->waitForElement(\ConfigurationManageJ3Page::$stateSearchPrice, 5);
		$I->fillField(\ConfigurationManageJ3Page::$stateSearchPrice, $state);
		$I->waitForElement($userConfigurationPage->returnChoice($state),30);
		$I->click($userConfigurationPage->returnChoice($state));

		//get default vat
		$I->click(\ConfigurationManageJ3Page::$vatGroup);
		$I->waitForElement(\ConfigurationManageJ3Page::$vatSearchGroup, 5);
		$I->fillField(\ConfigurationManageJ3Page::$vatSearchGroup, $vatDefault);
		$I->waitForElement($userConfigurationPage->returnChoice($vatDefault),30);
		$I->click(\ConfigurationManageJ3Page::$varFirstResults);

		//get vat base on
		$I->click(\ConfigurationManageJ3Page::$vatDefaultBase);
		$I->waitForElement(\ConfigurationManageJ3Page::$vatSearchDefaultBase, 5);
		$I->fillField(\ConfigurationManageJ3Page::$vatSearchDefaultBase, $vatCalculation);
		$I->waitForElement($userConfigurationPage->returnChoice($vatCalculation),30);
		$I->click(\ConfigurationManageJ3Page::$searchDefaultFirstResult);

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
			$I->waitForElement($userConfiguration->returnChoice($defaultCart));
			$I->click($userConfiguration->returnChoice($defaultCart));
		}

		//Choice add to cart button lead
		$I->click(\ConfigurationManageJ3Page::$buttonCartLead);
		$I->waitForElement(\ConfigurationManageJ3Page::$buttonCartSearch);
		$I->fillField(\ConfigurationManageJ3Page::$buttonCartSearch, $buttonCartLead);
		$I->waitForElement($userConfiguration->returnChoice($buttonCartLead));
		$I->click(\ConfigurationManageJ3Page::$firstCartSearch);

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

	public function priceDiscount($discount = array())
	{
		$I = $this;
		$I->amOnPage(\ConfigurationManageJ3Page::$URL);
		$I->click(\ConfigurationManageJ3Page::$price);
		$userConfiguration = new \ConfigurationManageJ3Page();

		if(isset($discount['enable']))
		{
			if ($discount['enable'] == 'yes')
			{
				if(isset($discount['allow']))
				{
					$I->click(\ConfigurationManageJ3Page::$allowedDiscountId);
					$I->waitForElement(\ConfigurationManageJ3Page::$allowDiscountSearch, 30);
					$I->fillField(\ConfigurationManageJ3Page::$allowDiscountSearch, $discount['allow']);
					$I->waitForElement($userConfiguration->returnChoice($discount['allow']), 30);
					$I->click($userConfiguration->returnChoice($discount['allow']));
				}

				if (isset($discount['enableCoupon']))
				{
					if ($discount['enableCoupon'] == 'yes')
					{
						$I->click(\ConfigurationManageJ3Page::$enableCouponYes);
					}else{
						$I->click(\ConfigurationManageJ3Page::$enableCouponNo);
					}
				}

				if (isset($discount['couponInfo']))
				{
					if ($discount['couponInfo'] == 'yes')
					{
						$I->click(\ConfigurationManageJ3Page::$enableCouponInfoYes);
					}else{
						$I->click(\ConfigurationManageJ3Page::$enableCouponInfoNo);
					}
				}

				if(isset($discount['enableVoucher']))
				{
					if ($discount['enableVoucher'] == 'yes')
					{
						$I->click(\ConfigurationManageJ3Page::$enableVoucherYes);
					}else{
						$I->click(\ConfigurationManageJ3Page::$enableVoucherNo);
					}
				}


				if(isset($discount['spendTime']))
				{
					if ($discount['spendTime'] == 'yes')
					{
						$I->click(\ConfigurationManageJ3Page::$spendTimeDiscountYes);
					}else{
						$I->click(\ConfigurationManageJ3Page::$spendTimeDiscountNo);
					}
				}

				if(isset($discount['applyForProductDiscount']))
				{
					if ($discount['applyForProductDiscount'] == 'yes')
					{
						$I->click(\ConfigurationManageJ3Page::$applyDiscountForProductAlreadyDiscountYes);
					}else{
						$I->click(\ConfigurationManageJ3Page::$applyDiscountForProductAlreadyDiscountNo);
					}
				}

				if(isset($discount['calculate']))
				{
					if ($discount['calculate'] == 'total')
					{
						$I->click(\ConfigurationManageJ3Page::$calculateShippingBasedTotal);
					}else{
						$I->click(\ConfigurationManageJ3Page::$calculateShippingBasedSubTotal);
					}
				}

				if(isset($discount['valueOfDiscount']))
				{
					$I->click(\ConfigurationManageJ3Page::$valueDiscountCouponId);
					$I->waitForElement(\ConfigurationManageJ3Page::$valueDiscountCouponSearch, 30);
					$I->fillField(\ConfigurationManageJ3Page::$valueDiscountCouponSearch, $discount['valueOfDiscount']);
					$I->waitForElement($userConfiguration->returnChoice($discount['valueOfDiscount']), 30);
					$I->pressKey(\ConfigurationManageJ3Page::$valueDiscountCouponSearch, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);
				}
			}
		}
		$I->click(\ConfigurationManageJ3Page::$buttonSave);
		$I->see(\ConfigurationManageJ3Page::$namePage, \ConfigurationManageJ3Page::$selectorPageTitle);
	}

}