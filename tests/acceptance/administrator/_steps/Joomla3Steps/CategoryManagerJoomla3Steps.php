<?php
/**
 * @package     redSHOP
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;
use CategoryManagerJ3Page as CategoryManagerJ3Page;
use CategoryPage;

/**
 * Class CategoryManagerJoomla3Steps
 *
 * @package  AcceptanceTester
 *
 * @since    1.4
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 */
class CategoryManagerJoomla3Steps extends AdminManagerJoomla3Steps
{
	/**
	 * Method for save category
	 *
	 * @param   string $categoryName Name of category
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function addCategorySave($categoryName)
	{
		$I = $this;
		$I->amOnPage(CategoryManagerJ3Page::$URL);
		$I->waitForJS("return window.jQuery && jQuery.active == 0;", 30);
		$I->waitForText(CategoryManagerJ3Page::$buttonNew, 30);
		$I->click(CategoryManagerJ3Page::$buttonNew);
		$I->fillField(CategoryManagerJ3Page::$categoryName, $categoryName);

		$I->waitForElementVisible(CategoryManagerJ3Page::$template, 30);
		$I->click(CategoryManagerJ3Page::$template);
		$I->waitForElementVisible(CategoryManagerJ3Page::$choiceTemplate, 30);
		$I->click(CategoryManagerJ3Page::$choiceTemplate);
		$I->wait(0.5);

		$I->click(CategoryManagerJ3Page::$buttonSave);
		$I->waitForElement(CategoryManagerJ3Page::$categoryName, 30);
		$I->see(CategoryManagerJ3Page::$messageItemSaveSuccess, CategoryManagerJ3Page::$selectorSuccess);
		$I->click(\CategoryPage::$buttonClose);
	}

	/**
	 * @param $categoryName
	 * @throws \Exception
	 * @since 2.1.4
	 */
	public function addCategorySaveClose($categoryName)
	{
		$I = $this;
		$I->amOnPage(CategoryManagerJ3Page::$URL);
		$I->checkForPhpNoticesOrWarnings();
		$I->click(CategoryManagerJ3Page::$buttonNew);
		$I->waitForJS("return window.jQuery && jQuery.active == 0;", 30);
		$I->waitForElementVisible(CategoryManagerJ3Page::$categoryName, 30);
		$I->fillField(CategoryManagerJ3Page::$categoryName, $categoryName);
		$I->waitForElementVisible(CategoryManagerJ3Page::$template, 30);
		$I->click(CategoryManagerJ3Page::$template);
		$I->waitForElementVisible(CategoryManagerJ3Page::$choiceTemplate, 30);
		$I->click(CategoryManagerJ3Page::$choiceTemplate);
		$I->waitForElementVisible(CategoryManagerJ3Page::$saveCloseButton, 30);
		$I->click(CategoryManagerJ3Page::$saveCloseButton);
		$I->waitForElement(CategoryManagerJ3Page::$categoryFilter, 30);
		$I->see(CategoryManagerJ3Page::$messageItemSaveSuccess, CategoryManagerJ3Page::$selectorSuccess);
	}

	/** Create category Save and New button
	 *
	 * @param $categoryName
	 * @param $noPage
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function addCategorySaveNew($categoryName, $noPage)
	{
		$I = $this;
		$I->amOnPage(CategoryManagerJ3Page::$URL);
		$I->checkForPhpNoticesOrWarnings();
		$I->click(CategoryManagerJ3Page::$buttonNew);
		$I->fillField(CategoryManagerJ3Page::$categoryName, $categoryName);
		$I->fillField(CategoryManagerJ3Page::$categoryNoPage, $noPage);
		$I->click(CategoryManagerJ3Page::$template);
		$I->click(CategoryManagerJ3Page::$choiceTemplate);
		$I->click(CategoryManagerJ3Page::$buttonSaveNew);
		$I->waitForElement(CategoryManagerJ3Page::$categoryName, 30);
	}

	/**
	 *  Function check Cancel button
	 * @since 1.4.0
	 * @throws \Exception
	 */
	public function addCategoryCancel()
	{
		$I = $this;
		$I->amOnPage(CategoryManagerJ3Page::$URL);
		$I->click(CategoryManagerJ3Page::$buttonNew);
		$I->click(CategoryManagerJ3Page::$buttonCancel);
		$I->waitForElement(CategoryManagerJ3Page::$categoryFilter, 30);
	}

