<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Step\AbstractStep;

/**
 * Class CouponManagerJoomla3Steps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class CouponSteps extends AbstractStep
{
	use Step\Traits\CheckIn, Step\Traits\Publish, Step\Traits\Delete;

	/**
	 * @param array $data
	 * @param $startDate
	 * @param $endDate
	 * @since 2.1.3
	 */
	public function checkStartDateLargerThanEndDate($data = array(), $type, $startDate, $endDate)
	{
		$I = $this;
		$I->amOnPage(CouponPage::$url);
		$I->checkForPhpNoticesOrWarnings();
		$I->click(CouponPage::$buttonNew);
		$I->checkForPhpNoticesOrWarnings();
		$I->fillFormData($this->getFormFields(), $data);
		$I->click(CouponPage::$startDateField);
		$I->addValueForField(CouponPage::$startDateField, $startDate, 10);
		$I->selectOption(CouponPage::$couponType, $type);
		$I->addValueForField(CouponPage::$endDateField, $endDate, 10);
		$I->click(CouponPage::$buttonSave);
		$I->assertSystemMessageContains(CouponPage::$messageFail);
	}

	/**
	 * @param $coupontName
	 * @since 2.1.3
	 */
	public function searchCoupon($coupontName)
	{
		$I = $this;
		$I->wantTo('Search the Product');
		$I->amOnPage(CouponPage::$url);
		$I->executeJS('window.scrollTo(0,0)');
		$I->click(\FrontEndProductManagerJoomla3Page::$buttonReset);
		$I->fillField(CouponPage::$searchField, $coupontName);
		$I->pressKey(CouponPage::$searchField, \Facebook\WebDriver\WebDriverKeys::ENTER);
		$I->waitForElement(['link' => $coupontName], 30);
	}

	/**
	 * @param $coupontName
	 * @throws Exception
	 * @since 2.1.3
	 */
	public function deleteCoupon($coupontName)
	{
		$I = $this;
		$I->amOnPage(CouponPage::$url);
		$I->checkForPhpNoticesOrWarnings(CouponPage::$url);
		$this->searchCoupon($coupontName);
		$I->checkAllResults();
		$I->wantTo('Test with delete product then accept');
		$I->click(CouponPage::$buttonDeleteCoupon);
		$I->acceptPopup();
		$I->waitForText(CouponPage::$messageDeleteCouponSuccess, 60);
		$I->dontSee($coupontName);
	}
}
