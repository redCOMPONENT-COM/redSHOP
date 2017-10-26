<?php


namespace AcceptanceTester;
use \MediaPage as MediaPage;

class MediaSteps extends AdminManagerJoomla3Steps
{

	public function addImageMedia($nameProduct)
	{
		$I = $this;
		$I->amOnPage(MediaPage::$URL);
		$I->click(MediaPage::$buttonNew);
		$I->waitForElement(MediaPage::$inputFile, 30);
//		$I->attachFile(MediaPage::$inputFile,'test.jpg');
		$I->click(MediaPage::$btnSectionItem);
		$I->waitForElement(MediaPage::$searchSectionItem,30);
		$I->fillField(MediaPage::$searchSectionItem,$nameProduct);
		$I->pressKey(MediaPage::$btnSectionItem, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);

		$I->waitForElement(MediaPage::$buttonSave,30);
		$I->click(MediaPage::$buttonSave);
		$I->waitForElement(MediaPage::$selectorSuccess,30);
	}
}