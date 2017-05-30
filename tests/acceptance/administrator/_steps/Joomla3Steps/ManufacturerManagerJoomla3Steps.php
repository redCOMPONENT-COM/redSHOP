<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace AcceptanceTester;
/**
 * Class ManufacturerManagerJoomla3Steps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class ManufacturerManagerJoomla3Steps extends AdminManagerJoomla3Steps
{


    public function checkEditButton(){
        $I = $this;
        $I->amOnPage(\ManufacturerManagerJoomla3Page::$URL);
        $I->verifyNotices(false, $this->checkForNotices(), 'Manufacturer Manager Page');
        $I->click('Edit');
        $I->acceptPopup();
    }

    public function copyManufacture($nameManufacture){
        $I = $this;
        $I->amOnPage(\ManufacturerManagerJoomla3Page::$URL);
        $I->searchManufacturer($nameManufacture);
        $I->wait(3);
        $I->see($nameManufacture, \ManufacturerManagerJoomla3Page::$firstResultRow);
        $I->click(\ManufacturerManagerJoomla3Page::$selectFirst);
        $I->click('Copy');
        $I->see(\ManufacturerManagerJoomla3Page::$manufactureCoPySuccess, '.alert-success');
    }

    public function checkDeleteButton(){
        $I = $this;
        $I->amOnPage(\ManufacturerManagerJoomla3Page::$URL);
        $I->verifyNotices(false, $this->checkForNotices(), 'Manufacturer Manager Page');
        $I->click('Delete');
        $I->acceptPopup();
    }

    public function deleteManufacture($nameManufacture){
        $I = $this;
        $I->amOnPage(\ManufacturerManagerJoomla3Page::$URL);
        $I->searchManufacturer($nameManufacture);
        $I->wait(3);
        $I->see($nameManufacture, \ManufacturerManagerJoomla3Page::$firstResultRow);
        $I->click(\ManufacturerManagerJoomla3Page::$selectFirst);
        $I->click('Delete');
//        $I->acceptPopup();
        $I->see(\ManufacturerManagerJoomla3Page::$manufacturerDeleteSuccessMessage, '.alert-success');
    }

    public function checkPublishButton(){
        $I = $this;
        $I->amOnPage(\ManufacturerManagerJoomla3Page::$URL);
        $I->verifyNotices(false, $this->checkForNotices(), 'Manufacturer Manager Page');
        $I->click('Publish');
        $I->acceptPopup();
    }

    public function checkUnpublishButton(){
        $I = $this;
        $I->amOnPage(\ManufacturerManagerJoomla3Page::$URL);
        $I->verifyNotices(false, $this->checkForNotices(), 'Manufacturer Manager Page');
        $I->click('Unpublish');
        $I->acceptPopup();
    }

    public function unpublishAllManufacturer(){
        $I = $this;
        $I->amOnPage(\ManufacturerManagerJoomla3Page::$URL);
        $I->verifyNotices(false, $this->checkForNotices(), 'Manufacturer Manager Page');
        $I->click(\ManufacturerManagerJoomla3Page::$checkAllManufacturer);
        $I->click('Unpublish');

    }

    public function publishAllManufacturer(){
        $I = $this;
        $I->amOnPage(\ManufacturerManagerJoomla3Page::$URL);
        $I->verifyNotices(false, $this->checkForNotices(), 'Manufacturer Manager Page');
        $I->click(\ManufacturerManagerJoomla3Page::$checkAllManufacturer);
        $I->click('Publish');

    }

	/**
	 * Function to Test Manufacturer Creation
	 *
	 * @param   string  $manufacturerName  Name of the Manufacturer
	 *
	 * @return void
	 */
	public function addManufacturerSaveClose($manufacturerName , $noPae)
	{
		$I = $this;
		$I->amOnPage(\ManufacturerManagerJoomla3Page::$URL);
		$I->verifyNotices(false, $this->checkForNotices(), 'Manufacturer Manager Page');
		$I->click('New');
		$I->waitForElement(\ManufacturerManagerJoomla3Page::$detailsTab,30);
		$I->click(\ManufacturerManagerJoomla3Page::$detailsTab);
		$I->fillField(\ManufacturerManagerJoomla3Page::$manufacturerName, $manufacturerName);
		$I->fillField(\ManufacturerManagerJoomla3Page::$NoPage,$noPae);
		$I->click('Save & Close');
		$I->waitForText(\ManufacturerManagerJoomla3Page::$manufacturerSuccessMessage,60,'.alert-success');
		$I->see(\ManufacturerManagerJoomla3Page::$manufacturerSuccessMessage,'.alert-success');
	}

    public function addManufacturerSave($manufacturerName , $noPae)
    {
        $I = $this;
        $I->amOnPage(\ManufacturerManagerJoomla3Page::$URL);
        $I->verifyNotices(false, $this->checkForNotices(), 'Manufacturer Manager Page');
        $I->click('New');
        $I->waitForElement(\ManufacturerManagerJoomla3Page::$detailsTab,30);
        $I->click(\ManufacturerManagerJoomla3Page::$detailsTab);
        $I->fillField(\ManufacturerManagerJoomla3Page::$manufacturerName, $manufacturerName);
        $I->fillField(\ManufacturerManagerJoomla3Page::$NoPage,$noPae);
        $I->click('Save');
        $I->waitForText(\ManufacturerManagerJoomla3Page::$manufacturerSuccessMessage,60,'.alert-success');
        $I->see(\ManufacturerManagerJoomla3Page::$manufacturerSuccessMessage,'.alert-success');
    }

	/**
	 * Function to Test Editing of a Manufacturer
	 *
	 * @param   string  $manufacturerName  Name of the Manufacturer which is to be edited
	 * @param   string  $updatedName       Updated Name for the Manufacturer
	 *
	 * @return void
	 */
	public function editManufacturer($manufacturerName, $updatedName = 'Updated Name', $noPage)
	{
		$I = $this;
		$I->amOnPage(\ManufacturerManagerJoomla3Page::$URL);
		$I->searchManufacturer($manufacturerName);
		$I->wait(3);

		$I->see($manufacturerName, \ManufacturerManagerJoomla3Page::$firstResultRow);
		$I->click(\ManufacturerManagerJoomla3Page::$selectFirst);
		$I->click('Edit');
		$I->waitForElement(\ManufacturerManagerJoomla3Page::$detailsTab,30);

		$I->click(\ManufacturerManagerJoomla3Page::$detailsTab);
		$I->verifyNotices(false, $this->checkForNotices(), 'Manufacturer Manager Edit Page');
		$I->fillField(\ManufacturerManagerJoomla3Page::$manufacturerName, $updatedName);
		$I->fillField(\ManufacturerManagerJoomla3Page::$NoPage,$noPage);
		$I->click('Save & Close');
		$I->waitForText(\ManufacturerManagerJoomla3Page::$manufacturerSuccessMessage,60,'.alert-success');
		$I->see(\ManufacturerManagerJoomla3Page::$manufacturerSuccessMessage, '.alert-success');
		$I->see($updatedName, \ManufacturerManagerJoomla3Page::$firstResultRow);
		$I->executeJS('window.scrollTo(0,0)');
		$I->click(['link' => 'ID']);
	}

	/**
	 * Function to change State of a Manufacturer
	 *
	 * @param   string  $name   Name of the Manufacturer
	 * @param   string  $state  State of the Manufacturer
	 *
	 * @return void
	 */
	public function changeManufacturerState($name, $state = 'unpublish')
	{
		$I = $this;
		$I->amOnPage(\ManufacturerManagerJoomla3Page::$URL);
		$I->executeJS('window.scrollTo(0,0)');
		$I->click(['link' => 'ID']);
		$I->click(['link' => 'ID']);
		$I->see($name, \ManufacturerManagerJoomla3Page::$firstResultRow);
		$I->click(\ManufacturerManagerJoomla3Page::$selectFirst);

		if ($state == 'unpublish')
		{
			$I->click("Unpublish");
		}
		else
		{
			$I->click("Publish");
		}

		$I->executeJS('window.scrollTo(0,0)');
		$I->click(['link' => 'ID']);
	}

	/**
	 * Function to Search for a Manufacturer
	 *
	 * @param   string  $name          Name of the Manufacturer
	 * @param   string  $functionName  Name of the function After Which search is being Called
	 *
	 * @return void
	 */
	public function searchManufacturer($manufacturerName)
	{
        $I = $this;
        $I->wantTo('Search the Category');
        $I->amOnPage(\ManufacturerManagerJoomla3Page::$URL);
        $I->waitForText('Manufacturer Management', 30, ['xpath' => "//h1"]);
        $I->filterListBySearch($manufacturerName);
	}

	/**
	 * Function to get State of the Manufacturer
	 *
	 * @param   String  $name  Name of the Manufacturer
	 *
	 * @return string
	 */
	public function getManufacturerState($name)
	{
		$I = $this;
		$I->amOnPage(\ManufacturerManagerJoomla3Page::$URL);
		$I->executeJS('window.scrollTo(0,0)');
		$I->click(['link' => 'ID']);
		$I->see($name, \ManufacturerManagerJoomla3Page::$firstResultRow);
		$text = $I->grabAttributeFrom(\ManufacturerManagerJoomla3Page::$manufacturerStatePath, 'onclick');

		if (strpos($text, 'unpublish') > 0)
		{
			$result = 'published';
		}

		if (strpos($text, 'publish') > 0)
		{
			$result = 'unpublished';
		}

		$I->executeJS('window.scrollTo(0,0)');
		$I->click(['link' => 'ID']);

		return $result;
	}

	/**
	 * Function to Delete Manufacturer
	 *
	 * @param   String  $name  Name of the Manufacturer which is to be Deleted
	 *
	 * @return void
	 */
	public function deleteManufacturer($name)
	{
		$I = $this;
		$I->amOnPage(\ManufacturerManagerJoomla3Page::$URL);
		$I->executeJS('window.scrollTo(0,0)');
		$I->click(['link' => 'ID']);
		$I->click(['link' => 'ID']);
		$I->see($name, \ManufacturerManagerJoomla3Page::$firstResultRow);
		$I->click(\ManufacturerManagerJoomla3Page::$selectFirst);
		$I->click('Delete');
		$I->dontSee($name, \ManufacturerManagerJoomla3Page::$firstResultRow);
		$I->executeJS('window.scrollTo(0,0)');
		$I->click(['link' => 'ID']);
	}


}
