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
		$I->executeJS("jQuery('#use_stockroom1-lbl').click()");
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
		$I->executeJS("jQuery('#use_stockroom0-lbl').click()");
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
		$I->executeJS("jQuery('#inline_editing1-lbl').click()");
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
		$I->executeJS("jQuery('#inline_editing0-lbl').click()");
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
		$I->executeJS("jQuery('#inline_editing0-lbl').click()");
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
		$I->executeJS("jQuery('#compare_products1-lbl').click()");
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
		$I->executeJS("jQuery('#show_price0-lbl').click()");
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
		$I->executeJS("jQuery('#show_price1-lbl').click()");
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
				$I->executeJS("jQuery('#apply_vat_on_discount0-lbl').click()");
				break;
			case 'before':
				$I->executeJS("jQuery('#apply_vat_on_discount1-lbl').click()");
				break;
		}

		// value after discount
		$I->fillField(\ConfigurationPage::$vatAfterDiscount, $vatNumber);

		//get value calculation based on
		switch ($calculationBase)
		{
			case 'billing':
				$I->executeJS("jQuery('#calculate_vat_onBT-lbl').click()");
				break;
			case 'shipping':
				$I->executeJS("jQuery('#calculate_vat_onST-lbl').click()");
				break;
		}

		//get requi vat yesno

		switch ($requiVAT)
		{
			case 'yes':
				$I->executeJS("jQuery('#required_vat_number1-lbl').click()");
				break;
			case 'no':
				$I->executeJS("jQuery('#required_vat_number0-lbl').click()");
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
				$I->executeJS("jQuery('#individual_add_to_cart_enable0-lbl').click()");
				break;
			case 'attribute':
				$I->executeJS("jQuery('#individual_add_to_cart_enable1-lbl').click()");
				break;
		}
		switch ($allowPreOrder)
		{
			case 'yes':
				$I->executeJS("jQuery('#allow_pre_order1-lbl').click()");
				break;

			case 'no':
				$I->executeJS("jQuery('#allow_pre_order0-lbl').click()");
				break;
		}
		switch ($enableQuation)
		{
			case 'yes':
				$I->executeJS("jQuery('#default_quotation_mode1-lbl').click()");
				break;
			case 'no':
				$I->executeJS("jQuery('#default_quotation_mode0-lbl').click()");
				break;
		}

		$I->fillField(\ConfigurationPage::$cartTimeOut, $cartTimeOut);

		switch ($enabldAjax)
		{
			case 'yes':
				$I->executeJS("jQuery('#ajax_cart_box1-lbl').click()");
				break;
			case 'no':
				$I->executeJS("jQuery('#ajax_cart_box0-lbl').click()");
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
				$I->executeJS("jQuery('#onestep_checkout_enable1-lbl').click()");
				break;
			case 'no':
				$I->executeJS("jQuery('#onestep_checkout_enable0-lbl').click()");
				break;
		}

		switch ($showShippingCart)
		{
			case 'yes':
				$I->executeJS("jQuery('#show_shipping_in_cart1-lbl').click()");
				break;
			case 'no':
				$I->executeJS("jQuery('#show_shipping_in_cart0-lbl').click()");
				break;
		}

		switch ($attributeImage)
		{
			case 'yes':
				$I->executeJS("jQuery('#wanttoshowattributeimage1-lbl').click()");
				break;
			case 'no':
				$I->executeJS("jQuery('#wanttoshowattributeimage0-lbl').click()");
				break;
		}

		switch ($quantityChange)
		{
			case 'yes':
				$I->executeJS("jQuery('#quantity_text_display1-lbl').click()");
				break;
			case 'no':
				$I->executeJS("jQuery('#quantity_text_display0-lbl').click()");
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
						$I->executeJS("jQuery('#coupons_enable1-lbl').click()");
					}else{
						$I->executeJS("jQuery('#coupons_enable0-lbl').click()");
					}
				}

				if (isset($discount['couponInfo']))
				{
					if ($discount['couponInfo'] == 'yes')
					{
						$I->executeJS("jQuery('#couponinfo1-lbl').click()");
					}else{
						$I->executeJS("jQuery('#couponinfo0-lbl').click()");
					}
				}

				if(isset($discount['enableVoucher']))
				{
					if ($discount['enableVoucher'] == 'yes')
					{
						$I->executeJS("jQuery('#vouchers_enable1-lbl').click()");
					}else{
						$I->executeJS("jQuery('#vouchers_enable0-lbl').click()");
					}
				}


				if(isset($discount['spendTime']))
				{
					if ($discount['spendTime'] == 'yes')
					{
						$I->executeJS("jQuery('#special_discount_mail_send1-lbl').click()");
					}else{
						$I->executeJS("jQuery('#special_discount_mail_send0-lbl').click()");
					}
				}

				if(isset($discount['applyForProductDiscount']))
				{
					if ($discount['applyForProductDiscount'] == 'yes')
					{
						$I->executeJS("jQuery('#apply_voucher_coupon_already_discount1-lbl').click()");
					}else{
						$I->executeJS("jQuery('#apply_voucher_coupon_already_discount0-lbl').click()");
					}
				}

				if(isset($discount['calculate']))
				{
					if ($discount['calculate'] == 'total')
					{
						$I->executeJS("jQuery('#shipping_aftertotal-lbl').click()");
					}else{
						$I->executeJS("jQuery('#shipping_aftersubtotal-lbl').click()");
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