	/** Function create new Category is child of other category
	 *
	 * @param $categoryName
	 * @param $noPage
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function addCategoryChild($categoryName, $noPage)
	{
		$I = $this;
		$I->pauseExecution();
		$I->amOnPage(CategoryManagerJ3Page::$URL);
		$I->click(CategoryManagerJ3Page::$buttonNew);
		$I->fillField(CategoryManagerJ3Page::$categoryName, $categoryName);
		$I->click(CategoryManagerJ3Page::$parentCategory);
		$I->click(CategoryManagerJ3Page::$choiceTemplate);
		$I->fillField(CategoryManagerJ3Page::$categoryNoPage, $noPage);
		$I->click(CategoryManagerJ3Page::$template);
		$I->click(CategoryManagerJ3Page::$choiceTemplate);
		$I->pauseExecution();
		$I->click(CategoryManagerJ3Page::$saveCloseButton);
		$I->waitForElement(CategoryManagerJ3Page::$categoryFilter, 30);
	}

	/**
	 * @param $parentname
	 * @param $childname
	 * @throws \Exception
	 * @since 2.1.2
	 */
	public function createCategoryChild($parentname, $childname)
	{
		$I = $this;
		$I->amOnPage(CategoryManagerJ3Page::$URL);
		$I->click(CategoryManagerJ3Page::$buttonNew);
		$I->waitForElementVisible(CategoryManagerJ3Page::$categoryName, 30);
		$I->fillField(CategoryManagerJ3Page::$categoryName, $childname);
		$I->click(CategoryManagerJ3Page::$parentCategory);
		$I->waitForElementVisible(CategoryPage::$parentCategoryInput, 30);
		$I->fillField(CategoryPage::$parentCategoryInput, $parentname);
		$I->pressKey(CategoryPage::$parentCategoryInput, \Facebook\WebDriver\WebDriverKeys::ENTER);
		$I->click(CategoryManagerJ3Page::$saveCloseButton);
		$I->waitForText(CategoryManagerJ3Page::$messageItemSaveSuccess, 30, CategoryManagerJ3Page::$selectorSuccess);
	}

	/**
	 * @param $name
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function checkReview($name)
	{
		$I = $this;
		$I->amOnPage(CategoryManagerJ3Page::$URL);
		$I->searchCategory($name);
		$I->click(CategoryManagerJ3Page::xpathLink($name));
		$I->waitForElement(CategoryManagerJ3Page::$categoryName, 30);
		$I->click(CategoryManagerJ3Page::$buttonReview);
		$I->switchToNextTab();
		$I->waitForElement(CategoryManagerJ3Page::$headPageName, 30);
		$I->waitForText($name, 30, CategoryManagerJ3Page::$headPageName);
	}

	/**
	 * Function to Search for a Category
	 *
	 * @param   string $categoryName Name of the Category
	 * @param   string $functionName Name of the function After Which search is being Called
	 *
	 * @return void
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function searchCategory($categoryName)
	{
		$I = $this;
		$I->wantTo('Search the Category');
		$I->amOnPage(CategoryManagerJ3Page::$URL);
		$I->waitForText(CategoryManagerJ3Page::$pageManageName, 30, CategoryManagerJ3Page::$headPageName);
		$I->filterListBySearching($categoryName);
	}

	/**
	 * @param $categoryName
	 * @param $noPage
	 * @param $productAccessories
	 * Here, can you support to fills in value to choice
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function addCategoryAccessories($categoryName, $noPage, $productAccessories)
	{
		$I = $this;
		$I->amOnPage(CategoryManagerJ3Page::$URL);
		$I->click(CategoryManagerJ3Page::$buttonNew);
		$I->fillField(CategoryManagerJ3Page::$categoryName, $categoryName);
		$I->click(CategoryManagerJ3Page::$parentCategory);
		$I->click(CategoryManagerJ3Page::$choiceTemplate);
		$I->fillField(CategoryManagerJ3Page::$categoryNoPage, $noPage);
		$I->click(CategoryManagerJ3Page::$template);
		$I->click(CategoryManagerJ3Page::$choiceTemplate);
		$I->click(CategoryManagerJ3Page::$tabAccessory);
		$I->waitForElement(CategoryManagerJ3Page::$getAccessory, 60);
		$this->selectAccessories($productAccessories);
		$I->click(CategoryManagerJ3Page::$saveCloseButton);
		$I->waitForElement(CategoryManagerJ3Page::$categoryFilter, 30);
		$I->see(CategoryManagerJ3Page::$messageItemSaveSuccess, CategoryManagerJ3Page::$selectorSuccess);
	}

	/**
	 * @param $accessoryName
	 * @throws \Exception
	 * @since 1.4.0
	 */
	private function selectAccessories($accessoryName)
	{
		$I = $this;
		$I->click(CategoryManagerJ3Page::$accessorySearch);
		$I->waitForElement(CategoryManagerJ3Page::$searchFirst, 30);
		$I->fillField(CategoryManagerJ3Page::$searchFirst, $accessoryName);
		$userCategoryPage = new CategoryManagerJ3Page();
		$I->waitForElement($userCategoryPage->xPathAccessory($accessoryName), 60);
		$I->click($userCategoryPage->xPathAccessory($accessoryName));
	}

