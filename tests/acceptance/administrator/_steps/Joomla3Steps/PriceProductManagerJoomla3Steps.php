<?php
/**
 * @package     redSHOP
 * @subpackage  steps
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;
use PriceProductJoomla3Page;

/**
 * Class PriceProductManagerJoomla3Steps
 * @package AcceptanceTester
 * @since 3.0.2
 */
class PriceProductManagerJoomla3Steps extends AdminManagerJoomla3Steps
{
	/**
	 * @param $productName
	 * @param $priceDiscount
	 * @throws \Exception
	 * @since 3.0.2
	 */
	public function addDiscountPrice($productName, $priceDiscount)
	{
		$I = $this;
		$I->amOnPage(PriceProductJoomla3Page::$URL);
		$I->searchProduct($productName);
		$I->wait(5);
		$I->fillField(PriceProductJoomla3Page::$discount, $priceDiscount);
		$I->click(PriceProductJoomla3Page::$saveButton);
	}

	/**
	 * @param $productName
	 * @param $priceDiscountThan
	 * @throws \Exception
	 * @since 3.0.2
	 */
	public function addDiscountPriceMoreThan($productName, $priceDiscountThan)
	{
		$I = $this;
		$I->amOnPage(PriceProductJoomla3Page::$URL);
		$I->searchProduct($productName);
		$I->waitForElementVisible(PriceProductJoomla3Page::$discount, 30);
		$I->fillField(PriceProductJoomla3Page::$discount, $priceDiscountThan);
		$I->click( PriceProductJoomla3Page::$saveButton);

	}

	/**
	 * @param $productName
	 * @param $randomPriceLess
	 * @throws \Exception
	 * @since 3.0.2
	 */
	public function addPriceLessDiscount($productName, $randomPriceLess)
	{
		$I = $this;
		$I->amOnPage(PriceProductJoomla3Page::$URL);
		$I->searchProduct($productName);
		$I->waitForElementVisible(PriceProductJoomla3Page::$discount, 30);
		$I->fillField(PriceProductJoomla3Page::$price, $randomPriceLess);
		$I->click(PriceProductJoomla3Page::$savePrice);
	}

	/**
	 * @param $productName
	 * @throws \Exception
	 * @since 3.0.2
	 */
	public function searchProduct($productName)
	{
		$I = $this;
		$I->wantTo('Search the Product');
		$I->amOnPage(PriceProductJoomla3Page::$URL);
		$I->see(PriceProductJoomla3Page::$namePage, PriceProductJoomla3Page::$headPage);
		$I->filterListBySearchingProductPrice($productName);
	}

	/**
	 * @param $text
	 * @param array $searchField
	 * @throws \Exception
	 * @since 3.0.2
	 */
	public function filterListBySearchingProductPrice($text, $searchField = ['name' => 'keyword'])
	{
		$I = $this;
		$I->executeJS('window.scrollTo(0,0)');
		$I->fillField($searchField, $text);
		$I->pressKey($searchField, \Facebook\WebDriver\WebDriverKeys::ENTER);
		$I->waitForElement(['link' => $text]);
	}
}
