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
	 * @param $name
	 * @param $xpathValue
	 * @param $productName
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function valueComparison($name, $xpathValue, $productName)
	{
		$I = $this;
		$I->waitForText($name, 30);
		$I->click($name);
		$text = $I->grabTextFrom($xpathValue);
		$I->assertEquals($text, $productName);
	}

	/**
	 * @param $productname
	 * @param $productNewest
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function checkModuleProductTab($productname, $productNewest)
	{
		$I = $this;
		$I->valueComparison(FrontEndProductManagerJoomla3Page::$newestProducts, FrontEndProductManagerJoomla3Page::$nameProductNewest, $productNewest);
		$I->valueComparison(FrontEndProductManagerJoomla3Page::$latestProducts,FrontEndProductManagerJoomla3Page::$namProductsLatest, $productNewest);
		$I->valueComparison(FrontEndProductManagerJoomla3Page::$mostSoldProducts, FrontEndProductManagerJoomla3Page::$nameProductSold, $productname);
	}
}