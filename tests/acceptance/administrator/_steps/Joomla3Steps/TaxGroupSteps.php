<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
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
		$client->wait(3);
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
		$client->wait(3);
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
		$client->wait(3);
		$client->click($VATGroupName);
		$client->waitForElement(\TaxGroupPage::$fieldName, 30);
		$client->verifyNotices(false, $this->checkForNotices(), \TaxGroupPage::$nameEditPage);
		$client->fillField(\TaxGroupPage::$fieldName, "");
		$client->click(\TaxGroupPage::$buttonSave);
		$client->waitForText(\TaxGroupPage::$messageErrorFieldMissing, 60, \TaxGroupPage::$selectorError);
		$client->waitForElement(\TaxGroupPage::$fieldName, 30);
	}

	public function unpublishVATGroupsName($VATGroupName)
	{
		$client = $this;
		$client->amOnPage(\TaxGroupPage::$url);
		$client->searchVATGroup($VATGroupName);
		$client->wait(3);
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
		$client->wait(3);
		$client->see($VATGroupsName, \TaxGroupPage::$resultRow);
		$client->click(\TaxGroupPage::$pathStatus);
	}

	public function getVATGroupsState($VATGroupsName)
	{
		$client = $this;
		$client->amOnPage(\TaxGroupPage::$url);
		$client->searchVATGroup($VATGroupsName);
		$client->wait(3);
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
		$client->wait(3);
		$client->click(\TaxGroupPage::$buttonDelete);
		$client->cancelPopup();

	}

	public function deleteVATGroupOK($VATGroupsName)
	{
		$client = $this;
		$client->amOnPage(\TaxGroupPage::$url);
		$client->searchVATGroup($VATGroupsName);
		$client->wait(3);
		$client->click(\TaxGroupPage::$pathCheckAll);
		$client->click(\TaxGroupPage::$buttonDelete);
		$client->acceptPopup();
		$client->waitForElement(\TaxGroupPage::$searchField,30);
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
		$client->waitForText(\TaxGroupPage::$messageErrorFieldMissing, 60, \TaxGroupPage::$selectorError);
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
		$client->click(\TaxGroupPage::$pathCheckAll);
		$client->click(\TaxGroupPage::$buttonPublish);
		$client->waitForText("Message", 30, \TaxGroupPage::$selectorSuccess);
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
		$client->click(\TaxGroupPage::$pathCheckAll);
		$client->click(\TaxGroupPage::$buttonUnpublish);
		$client->waitForText("Message", 30, \TaxGroupPage::$selectorSuccess);
	}

	public function searchVATGroup($VATGroupName)
	{
		$client = $this;
		$client->amOnPage(\TaxGroupPage::$url);
		$client->waitForText(\TaxGroupPage::$namePage, 30, \TaxGroupPage::$headPage);
		$client->filterListBySearching($VATGroupName);
	}
}