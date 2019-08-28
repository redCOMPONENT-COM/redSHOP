<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

//namespace AcceptanceTester;
use Step\AbstractStep;

/**
 * Class TaxGroupSteps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class Tax_GroupSteps extends AbstractStep
{
	use Step\Traits\CheckIn, Step\Traits\Publish, Step\Traits\Delete; //extends AdminManagerJoomla3Steps
	public function addVATGroupsSave($VATGroupName)
	{
		$client = $this;
		$client->amOnPage(Tax_GroupPage::$url);
		$client->click(Tax_GroupPage::$buttonNew);
		$client->verifyNotices(false, $this->checkForNotices(), Tax_GroupPage::$nameEditPage);
		$client->checkForPhpNoticesOrWarnings();
		$client->fillField(Tax_GroupPage::$fieldName, $VATGroupName);
		$client->click(Tax_GroupPage::$buttonSave);
		$client->waitForElement(Tax_GroupPage::$fieldName, 30);
	}

	public function addVATGroupsSaveClose($VATGroupName)
	{
		$client = $this;
		$client->amOnPage(Tax_GroupPage::$url);
		$client->click(Tax_GroupPage::$buttonNew);
		$client->verifyNotices(false, $this->checkForNotices(), Tax_GroupPage::$nameEditPage);
		$client->checkForPhpNoticesOrWarnings();
		$client->fillField(Tax_GroupPage::$fieldName, $VATGroupName);
		$client->click(Tax_GroupPage::$buttonSaveClose);
		$client->see(Tax_GroupPage::$messageItemSaveSuccess, Tax_GroupPage::$selectorSuccess);
	}

	public function addVATGroupsCancel()
	{
		$client = $this;
		$client->amOnPage(Tax_GroupPage::$url);
		$client->click(Tax_GroupPage::$buttonNew);
		$client->verifyNotices(false, $this->checkForNotices(), Tax_GroupPage::$nameEditPage);
		$client->checkForPhpNoticesOrWarnings();
		$client->click(Tax_GroupPage::$buttonCancel);
		$client->see(Tax_GroupPage::$namePage, Tax_GroupPage::$headPage);
	}

	public function editVATGroupsName($VATGroupName, $VATGroupEdit)
	{
		$client = $this;
		$client->amOnPage(Tax_GroupPage::$url);
		$client->searchVATGroup($VATGroupName);
		$client->click($VATGroupName);
		$client->waitForElement(Tax_GroupPage::$fieldName, 30);
		$client->verifyNotices(false, $this->checkForNotices(), Tax_GroupPage::$nameEditPage);
		$client->fillField(Tax_GroupPage::$fieldName, $VATGroupEdit);
		$client->click(Tax_GroupPage::$buttonSave);
		$client->waitForText(Tax_GroupPage::$messageItemSaveSuccess, 60, Tax_GroupPage::$selectorSuccess);
		$client->see(Tax_GroupPage::$messageItemSaveSuccess, Tax_GroupPage::$selectorSuccess);
	}

	public function editVATGroupsNameSaveClose($VATGroupName, $VATGroupEdit)
	{
		$client = $this;
		$client->amOnPage(Tax_GroupPage::$url);
		$client->searchVATGroup($VATGroupName);
		$client->click($VATGroupName);
		$client->waitForElement(Tax_GroupPage::$fieldName, 30);
		$client->verifyNotices(false, $this->checkForNotices(), Tax_GroupPage::$nameEditPage);
		$client->fillField(Tax_GroupPage::$fieldName, $VATGroupEdit);
		$client->click(Tax_GroupPage::$buttonSaveClose);
		$client->waitForText(Tax_GroupPage::$messageItemSaveSuccess, 60, Tax_GroupPage::$selectorSuccess);
		$client->see(Tax_GroupPage::$messageItemSaveSuccess, Tax_GroupPage::$selectorSuccess);
	}

	public function editVATGroupsWithoutName($VATGroupName)
	{
		$client = $this;
		$client->amOnPage(Tax_GroupPage::$url);
		$client->searchVATGroup($VATGroupName);
		$client->click($VATGroupName);
		$client->waitForElement(Tax_GroupPage::$fieldName, 30);
		$client->verifyNotices(false, $this->checkForNotices(), Tax_GroupPage::$nameEditPage);
		$client->fillField(Tax_GroupPage::$fieldName, "");
		$client->click(Tax_GroupPage::$buttonSaveTax);
		$client->waitForText(Tax_GroupPage::$messageErrorFieldMissing, 60, Tax_GroupPage::$selectorMissing);
		$client->waitForElement(Tax_GroupPage::$fieldName, 30);
	}

	public function unpublishVATGroupsName($VATGroupName)
	{
		$client = $this;
		$client->amOnPage(Tax_GroupPage::$url);
		$client->searchVATGroup($VATGroupName);
		$client->click($VATGroupName);
		$client->waitForElement(Tax_GroupPage::$fieldName, 30);
		$client->verifyNotices(false, $this->checkForNotices(), Tax_GroupPage::$nameEditPage);
		$client->click(Tax_GroupPage::$buttonSave);
	}

	public function changeVATGroupState($VATGroupsName)
	{
		$client = $this;
		$client->amOnPage(Tax_GroupPage::$url);
		$client->searchVATGroup($VATGroupsName);
		$client->see($VATGroupsName, Tax_GroupPage::$resultRow);
		$client->click(Tax_GroupPage::$pathStatus);
	}

	public function getVATGroupsState($VATGroupsName)
	{
		$client = $this;
		$client->amOnPage(Tax_GroupPage::$url);
		$client->searchVATGroup($VATGroupsName);
		$client->see($VATGroupsName, Tax_GroupPage::$resultRow);
		$text = $client->grabAttributeFrom(Tax_GroupPage::$pathStatus, 'onclick');

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
		$client->amOnPage(Tax_GroupPage::$url);
		$client->searchVATGroup($VATGroupsName);
		$client->click(Tax_GroupPage::$buttonDelete);
		$client->cancelPopup();

	}

	public function deleteVATGroupOK($VATGroupsName)
	{
		$client = $this;
		$client->amOnPage(Tax_GroupPage::$url);
		$client->searchVATGroup($VATGroupsName);
		$client->checkAllResults();
		$client->click(Tax_GroupPage::$buttonDelete);
		$client->acceptPopup();
		$client->waitForElement(Tax_GroupPage::$searchField, 30);
		$client->see(Tax_GroupPage::$messageDeleteSuccess, Tax_GroupPage::$selectorSuccess);
		$client->fillField(Tax_GroupPage::$searchField, $VATGroupsName);
		$client->pressKey(Tax_GroupPage::$searchField, \Facebook\WebDriver\WebDriverKeys::ENTER);
		$client->dontSee($VATGroupsName, Tax_GroupPage::$pathName);
	}

	public function addVATGroupsMissingName()
	{
		$client = $this;
		$client->amOnPage(\Tax_GroupPage::$url);
		$client->click(Tax_GroupPage::$buttonNew);
		$client->verifyNotices(false, $this->checkForNotices(), Tax_GroupPage::$nameEditPage);
		$client->checkForPhpNoticesOrWarnings();
		$client->click(Tax_GroupPage::$buttonSave);
		$client->waitForText(Tax_GroupPage::$messageErrorFieldMissing, 60, Tax_GroupPage::$selectorMissing);
		$client->waitForElement(Tax_GroupPage::$fieldName, 30);
	}

	public function deleteButton()
	{
		$client = $this;
		$client->amOnPage(Tax_GroupPage::$url);
		$client->click(Tax_GroupPage::$buttonDelete);
		$client->acceptPopup();
		$client->see(Tax_GroupPage::$namePage, Tax_GroupPage::$selectorPageTitle);
	}

	public function publishButton()
	{
		$client = $this;
		$client->amOnPage(Tax_GroupPage::$url);
		$client->click(Tax_GroupPage::$buttonPublish);
		$client->acceptPopup();
		$client->see(Tax_GroupPage::$namePage, Tax_GroupPage::$selectorPageTitle);
	}

	public function publishAllGroups()
	{
		$client = $this;
		$client->amOnPage(Tax_GroupPage::$url);
		$client->checkAllResults();
		$client->click(Tax_GroupPage::$buttonPublish);
		$client->see(\Tax_GroupPage::$namePage, \Tax_GroupPage::$headPage);
	}


	public function unpublishButton()
	{
		$client = $this;
		$client->amOnPage(Tax_GroupPage::$url);
		$client->click(Tax_GroupPage::$buttonUnpublish);
		$client->acceptPopup();
	}

	public function unpublishAllGroups()
	{
		$client = $this;
		$client->amOnPage(Tax_GroupPage::$url);
		$client->checkAllResults();
		$client->click(Tax_GroupPage::$buttonUnpublish);
		$client->see(\Tax_GroupPage::$namePage, \Tax_GroupPage::$headPage);
	}

	public function searchVATGroup($VATGroupName)
	{
		$client = $this;
		$client->amOnPage(Tax_GroupPage::$url);
		$client->waitForText(Tax_GroupPage::$namePage, 30, Tax_GroupPage::$headPage);
		$client->filterListBySearching($VATGroupName);
	}

	public function checkButton($name)
	{
		$I = $this;
		$I->amOnPage(Tax_GroupPage::$url);
		switch ($name)
		{
			case 'copy':
				$I->click(Tax_GroupPage::$buttonCopy);
				$I->acceptPopup();
				break;
			case 'delete':
				$I->click(Tax_GroupPage::$buttonDelete);
				$I->acceptPopup();
				break;
			case 'publish':
				$I->click(Tax_GroupPage::$buttonPublish);
				$I->acceptPopup();
				break;
			case 'unpublish':
				$I->click(Tax_GroupPage::$buttonUnpublish);
				$I->acceptPopup();
				break;
			default:
				break;
		}
		$I->see(Tax_GroupPage::$namePage, Tax_GroupPage::$headPage);
	}
}