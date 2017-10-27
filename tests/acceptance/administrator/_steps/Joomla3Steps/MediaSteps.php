<?php


namespace AcceptanceTester;
use \MediaPage as MediaPage;

class MediaSteps extends AdminManagerJoomla3Steps
{
	public function deleteAllMedia(){
		$I = $this;
		$I->amOnPage(MediaPage::$URL);
		$I->checkAllResults();
		$I->click(MediaPage::$buttonDelete);
		$I->waitForElement(MediaPage::$selectorSuccess,30);
	}

	public function addImageMedia($nameProduct,$fieldMediaAlter)
	{
		$I = $this;
		$I->amOnPage(MediaPage::$URL);
		$I->click(MediaPage::$buttonNew);
		$I->waitForElement(MediaPage::$inputFile, 30);

		$I->attachFile(MediaPage::$inputFile,MediaPage::$imageFileAttach);

		$I->waitForElement(MediaPage::$fieldMediaAlter,30);
		$I->fillField(MediaPage::$fieldMediaAlter,$fieldMediaAlter);

		$I->click(MediaPage::$btnSectionItem);
		$I->waitForElement(MediaPage::$searchSectionItem,30);
		$I->fillField(MediaPage::$searchSectionItem,$nameProduct);

		$userMediaPage = new MediaPage();
		$I->waitForElement($userMediaPage->returnChoice($nameProduct),30);
		$I->click($userMediaPage->returnChoice($nameProduct));
		$I->click(MediaPage::$buttonSaveMedia);
		$I->waitForElement(MediaPage::$selectorSuccess,30);
		$I->amOnPage(MediaPage::$URL);
		$I->waitForElement(MediaPage::$mediaAlterText,30);
		$I->see($fieldMediaAlter,MediaPage::$mediaAlterText);
		$I->waitForElement(MediaPage::$mediaImage,30);

	}

	public function editImageMedia($fieldMediaAlter,$fieldMediaAlterEdit)
	{
		$I = $this;
		$I->amOnPage(MediaPage::$URL);
		$I->waitForElement(MediaPage::$mediaAlterText,30);
		$I->see($fieldMediaAlter,MediaPage::$mediaAlterText);
		$I->checkAllResults();
		$I->click(MediaPage::$buttonEdit);
		$I->waitForElement(MediaPage::$imageXpath,30);
		$I->fillField(MediaPage::$fieldMediaAlter,$fieldMediaAlterEdit);
		$I->click(MediaPage::$buttonSaveMedia);

		$I->amOnPage(MediaPage::$URL);
		$I->waitForElement(MediaPage::$mediaAlterText,30);
		$I->see($fieldMediaAlter,MediaPage::$mediaAlterText);
	}

	public function addYouTubeMedia($nameProduct,$fieldMediaAlter,$youTube)
	{
		$I = $this;
		$I->amOnPage(MediaPage::$URL);
		$I->click(MediaPage::$buttonNew);
		$I->waitForElement(MediaPage::$inputFile, 30);

		$userMediaPage = new MediaPage();
		$I->click(MediaPage::$buttonMediaType);
		$I->waitForElement(MediaPage::$searchMedia,30);
		$I->fillField(MediaPage::$searchMedia,'Youtube');
		$I->click($userMediaPage->returnChoice('Youtube'));

		$I->fillField(MediaPage::$youTubeId,$youTube);
		$I->fillField(MediaPage::$fieldMediaAlter,$fieldMediaAlter);


		$I->click(MediaPage::$btnSectionItem);
		$I->waitForElement(MediaPage::$searchSectionItem,30);
		$I->fillField(MediaPage::$searchSectionItem,$nameProduct);

		$userMediaPage = new MediaPage();
		$I->waitForElement($userMediaPage->returnChoice($nameProduct),30);
		$I->click($userMediaPage->returnChoice($nameProduct));


		$I->click(MediaPage::$buttonSaveMedia);
		$I->amOnPage(MediaPage::$URL);
		$I->waitForElement(MediaPage::$mediaAlterText,30);
		$I->see($fieldMediaAlter,MediaPage::$mediaAlterText);
		$I->waitForElement(MediaPage::$mediaYou,30);
	}
}