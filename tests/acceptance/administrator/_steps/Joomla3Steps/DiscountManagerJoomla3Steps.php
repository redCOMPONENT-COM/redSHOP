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
	 * @param   string  $name            Discount name
	 * @param   string  $amount          Discount Amount
	 * @param   string  $discountAmount  Amount on the Discount
	 * @param   string  $shopperGroup    Group for the Shopper
	 * @param   string  $discountType    Type of Discount
	 *
	 * @return void
	 */
	public function addDiscount($name = '', $amount = '100', $discountAmount = '100', $shopperGroup = 'Default Private', $discountType = 'Total')
	{
		$I = $this;
		$I->amOnPage(\DiscountManagerJ3Page::$URL);
		$verifyAmount = 'DKK ' . $amount . ',00';
		$I->checkForPhpNoticesOrWarnings();
		$I->click('New');
		$I->waitForElement(\DiscountManagerJ3Page::$amount, 30);
		$I->fillField(\DiscountManagerJ3Page::$name, $name);
		$I->fillField(\DiscountManagerJ3Page::$amount, $amount);
		$I->fillField(\DiscountManagerJ3Page::$discountAmount, $discountAmount);
		$I->click(['id' => "s2id_discount_type"]);
		$I->fillField(['id' => "s2id_autogen2_search"], $discountType);
		$I->waitForElement(['id' => "select2-results-2"], 30);
		$I->click(['id' => "select2-results-2"]);
		$I->click(['id' => "s2id_shopper_group_id"]);
		$I->waitForElement(['xpath' => "//ul[@class='select2-results']//li//div//span//..[contains(text(), '" . $shopperGroup . "')]"], 30);
		$I->click(['xpath' => "//ul[@class='select2-results']//li//div//span//..[contains(text(), '" . $shopperGroup . "')]"]);
		$I->click('Save & Close');
		$I->waitForText('Discount Detail Saved', 60, ['id' => 'system-message-container']);
        $I->filterListBySearchDiscount($name, ['id' => 'name_filter']);
        $I->seeElement(['link' => $name]);
	}

	/**
	 * Function to edit an existing Discount
	 *
	 * @param   string  $name       Discount name
	 * @param   string  $amount     Amount for the Discount
	 * @param   string  $newAmount  New Amount for the Discount
	 *
	 * @return void
	 */
	public function editDiscount($name = '', $amount = '100', $newAmount = '1000')
	{
		$I = $this;
		$I->amOnPage(\DiscountManagerJ3Page::$URL);
		$verifyAmount = \DiscountManagerJ3Page::getCurrencyCode() . $amount . ',00';
		$newVerifyAmount = \DiscountManagerJ3Page::getCurrencyCode() . $newAmount . ',00';


        $I->filterListBySearchDiscount($name, ['id' => 'name_filter']);
		$I->executeJS('window.scrollTo(0,0)');
		$I->waitForElement(['link' => $verifyAmount]);
		$I->click(['link' => $verifyAmount]);
		$I->waitForElement(\DiscountManagerJ3Page::$amount,30);
		$I->fillField(\DiscountManagerJ3Page::$amount, $newAmount);
		$I->click('Save & Close');
		$I->waitForText('Discount Detail Saved', 60, ['id' => 'system-message-container']);
		$I->click('Reset');
        $I->filterListBySearchDiscount($name, ['id' => 'name_filter']);
        $I->seeElement(['link' => $name]);
	}

	/**
	 * Function to change State of a Discount
	 *
	 * @param   string  $name       Discount name
	 * @param   string  $amount  Amount of the Discount
	 * @param   string  $state   State of the Discount
	 *
	 * @return void
	 */
	public function changeDiscountState($name, $amount, $state = 'unpublish')
	{
		$I = $this;
		$verifyAmount = \DiscountManagerJ3Page::getCurrencyCode() . $amount . ',00';
		$I->amOnPage(\DiscountManagerJ3Page::$URL);
		$I->executeJS('window.scrollTo(0,0)');
		$I->click('Reset');
        $I->filterListBySearchDiscount($name, ['id' => 'name_filter']);
		$I->waitForElement(['link' => $verifyAmount]);

		if ($state == 'unpublish')
		{
			$I->click(['css' => "a[data-original-title='Unpublish Item']"], 0);
			$I->waitForText('Discount Detail UnPublished Successfully', 60, ['id' => 'system-message-container']);
		}
		else
		{
			$I->click(['css' => "a[data-original-title='Publish Item']"], 0);
			$I->waitForText('Discount Detail Published Successfully', 60, ['id' => 'system-message-container']);
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
	 * @param   string  $name       Discount name
	 * @param   String  $amount  Amount of the Discount
	 *
	 * @return string
	 */
	public function getDiscountState($name, $amount)
	{
		$I = $this;
		$verifyAmount = \DiscountManagerJ3Page::getCurrencyCode() . $amount . ',00';
        $I->filterListBySearchDiscount($name, ['id' => 'name_filter']);

		$result = $I->getState(new \DiscountManagerJ3Page, $verifyAmount, \DiscountManagerJ3Page::$firstResultRow, \DiscountManagerJ3Page::$discountStatePath);

		return $result;
	}

	/**
	 * Function to Delete Discount
	 *
	 * @param   string  $name       Discount name
	 * @param   String  $amount  Amount of the Discount which is to be Deleted
	 *
	 * @return void
	 */
	public function deleteDiscount($name, $amount)
	{
		$I = $this;
		$I->amOnPage(\DiscountManagerJ3Page::$URL);
		$I->click('Reset');
        $I->filterListBySearchDiscount($name, ['id' => 'name_filter']);

		$I->click(\DiscountManagerJ3Page::$selectFirst);
		$I->click('Delete');
		$I->waitForText('Discount Detail Deleted Successfully', 60, ['id' => 'system-message-container']);
		$I->dontSeeElement(['link' => $name]);
	}
}
