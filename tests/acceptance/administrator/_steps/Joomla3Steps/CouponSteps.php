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
	 * @throws Exception
	 * @since 2.1.3
	 */
	public function deleteAllCoupon()
	{
		$I = $this;
		$I->amOnPage(CouponPage::$url);
		$I->checkForPhpNoticesOrWarnings();
		$I->checkAllResults();
		$I->waitForElementVisible(CouponPage::$buttonDeleteCoupon, 30);
		$I->click(CouponPage::$buttonDeleteCoupon);
		$I->acceptPopup();
		$I->waitForText('item successfully deleted');
	}

	public function searchCoupon($coupontName)
	{
		$I = $this;
		$I->wantTo('Search the Product');
		$I->amOnPage(\ProductManagerPage::$URL);
		$I->filterListBySearchingProduct($coupontName);
	}

	public function deleteCoupon($coupontName)
	{
		$I = $this;
		$I->amOnPage(CouponPage::$url);
		$I->checkForPhpNoticesOrWarnings();
		$this->searchProduct($coupontName);
		$I->checkAllResults();
		$I->click(ProductManagerPage::$buttonDelete);

		$I->wantTo('Test with delete product but then cancel');
		$I->cancelPopup();

		$I->wantTo('Test with delete product then accept');
		$I->click(ProductManagerPage::$coupontName);
		$I->acceptPopup();
		$I->waitForText(ProductManagerPage::$messageDeleteProductSuccess, 60, ProductManagerPage::$selectorSuccess);
		$I->dontSee($productName);

	}
}
