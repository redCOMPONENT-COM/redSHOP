<?php
/**
 * ShippingSteps for shipping rate
 */

namespace AcceptanceTester;

use ShippingPage;

/**
 * Class StateSteps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class ShippingSteps extends AdminManagerJoomla3Steps
{
	/**
	 * @param   string $shippingMethod Shipping method
	 * @param   array  $shipping       Shipping data
	 * @param   string $function       Function
	 *
	 * @return  void
	 * @throws  \Exception
	 */
	public function createShippingRateStandard($shippingMethod, $shipping = array(), $function = 'save')
	{
		$I = $this;
		$I->amOnPage(ShippingPage::$shippingManagementUrl);
		$usePage = new ShippingPage;
		$I->waitForElement($usePage->xPathATag($shippingMethod), 30);
		$I->click($usePage->xPathATag($shippingMethod));
		$I->waitForElement(ShippingPage::$shippingRate, 30);
		$I->click(ShippingPage::$shippingRate);
		$I->click(ShippingPage::$buttonNew);
		$I->waitForElement(ShippingPage::$shippingName, 30);

		if (isset($shipping['shippingName']))
		{
			$I->fillField(ShippingPage::$shippingName, $shipping['shippingName']);
		}

		if (isset($shipping['weightStart']))
		{
			$I->fillField(ShippingPage::$weightStart, $shipping['weightStart']);
		}

		if (isset($shipping['weightEnd']))
		{
			$I->fillField(ShippingPage::$weightEnd, $shipping['weightEnd']);
		}

		if (isset($shipping['weightStart']))
		{
			$I->fillField(ShippingPage::$weightStart, $shipping['$weightStart']);
		}

		if (isset($shipping['volumeStart']))
		{
			$I->fillField(ShippingPage::$volumeStart, $shipping['volumeStart']);
		}

		if (isset($shipping['volumeEnd']))
		{
			$I->fillField(ShippingPage::$volumeEnd, $shipping['volumeEnd']);
		}

		if (isset($shipping['shippingRateLenghtStart']))
		{
			$I->fillField(ShippingPage::$shippingRateLenghtStart, $shipping['shippingRateLenghtStart']);
		}

		$I->fillField(ShippingPage::$shippingRateValue, $shipping['shippingRate']);

		if (isset($shipping['shippingRateLegnhtEnd']))
		{
			$I->fillField(ShippingPage::$shippingRateLegnhtEnd, $shipping['shippingRateLegnhtEnd']);
		}

		if (isset($shipping['shippingRateWidthStart']))
		{
			$I->fillField(ShippingPage::$shippingRateWidthStart, $shipping['shippingRateWidthStart']);
		}

		if (isset($shipping['shippingRateWidthEnd']))
		{
			$I->fillField(ShippingPage::$shippingRateWidthEnd, $shipping['shippingRateWidthEnd']);
		}

		if (isset($shipping['shippingRateHeightEnd']))
		{
			$I->fillField(ShippingPage::$shippingRateHeightEnd, $shipping['shippingRateHeightEnd']);
		}

		if (isset($shipping['shippingRateHeightStart']))
		{
			$I->fillField(ShippingPage::$shippingRateHeightStart, $shipping['shippingRateHeightStart']);
		}

		if (isset($shipping['orderTotalStart']))
		{
			$I->fillField(ShippingPage::$orderTotalStart, $shipping['orderTotalStart']);
		}

		if (isset($shipping['orderTotalEnd ']))
		{
			$I->fillField(ShippingPage::$orderTotalEnd, $shipping['orderTotalEnd ']);
		}

		if (isset($shipping['zipCodeStart']))
		{
			$I->fillField(ShippingPage::$zipCodeStart, $shipping['zipCodeStart']);
		}

		if (isset($shipping['zipCodeEnd']))
		{
			$I->fillField(ShippingPage::$zipCodeEnd, $shipping['zipCodeEnd']);
		}

		if (isset($shipping['country']))
		{
			$I->waitForElement(ShippingPage::$country, 30);
			$I->fillField(ShippingPage::$country, $shipping['country']);
			$I->pressKey(ShippingPage::$country, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);
		}

		if (isset($shipping['shippingRateProduct']))
		{
			$I->waitForElement(ShippingPage::$country, 30);
			$I->fillField(ShippingPage::$shippingRateProduct, $shipping['shippingRateProduct']);
			$I->pressKey(ShippingPage::$shippingRateProduct, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);
		}

		if (isset($shipping['shippingCategory']))
		{
			$I->waitForElement(ShippingPage::$shippingCategory, 30);
			$I->click(ShippingPage::$shippingCategory);
			$I->fillField(ShippingPage::$shippingCategory, $shipping['shippingCategory']);

			$I->pressKey(ShippingPage::$shippingCategory, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);
		}

		if (isset($shipping['shippingShopperGroups']))
		{
			$I->waitForElement(ShippingPage::$shippingShopperGroups, 30);
			$I->fillField(ShippingPage::$shippingShopperGroups, $shipping['shippingShopperGroups']);
			$I->pressKey(ShippingPage::$shippingShopperGroups, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);
		}

		if (isset($shipping['shippingPriority']))
		{
			$I->fillField(ShippingPage::$shippingPriority, $shipping['shippingPriority']);
		}

		if (isset($shipping['shippingRateFor']))
		{
			$I->click(ShippingPage::$shippingRateFor);
			$I->seeElement(ShippingPage::$shippingRateForSearch, 30);
			$I->fillField(ShippingPage::$shippingRateForSearch, $shipping['shippingRateFor']);
			$I->pressKey(ShippingPage::$shippingRateFor, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);
		}

		if (isset($shipping['shippingVATGroups']))
		{
			$I->click(ShippingPage::$shippingVATGroups);
			$I->seeElement(ShippingPage::$shippingVATGroupsSearh, 30);
			$I->fillField(ShippingPage::$shippingVATGroupsSearh, $shipping['shippingVATGroups']);
			$I->pressKey(ShippingPage::$shippingVATGroups, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);
		}

		switch ($function)
		{
			case 'save':
				$I->click(ShippingPage::$buttonSave);
				$I->waitForElement(ShippingPage::$selectorSuccess, 30);
				$I->click(ShippingPage::$buttonClose);
				$I->seeLink($shipping['shippingName']);
				break;

			case 'saveclose':
				$I->click(ShippingPage::$buttonSaveClose);
                $I->waitForElement(ShippingPage::$selectorSuccess, 30);
				$I->seeLink($shipping['shippingName']);
				break;

			default:
				break;
		}
	}
    /**
     * @param $shippingName
     * @param $shippingNameEdit
     * @param $shippingRate
     * @param $function
     * @throws \Exception
     */
	public function editShippingRateStandard($shippingName, $shippingNameEdit, $shippingRate, $function)
	{
		$I = $this;
		$I->amOnPage(ShippingPage::$shippingManagementUrl);
		$I->click(ShippingPage::$standShipping);
		$I->waitForElement(ShippingPage::$shippingRate, 30);
		$I->click(ShippingPage::$shippingRate);
		$I->seeLink($shippingName);
		$I->click($shippingName);
		$I->waitForElement(ShippingPage::$shippingName, 30);
		$I->fillField(ShippingPage::$shippingName, $shippingNameEdit);
		$I->executeJS(ShippingPage::$scrollDown);
		$I->fillField(ShippingPage::$shippingRateValue, $shippingRate);
		switch ($function)
		{
			case 'save':
				$I->click(ShippingPage::$buttonSave);
                $I->waitForElement(ShippingPage::$selectorSuccess, 30);
                $I->click(ShippingPage::$buttonClose);
                $I->seeLink($shippingNameEdit);
				break;

			case 'saveclose':
				$I->click(ShippingPage::$buttonSaveClose);
				$I->waitForElement(ShippingPage::$selectorSuccess, 30);
				$I->seeLink($shippingNameEdit);
		}

	}
    /**
     * @param $shippingMethod
     * @param $shippingName
     * @throws \Exception
     */
	public function deleteShippingRate($shippingMethod, $shippingName)
	{
		$I = $this;
		$I->amOnPage(ShippingPage::$shippingManagementUrl);
		$usePage = new ShippingPage();

		$I->click($usePage->xPathATag($shippingMethod));
		$I->waitForElement(ShippingPage::$shippingRate, 30);
		$I->click(ShippingPage::$shippingRate);
		$I->seeLink($shippingName);
		$I->checkAllResults();
		$I->click(ShippingPage::$buttonDelete);
		$I->dontSee($shippingName);
	}
}