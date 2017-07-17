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
    public function addManufacturerWithoutPageNumber($manufacturerName )
    {
        $I = $this;
        $I->amOnPage(\ManufacturerManagerJoomla3Page::$URL);
        $I->verifyNotices(false, $this->checkForNotices(), 'Manufacturer Manager Page');
        $I->click('New');
        $I->waitForElement(\ManufacturerManagerJoomla3Page::$detailsTab,30);
        $I->click(\ManufacturerManagerJoomla3Page::$detailsTab);
        $I->fillField(\ManufacturerManagerJoomla3Page::$manufacturerName, $manufacturerName);
        $I->click('Save & Close');
        $I->acceptPopup();
    }

    public function addManufacturerWrongURL($manufacturerName, $URL, $noPage )
    {
        $I = $this;
        $I->amOnPage(\ManufacturerManagerJoomla3Page::$URL);
        $I->verifyNotices(false, $this->checkForNotices(), 'Manufacturer Manager Page');
        $I->click('New');
        $I->waitForElement(\ManufacturerManagerJoomla3Page::$detailsTab,30);
        $I->click(\ManufacturerManagerJoomla3Page::$detailsTab);
        $I->fillField(\ManufacturerManagerJoomla3Page::$manufacturerName, $manufacturerName);
        $I->fillField(\ManufacturerManagerJoomla3Page::$NoPage,$noPage);
        $I->fillField(\ManufacturerManagerJoomla3Page::$manufactureURL,$URL);
        $I->click('Save & Close');
        $I->acceptPopup();
    }

    public function addManufacturerWrongEmail($manufacturerName, $email, $noPage )
    {
        $I = $this;
        $I->amOnPage(\ManufacturerManagerJoomla3Page::$URL);
        $I->verifyNotices(false, $this->checkForNotices(), 'Manufacturer Manager Page');
        $I->click('New');
        $I->waitForElement(\ManufacturerManagerJoomla3Page::$detailsTab,30);
        $I->click(\ManufacturerManagerJoomla3Page::$detailsTab);
        $I->fillField(\ManufacturerManagerJoomla3Page::$manufacturerName, $manufacturerName);
        $I->fillField(\ManufacturerManagerJoomla3Page::$manufactureEmail,$email);
        $I->fillField(\ManufacturerManagerJoomla3Page::$NoPage,$noPage);
        $I->click('Save & Close');
        $I->acceptPopup();
    }

    public function addManufacturerCancelButton($manufacturerName, $noPage )
    {
        $I = $this;
        $I->amOnPage(\ManufacturerManagerJoomla3Page::$URL);
        $I->verifyNotices(false, $this->checkForNotices(), 'Manufacturer Manager Page');
        $I->click('New');
        $I->waitForElement(\ManufacturerManagerJoomla3Page::$detailsTab,30);
        $I->click(\ManufacturerManagerJoomla3Page::$detailsTab);
        $I->fillField(\ManufacturerManagerJoomla3Page::$manufacturerName, $manufacturerName);
        $I->fillField(\ManufacturerManagerJoomla3Page::$NoPage,$noPage);
        $I->click('Cancel');

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
        $I->searchManufacturer($updatedName);
        $I->see($updatedName, \ManufacturerManagerJoomla3Page::$firstResultRow);
	}

	/**
	 * Function to change State of a Manufacturer
	 *
	 * @param   string  $name   Name of the Manufacturer
	 * @param   string  $state  State of the Manufacturer
	 *
	 * @return void
	 */
    public function changeManufactureState($manufactureName, $state = 'unpublish')
    {
        $I = $this;
        $I->amOnPage(\ManufacturerManagerJoomla3Page::$URL);
        $I->searchManufacturer($manufactureName);
        $I->wait(3);
        $I->see($manufactureName, \ManufacturerManagerJoomla3Page::$firstResultRow);
        $I->click(\ManufacturerManagerJoomla3Page::$selectFirst);

        if ($state == 'unpublish') {
            $I->click(\ManufacturerManagerJoomla3Page::$manufacturerStatePath);
        } else {
            $I->click(\ManufacturerManagerJoomla3Page::$manufacturerStatePath);
        }
    }

    //get status of manufacture
    public function getManufactureState($manufactureName)
    {
        $I = $this;
        $I->amOnPage(\ManufacturerManagerJoomla3Page::$URL);
        $I->searchManufacturer($manufactureName);
        $I->wait(3);
        $I->see($manufactureName, \ManufacturerManagerJoomla3Page::$firstResultRow);
        $text = $I->grabAttributeFrom(\ManufacturerManagerJoomla3Page::$manufacturerStatePath, 'onclick');
        $I->wait(3);

        if (strpos($text, 'unpublish') > 0) {
            $result = 'published';
        }else{
            $result = 'unpublished';
        }

        return $result;
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
        $I->wantTo('Search the Manufacturer');
        $I->amOnPage(\ManufacturerManagerJoomla3Page::$URL);
        $I->waitForText('Manufacturer Management', 30, ['xpath' => "//h1"]);
        $I->filterListBySearch($manufacturerName);
	}


	public function editManufactureWithoutPage($manufacturerName, $manufactureNoPage){
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
        $I->fillField(\ManufacturerManagerJoomla3Page::$NoPage,$manufactureNoPage);
        $I->click('Save & Close');
        $I->acceptPopup();

    }

    public function editManufactureWithWrongURL($manufacturerName, $WrongURL){
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
        $I->fillField(\ManufacturerManagerJoomla3Page::$manufactureURL,$WrongURL);
        $I->click('Save & Close');
        $I->acceptPopup();

    }
    public function editManufactureCheckClose($manufacturerName){
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
        $I->click('Close');
        $I->waitForText(\ManufacturerManagerJoomla3Page::$manufactureCloseMessage,60,'.alert-success');
        $I->see(\ManufacturerManagerJoomla3Page::$manufactureCloseMessage,'.alert-success');

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
        $I->searchManufacturer($name);
        $I->wait(3);
        $I->click(\ManufacturerManagerJoomla3Page::$selectFirst);
        $I->click('Delete');
        $I->waitForText("Manufacturer Detail Deleted Successfully", 60, '.alert-success');
        $I->see("Manufacturer Detail Deleted Successfully", '.alert-success');
        $I->fillField(\ManufacturerManagerJoomla3Page::$manufactureFilter, $name);
        $I->pressKey(\ManufacturerManagerJoomla3Page::$manufactureFilter, \Facebook\WebDriver\WebDriverKeys::ENTER);
        $I->dontSee($name, \ManufacturerManagerJoomla3Page::$firstResultRow);
	}


}
