<?php
/**
 * @package     redSHOP
 * @subpackage  Steps redSHOPProductSteps
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Frontend\Module;
use AcceptanceTester\OrderManagerJoomla3Steps;
use CheckoutOnFrontEnd;
use FrontEndProductManagerJoomla3Page;
use ProductManagerPage;

/**
 * Class redSHOPProduct
 * @package Frontend\Module
 * @since 2.1.3
 */
class redSHOPProductSteps extends CheckoutOnFrontEnd
{
	/**
	 * @param $value
	 * @param $value1
	 * @since 2.1.3
	 */
	public function assertEqualsValue($value, $value1)
	{
		$I = $this;
		$text1 = $I->grabTextFrom(FrontEndProductManagerJoomla3Page::$nameRedSHOPProduct1);
		$I->assertEquals($text1, $value);
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
}