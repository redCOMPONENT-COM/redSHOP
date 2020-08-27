<?php
/**
 * @package     redSHOP
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;

use WrapperPage;

/**
 * Class WrappingSteps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    2.4
 */
class WrapperSteps extends AdminManagerJoomla3Steps
{
	/**
	 * Function to Create a Wrapper Item
	 *
	 * @param   string $nameWrapper Name of the Wrapper which is to be Create
	 *
	 * @return void
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function createWrapper($nameWrapper = 'ManageWrapperAdministratorCest ?##?', $nameCategoryID = 'Category Demo ?##?', $wrapperPrice)
	{
		$I = $this;
		$I->amOnPage(WrapperPage::$URL);
		$I->click(WrapperPage::$buttonNew);
		$I->fillField(WrapperPage::$wrapperName, $nameWrapper);
		$I->fillField(WrapperPage::$wrapperPrice, $wrapperPrice);
		$I->waitForElement(WrapperPage::$categoryID, 30);
		$I->fillField(WrapperPage::$categoryID, $nameCategoryID);
		$I->click(WrapperPage::$chooseCategoryID);
		$I->click(WrapperPage::$buttonSaveClose);
	}
	/**
	 * Function to Update a Wrapper Item
	 *
	 * @param   string $nameWrapper Name of the Wrapper which is to be Update
	 *
	 * @return void
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function updateWrapper($nameWrapper = 'ManageWrapperAdministratorCest ?##?', $newPrice = '50.00')
	{
		$I = $this;
		$I->amOnPage(WrapperPage::$URL);
		$I->click(WrapperPage::$search);
		$I->fillField(WrapperPage::$search, $nameWrapper);
		$I->click($nameWrapper);
		$I->click(WrapperPage::$wrapperPrice);
		$I->waitForElement(WrapperPage::$wrapperPrice, 30);
		$I->fillField(WrapperPage::$wrapperPrice, $newPrice);
		$I->click(WrapperPage::$buttonSaveClose);
		$I->seeElement(['link' => $nameWrapper]);
	}

	/**
	 * Function to Change Status a Wrapper Item
	 *
	 * @param   string $nameWrapper Name of the Wrapper which is to be Change Status
	 *
	 * @return void
	 * @since 1.4.0
	 */
	public function changeWrapperState($nameWrapper = 'ManageWrapperAdministratorCest ?##?')
	{
		$I = $this;
		$I->amOnPage(WrapperPage::$URL);
		$I->click(WrapperPage::$search);
		$I->fillField(WrapperPage::$search, $nameWrapper);
		$I->checkAllResults();
		$I->click(WrapperPage::$buttonUnpublish);
		$I->seeElement(['link' => $nameWrapper]);
		$I->click(WrapperPage::$search);
		$I->fillField(WrapperPage::$search, $nameWrapper);
		$I->checkAllResults();
		$I->click(WrapperPage::$buttonPublish);
		$I->seeElement(['link' => $nameWrapper]);
	}

	/**
	 * Function to Delete a Wrapper Item
	 *
	 * @param   string $nameWrapper Name of the Wrapper which is to be Delete
	 *
	 * @return void
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function deleteWrapper($nameWrapper = 'ManageWrapperAdministratorCest ?##?')
	{
		$I = $this;
		$I->amOnPage(WrapperPage::$URL);
		$I->checkForPhpNoticesOrWarnings();
		$I->click(WrapperPage::$search);
		$I->fillField(WrapperPage::$search, $nameWrapper);
		$I->checkAllResults();
		$I->waitForText(WrapperPage::$buttonDelete, 30);
		$I->click(WrapperPage::$buttonDelete);
		$I->acceptPopup();
		$I->dontSeeElement(['link' => $nameWrapper]);
	}

	/**
	 * @param $name
	 * @param $categoryname
	 * @param $price
	 * @throws \Exception
	 * @since 2.1.2.2
	 */
	public function checkWrapperInvalidField()
	{
		$I = $this;
		$I->amOnPage(WrapperPage::$URL);
		$I->click(WrapperPage::$buttonNew);
		$I->waitForText(WrapperPage::$buttonSaveClose, 30);
		$I->click(WrapperPage::$buttonSaveClose);
		$I->waitForText(WrapperPage::$messageMissingName, 30);
	}
}
