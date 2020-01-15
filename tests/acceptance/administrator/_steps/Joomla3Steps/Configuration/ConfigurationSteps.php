<?php
/**
 * Created by PhpStorm.
 * User: nhung nguyen
 * Date: 5/25/2017
 * Time: 3:51 PM
 */

namespace Configuration;
use AcceptanceTester\AdminManagerJoomla3Steps;
use ConfigurationPage;
use OrderManagerPage;

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
	 * @param $configureWithlist
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function ConfigFeatureWishList($configureWithlist)
	{
		$I = $this;
		$I->amOnPage(ConfigurationPage::$URL);
		$I->waitForText(ConfigurationPage::$namePage, 30, ConfigurationPage::$h1);
		$I->waitForElementVisible(["link" => ConfigurationPage::$featureSetting], 30);
		$I->click(ConfigurationPage::$featureSetting);
		$I->waitForElement(ConfigurationPage::$wishListTab, 60);

		switch ($configureWithlist['enableWishList'])
		{
			case 'yes':
				$I->waitForElementVisible(ConfigurationPage::$wishListYes, 30);
				$I->click(ConfigurationPage::$wishListYes);
				break;

			case 'no':
				$I->waitForElementVisible(ConfigurationPage::$wishListNo, 30);
				$I->click(ConfigurationPage::$wishListNo);
				break;
		}

		switch ($configureWithlist['wishlistLoginRequired'])
		{
			case 'yes':
				$I->waitForElementVisible(ConfigurationPage::$loginRequireYes, 30);
				$I->click(ConfigurationPage::$loginRequireYes);
				break;

			case 'no':
				$I->waitForElementVisible(ConfigurationPage::$loginRequireNo, 30);
				$I->click(ConfigurationPage::$loginRequireNo);
				break;
		}

		switch ($configureWithlist['enableWishlistList'])
		{
			case 'yes':
				$I->waitForElementVisible(ConfigurationPage::$wishListListYes, 30);
				$I->click(ConfigurationPage::$wishListListYes);
				break;

			case 'no':
				$I->waitForElementVisible(ConfigurationPage::$wishListListNo, 30);
				$I->click(ConfigurationPage::$wishListListNo);
				break;
		}

		$I->click(ConfigurationPage::$buttonSave);
		$I->waitForElement(ConfigurationPage::$selectorPageTitle, 60);
		$I->assertSystemMessageContains(ConfigurationPage::$messageSaveSuccess);
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
		$I->waitForElement($userConfigurationPage->returnChoice($country), 30);
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
			$I->waitForElement($userConfigurationPage->returnChoice($state), 30);
			$I->click($userConfigurationPage->returnChoice($state));
		}

		// Get default vat
		$I->click(\ConfigurationPage::$vatGroup);
		$I->waitForElement(\ConfigurationPage::$vatSearchGroup, 5);
		$I->fillField(\ConfigurationPage::$vatSearchGroup, $vatDefault);
		$I->waitForElement($userConfigurationPage->returnChoice($vatDefault), 30);
		$I->click(\ConfigurationPage::$varFirstResults);

		//get vat base on
		$I->click(\ConfigurationPage::$vatDefaultBase);
		$I->waitForElement(\ConfigurationPage::$vatSearchDefaultBase, 5);
		$I->fillField(\ConfigurationPage::$vatSearchDefaultBase, $vatCalculation);
		$I->waitForElement($userConfigurationPage->returnChoice($vatCalculation), 30);
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

	/**
	 * @param $cartSetting
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function cartSetting($cartSetting)
	{
		$I = $this;
		$I->amOnPage(\ConfigurationPage::$URL);
		$I->click(\ConfigurationPage::$cartCheckout);
		$userConfiguration = new \ConfigurationPage();

		switch ($cartSetting['addCart'])
		{
			case 'product':
				$I->waitForElementVisible(\ConfigurationPage::$addCartProduct, 30);
				$I->click(\ConfigurationPage::$addCartProduct);
				break;
			case 'attribute':
				$I->waitForElementVisible(\ConfigurationPage::$addCartAttibute, 30);
				$I->click(\ConfigurationPage::$addCartAttibute);
				break;
		}

		switch ($cartSetting['allowPreOrder'])
		{
			case 'yes':
				$I->waitForElementVisible(\ConfigurationPage::$allowPreOrOderYes, 30);
				$I->click(\ConfigurationPage::$allowPreOrOderYes);
				break;
			case 'no':
				$I->waitForElementVisible(\ConfigurationPage::$allowPreorderNo, 30);
				$I->click(\ConfigurationPage::$allowPreorderNo);
				break;
		}

		switch ($cartSetting['enableQuotation'])
		{
			case 'yes':
				$I->waitForElementVisible(\ConfigurationPage::$enableQuotationYes, 30);
				$I->click(\ConfigurationPage::$enableQuotationYes);
				break;
			case 'no':
				$I->waitForElementVisible(\ConfigurationPage::$enableQuotationNo, 30);
				$I->click(\ConfigurationPage::$enableQuotationNo);
				break;
		}

		$I->fillField(\ConfigurationPage::$cartTimeOut, $cartSetting['cartTimeOut']);

		switch ($cartSetting['enabledAjax'])
		{
			case 'yes':
				$I->waitForElementVisible(\ConfigurationPage::$enableAjaxYes, 30);
				$I->click(\ConfigurationPage::$enableAjaxYes);
				break;
			case 'no':
				$I->waitForElementVisible(\ConfigurationPage::$enableAjaxNo, 30);
				$I->click(\ConfigurationPage::$enableAjaxNo);
				break;
		}

		//choice default cart/checkout item ID
		if ($cartSetting['defaultCart'] != null)
		{
			$I->click(\ConfigurationPage::$defaultCart);
			$I->waitForElement(\ConfigurationPage::$defaultCartSearch, 5);
			$I->fillField(\ConfigurationPage::$defaultCartSearch, $cartSetting['defaultCart']);
			$I->waitForElement($userConfiguration->returnChoice($cartSetting['defaultCart']));
			$I->click($userConfiguration->returnChoice($cartSetting['defaultCart']));
		}

		//Choice add to cart button lead
		$I->waitForElementVisible(\ConfigurationPage::$buttonCartLead, 30);
		$I->wait(0.5);
		$I->click(\ConfigurationPage::$buttonCartLead);
		$I->waitForElementVisible(\ConfigurationPage::$buttonCartSearch, 30);
		$I->fillField(\ConfigurationPage::$buttonCartSearch, $cartSetting['buttonCartLead']);
		$I->waitForElement($userConfiguration->returnChoice($cartSetting['buttonCartLead']),30);
		$I->click(\ConfigurationPage::$firstCartSearch);

		switch ($cartSetting['onePage'])
		{
			case 'yes':
				$I->waitForElementVisible(\ConfigurationPage::$onePageYes, 30);
				$I->click(\ConfigurationPage::$onePageYes);
				break;
			case 'no':
				$I->waitForElementVisible(\ConfigurationPage::$onePageNo, 30);
				$I->click(\ConfigurationPage::$onePageNo);
				break;
		}

		switch ($cartSetting['showShippingCart'])
		{
			case 'yes':
				$I->waitForElementVisible(\ConfigurationPage::$showShippingCartYes, 30);
				$I->click(\ConfigurationPage::$showShippingCartYes);
				break;
			case 'no':
				$I->waitForElementVisible(\ConfigurationPage::$showShippingCartNo, 30);
				$I->click(\ConfigurationPage::$showShippingCartNo);
				break;
		}

		switch ($cartSetting['attributeImage'])
		{
			case 'yes':
				$I->waitForElementVisible(\ConfigurationPage::$attributeImageInCartYes, 30);
				$I->click(\ConfigurationPage::$attributeImageInCartYes);
				break;
			case 'no':
				$I->waitForElementVisible(\ConfigurationPage::$attributeImageInCartNo, 30);
				$I->click(\ConfigurationPage::$attributeImageInCartNo);
				break;
		}

		switch ($cartSetting['quantityChange'])
		{
			case 'yes':
				$I->waitForElementVisible(\ConfigurationPage::$quantityChangeInCartYes, 30);
				$I->click(\ConfigurationPage::$quantityChangeInCartYes);
				break;
			case 'no':
				$I->waitForElementVisible(\ConfigurationPage::$quantityChangeInCartNo, 30);
				$I->click(\ConfigurationPage::$quantityChangeInCartNo);
				break;
		}

		$I->fillField(\ConfigurationPage::$quantityInCart, $cartSetting['quantityInCart']);
		$I->fillField(\ConfigurationPage::$minimunOrderTotal, $cartSetting['quantityInCart']);
		$I->click(\ConfigurationPage::$buttonSave);
		$I->waitForElement(\ConfigurationPage::$selectorPageTitle, 60);
		$I->assertSystemMessageContains(\ConfigurationPage::$messageSaveSuccess);
	}

	/**
	 * @param array $discount
	 * @throws \Exception
	 */
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
					$I->waitForElement($userConfiguration->returnChoice($discount['allow']), 30);
					$I->pressKey(\ConfigurationPage::$allowDiscountSearch, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);
				}

				if (isset($discount['enableCoupon']))
				{
					if ($discount['enableCoupon'] == 'yes')
					{
						$I->click(\ConfigurationPage::$enableCouponYes);
					}else{
						$I->click(\ConfigurationPage::$enableCouponNo);
					}
				}

				if (isset($discount['couponInfo']))
				{
					if ($discount['couponInfo'] == 'yes')
					{
						$I->click(\ConfigurationPage::$enableCouponInfoYes);
					}else{
						$I->click(\ConfigurationPage::$enableCouponInfoNo);
					}
				}

				if(isset($discount['enableVoucher']))
				{
					if ($discount['enableVoucher'] == 'yes')
					{
						$I->click(\ConfigurationPage::$enableVoucherYes);
					}else{
						$I->click(\ConfigurationPage::$enableVoucherNo);
					}
				}


				if(isset($discount['spendTime']))
				{
					if ($discount['spendTime'] == 'yes')
					{
						$I->click(\ConfigurationPage::$spendTimeDiscountYes);
					}else{
						$I->click(\ConfigurationPage::$spendTimeDiscountNo);
					}
				}

				if(isset($discount['applyForProductDiscount']))
				{
					if ($discount['applyForProductDiscount'] == 'yes')
					{
						$I->click(\ConfigurationPage::$applyDiscountForProductAlreadyDiscountYes);
					}else{
						$I->click(\ConfigurationPage::$applyDiscountForProductAlreadyDiscountNo);
					}
				}

				if(isset($discount['calculate']))
				{
					if ($discount['calculate'] == 'total')
					{
						$I->click(\ConfigurationPage::$calculateShippingBasedTotal);
					}else{
						$I->click(\ConfigurationPage::$calculateShippingBasedSubTotal);
					}
				}

				if(isset($discount['valueOfDiscount']))
				{
					$I->click(\ConfigurationPage::$valueDiscountCouponId);
					$I->waitForElement(\ConfigurationPage::$valueDiscountCouponSearch, 30);
					$I->fillField(\ConfigurationPage::$valueDiscountCouponSearch, $discount['valueOfDiscount']);
					$I->waitForElement($userConfiguration->returnChoice($discount['valueOfDiscount']), 30);
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
	 * @param $firstName
	 * @param $lastName
	 * @param $productName
	 * @param $categoryName
	 * @param $paymentMethod
	 * @throws \Exception
	 * @since 2.1.3
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
		$I->wait(0.5);
		$I->waitForElementVisible(\OrderManagerPage::$iconEdit, 30);
		$I->click(\OrderManagerPage::$iconEdit);
		$I->waitForElementVisible(\OrderManagerPage::$quantityp1, 30);
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

	/**
	 * @throws \Exception
	 * @since 2.1.2
	 */
	public function productsUsedStockRoomAttribute()
	{
		$I = $this;
		$I->amOnPage(ConfigurationPage::$URL);
		$I->waitForElementVisible(ConfigurationPage::$productTab, 30);
		$I->click(ConfigurationPage::$productTab);
		$I->waitForElementVisible(ConfigurationPage::$stockRoomAttributeYes, 30);
		$I->click(ConfigurationPage::$stockRoomAttributeYes);
		$I->click(ConfigurationPage::$buttonSave);
		$I->waitForElement(ConfigurationPage::$selectorPageTitle, 60);
		$I->assertSystemMessageContains(ConfigurationPage::$messageSaveSuccess);
	}

	/**
	 * @throws \Exception
	 * @since 2.1.2
	 */
	public function productsOffStockRoomAttribute()
	{
		$I = $this;
		$I->amOnPage(ConfigurationPage::$URL);
		$I->waitForElementVisible(ConfigurationPage::$productTab, 30);
		$I->click(ConfigurationPage::$productTab);
		$I->waitForElementVisible(ConfigurationPage::$stockRoomAttributeNo, 30);
		$I->click(ConfigurationPage::$stockRoomAttributeNo);
		$I->click(ConfigurationPage::$buttonSave);
		$I->waitForElement(ConfigurationPage::$selectorPageTitle, 60);
		$I->assertSystemMessageContains(ConfigurationPage::$messageSaveSuccess);
	}

	/**
	 * @param $function
	 * @throws \Exception
	 * @since 2.1.2
	 */
	public function checkConfigurationProductRelated($function)
	{
		$I = $this;
		$I->amOnPage(ConfigurationPage::$URL);
		$I->waitForElementVisible(ConfigurationPage::$productTab, 30);
		$I->click(ConfigurationPage::$productTab);
		$I->waitForElementVisible(ConfigurationPage::$relatedProductTab, 30);
		$I->click(ConfigurationPage::$relatedProductTab);
		switch ($function)
		{
			case 'Yes':
				$I->waitForElementVisible(ConfigurationPage::$twoWayRelatedYes, 30);
				$I->click(ConfigurationPage::$twoWayRelatedYes);
				break;
			case 'No':
				$I->waitForElementVisible(ConfigurationPage::$twoWayRelatedNo, 30);
				$I->click(ConfigurationPage::$twoWayRelatedNo);
				break;
		}
		$I->click(ConfigurationPage::$buttonSaveClose);
		$I->waitForElement(ConfigurationPage::$selectorPageTitle, 60);
		$I->assertSystemMessageContains(ConfigurationPage::$messageSaveSuccess);
	}

	/**
	 * @param $showAddToCart
	 * @throws \Exception
	 * @since 2.1.4
	 */
	public function configProductPurchaseParent($showAddToCart)
	{
		$I = $this;
		$I->amOnPage(ConfigurationPage::$URL);
		$I->waitForElementVisible(ConfigurationPage::$productTab, 30);
		$I->click(ConfigurationPage::$productTab);
		$I->waitForElementVisible(ConfigurationPage::$relatedProductTab, 30);
		$I->click(ConfigurationPage::$relatedProductTab);

		switch ($showAddToCart)
		{
			case 'Yes':
				$I->waitForElementVisible(ConfigurationPage::$purchaseParentYes, 30);
				$I->click(ConfigurationPage::$purchaseParentYes);
				break;

			case 'No':
				$I->waitForElementVisible(ConfigurationPage::$purchaseParentNo, 30);
				$I->click(ConfigurationPage::$purchaseParentNo);
				break;
		}

		$I->click(ConfigurationPage::$buttonSaveClose);
		$I->waitForElement(ConfigurationPage::$selectorPageTitle, 60);
		$I->assertSystemMessageContains(ConfigurationPage::$messageSaveSuccess);
	}

	/**
	 * @param $price
	 * @param $order
	 * @param $firstName
	 * @param $lastName
	 * @param $productName
	 * @param $categoryName
	 * @param $paymentMethod
	 * @param $status
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function checkPriceTotalHaveStatusOder($price, $order, $firstName, $lastName, $productName, $categoryName, $paymentMethod, $status)
	{
		$I = $this;
		$I->amOnPage(ConfigurationPage::$URL);
		$currencySymbol = $I->grabValueFrom(ConfigurationPage::$currencySymbol);
		$decimalSeparator = $I->grabValueFrom(ConfigurationPage::$decimalSeparator);
		$numberOfPriceDecimals = $I->grabValueFrom(ConfigurationPage::$numberOfPriceDecimals);
		$numberOfPriceDecimals = (int)$numberOfPriceDecimals;
		$NumberZero = null;

		for ($b = 1; $b <= $numberOfPriceDecimals; $b++)
		{
			$NumberZero = $NumberZero."0";
		}

		$I->amOnPage(OrderManagerPage::$URL);
		$I->searchOrder($order);
		$I->wait(0.5);
		$I->waitForElementVisible(OrderManagerPage::$iconEdit, 30);
		$I->click(OrderManagerPage::$iconEdit);
		$I->waitForText(OrderManagerPage::$titlePage,30, OrderManagerPage:: $h1 );
		$quantity = $I->grabValueFrom(OrderManagerPage::$quantityp1);
		$quantity = (int)$quantity;
		$priceProduct = $currencySymbol.' '.$price.$decimalSeparator.$NumberZero;
		$priceTotal = 'Total: '.$currencySymbol.' '.$price*$quantity.$decimalSeparator.$NumberZero;
		$firstName = 'First Name: '.$firstName;
		$lastName = 'Last Name: '.$lastName;
		$I->waitForText($firstName, 30);
		$I->waitForText($lastName, 30);
		$I->waitForText($paymentMethod, 30);
		$I->waitForText($productName, 30);
		$I->waitForText($categoryName, 30);
		$I->see($priceProduct);
		$I->see($priceTotal);
		$I->waitForText($status, 30);
	}

	/**
	 * @param array $configurationOder
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function ConfigurationOder($configurationOder = array())
	{
		$I = $this;
		$I->amOnPage(ConfigurationPage::$URL);
		$I->waitForElementVisible(ConfigurationPage::$ordersTab, 30);
		$I->click(ConfigurationPage::$ordersTab);

		if(isset($configurationOder['resetIdOder']))
		{
			$I->waitForElementVisible(ConfigurationPage::$resetOderId, 30);
			$I->click(ConfigurationPage::$resetOderId);
			$I->acceptPopup();
			$I->wait(2);
			$I->canSeeInPopup(ConfigurationPage::$messagePopup);
			$I->seeInPopup(ConfigurationPage::$messagePopup);
			$I->acceptPopup();
		}

		if(isset($configurationOder['sendOderEmail']))
		{
			if (isset($configurationOder['afterPayment'])) {
				$I->waitForElementVisible(ConfigurationPage::$sendOrderEmail);
				$I->click(ConfigurationPage::$sendOrderEmail);
				$I->waitForElementVisible(ConfigurationPage::$afterPayment);
				$I->click(ConfigurationPage::$afterPayment);
			}

			if (isset($configurationOder['afterPayment2'])) {
				$I->waitForElementVisible(ConfigurationPage::$sendOrderEmail);
				$I->click(ConfigurationPage::$sendOrderEmail);
				$I->waitForElementVisible(ConfigurationPage::$inputOderEmail, 30);
				$I->fillField(ConfigurationPage::$inputOderEmail, $configurationOder['afterPayment2']);
				$usePage = new ConfigurationPage();
				$I->waitForElement($usePage->returnChoice($configurationOder['afterPayment2']), 30);
				$I->click($usePage->returnChoice($configurationOder['afterPayment2']));
			}

			if (isset($configurationOder['beforePayment'])){
				$I->waitForElementVisible(ConfigurationPage::$sendOrderEmail);
				$I->click(ConfigurationPage::$sendOrderEmail);
				$I->waitForElementVisible(ConfigurationPage::$inputOderEmail, 30);
				$I->fillField(ConfigurationPage::$inputOderEmail, $configurationOder['beforePayment']);
				$usePage = new ConfigurationPage();
				$I->waitForElement($usePage->returnChoice($configurationOder['beforePayment']), 30);
				$I->click($usePage->returnChoice($configurationOder['beforePayment']));
			}
		}

		if (isset($configurationOder['enableInVoiceEmail']))
		{
			if (isset($configurationOder['Yes']))
			{
				$I->waitForElementVisible(ConfigurationPage::$enableInvoiceEmailYes, 30);
				$I->click(ConfigurationPage::$enableInvoiceEmailYes);

				if (isset($configurationOder['None']))
				{
					$I->waitForElementVisible(ConfigurationPage::$noneButton, 30);
					$I->click(ConfigurationPage::$noneButton);
				}

				if (isset($configurationOder['Administrator']))
				{
					$I->waitForElementVisible(ConfigurationPage::$administratorButton, 30);
					$I->click(ConfigurationPage::$administratorButton);
				}

				if (isset($configurationOder['Customer']))
				{
					$I->waitForElementVisible(ConfigurationPage::$customerButton, 30);
					$I->click(ConfigurationPage::$customerButton);
				}

				if (isset($configurationOder['Both']))
				{
					$I->waitForElementVisible(ConfigurationPage::$bothButton, 30);
					$I->click(ConfigurationPage::$bothButton);
				}

			}
			if (isset($configurationOder['No']))
			{
				$I->waitForElementVisible(ConfigurationPage::$enableInvoiceEmailNo, 30);
				$I->click(ConfigurationPage::$enableInvoiceEmailNo);
			}
		}

		if (isset($configurationOder['sendMailToCustomerInOder']))
		{
			if (isset($configurationOder['Yes']))
			{
				$I->waitForElementVisible(ConfigurationPage::$sendMailToCustomerInOrderYes, 30);
				$I->click(ConfigurationPage::$sendMailToCustomerInOrderYes);
			}
			if (isset($configurationOder['No']))
			{
				$I->waitForElementVisible(ConfigurationPage::$sendMailToCustomerInOrderNo, 30);
				$I->click(ConfigurationPage::$sendMailToCustomerInOrderNo);
			}
		}

		$I->click(ConfigurationPage::$buttonSaveClose);
		$I->waitForElement(ConfigurationPage::$selectorPageTitle, 60);
		$I->assertSystemMessageContains(ConfigurationPage::$messageSaveSuccess);
	}

	/**
	 * @param $shipping
	 * @param $product
	 * @param $customerInformation
	 * @param $categoryName
	 * @param $paymentMethod
	 * @param $shippingMethod
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function checkShippingTotal($shipping, $product, $customerInformation, $categoryName, $paymentMethod, $shippingMethod)
	{
		$I = $this;
		$currencyUnit = $I->getCurrencyValue();

		$I->amOnPage(\OrderManagerPage::$URL);
		$I->searchOrder($customerInformation["firstName"]);
		$I->wait(0.5);
		$I->waitForElementVisible(OrderManagerPage::$iconEdit, 30);
		$I->click(OrderManagerPage::$iconEdit);
		$I->waitForElementVisible(OrderManagerPage::$quantityp1, 30);
		$quantity = $I->grabValueFrom(OrderManagerPage::$quantityp1);
		$quantity = (int)$quantity;
		$total = $product['price']*$quantity + $shipping['shippingRate'];
		$priceProduct = $currencyUnit['currencySymbol'].$product['price']*$quantity.$currencyUnit['decimalSeparator'].$currencyUnit['numberZero'];
		$priceTotal = 'Total: '.$currencyUnit['currencySymbol'].($total).$currencyUnit['decimalSeparator'].$currencyUnit['numberZero'];
		$priceShippingRate = 'Shipping: '.$currencyUnit['currencySymbol'].($shipping['shippingRate']).$currencyUnit['decimalSeparator'].$currencyUnit['numberZero'];
		$firstName = 'First Name: '.$customerInformation["firstName"];
		$lastName = 'Last Name: '.$customerInformation["lastName"];

		$I->waitForText($firstName, 30);
		$I->see($firstName);
		$I->waitForText($lastName, 30);
		$I->see($lastName);
		$I->waitForText($paymentMethod, 30);
		$I->see($paymentMethod);
		$I->waitForText($shippingMethod, 30);
		$I->see($shippingMethod);
		$I->waitForText($categoryName, 30);
		$I->see($categoryName);
		$I->waitForText($product['name'], 30);
		$I->see($product['name']);
		$I->waitForText($priceProduct, 30);
		$I->see($priceProduct);
		$I->waitForText($priceShippingRate, 30);
		$I->see($priceShippingRate);
		$I->waitForText($priceTotal, 30);
		$I->see($priceTotal);
		$I->waitForElement(OrderManagerPage::$close, 30);
		$I->waitForText(OrderManagerPage::$buttonClose, 10, OrderManagerPage::$close);
		$I->click(OrderManagerPage::$close);
	}
}
