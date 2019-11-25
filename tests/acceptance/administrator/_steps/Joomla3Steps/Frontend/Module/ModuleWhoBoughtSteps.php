<?php
/**
 * @package     redSHOP
 * @subpackage  Steps
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Frontend\Module;
use CheckoutOnFrontEnd;
use FrontEndProductManagerJoomla3Page;

/**
 * Class ModuleWhoBoughtSteps
 * @package Frontend\Module
 */
class ModuleWhoBoughtSteps extends CheckoutOnFrontEnd
{
	/**
	 * @param $moduleName
	 * @param $productName
	 * @param $price
	 * @throws \Exception
	 */
	public function checkDisplayModuleWhoBought($moduleName, $productName, $price)
	{
		$I = $this;
		$currencyUnit = $I->getCurrencyValue();
		$priceProduct = $currencyUnit['currencySymbol'].$price.$currencyUnit['decimalSeparator'].$currencyUnit['numberZero'];
		$page = new FrontEndProductManagerJoomla3Page();

		$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
		$I->verifyNotices(false, $this->checkForNotices(), FrontEndProductManagerJoomla3Page::$page);
		$I->waitForElementVisible($page->nameModule($moduleName), 30);
		$I->waitForElement(['link' => $productName], 30);
		$I->waitForText($priceProduct, 30, FrontEndProductManagerJoomla3Page::$priceWhoBought);
		$I->see($priceProduct, FrontEndProductManagerJoomla3Page::$priceWhoBought);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$btnAddToCartWhoBought, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$btnAddToCartWhoBought);
		$I->waitForText(FrontEndProductManagerJoomla3Page::$alertSuccessMessage, 30, FrontEndProductManagerJoomla3Page::$selectorSuccess);
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$cartPageUrL);
		$I->waitForElementVisible(['link' => $productName], 30);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutButton, 30);
	}
}