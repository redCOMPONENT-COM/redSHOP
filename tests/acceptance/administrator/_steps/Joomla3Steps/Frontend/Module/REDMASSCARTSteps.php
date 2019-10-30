<?php
/**
 * @package     redSHOP
 * @subpackage  Steps ShopperGroupProduct
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Frontend\Module;

use CheckoutMissingData;
use FrontEndProductManagerJoomla3Page;
use CheckoutModulePage;

/**
 * Class REDMASSCARTSteps
 * @package Frontend\Module
 * @since 2.1.3
 */
class REDMASSCARTSteps extends CheckoutMissingData
{
	/**
	 * @param $productName
	 * @param $productNumber
	 * @param $productQuantity
	 * @param $titleButton
	 * @param $function
	 * @param $addressDetail
	 * @param $shipmentDetail
	 * @param $total
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function checkOutRedMassCartModule($productName, $productNumber, $productQuantity, $titleButton, $function, $addressDetail, $shipmentDetail, $total)
	{
		$I = $this;
		$currencyUnit = $I->getCurrencyValue();
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
		$frontend = new CheckoutModulePage();
		$I->waitForText(CheckoutModulePage::$redMASSCART, 30, CheckoutModulePage::$selectorRedMasscart);
		$I->waitForElementVisible(CheckoutModulePage::$productNumberTextarea, 30);
		$I->fillField(CheckoutModulePage::$productNumberTextarea, $productNumber);
		$I->fillField(CheckoutModulePage::$productQuantity, $productQuantity);
		$I->click($frontend->titleButton($titleButton));
		$I->waitForText($productName, 30, CheckoutModulePage::$productNameOnCart);
		$I->click(CheckoutModulePage::$checkoutButton);

		switch ($function)
		{
			case 'no':
				$I->waitForElementVisible(CheckoutModulePage::$newCustomerSpan, 30);
				$I->click(CheckoutModulePage::$newCustomerSpan);
				$I->addressInformation($addressDetail);
				$I->shippingInformation($shipmentDetail);
				$I->waitForElementVisible(CheckoutModulePage::$proceedButtonId, 30);
				$I->click(CheckoutModulePage::$proceedButtonId);
				$I->waitForElement(CheckoutModulePage::$billingFinal, 30);
				$I->waitForElement(CheckoutModulePage::$bankTransfer, 30);
				$I->executeJS($frontend->radioCheckID(CheckoutModulePage::$bankTransferId));
				$I->click(CheckoutModulePage::$checkoutButton);
				break;

			case 'yes':
				$I->waitForText(CheckoutModulePage::$headBilling, 30);
				$I->addressInformation($addressDetail);
				$I->waitForElement(CheckoutModulePage::$bankTransfer, 30);
				$I->click(CheckoutModulePage::$bankTransfer);
				break;
		}

		$I->waitForElement($frontend->product($productName), 30);
		$I->seeElement($frontend->product($productName));
		$I->click(CheckoutModulePage::$termAndConditions);
		$I->waitForElement(CheckoutModulePage::$checkoutFinalStep, 30);
		$I->scrollTo(CheckoutModulePage::$checkoutFinalStep);
		$I->click(CheckoutModulePage::$checkoutFinalStep);
		$priceTotal = 'Total: '.$currencyUnit['currencySymbol'].' '.($total).$currencyUnit['decimalSeparator'].$currencyUnit['numberZero'];
		$I->waitForText(CheckoutModulePage::$orderReceipt, 30, CheckoutModulePage::$orderReceiptTitle);
		$quantity = $I->grabTextFrom("//div[@class='update_cart']");
		$quantity = (int)$quantity;
		$productQuantity = (int)$productQuantity;

		if ($quantity == $productQuantity)
		{
			$I->see($productQuantity);
		}
		else
		{
			$I->dontSee($productQuantity);
		}

		$I->seeElement($frontend->finalCheckout($productName));
		$I->see($priceTotal);
	}
}