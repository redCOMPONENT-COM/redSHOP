<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Step\AbstractStep;

/**
 * Class Category Steps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class CategorySteps extends AbstractStep
{
	use Step\Traits\CheckIn, Step\Traits\Publish, Step\Traits\Delete;

	/**
	 * Function create new Category is child of other category
	 *
	 * @param   string   $categoryParent  Category parent
	 * @param   string   $categoryName    Category name
	 * @param   integer  $noPage          No page.
	 *
	 * @return  void
	 * @throws \Exception
	 */
	public function addCategoryChild($categoryParent, $categoryName, $noPage)
	{
		$tester = $this;
		$tester->amOnPage(CategoryPage::$url);
		$tester->click(CategoryPage::$buttonNew);
		$tester->fillField(CategoryPage::$idFieldName, $categoryName);
		$tester->fillField(CategoryPage::$categoryNoPage, $noPage);
		$tester->click(CategoryPage::$parentCategory);
		$tester->waitForElement(CategoryPage::$parentCategoryInput, 30);
		$tester->wait(0.5);
		$tester->fillField(CategoryPage::$parentCategoryInput, $categoryParent);
		$tester->pressKey(CategoryPage::$parentCategoryInput, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);
		$tester->click(CategoryPage::$buttonSave);
		$categoryParent = '- ' . $categoryParent;
		$tester->waitForText($categoryParent,10);
		$tester->click(CategoryPage::$buttonClose);
	}

	/**
	 * Function for use assign accessories for product inside category
	 *
	 * @param   string   $categoryName        Category parent
	 * @param   integer  $noPage              No page.
	 * @param   integer  $productAccessories  Product accessories.
	 *
	 * @return  void
	 */
	public function addCategoryAccessories($categoryName, $noPage, $productAccessories)
	{
		$tester = $this;
		$tester->amOnPage(CategoryPage::$url);
		$tester->searchItemCheckIn($categoryName);
		$tester->click($categoryName);
		$tester->click(CategoryPage::$tabAccessory);
		$tester->waitForElement(CategoryPage::$getAccessory, 60);
		$this->selectAccessories($productAccessories);
		$tester->click(CategoryPage::$buttonSave);
		$tester->click(CategoryPage::$tabAccessory);
		$tester->see($productAccessories);
	}

	/**
	 * Function for use assign accessories for product inside category
	 *
	 * @param   string   $accessoryName  Accessory name
	 *
	 * @return  void
	 */
	private function selectAccessories($accessoryName)
	{
		$tester = $this;
		$tester->click(CategoryPage::$accessorySearch);
		$tester->waitForElement(CategoryPage::$searchFirst);
		$tester->fillField(CategoryPage::$searchFirst, $accessoryName);

		$userCategoryPage = new CategoryPage;
		$tester->waitForElement($userCategoryPage->xPathAccessory($accessoryName), 60);
		$tester->click($userCategoryPage->xPathAccessory($accessoryName));
	}

	/**
	 * @param $categoryName
	 * @param $noPage
	 * @param $fileImage
	 * @param $titleSEO
	 * @param $keySEO
	 * @param $descriptionSEO
	 * @throws Exception
	 * @since 2.1.2
	 */
	public function createCategoryImageAndSEO($categoryName, $noPage, $fileImage, $titleSEO, $keySEO, $descriptionSEO)
	{
		$I = $this;
		$I->amOnPage(CategoryPage::$url);
		$I->click(CategoryPage::$buttonNew);
		$I->fillField(CategoryPage::$idFieldName, $categoryName);
		$I->fillField(CategoryPage::$categoryNoPage, $noPage);
		$I->waitForElement(CategoryPage::$fieldUploadImage, 30);
//		$I->attachFile(CategoryPage::$fieldUploadImage, $fileImage);
		$I->click(CategoryPage::$tabSEO);
		$I->waitForElementVisible(CategoryPage::$titlePage, 30);
		$I->fillField(CategoryPage::$titlePage, $titleSEO);
		$I->waitForElementVisible(CategoryPage::$metaKey, 30);
		$I->fillField(CategoryPage::$metaKey, $keySEO);
		$I->waitForElementVisible(CategoryPage::$descriptionSEO, 30);
		$I->fillField(CategoryPage::$descriptionSEO, $descriptionSEO);
		$I->click(CategoryPage::$buttonSaveClose);
	}
}
