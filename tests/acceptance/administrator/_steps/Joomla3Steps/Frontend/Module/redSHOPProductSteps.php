<?php
/**
 * @package     redSHOP
 * @subpackage  Steps redSHOPProductSteps
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Frontend\Module;
use AcceptanceTester\OrderManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use CheckoutOnFrontEnd;
use FrontEndProductManagerJoomla3Page;
use ProductManagerPage;

/**
 * Class redSHOPProduct
 * @package Frontend\Module
 * @since 2.1.3
 */
class redSHOPProductSteps extends ProductManagerJoomla3Steps
{
    /**
     * @param $value
     * @param $value1
     * @throws \Exception
     */
	public function assertEqualsValue($value, $value1)
	{
		$I = $this;
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$nameRedSHOPProduct1, 30);
		$text1 = $I->grabTextFrom(FrontEndProductManagerJoomla3Page::$nameRedSHOPProduct1);
		$I->assertEquals($text1, $value);

		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$nameRedSHOPProduct2, 30);
		$text2 = $I->grabTextFrom(FrontEndProductManagerJoomla3Page::$nameRedSHOPProduct2);
		$I->assertEquals($text2, $value1);
	}

	/**
	 * @param $moduleName
	 * @param $moduleConfig
	 * @param $value
	 * @param $value1
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function checkModuleRedSHOPProduct($moduleName, $moduleConfig, $value, $value1)
	{
		$I = $this;
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForText($moduleName, 30);

		if ($moduleConfig['moduleType'] == 'Newest')
		{
		   $I->assertEqualsValue($value, $value1);
		}

		if ($moduleConfig['moduleType'] == 'Most sold products')
		{
			$I->assertEqualsValue($value, $value1);
		}

		if ($moduleConfig['moduleType'] == 'Product on sale')
		{
			$currencyUnit = $I->getCurrencyValue();
			$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
			$text1 = $I->grabTextFrom(FrontEndProductManagerJoomla3Page::$nameRedSHOPProduct1);
			$I->assertEquals($text1, $value);
			$text = $I->grabTextFrom(FrontEndProductManagerJoomla3Page::$discount);
			$priceTotal = $currencyUnit['currencySymbol'].($value1).$currencyUnit['decimalSeparator'].$currencyUnit['numberZero'];
			$I->assertEquals($text, $priceTotal);
		}

		if ($moduleConfig['moduleType'] == 'Watched Product')
		{
			$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
			$I->assertEqualsValue($value, $value1);
		}

		if ($moduleConfig['moduleType'] == 'Specific products')
		{
			$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
			$I->assertEqualsValue($value, $value1);
		}
	}

	/**
	 * @param $product
	 * @throws \Exception
	 */
	public function checkWatchedProductForntEnd($product)
	{
		$I = $this;

		$lenght = count($product);

		for ($a = 0 ;  $a < $lenght; $a++)
        {

            $I->amOnPage(\ProductManagerPage::$URL);

            $I->searchProduct( $product[$a]['productName']);
            $I->click(['link' => $product[$a]['productName']]);
            $I->waitForElement(\ProductManagerPage::$productName, 30);
            $I->click(\ProductManagerPage::$buttonReview);
            $I->switchToNextTab();
            $I->waitForElement(\ProductManagerPage::$namePageXpath, 30);
            $I->waitForText($product[$a]['productName'], 30, \ProductManagerPage::$namePageXpath);
            $I->closeTab();

            $I->waitForElement(\ProductManagerPage::$productName, 30);

            $I->click(\ProductManagerPage::$buttonClose);
        }


	}
}