<?php
/**
 * @package     redSHOP
 * @subpackage  Step
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use PromotionsManagementPage as PromotionsPage;

/**
 * Class PromotionsManagementSteps
 * @since 3.0.3
 */
class PromotionsManagementSteps extends CheckoutWithAjaxCart
{
	/**
	 * @param $promotion
	 * @param $function
	 * @throws Exception
	 * @since 3.0.3
	 */
	public function createPromotion($promotion, $function)
	{
		$I = $this;
		$I->amOnPage(PromotionsPage::$url);
		$I->waitForText(PromotionsPage::$titlePage, 30, PromotionsPage::$h1);
		$I->click(PromotionsPage::$buttonNew);
		$I->waitForText(PromotionsPage::$titlePageNew, 30, PromotionsPage::$h1);

		if (isset($promotion['promotionType']))
		{
			$I->waitForElementVisible(PromotionsPage::$selectPromotionType, 30);
			$I->click(PromotionsPage::$selectPromotionType);
			$I->waitForElementVisible(PromotionsPage::$searchPromotionType, 30);
			$I->fillField(PromotionsPage::$searchPromotionType, $promotion['promotionType']);
			$I->pressKey(PromotionsPage::$searchPromotionType, \Facebook\WebDriver\WebDriverKeys::ENTER);

			if ($promotion['promotionType'] == 'Amount Product')
			{
				$I->waitForElementVisible(PromotionsPage::$selectManufacturer, 30);
				$I->click(PromotionsPage::$selectManufacturer);
				$I->fillField(PromotionsPage::$inputManufacturer, $promotion['manufacturer']);
				$I->pressKey(PromotionsPage::$inputManufacturer, \Facebook\WebDriver\WebDriverKeys::ENTER);

				$I->waitForElementVisible(PromotionsPage::$selectCategory, 30);
				$I->click(PromotionsPage::$selectCategory);
				$I->fillField(PromotionsPage::$inputCategory, $promotion['category']);
				$I->pressKey(PromotionsPage::$inputCategory, \Facebook\WebDriver\WebDriverKeys::ENTER);

				$I->waitForElementVisible(PromotionsPage::$selectProduct, 30);
				$I->click(PromotionsPage::$selectProduct);
				$I->fillField(PromotionsPage::$inputProduct, $promotion['product']);
				$I->pressKey(PromotionsPage::$inputProduct, \Facebook\WebDriver\WebDriverKeys::ENTER);

				$I->waitForElementVisible(PromotionsPage::$inputConditionAmount, 30);
				$I->fillField(PromotionsPage::$inputConditionAmount, $promotion['conditionAmount']);

				$I->waitForElementVisible(PromotionsPage::$inputFromDate, 30);
				$I->fillField(PromotionsPage::$inputFromDate, $promotion['fromDate']);

				$I->waitForElementVisible(PromotionsPage::$inputToDate, 30);
				$I->fillField(PromotionsPage::$inputToDate, $promotion['toDate']);

				if (isset($promotion['productAward']))
				{
					$I->waitForElementVisible(PromotionsPage::$selectProductAwards, 30);
					$I->click(PromotionsPage::$selectProductAwards);
					$I->waitForElementVisible(PromotionsPage::$searchProductAwards, 30);
					$I->fillField(PromotionsPage::$searchProductAwards, $promotion['productAward']);
					$I->pressKey(PromotionsPage::$searchProductAwards, \Facebook\WebDriver\WebDriverKeys::ENTER);

					if (isset($promotion['awardAmount']))
					{
						$I->waitForElementVisible(PromotionsPage::$inputAwardAmount, 30);
						$I->fillField(PromotionsPage::$inputAwardAmount, $promotion['awardAmount']);
					}

					if (isset($promotion['freeShipping']))
					{
						$I->waitForElementVisible(PromotionsPage::$selectFreeShipping, 30);
						$I->click(PromotionsPage::$selectFreeShipping);
						$I->waitForElementVisible(PromotionsPage::$searchFreeShipping, 30);
						$I->fillField(PromotionsPage::$searchFreeShipping, $promotion['freeShipping']);
						$I->pressKey(PromotionsPage::$searchFreeShipping, \Facebook\WebDriver\WebDriverKeys::ENTER);
					}
				}
			}
			else
			{
				$I->waitForElementVisible(PromotionsPage::$inputOrderVolume, 30);
				$I->fillField(PromotionsPage::$inputOrderVolume, $promotion['orderVolume']);

				$I->waitForElementVisible(PromotionsPage::$inputFromDate, 30);
				$I->fillField(PromotionsPage::$inputFromDate, $promotion['fromDate']);

				$I->waitForElementVisible(PromotionsPage::$inputToDate, 30);
				$I->fillField(PromotionsPage::$inputToDate, $promotion['toDate']);

				if (isset($promotion['productAward']))
				{
					$I->waitForElementVisible(PromotionsPage::$selectProductAwards, 30);
					$I->click(PromotionsPage::$selectProductAwards);
					$I->waitForElementVisible(PromotionsPage::$searchProductAwards2, 30);
					$I->fillField(PromotionsPage::$searchProductAwards2, $promotion['productAward']);
					$I->pressKey(PromotionsPage::$searchProductAwards2, \Facebook\WebDriver\WebDriverKeys::ENTER);

					if (isset($promotion['awardAmount']))
					{
						$I->waitForElementVisible(PromotionsPage::$inputAwardAmount, 30);
						$I->fillField(PromotionsPage::$inputAwardAmount, $promotion['awardAmount']);
					}

					if (isset($promotion['freeShipping']))
					{
						$I->waitForElementVisible(PromotionsPage::$selectFreeShipping, 30);
						$I->click(PromotionsPage::$selectFreeShipping);
						$I->waitForElementVisible(PromotionsPage::$searchFreeShipping2, 30);
						$I->fillField(PromotionsPage::$searchFreeShipping2, $promotion['freeShipping']);
						$I->pressKey(PromotionsPage::$searchFreeShipping2, \Facebook\WebDriver\WebDriverKeys::ENTER);
					}
				}
			}
		}

		$I->waitForElementVisible(PromotionsPage::$idFieldName, 30);
		$I->fillField(PromotionsPage::$idFieldName, $promotion['name']);

		if (isset($promotion['desc']))
		{
			$I->waitForElementVisible(PromotionsPage::$textareDescription, 30);
			$I->fillField(PromotionsPage::$textareDescription, $promotion['desc']);
		}

		Switch ($function)
		{
			case ('Save'):
				$I->waitForText(PromotionsPage::$buttonSave, 30);
				$I->click(PromotionsPage::$buttonSave);
				$I->waitForText(PromotionsPage::$messageItemSaveSuccess, 30);
				$I->waitForText(PromotionsPage::$titlePageEdit, 30, PromotionsPage::$h1);
				break;

			case ('Save & Close'):
				$I->waitForText(PromotionsPage::$buttonSaveClose, 30);
				$I->click(PromotionsPage::$buttonSaveClose);
				$I->waitForText(PromotionsPage::$messageItemSaveSuccess, 30);
				$I->waitForText(PromotionsPage::$titlePage, 30, PromotionsPage::$h1);
				break;

			case ('Save & New'):
				$I->waitForText(PromotionsPage::$buttonSaveNew, 30);
				$I->click(PromotionsPage::$buttonSaveNew);
				$I->waitForText(PromotionsPage::$messageItemSaveSuccess, 30);
				$I->waitForText(PromotionsPage::$titlePageNew, 30, PromotionsPage::$h1);
				break;

			case ('Cancel'):
				$I->waitForText(PromotionsPage::$buttonCancel, 30);
				$I->click(PromotionsPage::$buttonCancel);
				$I->waitForText(PromotionsPage::$titlePage, 30, PromotionsPage::$h1);
				break;
		}
	}