	/** Function update category and clicks "Save" button
	 *
	 * @param $categoryName
	 * @param $updatedName
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function updateCategorySave($categoryName, $updatedName)
	{
		$I = $this;
		$I->amOnPage(CategoryManagerJ3Page::$URL);
		$I->searchCategory($categoryName);
		$I->see($categoryName);

		$value = $I->grabTextFrom(CategoryManagerJ3Page::$categoryId);
		$I->click(CategoryManagerJ3Page::xpathLink($categoryName));
		$I->waitForElement(CategoryManagerJ3Page::$categoryName, 30);

		$URLEdit = CategoryManagerJ3Page::$URLEdit . $value;
		$I->fillField(CategoryManagerJ3Page::$categoryName, $updatedName);
		$I->click(CategoryManagerJ3Page::$buttonSave);
		$I->waitForElement(CategoryManagerJ3Page::$categoryName, 30);
		$I->see(CategoryManagerJ3Page::$messageItemSaveSuccess, CategoryManagerJ3Page::$selectorSuccess);
	}

	/**
	 * Function to Update name of  Category
	 *
	 * @param   String $categoryName Name of the Category
	 * @param   String $updatedName  Updated Name of the Category
	 *
	 * @return void
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function updateCategory($categoryName, $updatedName)
	{
		$I = $this;
		$I->amOnPage(CategoryManagerJ3Page::$URL);
		$I->searchCategory($categoryName);
		$I->see($categoryName);
		$value = $I->grabTextFrom(CategoryManagerJ3Page::$categoryId);
		$I->click(CategoryManagerJ3Page::xpathLink($categoryName));
		$I->waitForElement(CategoryManagerJ3Page::$categoryName, 30);

		$URLEdit = CategoryManagerJ3Page::$URLEdit . $value;
		$I->fillField(CategoryManagerJ3Page::$categoryName, $updatedName);
		$I->click(CategoryManagerJ3Page::$saveCloseButton);
		$I->waitForElement(CategoryManagerJ3Page::$categoryFilter, 60);
		$I->see(CategoryManagerJ3Page::$messageItemSaveSuccess, CategoryManagerJ3Page::$selectorSuccess);
	}

	/**
	 * Function to change State of a Category
	 *
	 * @param   string $categoryName Name of the Category
	 * @param   string $state        State of the Category
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function changeCategoryState($categoryName, $state = 'unpublish')
	{
		$I = $this;
		$I->amOnPage(CategoryManagerJ3Page::$URL);
		$I->searchCategory($categoryName);
		$I->see($categoryName);
		$I->click(CategoryManagerJ3Page::$checkAll);

		if ($state == 'unpublish')
		{
			$I->click(CategoryManagerJ3Page::$categoryStatePath);
		}
		else
		{
			$I->click(CategoryManagerJ3Page::$categoryStatePath);
		}
	}

	/**
	 * Function to get State of the Category
	 *
	 * @param   String $categoryName Name of the Category
	 *
	 * @return string
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function getCategoryState($categoryName)
	{
		$I = $this;
		$I->amOnPage(CategoryManagerJ3Page::$URL);
		$I->searchCategory($categoryName);
		$I->see($categoryName);
		$text = $I->grabAttributeFrom(CategoryManagerJ3Page::$categoryStatePath, 'onclick');

		if (strpos($text, 'unpublish') > 0)
		{
			$result = 'published';
		}

		if (strpos($text, 'publish') > 0)
		{
			$result = 'unpublished';
		}

		return $result;
	}

	/**
	 * @throws \Exception
	 * @since 1.4.0
	 */
	public function deleteWithoutChoice()
	{
		$I = $this;
		$I->amOnPage(CategoryManagerJ3Page::$URL);
		$I->click(CategoryManagerJ3Page::$buttonDelete);
		$I->acceptPopup();
		$I->waitForElement(CategoryManagerJ3Page::$categoryFilter, 30);
	}

