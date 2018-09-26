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
		$I->executeJS("jQuery('Yes').click()");
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
		$I->executeJS("jQuery('No').click()");
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
		$I->executeJS("jQuery('Yes').click()");
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
		$I->executeJS("jQuery('No').click()");
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
		$I->executeJS("jQuery('No').click()");
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
		$I->executeJS("jQuery('Yes').click()");
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
		$I->executeJS("jQuery('No').click()");
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
		$I->executeJS("jQuery('Yes').click()");
		$I->click(\ConfigurationPage::$buttonSave);
		$I->waitForElement(\ConfigurationPage::$selectorPageTitle, 60);
		$I->assertSystemMessageContains(\ConfigurationPage::$messageSaveSuccess);
	}

	/**
	 * @param   string $country
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
		if ($country == 'Denmark')
		{
			$I->pressKey(\ConfigurationPage::$countrySearchPrice, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);
		}
		else{
			$I->click($userConfigurationPage->returnChoice($country));
		}

		// Get state
		if (isset($state))
		{
			$I->click(\ConfigurationPage::$statePrice);
			$I->waitForElement(\ConfigurationPage::$stateSearchPrice, 5);
			$I->fillField(\ConfigurationPage::$stateSearchPrice, $state);
			$I->waitForElement($userConfigurationPage->returnChoice($state));
			$I->click($userConfigurationPage->returnChoice($state));
		}
		
		// Get default vat
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
				$I->waitForElement(\ConfigurationPage::$applyDiscountAfter, 5);
				$I->executeJS("jQuery('After').click()");
				break;
			case 'before':
				$I->waitForElement(\ConfigurationPage::$applyDiscountBefore,5);
				$I->executeJS("jQuery('Before').click()");
				break;
		}

		// value after discount
		$I->fillField(\ConfigurationPage::$vatAfterDiscount, $vatNumber);

		//get value calculation based on
		switch ($calculationBase)
		{
			case 'billing':
				$I->waitForElement(\ConfigurationPage::$calculationBaseBilling,5);
				$I->executeJS("jQuery('Billing address').click()");
				break;
			case 'shipping':
				$I->waitForElement(\ConfigurationPage::$calculationBaseShipping,5);
				$I->executeJS("jQuery('Shipping address').click()");
				break;
		}

		//get requi vat yesno

		switch ($requiVAT)
		{
			case 'yes':
				$I->waitForElement(\ConfigurationPage::$vatNumberYes, 5);
				$I->executeJS("jQuery('Yes').click()");
				break;
			case 'no':
				$I->waitForElement(\ConfigurationPage::$vatNumberNo, 5);
				$I->executeJS("jQuery('No').click()");
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
				$I->waitForElement(\ConfigurationPage::$addCartProduct, 5);
				$I->executeJS("jQuery('Add to cart per product').click()");
				break;
			case 'attribute':
				$I->waitForElement(\ConfigurationPage::$addCartAttibute, 5);
				$I->executeJS("jQuery('Add to cart per attribute').click()");
				break;
		}
		switch ($allowPreOrder)
		{
			case 'yes':
				$I->waitForElement(\ConfigurationPage::$allowPreOrOderYes, 5);
				$I->executeJS("jQuery('Yes').click()");
				break;

			case 'no':
				$I->waitForElement(\ConfigurationPage::$allowPreorderNo, 5);
				$I->executeJS("jQuery('No').click()");
				break;
		}
		switch ($enableQuation)
		{
			case 'yes':
				$I->waitForElement(\ConfigurationPage::$enableQuotationYes, 5);
				$I->executeJS("jQuery('Yes').click()");
				break;
			case 'no':
				$I->waitForElement(\ConfigurationPage::$enableQuotationNo, 5);
				$I->executeJS("jQuery('No').click()");
				break;
		}

		$I->fillField(\ConfigurationPage::$cartTimeOut, $cartTimeOut);

		switch ($enabldAjax)
		{
			case 'yes':
				$I->waitForElement(\ConfigurationPage::$enableAjaxYes, 5);
				$I->executeJS("jQuery('Yes').click()");
				break;
			case 'no':
				$I->waitForElement(\ConfigurationPage::$enableAjaxNo, 5);
				$I->executeJS("jQuery('No').click()");
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
				$I->waitForElement(\ConfigurationPage::$onePageYes,5);
				$I->executeJS("jQuery('Yes').click()");
				break;
			case 'no':
				$I->waitForElement(\ConfigurationPage::$onePageNo,5);
				$I->executeJS("jQuery('No').click()");
				break;
		}

		switch ($showShippingCart)
		{
			case 'yes':
				$I->waitForElement(\ConfigurationPage::$showShippingCartYes,5);
				$I->executeJS("jQuery('Yes').click()");
				break;
			case 'no':
				$I->waitForElement(\ConfigurationPage::$showShippingCartNo,5);
				$I->executeJS("jQuery('No').click()");
				break;
		}

		switch ($attributeImage)
		{
			case 'yes':
				$I->waitForElement(\ConfigurationPage::$attributeImageInCartYes, 5);
				$I->executeJS("jQuery('Yes').click()");
				break;
			case 'no':
				$I->waitForElement(\ConfigurationPage::$attributeImageInCartNo,5);
				$I->executeJS("jQuery('No').click()");
				break;
		}

		switch ($quantityChange)
		{
			case 'yes':
				$I->waitForElement(\ConfigurationPage::$quantityChangeInCartYes, 5);
				$I->executeJS("jQuery('Yes').click()");
				break;
			case 'no':
				$I->waitForElement(\ConfigurationPage::$quantityChangeInCartNo,5);
				$I->executeJS("jQuery('No').click()");
				break;
		}
		$I->fillField(\ConfigurationPage::$quantityInCart, $quantityInCart);

		$I->fillField(\ConfigurationPage::$minimunOrderTotal, $minimunOrder);
		$I->click(\ConfigurationPage::$buttonSave);
		$I->waitForElement(\ConfigurationPage::$selectorPageTitle, 60);
		$I->assertSystemMessageContains(\ConfigurationPage::$messageSaveSuccess);
	}

	// Enable Quantity in Configuration (Allow user change quantity product in checkout page)
	public function configChangeQuantityProduct($quantity ='3')
	{
		$I = $this;
		$I->amOnPage(\ConfigurationPage::$URL);
		$I->click(\ConfigurationPage::$cartCheckout);
		$I->click(\ConfigurationPage::$onePageYes);
		$I->waitForElement(\ConfigurationPage::$quantityChangeInCartYes, 30);
		$I->click(\ConfigurationPage::$quantityChangeInCartYes);
		$I->click(\ConfigurationPage::$quantityInCart);
		$I->fillField(\ConfigurationPage::$quantityInCart, $quantity) ;
		$I->click(\ConfigurationPage::$showSameAddressForBillingYes);
		$I->click(\ConfigurationPage::$buttonSave);
	}
	// Disable Quantity in Configuration (Not allow user change quantity when checkout)
	public function returnConfigChangeQuantityProduct()
	{
		$I = $this;
		$I->amOnPage(\ConfigurationPage::$URL);
		$I->click(\ConfigurationPage::$cartCheckout);
		$I->click(\ConfigurationPage::$onePageNo);
		$I->waitForElement(\ConfigurationPage::$quantityChangeInCartNo, 30);
		$I->click(\ConfigurationPage::$quantityChangeInCartNo);
		$I->click(\ConfigurationPage::$showSameAddressForBillingNo);
		$I->click(\ConfigurationPage::$buttonSave);
	}

	public function priceDiscount($discount = array())
	{
		$I = $this;
		$I->amOnPage(\ConfigurationPage::$URL);
		$I->click(\ConfigurationPage::$price);
		$userConfiguration = new \ConfigurationPage();

		if(isset($discount['enable']))
		{
			if ($discount['enable'] == 'yes')
			{
				if(isset($discount['allow']))
				{
					$I->click(\ConfigurationPage::$allowedDiscountId);
					$I->waitForElement(\ConfigurationPage::$allowDiscountSearch, 30);
					$I->fillField(\ConfigurationPage::$allowDiscountSearch, $discount['allow']);
					$I->waitForElement($userConfiguration->returnChoice($discount['allow']));
					$I->pressKey(\ConfigurationPage::$allowDiscountSearch, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);
				}

				if (isset($discount['enableCoupon']))
				{
					if ($discount['enableCoupon'] == 'yes')
					{
						$I->waitForElement(\ConfigurationPage::$enableCouponYes,5);
						$I->executeJS("jQuery('Yes').click()");
					}else{
						$I->waitForElement(\ConfigurationPage::$enableCouponNo,5);
						$I->executeJS("jQuery('No').click()");
					}
				}

				if (isset($discount['couponInfo']))
				{
					if ($discount['couponInfo'] == 'yes')
					{
						$I->waitForElement(\ConfigurationPage::$enableCouponInfoYes,5);
						$I->executeJS("jQuery('Yes').click()");
					}else{
						$I->waitForElement(\ConfigurationPage::$enableCouponInfoNo,5);
						$I->executeJS("jQuery('No').click()");
					}
				}

				if(isset($discount['enableVoucher']))
				{
					if ($discount['enableVoucher'] == 'yes')
					{
						$I->waitForElement(\ConfigurationPage::$enableVoucherYes,5);
						$I->executeJS("jQuery('Yes').click()");
					}else{
						$I->waitForElement(\ConfigurationPage::$enableVoucherNo,5);
						$I->executeJS("jQuery('No').click()");
					}
				}


				if(isset($discount['spendTime']))
				{
					if ($discount['spendTime'] == 'yes')
					{
						$I->waitForElement(\ConfigurationPage::$spendTimeDiscountYes,5);
						$I->executeJS("jQuery('Yes').click()");
					}else{
						$I->waitForElement(\ConfigurationPage::$spendTimeDiscountNo,5);
						$I->executeJS("jQuery('No').click()");
					}
				}

				if(isset($discount['applyForProductDiscount']))
				{
					if ($discount['applyForProductDiscount'] == 'yes')
					{
						$I->waitForElement(\ConfigurationPage::$applyDiscountForProductAlreadyDiscountYes,5);
						$I->executeJS("jQuery('Yes').click()");
					}else{
						$I->waitForElement(\ConfigurationPage::$applyDiscountForProductAlreadyDiscountNo,5);
						$I->executeJS("jQuery('No').click()");
					}
				}

				if(isset($discount['calculate']))
				{
					if ($discount['calculate'] == 'total')
					{
						$I->waitForElement(\ConfigurationPage::$calculateShippingBasedTotal, 5);
						$I->executeJS("jQuery('Total').click()");
					}else{
						$I->waitForElement(\ConfigurationPage::$calculateShippingBasedSubTotal,5);
						$I->executeJS("jQuery('Subtotal').click()");
					}
				}

				if(isset($discount['valueOfDiscount']))
				{
					$I->click(\ConfigurationPage::$valueDiscountCouponId);
					$I->waitForElement(\ConfigurationPage::$valueDiscountCouponSearch, 30);
					$I->fillField(\ConfigurationPage::$valueDiscountCouponSearch, $discount['valueOfDiscount']);
					$I->waitForElement($userConfiguration->returnChoice($discount['valueOfDiscount']));
//					$I->click($userConfiguration->returnChoice($discount['valueOfDiscount']));
					$I->pressKey(\ConfigurationPage::$valueDiscountCouponSearch, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);
				}
			}
		}
		$I->click(\ConfigurationPage::$buttonSave);
		$I->see(\ConfigurationPage::$namePage, \ConfigurationPage::$selectorPageTitle);
	}
	/**
	 * @param $name
	 */
	public function searchOrder($name)
	{
		$I = $this;
		$I->wantTo('Search the User ');
		$I->amOnPage(\OrderManagerPage::$URL);
		$I->filterListBySearchOrder($name, \OrderManagerPage::$filter);
	}
	/**
	 * @param $price
	 * @param $order
	 */
	public function checkPriceTotal($price, $order, $firstName, $lastName, $productName, $categoryName, $paymentMethod)
	{
		$I = $this;
		$I->amOnPage(\ConfigurationPage::$URL);
		$currencySymbol = $I->grabValueFrom(\ConfigurationPage::$currencySymbol);
		$decimalSeparator = $I->grabValueFrom(\ConfigurationPage::$decimalSeparator);
		$numberOfPriceDecimals = $I->grabValueFrom(\ConfigurationPage::$numberOfPriceDecimals);
		$numberOfPriceDecimals = (int)$numberOfPriceDecimals;
		$NumberZero = null;
		for  ( $b = 1; $b <= $numberOfPriceDecimals; $b++)
		{
			$NumberZero = $NumberZero."0";
		}
		$I->amOnPage(\OrderManagerPage::$URL);
		$I->searchOrder($order);
		$I->click(\OrderManagerPage::$iconEdit);
		$quantity = $I->grabValueFrom(\OrderManagerPage::$quantityp1);
		$quantity = (int)$quantity;
		$priceProduct = $currencySymbol.' '.$price.$decimalSeparator.$NumberZero;
		$priceTotal = 'Total: '.$currencySymbol.' '.$price*$quantity.$decimalSeparator.$NumberZero;
		$firstName = 'First Name: '.$firstName;
		$lastName = 'Last Name: '.$lastName;
		$I->see($firstName);
		$I->see($lastName);
		$I->see($paymentMethod);
		$I->see($productName);
		$I->see($categoryName);
		$I->see($priceProduct);
		$I->see($priceTotal);
	}
}