	/**
	 * @param $promotion
	 * @param $shipping
	 * @param $customerInformation
	 * @throws Exception
	 * @since 3.0.3
	 */
	public function checkoutPromotionWithShipping($promotion, $shipping, $customerInformation)
	{
		$I = $this;
		$currencyUnit = $I->getCurrencyValue();
		$I->doFrontEndLogin($customerInformation['userName'], $customerInformation['password']);

		if ($promotion['promotionType'] == 'Amount Product')
		{
			$I->amOnPage(CheckoutChangeQuantityProductPage::$url);
			$I->click($promotion['category']);
			$I->waitForElementVisible(PromotionsPage::xpathLink($promotion['product']), 30);
			$I->click(PromotionsPage::xpathLink($promotion['product']));

			for ($a= 0; $a < $promotion['conditionAmount']; $a++)
			{
				$I->waitForElementVisible(AdminJ3Page::$addToCart, 30);
				$I->seeElement(AdminJ3Page:: $addToCart);
				$I->click(AdminJ3Page:: $addToCart);
				$I->waitForText(AdminJ3Page::$alertSuccessMessage, 120, FrontEndProductManagerJoomla3Page::$selectorSuccess);
			}
		}
		else
		{
			$I->addToCart($promotion['category'], $promotion['product']);
		}

		$I->amOnPage(CheckoutChangeQuantityProductPage::$cartPageUrL);
		$I->waitForText($promotion['product'], 10);
		$I->waitForText($promotion['productAward'], 10);
		$I->waitForElementVisible(AdminJ3Page::$checkoutButton, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutButton);

		$I->waitForElement(FrontEndProductManagerJoomla3Page::$shippingMethod, 30);
		$I->scrollTo(FrontEndProductManagerJoomla3Page::$shippingMethod);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$radioShippingRate, 30);
		$I->selectOption(FrontEndProductManagerJoomla3Page::$radioShippingRate, $shipping['shippingName']);
		$I->wait(0.5);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$termAndConditions, 60);
		$I->seeElement(FrontEndProductManagerJoomla3Page::$termAndConditions);
		$I->click(FrontEndProductManagerJoomla3Page::$termAndConditions);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
		$I->seeElement(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$orderReceiptTitle, 30);

		if ($promotion['freeShipping'] == 'No')
		{
			$priceRate = $currencyUnit['currencySymbol'].($shipping['shippingRate']).$currencyUnit['decimalSeparator'].$currencyUnit['numberZero'];
			$I->waitForText($priceRate, 30);
			$I->see($priceRate);
			$I->doFrontendLogout();
		}
		else
		{
			$priceRate = $currencyUnit['currencySymbol'].(0).$currencyUnit['decimalSeparator'].$currencyUnit['numberZero'];
			$I->waitForText($priceRate, 30);
			$I->see($priceRate);
			$I->doFrontendLogout();
		}
	}

	/**
	 * @param $promotion
	 * @param $customerInformation
	 * @throws Exception
	 * @since 3.0.3
	 */
	public function checkPromotionWithCartAjax($promotion, $customerInformation)
	{
		$I = $this;
		$I->doFrontEndLogin($customerInformation['userName'], $customerInformation['password']);

		if ($promotion['promotionType'] == 'Amount Product')
		{
			for ( $a= 0; $a < $promotion['conditionAmount']; $a++)
			{
				$I->addToCartAjax($promotion['category'], $promotion['product'], 'no', 'no');
			}
		}
		else
		{
			$I->addToCartAjax($promotion['category'], $promotion['product'], 'no', 'yes');
		}

		$I->amOnPage(CheckoutChangeQuantityProductPage::$cartPageUrL);
		$I->waitForText($promotion['product'], 10);
		$I->waitForText($promotion['productAward'], 10);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$buttonEmptyCart, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$buttonEmptyCart);
		$I->doFrontendLogout();
	}

	/**
	 * @param $promotionName
	 * @throws Exception
	 * @since 3.0.3
	 */
	public function deletePromotion($promotionName)
	{
		$I = $this;
		$I->amOnPage(PromotionsPage::$url);
		$I->waitForText(PromotionsPage::$titlePage, 30, PromotionsPage::$h1);
		$I->filterListBySearching($promotionName);
		$I->checkAllResults();
		$I->waitForText(PromotionsPage::$buttonDelete, 30);
		$I->click(PromotionsPage::$buttonDelete);
		$I->acceptPopup();
		$I->waitForText(PromotionsPage::$messageNoItemOnTable, 30);
	}

	/**
	 * @param $promotionName
	 * @param $promotionNameEdit
	 * @throws Exception
	 * @since 3.0.3
	 */
	public function editPromotion($promotionName, $promotionNameEdit)
	{
		$I = $this;
		$I->amOnPage(PromotionsPage::$url);
		$I->waitForText(PromotionsPage::$titlePage, 30, PromotionsPage::$h1);
		$I->filterListBySearching($promotionName);
		$I->waitForElementVisible(PromotionsPage::xpathLink($promotionName), 30);
		$I->click($promotionName);
		$I->waitForElementVisible(PromotionsPage::$idFieldName, 30);
		$I->fillField(PromotionsPage::$idFieldName, $promotionNameEdit);
		$I->waitForText(PromotionsPage::$buttonSaveClose, 30);
		$I->click(PromotionsPage::$buttonSaveClose);
		$I->waitForText(PromotionsPage::$messageItemSaveSuccess, 30);
		$I->waitForText(PromotionsPage::$titlePage, 30, PromotionsPage::$h1);
	}

	/**
	 * @param $promotion
	 * @throws Exception
	 * @since 3.0.3
	 */
	public function badCasePromotion($promotion)
	{
		$I = $this;
		$I->amOnPage(PromotionsPage::$url);
		$I->waitForText(PromotionsPage::$titlePage, 30, PromotionsPage::$h1);
		$I->click(PromotionsPage::$buttonNew);
		$I->waitForText(PromotionsPage::$titlePageNew, 30, PromotionsPage::$h1);

		if (isset($promotion['promotionType']))
		{
			$I->waitForElementVisible(PromotionsPage::$selectPromotionType, 30);
			$I->click(PromotionsPage::$selectPromotionType);
			$I->waitForElementVisible(PromotionsPage::$searchPromotionType, 30);
			$I->fillField(PromotionsPage::$searchPromotionType, $promotion['promotionType']);
			$I->pressKey(PromotionsPage::$searchPromotionType, \Facebook\WebDriver\WebDriverKeys::ENTER);

			if ($promotion['promotionType'] == 'Amount Product')
			{
				$I->waitForElementVisible(PromotionsPage::$selectManufacturer, 30);
				$I->click(PromotionsPage::$selectManufacturer);
				$I->fillField(PromotionsPage::$inputManufacturer, $promotion['manufacturer']);
				$I->pressKey(PromotionsPage::$inputManufacturer, \Facebook\WebDriver\WebDriverKeys::ENTER);

				$I->waitForElementVisible(PromotionsPage::$selectCategory, 30);
				$I->click(PromotionsPage::$selectCategory);
				$I->fillField(PromotionsPage::$inputCategory, $promotion['category']);
				$I->pressKey(PromotionsPage::$inputCategory, \Facebook\WebDriver\WebDriverKeys::ENTER);

				$I->waitForElementVisible(PromotionsPage::$selectProduct, 30);
				$I->click(PromotionsPage::$selectProduct);
				$I->fillField(PromotionsPage::$inputProduct, $promotion['product']);
				$I->pressKey(PromotionsPage::$inputProduct, \Facebook\WebDriver\WebDriverKeys::ENTER);

				$I->waitForElementVisible(PromotionsPage::$inputConditionAmount, 30);
				$I->fillField(PromotionsPage::$inputConditionAmount, $promotion['conditionAmount']);

				$I->waitForElementVisible(PromotionsPage::$inputFromDate, 30);
				$I->fillField(PromotionsPage::$inputFromDate, $promotion['fromDate']);

				$I->waitForElementVisible(PromotionsPage::$inputToDate, 30);
				$I->fillField(PromotionsPage::$inputToDate, $promotion['toDate']);

				if (isset($promotion['productAward']))
				{
					$I->waitForElementVisible(PromotionsPage::$selectProductAwards, 30);
					$I->click(PromotionsPage::$selectProductAwards);
					$I->waitForElementVisible(PromotionsPage::$searchProductAwards, 30);
					$I->fillField(PromotionsPage::$searchProductAwards, $promotion['productAward']);
					$I->pressKey(PromotionsPage::$searchProductAwards, \Facebook\WebDriver\WebDriverKeys::ENTER);

					if (isset($promotion['awardAmount']))
					{
						$I->waitForElementVisible(PromotionsPage::$inputAwardAmount, 30);
						$I->fillField(PromotionsPage::$inputAwardAmount, $promotion['awardAmount']);
					}

					if (isset($promotion['freeShipping']))
					{
						$I->waitForElementVisible(PromotionsPage::$selectFreeShipping, 30);
						$I->click(PromotionsPage::$selectFreeShipping);
						$I->waitForElementVisible(PromotionsPage::$searchFreeShipping, 30);
						$I->fillField(PromotionsPage::$searchFreeShipping, $promotion['freeShipping']);
						$I->pressKey(PromotionsPage::$searchFreeShipping, \Facebook\WebDriver\WebDriverKeys::ENTER);
					}
				}
			}
			else
			{
				$I->waitForElementVisible(PromotionsPage::$inputOrderVolume, 30);
				$I->fillField(PromotionsPage::$inputOrderVolume, $promotion['orderVolume']);

				$I->waitForElementVisible(PromotionsPage::$inputFromDate, 30);
				$I->fillField(PromotionsPage::$inputFromDate, $promotion['fromDate']);

				$I->waitForElementVisible(PromotionsPage::$inputToDate, 30);
				$I->fillField(PromotionsPage::$inputToDate, $promotion['toDate']);

				if (isset($promotion['productAward']))
				{
					$I->waitForElementVisible(PromotionsPage::$selectProductAwards, 30);
					$I->click(PromotionsPage::$selectProductAwards);
					$I->waitForElementVisible(PromotionsPage::$searchProductAwards2, 30);
					$I->fillField(PromotionsPage::$searchProductAwards2, $promotion['productAward']);
					$I->pressKey(PromotionsPage::$searchProductAwards2, \Facebook\WebDriver\WebDriverKeys::ENTER);

					if (isset($promotion['awardAmount']))
					{
						$I->waitForElementVisible(PromotionsPage::$inputAwardAmount, 30);
						$I->fillField(PromotionsPage::$inputAwardAmount, $promotion['awardAmount']);
					}

					if (isset($promotion['freeShipping']))
					{
						$I->waitForElementVisible(PromotionsPage::$selectFreeShipping, 30);
						$I->click(PromotionsPage::$selectFreeShipping);
						$I->waitForElementVisible(PromotionsPage::$searchFreeShipping2, 30);
						$I->fillField(PromotionsPage::$searchFreeShipping2, $promotion['freeShipping']);
						$I->pressKey(PromotionsPage::$searchFreeShipping2, \Facebook\WebDriver\WebDriverKeys::ENTER);
					}
				}
			}
		}

		$I->waitForElementVisible(PromotionsPage::$idFieldName, 30);
		$I->fillField(PromotionsPage::$idFieldName, $promotion['name']);

		if (isset($promotion['desc']))
		{
			$I->waitForElementVisible(PromotionsPage::$textareDescription, 30);
			$I->fillField(PromotionsPage::$textareDescription, $promotion['desc']);
		}

		$I->waitForText(PromotionsPage::$buttonSave, 30);
		$I->click(PromotionsPage::$buttonSave);

		switch ($promotion['function'])
		{
			case ('nameMissing'):
				$I->waitForText(PromotionsPage::$messageErrorNameMissing, 30);
				break;

			case ('startThanEnd'):
				$I->waitForText(PromotionsPage::$messageErrorStartThanEnd, 30);
				break;

			case ('quantityAwardLowZero'):
				$I->waitForText(PromotionsPage::$messageErrorAwardQuantity, 30);
				break;

			case ('quantityConditionLowZero'):
				$I->waitForText(PromotionsPage::$messageErrorConditionQuantity, 30);
				break;

			case ('orderVolumeLowZero'):
				$I->waitForText(PromotionsPage::$messageErrorVolumeOrder, 30);
				break;
		}
	}

	/**
	 * @param $promotionName
	 * @param $state
	 * @throws Exception
	 * @since 3.0.3
	 */
	public function changeStatePromotionItem($promotionName, $state)
	{
		$I = $this;
		$I->amOnPage(PromotionsPage::$url);
		$I->waitForText(PromotionsPage::$titlePage, 30, PromotionsPage::$h1);
		$I->filterListBySearching($promotionName);
		$I->waitForElementVisible(PromotionsPage::$checkAllXpath, 30);
		$I->click(PromotionsPage::$checkAllXpath);

		if ($state == 'Unpublish')
		{
			$I->waitForText(PromotionsPage::$buttonUnpublish, 10);
			$I->click(PromotionsPage::$buttonUnpublish);
			$I->waitForText(PromotionsPage::$messageUnpublishSuccess, 30);
		}
		else
		{
			$I->waitForText(PromotionsPage::$buttonPublish, 10);
			$I->click(PromotionsPage::$buttonPublish);
			$I->waitForText(PromotionsPage::$messagePublishSuccess, 30);
		}
	}

	/**
	 * @param $namePromotion
	 * @throws \Exception
	 * @since 3.0.3
	 */
	public function copyPromotion($namePromotion)
	{
		$I = $this;
		$I->amOnPage(PromotionsPage::$url);
		$I->waitForText(PromotionsPage::$titlePage, 30, PromotionsPage::$h1);
		$I->filterListBySearching($namePromotion);
		$I->waitForElementVisible(PromotionsPage::$checkAllXpath, 30);
		$I->click(PromotionsPage::$checkAllXpath);
		$I->waitForText(PromotionsPage::$buttonCopy, 10);
		$I->click(PromotionsPage::$buttonCopy);
		$I->waitForText(PromotionsPage::$messageCopySuccess, 60, PromotionsPage::$selectorSuccess);
		$I->waitForText($namePromotion, 30, PromotionsPage::$promotionNameSecond);
	}
}