	/**
	 * Function to Delete a Category
	 *
	 * @param   String $categoryName Name of the Category
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function deleteCategory($categoryName)
	{
		$I = $this;
		$I->amOnPage(CategoryManagerJ3Page::$URL);
		$I->searchCategory($categoryName);
		$I->waitForElementVisible(CategoryManagerJ3Page::$checkAllXpath, 30);
		$I->checkAllResults();
		$I->click(CategoryManagerJ3Page::$buttonDelete);
		$I->acceptPopup();

		try
		{
			$I->waitForText(CategoryManagerJ3Page::$messageNoItemOnTable, 30);
		}catch (\Exception $e)
		{
			$I->waitForElementVisible(CategoryManagerJ3Page::$checkAllXpath, 30);
			$I->click(CategoryManagerJ3Page::$checkAllXpath);
			$I->waitForText(CategoryManagerJ3Page::$buttonDelete, 30);
			$I->click(CategoryManagerJ3Page::$buttonDelete);
			$I->acceptPopup();
		}

		$I->dontSee($categoryName);
	}

	/**
	 * Delete all category
	 * @since 1.4.0
	 */
	public function deleteAllCategory()
	{
		$I = $this;
		$I->amOnPage(CategoryManagerJ3Page::$URL);
		$I->checkAllResults();
		$I->click(CategoryManagerJ3Page::$buttonDelete);
		$I->acceptPopup();
	}

	/**
	 * @since 1.4.0
	 */
	public function publishWithoutChoice()
	{
		$I = $this;
		$I->amOnPage(CategoryManagerJ3Page::$URL);
		$I->click(CategoryManagerJ3Page::$buttonUnpublish);
		$I->acceptPopup();
	}

	/**
	 * @since 1.4.0
	 */
	public function publishAllCategory()
	{
		$I = $this;
		$I->amOnPage(CategoryManagerJ3Page::$URL);
		$I->checkAllResults();
		$I->click(CategoryManagerJ3Page::$buttonUnpublish);
	}

	/**
	 * @since 1.4.0
	 */
	public function unpublishWithoutChoice()
	{
		$I = $this;
		$I->amOnPage(CategoryManagerJ3Page::$URL);
		$I->click(CategoryManagerJ3Page::$buttonUnpublish);
		$I->acceptPopup();
	}

	/**
	 * Function unpublish all category
	 * @since 1.4.0
	 */
	public function unpublishAllCategories()
	{
		$I = $this;
		$I->amOnPage(CategoryManagerJ3Page::$URL);
		$I->checkAllResults();
		$I->click(CategoryManagerJ3Page::$buttonUnpublish);
	}

	/**
	 * Funtcion check button Check in
	 * @since 1.4.0
	 */
	public function checkinWithoutChoice()
	{
		$I = $this;
		$I->amOnPage(CategoryManagerJ3Page::$URL);
		$I->click(CategoryManagerJ3Page::$buttonCheckIn);
		$I->acceptPopup();
	}

	/**
	 * Method for test check-in all categories
	 * @since 1.4.0
	 */
	public function checkinAllCategories()
	{
		$I = $this;
		$I->amOnPage(CategoryManagerJ3Page::$URL);
		$I->checkAllResults();
		$I->click(CategoryManagerJ3Page::$buttonCheckIn);
	}
}
