<?php
/**
 * @package     redSHOP
 * @subpackage  Steps MultiCurrenciesSteps
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Frontend\Module;
use CheckoutOnFrontEnd;
use FrontEndProductManagerJoomla3Page;

/**
 * Class ProductTabsSteps
 * @package Frontend\Module
 * @since 2.1.3
 */
class ProductTabsSteps extends CheckoutOnFrontEnd
{

	/**
	 * @param $productname
	 * @param $productname1
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function checkModuleProductTab($productname, $productname1)
	{
		$I = $this;
		$I->amOnPage('/');

        $I->waitForText(FrontEndProductManagerJoomla3Page::$newestProducts, 30);
        $I->click(FrontEndProductManagerJoomla3Page::$newestProducts);
        $value = $I->grabTextFrom(FrontEndProductManagerJoomla3Page::$nameProductNewest);
        $I->assertEquals($value, $productname1);

        $I->waitForText(FrontEndProductManagerJoomla3Page::$latestProducts, 30);
        $I->click(FrontEndProductManagerJoomla3Page::$latestProducts);
        $value2 = $I->grabTextFrom(FrontEndProductManagerJoomla3Page::$namProductsLatest);
        $I->assertEquals($value2, $productname1);

        $I->waitForText(FrontEndProductManagerJoomla3Page::$mostSoldProducts, 30);
        $I->click(FrontEndProductManagerJoomla3Page::$mostSoldProducts);
        $value3 = $I->grabTextFrom(FrontEndProductManagerJoomla3Page::$nameProductSold);
        $I->assertEquals($value3, $productname);
	}

	public function checkCompare()
    {

    }

}