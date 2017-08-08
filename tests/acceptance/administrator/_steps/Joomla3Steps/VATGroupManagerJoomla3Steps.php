<?php
/**
 * Created by PhpStorm.
 * User: nhung nguyen
 * Date: 6/6/2017
 * Time: 11:01 AM
 */

namespace AcceptanceTester;


class VATGroupManagerJoomla3Steps extends AdminManagerJoomla3Steps
{
	public function addVATGroupsSave($VATGroupName)
	{
		$I = $this;
		$I->amOnPage(\VATGroupManagerJoomla3Page::$URL);
		$I->click("New");
		$I->verifyNotices(false, $this->checkForNotices(), 'VAT / Tax Group Management New');
		$I->checkForPhpNoticesOrWarnings();
		$I->fillField(\VATGroupManagerJoomla3Page::$VATGroupName, $VATGroupName);
		$I->click("Save");
		$I->waitForElement(\VATGroupManagerJoomla3Page::$VATGroupName, 30);
	}

	public function addVATGroupsSaveClose($VATGroupName)
	{
		$I = $this;
		$I->amOnPage(\VATGroupManagerJoomla3Page::$URL);
		$I->click("New");
		$I->verifyNotices(false, $this->checkForNotices(), 'VAT / Tax Group Management New');
		$I->checkForPhpNoticesOrWarnings();
		$I->fillField(\VATGroupManagerJoomla3Page::$VATGroupName, $VATGroupName);
		$I->click("Save & Close");
		$I->see("item saved", '.alert-success');
	}

	public function addVATGroupsCancel()
	{
		$I = $this;
		$I->amOnPage(\VATGroupManagerJoomla3Page::$URL);
		$I->click("New");
		$I->verifyNotices(false, $this->checkForNotices(), 'VAT / Tax Group Management New');
		$I->checkForPhpNoticesOrWarnings();
		$I->click("Cancel");
	}

	public function editVATGroupsName($VATGroupName, $VATGroupEdit)
	{
		$I = $this;
		$I->amOnPage(\VATGroupManagerJoomla3Page::$URL);
		$I->searchVATGroup($VATGroupName);
		$I->wait(3);
		$I->click($VATGroupName);
		$I->waitForElement(\VATGroupManagerJoomla3Page::$VATGroupName, 30);
		$I->verifyNotices(false, $this->checkForNotices(), 'VAT / Tax Group Management: [ Edit ]');
		$I->fillField(\VATGroupManagerJoomla3Page::$VATGroupName, $VATGroupEdit);
		$I->click("Save");
	}


	public function editVATGroupsNameSaveClose($VATGroupName, $VATGroupEdit)
	{
		$I = $this;
		$I->amOnPage(\VATGroupManagerJoomla3Page::$URL);
		$I->searchVATGroup($VATGroupName);
		$I->wait(3);
		$I->click($VATGroupName);
		$I->waitForElement(\VATGroupManagerJoomla3Page::$VATGroupName, 30);
		$I->verifyNotices(false, $this->checkForNotices(), 'VAT / Tax Group Management: [ Edit ]');
		$I->fillField(\VATGroupManagerJoomla3Page::$VATGroupName, $VATGroupEdit);
		$I->click("Save & Close");
		$I->see("item saved", '.alert-success');
	}

	public function editVATGroupsWithoutName($VATGroupName)
	{
		$I = $this;
		$I->amOnPage(\VATGroupManagerJoomla3Page::$URL);
		$I->searchVATGroup($VATGroupName);
		$I->wait(3);
		$I->click($VATGroupName);
		$I->waitForElement(\VATGroupManagerJoomla3Page::$VATGroupName, 30);
		$I->verifyNotices(false, $this->checkForNotices(), 'VAT / Tax Group Management: [ Edit ]');
		$I->fillField(\VATGroupManagerJoomla3Page::$VATGroupName, "");
		$I->click("Save & Close");
		$I->click("Save");
		$I->waitForText(\VATGroupManagerJoomla3Page::$fieldMissing, 60, '.alert-error');
		$I->waitForElement(\VATGroupManagerJoomla3Page::$VATGroupName, 30);
	}

	public function unpublishVATGroupsName($VATGroupName)
	{
		$I = $this;
		$I->amOnPage(\VATGroupManagerJoomla3Page::$URL);
		$I->searchVATGroup($VATGroupName);
		$I->wait(3);
		$I->click($VATGroupName);
		$I->waitForElement(\VATGroupManagerJoomla3Page::$VATGroupName, 30);
		$I->verifyNotices(false, $this->checkForNotices(), 'VAT / Tax Group Management: [ Edit ]');
		$I->click("Save");
	}

