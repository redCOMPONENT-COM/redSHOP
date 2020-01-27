<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;

/**
 * Class TaxGroupSteps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class TaxGroupSteps extends AdminManagerJoomla3Steps
{
	public function addVATGroupsSave($VATGroupName)
	{
		$client = $this;
		$client->amOnPage(\TaxGroupPage::$url);
		$client->click(\TaxGroupPage::$buttonNew);
		$client->verifyNotices(false, $this->checkForNotices(), \TaxGroupPage::$nameEditPage);
		$client->checkForPhpNoticesOrWarnings();
		$client->fillField(\TaxGroupPage::$fieldName, $VATGroupName);
		$client->click(\TaxGroupPage::$buttonSave);
		$client->waitForElement(\TaxGroupPage::$fieldName, 30);
	}

	public function addVATGroupsSaveClose($VATGroupName)
	{
		$client = $this;
		$client->amOnPage(\TaxGroupPage::$url);
		$client->click(\TaxGroupPage::$buttonNew);
		$client->verifyNotices(false, $this->checkForNotices(), \TaxGroupPage::$nameEditPage);
		$client->checkForPhpNoticesOrWarnings();
		$client->fillField(\TaxGroupPage::$fieldName, $VATGroupName);
		$client->click(\TaxGroupPage::$buttonSaveClose);
		$client->see(\TaxGroupPage::$messageItemSaveSuccess, \TaxGroupPage::$selectorSuccess);
	}

	public function addVATGroupsCancel()
	{
		$client = $this;
		$client->amOnPage(\TaxGroupPage::$url);
		$client->click(\TaxGroupPage::$buttonNew);
		$client->verifyNotices(false, $this->checkForNotices(), \TaxGroupPage::$nameEditPage);
		$client->checkForPhpNoticesOrWarnings();
		$client->click(\TaxGroupPage::$buttonCancel);
	}

	public function editVATGroupsName($VATGroupName, $VATGroupEdit)
	{
		$client = $this;
		$client->amOnPage(\TaxGroupPage::$url);
		$client->searchVATGroup($VATGroupName);
		$client->click($VATGroupName);
		$client->waitForElement(\TaxGroupPage::$fieldName, 30);
		$client->verifyNotices(false, $this->checkForNotices(), \TaxGroupPage::$nameEditPage);
		$client->fillField(\TaxGroupPage::$fieldName, $VATGroupEdit);
		$client->click(\TaxGroupPage::$buttonSave);
		$client->waitForText(\TaxGroupPage::$messageItemSaveSuccess, 60, \TaxGroupPage::$selectorSuccess);
		$client->see(\TaxGroupPage::$messageItemSaveSuccess, \TaxGroupPage::$selectorSuccess);
	}

	public function editVATGroupsNameSaveClose($VATGroupName, $VATGroupEdit)
	{
		$client = $this;
		$client->amOnPage(\TaxGroupPage::$url);
		$client->searchVATGroup($VATGroupName);
		$client->click($VATGroupName);
		$client->waitForElement(\TaxGroupPage::$fieldName, 30);
		$client->verifyNotices(false, $this->checkForNotices(), \TaxGroupPage::$nameEditPage);
		$client->fillField(\TaxGroupPage::$fieldName, $VATGroupEdit);
		$client->click(\TaxGroupPage::$buttonSaveClose);
		$client->waitForText(\TaxGroupPage::$messageItemSaveSuccess, 60, \TaxGroupPage::$selectorSuccess);
		$client->see(\TaxGroupPage::$messageItemSaveSuccess, \TaxGroupPage::$selectorSuccess);
	}

	public function editVATGroupsWithoutName($VATGroupName)
	{
		$client = $this;
		$client->amOnPage(\TaxGroupPage::$url);
		$client->searchVATGroup($VATGroupName);
		$client->click($VATGroupName);
		$client->waitForElement(\TaxGroupPage::$fieldName, 30);
		$client->verifyNotices(false, $this->checkForNotices(), \TaxGroupPage::$nameEditPage);
		$client->fillField(\TaxGroupPage::$fieldName, "");
		$client->click(\TaxGroupPage::$buttonSaveTax);
		$client->waitForText(\TaxGroupPage::$messageErrorFieldMissing, 60, \TaxGroupPage::$selectorMissing);
		$client->waitForElement(\TaxGroupPage::$fieldName, 30);
	}

	public function unpublishVATGroupsName($VATGroupName)
	{
		$client = $this;
		$client->amOnPage(\TaxGroupPage::$url);
		$client->searchVATGroup($VATGroupName);
		$client->click($VATGroupName);
		$client->waitForElement(\TaxGroupPage::$fieldName, 30);
		$client->verifyNotices(false, $this->checkForNotices(), \TaxGroupPage::$nameEditPage);
		$client->click(\TaxGroupPage::$buttonSave);
	}

	public function changeVATGroupState($VATGroupsName)
	{
		$client = $this;
		$client->amOnPage(\TaxGroupPage::$url);
		$client->searchVATGroup($VATGroupsName);
		$client->see($VATGroupsName, \TaxGroupPage::$resultRow);
		$client->click(\TaxGroupPage::$pathStatus);
	}

	public function getVATGroupsState($VATGroupsName)
	{
		$client = $this;
		$client->amOnPage(\TaxGroupPage::$url);
		$client->searchVATGroup($VATGroupsName);
		$client->see($VATGroupsName, \TaxGroupPage::$resultRow);
		$text = $client->grabAttributeFrom(\TaxGroupPage::$pathStatus, 'onclick');

		if (strpos($text, 'unpublish') > 0)
		{
			$result = 'published';
		}
		else
		{
			$result = 'unpublished';
		}

		return $result;
	}

	public function deleteVATGroupCancel($VATGroupsName)
	{
		$client = $this;
		$client->amOnPage(\TaxGroupPage::$url);
		$client->searchVATGroup($VATGroupsName);
		$client->click(\TaxGroupPage::$buttonDelete);
		$client->cancelPopup();

	}

	public function deleteVATGroupOK($VATGroupsName)
	{
		$client = $this;
		$client->amOnPage(\TaxGroupPage::$url);
		$client->searchVATGroup($VATGroupsName);
		$client->checkAllResults();
		$client->click(\TaxGroupPage::$buttonDelete);
		$client->acceptPopup();
		$client->waitForElement(\TaxGroupPage::$searchField, 30);
		$client->waitForJS("return window.jQuery && jQuery.active == 0;", 30);

		try
		{
			$client->waitForText(\StatePage::$messageDeleteSuccess, 5, \StatePage::$selectorSuccess);
			$client->see(\StatePage::$messageDeleteSuccess, \StatePage::$selectorSuccess);
		}catch (\Exception $e)
		{
			$client->waitForText(\StatePage::$messageNoItemOnTable, 10, \StatePage::$selectorAlert);
			$client->see(\StatePage::$messageNoItemOnTable, \StatePage::$selectorAlert);
		}

		$client->fillField(\TaxGroupPage::$searchField, $VATGroupsName);
		$client->pressKey(\TaxGroupPage::$searchField, \Facebook\WebDriver\WebDriverKeys::ENTER);
		$client->dontSee($VATGroupsName, \TaxGroupPage::$pathName);
	}

	public function addVATGroupsMissingName()
	{
		$client = $this;
		$client->amOnPage(\TaxGroupPage::$url);
		$client->click(\TaxGroupPage::$buttonNew);
		$client->verifyNotices(false, $this->checkForNotices(), \TaxGroupPage::$nameEditPage);
		$client->checkForPhpNoticesOrWarnings();
		$client->click(\TaxGroupPage::$buttonSave);
		$client->waitForText(\TaxGroupPage::$messageErrorFieldMissing, 60, \TaxGroupPage::$selectorMissing);
		$client->waitForElement(\TaxGroupPage::$fieldName, 30);
	}

	public function deleteButton()
	{
		$client = $this;
		$client->amOnPage(\TaxGroupPage::$url);
		$client->click(\TaxGroupPage::$buttonDelete);
		$client->acceptPopup();
		$client->see(\TaxGroupPage::$namePage, \TaxGroupPage::$selectorPageTitle);
	}

	public function publishButton()
	{
		$client = $this;
		$client->amOnPage(\TaxGroupPage::$url);
		$client->click(\TaxGroupPage::$buttonPublish);
		$client->acceptPopup();
		$client->see(\TaxGroupPage::$namePage, \TaxGroupPage::$selectorPageTitle);
	}

	public function publishAllGroups()
	{
		$client = $this;
		$client->amOnPage(\TaxGroupPage::$url);
		$client->checkAllResults();
		$client->click(\TaxGroupPage::$buttonPublish);
	}


	public function unpublishButton()
	{
		$client = $this;
		$client->amOnPage(\TaxGroupPage::$url);
		$client->click(\TaxGroupPage::$buttonUnpublish);
		$client->acceptPopup();
	}

	public function unpublishAllGroups()
	{
		$client = $this;
		$client->amOnPage(\TaxGroupPage::$url);
		$client->checkAllResults();
		$client->click(\TaxGroupPage::$buttonUnpublish);
	}

	public function searchVATGroup($VATGroupName)
	{
		$client = $this;
		$client->amOnPage(\TaxGroupPage::$url);
		$client->waitForText(\TaxGroupPage::$namePage, 30, \TaxGroupPage::$headPage);
		$client->filterListBySearching($VATGroupName);
	}

	public function checkButton($name)
	{
		$I = $this;
		$I->amOnPage(\TaxGroupPage::$url);

		switch ($name)
		{
			case 'copy':
				$I->click(\TaxGroupPage::$buttonCopy);
				$I->acceptPopup();
				break;
			case 'delete':
				$I->click(\TaxGroupPage::$buttonDelete);
				$I->acceptPopup();
				break;
			case 'publish':
				$I->click(\TaxGroupPage::$buttonPublish);
				$I->acceptPopup();
				break;
			case 'unpublish':
				$I->click(\TaxGroupPage::$buttonUnpublish);
				$I->acceptPopup();
				break;
			default:
				break;
		}
		$I->see(\TaxGroupPage::$namePage, \TaxGroupPage::$headPage);
	}
}
