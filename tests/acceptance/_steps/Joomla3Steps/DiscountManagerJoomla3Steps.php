<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace AcceptanceTester;
/**
 * Class DiscountManagerJoomla3Steps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class DiscountManagerJoomla3Steps extends AdminManagerJoomla3Steps
{
	/**
	 * Function to Add a New Discount
	 *
	 * @param   string  $amount          Discount Amount
	 * @param   string  $discountAmount  Amount on the Discount
	 * @param   string  $shopperGroup    Group for the Shopper
	 * @param   string  $discountType    Type of Discount
	 *
	 * @return void
	 */
	public function addDiscount($amount = '100', $discountAmount = '100', $shopperGroup = 'Default Private', $discountType = 'Total')
	{
		$I = $this;
		$config = $I->getConfig();
		$I->amOnPage(\DiscountManagerJ3Page::$URL);
		$discountManagerPage = new \DiscountManagerJ3Page;
		$verifyAmount = '$ ' . $amount . ',00';
		$I->verifyNotices(false, $this->checkForNotices(), 'Discount Manager Page');
		$I->click('New');
		$I->waitForElement(\DiscountManagerJ3Page::$amount, 30);
		$I->fillField(\DiscountManagerJ3Page::$amount, $amount);
		$I->fillField(\DiscountManagerJ3Page::$discountAmount, $discountAmount);
		$I->click(\DiscountManagerJ3Page::$discountTypeDropDown);
		$I->click($discountManagerPage->discountType($discountType));
		$I->click(\DiscountManagerJ3Page::$shopperGroupDropDown);
		$I->click($discountManagerPage->shopperGroup($shopperGroup));
		$I->click('Save & Close');
		$I->waitForText(\DiscountManagerJ3Page::$discountSuccessMessage, 60);
		$I->see(\DiscountManagerJ3Page::$discountSuccessMessage);
		$I->click('ID');
		$I->see($verifyAmount, \DiscountManagerJ3Page::$firstResultRow);
		$I->click('ID');
	}

	/**
	 * Function to edit an existing Discount
	 *
	 * @param   string  $amount     Amount for the Discount
	 * @param   string  $newAmount  New Amount for the Discount
	 *
	 * @return void
	 */
	public function editDiscount($amount = '100', $newAmount = '1000')
	{
		$I = $this;
		$I->amOnPage(\DiscountManagerJ3Page::$URL);
		$I->click('ID');
		$verifyAmount = '$ ' . $amount . ',00';
		$newVerifyAmount = '$ ' . $newAmount . ',00';
		$I->see($verifyAmount, \DiscountManagerJ3Page::$firstResultRow);
		$I->click(\DiscountManagerJ3Page::$selectFirst);
		$I->click('Edit');
		$I->waitForElement(\DiscountManagerJ3Page::$amount, 30);
		$I->fillField(\DiscountManagerJ3Page::$amount, $newAmount);
		$I->click('Save & Close');
		$I->waitForText(\DiscountManagerJ3Page::$discountSuccessMessage);
		$I->see(\DiscountManagerJ3Page::$discountSuccessMessage);
		$I->see($newVerifyAmount, \DiscountManagerJ3Page::$firstResultRow);
		$I->click('ID');
	}

	/**
	 * Function to change State of a Discount
	 *
	 * @param   string  $amount  Amount of the Discount
	 * @param   string  $state   State of the Discount
	 *
	 * @return void
	 */
	public function changeState($amount, $state = 'unpublish')
	{
		$I = $this;
		$I->amOnPage(\DiscountManagerJ3Page::$URL);
		$I->click('ID');
		$verifyAmount = '$ ' . $amount . ',00';
		$I->see($verifyAmount, \DiscountManagerJ3Page::$firstResultRow);
		$I->click(\DiscountManagerJ3Page::$selectFirst);

		if ($state == 'unpublish')
		{
			$I->click("Unpublish");
		}
		else
		{
			$I->click("Publish");
		}

		$I->click('ID');

	}

	/**
	 * Function to Search for a Discount
	 *
	 * @param   string  $amount        Amount of the Discount
	 * @param   string  $functionName  Name of the function After Which search is being Called
	 *
	 * @return void
	 */
	public function searchDiscount($amount, $functionName = 'Search')
	{
		$I = $this;
		$I->amOnPage(\DiscountManagerJ3Page::$URL);
		$I->click('ID');
		$verifyAmount = '$ ' . $amount . ',00';

		if ($functionName == 'Search')
		{
			$I->see($verifyAmount, \DiscountManagerJ3Page::$firstResultRow);
		}
		else
		{
			$I->dontSee($verifyAmount, \DiscountManagerJ3Page::$firstResultRow);
		}

		$I->click('ID');
	}

	/**
	 * Function to get State of the Discount
	 *
	 * @param   String  $amount  Amount of the Discount
	 *
	 * @return string
	 */
	public function getState($amount)
	{
		$I = $this;
		$I->amOnPage(\DiscountManagerJ3Page::$URL);
		$I->click('ID');
		$verifyAmount = '$ ' . $amount . ',00';
		$I->see($verifyAmount, \DiscountManagerJ3Page::$firstResultRow);
		$text = $I->grabAttributeFrom(\DiscountManagerJ3Page::$discountStatePath, 'onclick');

		if (strpos($text, 'unpublish') > 0)
		{
			$result = 'published';
		}

		if (strpos($text, 'publish') > 0)
		{
			$result = 'unpublished';
		}

		$I->click('ID');

		return $result;
	}

	/**
	 * Function to Delete Discount
	 *
	 * @param   String  $amount  Amount of the Discount which is to be Deleted
	 *
	 * @return void
	 */
	public function deleteDiscount($amount)
	{
		$I = $this;
		$I->amOnPage(\DiscountManagerJ3Page::$URL);
		$I->click('ID');
		$verifyAmount = '$ ' . $amount . ',00';
		$I->see($verifyAmount, \DiscountManagerJ3Page::$firstResultRow);
		$I->click(\DiscountManagerJ3Page::$selectFirst);
		$I->click('Delete');
		$I->dontSee($verifyAmount, \DiscountManagerJ3Page::$firstResultRow);
		$I->click('ID');
	}
}