	public function changeVATGroupState($VATGroupsName)
	{
		$I = $this;
		$I->amOnPage(\VATGroupManagerJoomla3Page::$URL);
		$I->searchVATGroup($VATGroupsName);
		$I->wait(3);
		$I->see($VATGroupsName, \VATGroupManagerJoomla3Page::$VATGroupsNamePath);
		$I->click(\VATGroupManagerJoomla3Page::$VATGroupNameStatus);
	}

	public function getVATGroupsState($VATGroupsName)
	{
		$I = $this;
		$I->amOnPage(\VATGroupManagerJoomla3Page::$URL);
		$I->searchVATGroup($VATGroupsName);
		$I->wait(3);
		$I->see($VATGroupsName, \VATGroupManagerJoomla3Page::$VATGroupsNamePath);
		$text = $I->grabAttributeFrom(\VATGroupManagerJoomla3Page::$VATGroupNameStatus, 'onclick');
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
		$I = $this;
		$I->amOnPage(\VATGroupManagerJoomla3Page::$URL);
		$I->searchVATGroup($VATGroupsName);
		$I->wait(3);
		$I->click("Delete");
		$I->cancelPopup();

	}

	public function deleteVATGroupOK($VATGroupsName)
	{
		$I = $this;
		$I->amOnPage(\VATGroupManagerJoomla3Page::$URL);
		$I->searchVATGroup($VATGroupsName);
		$I->wait(3);
		$I->click(\VATGroupManagerJoomla3Page::$CheckAllVATGroup);
		$I->click("Delete");
		$I->acceptPopup();
		$I->see("1 item successfully deleted", '.alert-success');
		$I->fillField(\VATGroupManagerJoomla3Page::$VATGroupManagementSearch, $VATGroupsName);
		$I->pressKey(\VATGroupManagerJoomla3Page::$VATGroupManagementSearch, \Facebook\WebDriver\WebDriverKeys::ENTER);
		$I->dontSee($VATGroupsName, \VATGroupManagerJoomla3Page::$VATGroupsNamePath);


	}

	public function addVATGroupsMissingName()
	{
		$I = $this;
		$I->amOnPage(\VATGroupManagerJoomla3Page::$URL);
		$I->click("New");
		$I->verifyNotices(false, $this->checkForNotices(), 'VAT / Tax Group Management New');
		$I->checkForPhpNoticesOrWarnings();
		$I->click("Save");
		$I->waitForText(\VATGroupManagerJoomla3Page::$fieldMissing, 60, '.alert-error');
		$I->waitForElement(\VATGroupManagerJoomla3Page::$VATGroupName, 30);
	}

	public function deleteButton()
	{
		$I = $this;
		$I->amOnPage(\VATGroupManagerJoomla3Page::$URL);
		$I->click("Delete");
		$I->acceptPopup();
	}

	public function publishButton()
	{
		$I = $this;
		$I->amOnPage(\VATGroupManagerJoomla3Page::$URL);
		$I->click("Publish");
		$I->acceptPopup();
	}

	public function publishAllGroups()
	{
		$I = $this;
		$I->amOnPage(\VATGroupManagerJoomla3Page::$URL);
		$I->click(\VATGroupManagerJoomla3Page::$CheckAllVATGroup);
		$I->click("Publish");
		$I->waitForText("Message", 30, '.alert-success');
	}


	public function unpublishButton()
	{
		$I = $this;
		$I->amOnPage(\VATGroupManagerJoomla3Page::$URL);
		$I->click("Unpublish");
		$I->acceptPopup();
	}

	public function unpublishAllGroups()
	{
		$I = $this;
		$I->amOnPage(\VATGroupManagerJoomla3Page::$URL);
		$I->click(\VATGroupManagerJoomla3Page::$CheckAllVATGroup);
		$I->click("Unpublish");
		$I->waitForText("Message", 30, '.alert-success');
	}

	public function searchVATGroup($VATGroupName)
	{
		$I = $this;
		$I->wantTo('Search the VAT/Tax Groups ');
		$I->amOnPage(\VATGroupManagerJoomla3Page::$URL);
		$I->waitForText('VAT / Tax Group Management', 30, ['xpath' => "//h1"]);
		$I->filterListBySearching($VATGroupName);
	}
}