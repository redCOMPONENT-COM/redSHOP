<?php
/**
 * @package     redSHOP
 * @subpackage  Steps ModuleProductRelated
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Frontend\Module;
use AcceptanceTester\OrderManagerJoomla3Steps;
use AcceptanceTester\ProductManagerJoomla3Steps;
use CheckoutMissingData;
use CheckoutOnFrontEnd;
use FrontEndProductManagerJoomla3Page;
use ProductManagerPage;

/**
 * Class ModuleProductRelatedSteps
 * @package Frontend\Module
 * @since 3.0.2
 */
class ModuleProductRelatedSteps extends CheckoutMissingData
{
	/**
	 * @param $moduleName
	 * @param $categoryName
	 * @param $productName
	 * @param $productRelated
	 * @param $productPrice
	 * @param $customerInformation
	 * @throws \Exception
	 * @since 3.0.2
	 */
	public function checkModuleRedSHOPProduct($moduleName, $categoryName, $productName, $productRelated,$productPrice, $customerInformation)
	{
		$I = $this;
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElementVisible(["link" => $categoryName], 30);
		$I->click(["link" => $categoryName]);
		$I->waitForElementVisible(["link" => $productRelated], 30);
		$I->click(["link" => $productRelated]);
		$I->waitForText($moduleName, 30);
		$I->waitForElementVisible(["link" => $productName], 30,FrontEndProductManagerJoomla3Page::$productRelatedTitle);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$addToCartProductRelated, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$addToCartProductRelated);
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$cartPageUrL);
		$I->waitForElementVisible(["link" => $productName], 30);
		$I->see($productName);
		$I->see($productPrice);
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutButton);
		$I->fillInformationPrivate($customerInformation);
		$I->wait(0.5);
		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$acceptTerms, 30);
		$I->scrollTo(FrontEndProductManagerJoomla3Page::$acceptTerms);
		$I->executeJS(FrontEndProductManagerJoomla3Page::radioCheckID(FrontEndProductManagerJoomla3Page::$termAndConditionsId));
		$I->wait(0.5);

		try
		{
			$I->seeCheckboxIsChecked(FrontEndProductManagerJoomla3Page::$termAndConditions);
		}
		catch (\Exception $e)
		{
			$I->click(FrontEndProductManagerJoomla3Page::$termAndConditions);
		}

		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$checkoutFinalStep, 30);
		$I->click(FrontEndProductManagerJoomla3Page::$checkoutFinalStep);
		$I->wait(0.5);
		$I->waitForText(FrontEndProductManagerJoomla3Page::$orderReceipt, 30, FrontEndProductManagerJoomla3Page:: $h1);
	}
}