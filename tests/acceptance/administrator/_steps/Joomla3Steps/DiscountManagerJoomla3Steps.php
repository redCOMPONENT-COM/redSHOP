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
		$I->waitForText('Discount Detail Saved', 60, ['id' => 'system-message-container']);
		$I->seeElement(['link' => $verifyAmount]);
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
		$verifyAmount = '$ ' . $amount . ',00';
		$newVerifyAmount = '$ ' . $newAmount . ',00';
		$I->executeJS('window.scrollTo(0,0)');
		$I->waitForElement(['link' => $verifyAmount]);
		$I->click(['link' => $verifyAmount]);
		$I->waitForElement(\DiscountManagerJ3Page::$amount,30);
		$I->fillField(\DiscountManagerJ3Page::$amount, $newAmount);
		$I->click('Save & Close');
		$I->waitForText('Discount Detail Saved', 60, ['id' => 'system-message-container']);
		$I->seeElement(['link' => $newVerifyAmount]);
	}

	/**
	 * Function to change State of a Discount
	 *
	 * @param   string  $amount  Amount of the Discount
	 * @param   string  $state   State of the Discount
	 *
	 * @return void
	 */
	public function changeDiscountState($amount, $state = 'unpublish')
	{
		$I = $this;
		$verifyAmount = '$ ' . $amount . ',00';
		$I->amOnPage(\DiscountManagerJ3Page::$URL);
		$I->executeJS('window.scrollTo(0,0)');
		$I->waitForElement(['link' => $verifyAmount]);
		$I->click(['link' => $verifyAmount]);
		$I->waitForElement(\DiscountManagerJ3Page::$amount,30);

		if ($state == 'unpublish')
		{
			$I->selectOption(['css' => 'input[name=published]'], 0);
		}
		else
		{
			$I->selectOption(['css' => 'input[name=published]'], 0);
		}
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
		$this->search(new \DiscountManagerJ3Page, $amount, \DiscountManagerJ3Page::$firstResultRow, $functionName);
	}

	/**
	 * Function to get State of the Discount
	 *
	 * @param   String  $amount  Amount of the Discount
	 *
	 * @return string
	 */
	public function getDiscountState($amount)
	{
		$verifyAmount = '$ ' . $amount . ',00';
		$result = $this->getState(new \DiscountManagerJ3Page, $verifyAmount, \DiscountManagerJ3Page::$firstResultRow, \DiscountManagerJ3Page::$discountStatePath);

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

		$this->delete(new \DiscountManagerJ3Page, $amount, \DiscountManagerJ3Page::$firstResultRow, \DiscountManagerJ3Page::$selectFirst);
	}
}
