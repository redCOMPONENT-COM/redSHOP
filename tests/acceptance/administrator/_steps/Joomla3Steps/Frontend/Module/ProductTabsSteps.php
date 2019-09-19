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
	 * @param $xpath
	 * @param $text
	 * @param $productname
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function valueComparison($xpath, $xpathvalue, $productname)
	{
		$I = $this;
		$I->waitForText($xpath, 30);
		$I->click($xpath);
		$text = $I->grabTextFrom($xpathvalue);
		$I->assertEquals($text, $productname);
	}

	/**
	 * @param $productname
	 * @param $productname1
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function checkModuleProductTab($productname, $productname1)
	{
		$I = $this;

		$I->valueComparison(FrontEndProductManagerJoomla3Page::$newestProducts, FrontEndProductManagerJoomla3Page::$nameProductNewest, $productname);

		$I->valueComparison(FrontEndProductManagerJoomla3Page::$latestProducts,FrontEndProductManagerJoomla3Page::$namProductsLatest, $productname1);

		$I->valueComparison(FrontEndProductManagerJoomla3Page::$mostSoldProducts, FrontEndProductManagerJoomla3Page::$nameProductSold, $productname);
	}
}