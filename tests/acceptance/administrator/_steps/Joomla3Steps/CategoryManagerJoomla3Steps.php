<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;

use Codeception\Module\WebDriver;

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


    public function addCategorySave($categoryName)
    {
        $I = $this;
        $I->amOnPage(\CategoryManagerJ3Page::$URL);
        $I->click(\CategoryManagerJ3Page::$newButton);
        $I->checkForPhpNoticesOrWarnings(\CategoryManagerJ3Page::$URLNew);
        $I->fillField(\CategoryManagerJ3Page::$categoryName, $categoryName);

        $I->click(\CategoryManagerJ3Page::$template);
        $I->click(\CategoryManagerJ3Page::$choiceTemplate);

        $I->click(\CategoryManagerJ3Page::$saveButton);
        $I->waitForElement(\CategoryManagerJ3Page::$categoryName, 30);
        $I->see(\CategoryManagerJ3Page::$messageSaveSuccess, \CategoryManagerJ3Page::$selectorSuccess);
    }

    /**
     * Function  to Create a New Category Save and close
     *
     * @param   String $categoryName Name of the Category
     *
     * @return void
     */
    public function addCategorySaveClose($categoryName)
    {
        $I = $this;
        $I->amOnPage(\CategoryManagerJ3Page::$URL);
        $I->click(\CategoryManagerJ3Page::$newButton);
        $I->checkForPhpNoticesOrWarnings(\CategoryManagerJ3Page::$URLNew);
        $I->fillField(\CategoryManagerJ3Page::$categoryName, $categoryName);
        $I->click(\CategoryManagerJ3Page::$template);
        $I->click(\CategoryManagerJ3Page::$choiceTemplate);
        $I->click(\CategoryManagerJ3Page::$saveCloseButton);
        $I->waitForElement(\CategoryManagerJ3Page::$categoryFilter, 30);
        $I->see(\CategoryManagerJ3Page::$messageSaveSuccess, \CategoryManagerJ3Page::$selectorSuccess);
    }

    /** Create category Save and New button
     * @param $categoryName
     * @param $noPage
     */

    public function addCategorySaveNew($categoryName, $noPage)
    {
        $I = $this;
        $I->amOnPage(\CategoryManagerJ3Page::$URL);
        $I->click(\CategoryManagerJ3Page::$newButton);
        $I->checkForPhpNoticesOrWarnings(\CategoryManagerJ3Page::$URLNew);
        $I->fillField(\CategoryManagerJ3Page::$categoryName, $categoryName);
        $I->fillField(\CategoryManagerJ3Page::$categoryNoPage, $noPage);
        $I->click(\CategoryManagerJ3Page::$template);
        $I->click(\CategoryManagerJ3Page::$choiceTemplate);
        $I->click(\CategoryManagerJ3Page::$saveNewButton);
        $I->waitForElement(\CategoryManagerJ3Page::$categoryName, 30);

    }

    /**
     *  Function check Cancel button
     */
    public function addCategoryCancel()
    {
        $I = $this;
        $I->amOnPage(\CategoryManagerJ3Page::$URL);
        $I->click(\CategoryManagerJ3Page::$newButton);
        $I->checkForPhpNoticesOrWarnings(\CategoryManagerJ3Page::$URLNew);
        $I->click(\CategoryManagerJ3Page::$cancelButton);
        $I->waitForElement(\CategoryManagerJ3Page::$categoryFilter, 30);
    }

    /** Function create new Category is child of other category
     * @param $categoryName
     * @param $NoPage
     */
    public function addCategoryChild($categoryName, $noPage)
    {
        $I = $this;
        $I->amOnPage(\CategoryManagerJ3Page::$URL);
        $I->click(\CategoryManagerJ3Page::$newButton);
        $I->checkForPhpNoticesOrWarnings(\CategoryManagerJ3Page::$URLNew);
        $I->checkForPhpNoticesOrWarnings();
        $I->fillField(\CategoryManagerJ3Page::$categoryName, $categoryName);
        $I->click(\CategoryManagerJ3Page::$parentCategory);
        $I->click(\CategoryManagerJ3Page::$choiceTemplate);
        $I->fillField(\CategoryManagerJ3Page::$categoryNoPage, $noPage);
        $I->click(\CategoryManagerJ3Page::$template);
        $I->click(\CategoryManagerJ3Page::$choiceTemplate);
        $I->click(\CategoryManagerJ3Page::$saveCloseButton);
        $I->waitForElement(\CategoryManagerJ3Page::$categoryFilter, 30);
    }


    /**
     * @param $categoryName
     * @param $noPage
     * @param $productAccessories
     * Here, can you support to fills in value to choice
     *
     */
    public function addCategoryAccessories($categoryName, $noPage, $productAccessories)
    {
        $I = $this;
        $I->amOnPage(\CategoryManagerJ3Page::$URL);
        $I->click(\CategoryManagerJ3Page::$newButton);
        $I->checkForPhpNoticesOrWarnings(\CategoryManagerJ3Page::$URLNew);
        $I->fillField(\CategoryManagerJ3Page::$categoryName, $categoryName);
        $I->click(\CategoryManagerJ3Page::$parentCategory);
        $I->click(\CategoryManagerJ3Page::$choiceTemplate);
        $I->fillField(\CategoryManagerJ3Page::$categoryNoPage, $noPage);
        $I->click(\CategoryManagerJ3Page::$template);
        $I->click(\CategoryManagerJ3Page::$choiceTemplate);
        $I->click(\CategoryManagerJ3Page::$tabAccessory);
        $I->waitForElement(\CategoryManagerJ3Page::$getAccessory, 60);
        $this->selectAccessories($productAccessories);
        $I->click(\CategoryManagerJ3Page::$saveCloseButton);
        $I->waitForElement(\CategoryManagerJ3Page::$categoryFilter, 30);
        $I->see(\CategoryManagerJ3Page::$messageSaveSuccess, \CategoryManagerJ3Page::$selectorSuccess);
    }

    private function selectAccessories($accessoryName)
    {
        $I = $this;
        $I->click(\CategoryManagerJ3Page::$accessorySearch);
        $I->waitForElement(\CategoryManagerJ3Page::$searchFirst);
        $I->fillField(\CategoryManagerJ3Page::$searchFirst, $accessoryName);
        $userCategoryPage = new \CategoryManagerJ3Page();
        $I->waitForElement($userCategoryPage->xPathAccessory($accessoryName), 60);
        $I->click($userCategoryPage->xPathAccessory($accessoryName));
    }

    // That is the function for udpate category

    /** Function update category and clicks "Save" button
     * @param $categoryName
     * @param $updatedName
     */
    public function updateCategorySave($categoryName, $updatedName)
    {
        $I = $this;
        $I->amOnPage(\CategoryManagerJ3Page::$URL);
        $I->searchCategory($categoryName);
        $I->wait(3);
        $I->see($categoryName, \CategoryManagerJ3Page::$categoryResultRow);
        $value = $I->grabTextFrom(\CategoryManagerJ3Page::$categoryId);
        $I->click(\CategoryManagerJ3Page::$checkAll);
        $I->click(['link' => $categoryName]);
        $I->waitForElement(\CategoryManagerJ3Page::$categoryName, 30);

        $URLEdit = \CategoryManagerJ3Page::$URLEdit . $value;
        $I->checkForPhpNoticesOrWarnings($URLEdit);
        $I->fillField(\CategoryManagerJ3Page::$categoryName, $updatedName);
        $I->click(\CategoryManagerJ3Page::$saveButton);
        $I->waitForElement(\CategoryManagerJ3Page::$categoryName, 30);
        $I->see(\CategoryManagerJ3Page::$messageSaveSuccess, \CategoryManagerJ3Page::$selectorSuccess);
    }


    /**
     * Function to Update name of  Category
     *
     * @param   String $categoryName Name of the Category
     * @param   String $updatedName Updated Name of the Category
     *
     * @return void
     */
    public function updateCategory($categoryName, $updatedName)
    {
        $I = $this;
        $I->amOnPage(\CategoryManagerJ3Page::$URL);
        $I->searchCategory($categoryName);
        $I->wait(3);
        $I->see($categoryName, \CategoryManagerJ3Page::$categoryResultRow);
        $value = $I->grabTextFrom(\CategoryManagerJ3Page::$categoryId);
        $I->click(\CategoryManagerJ3Page::$checkAll);
        $I->click(['link' => $categoryName]);
        $I->waitForElement(\CategoryManagerJ3Page::$categoryName, 30);

        $URLEdit = \CategoryManagerJ3Page::$URLEdit . $value;
        $I->checkForPhpNoticesOrWarnings($URLEdit);
        $I->fillField(\CategoryManagerJ3Page::$categoryName, $updatedName);
        $I->click(\CategoryManagerJ3Page::$saveCloseButton);
        $I->waitForElement(\CategoryManagerJ3Page::$categoryFilter, 60);
        $I->see(\CategoryManagerJ3Page::$messageSaveSuccess, \CategoryManagerJ3Page::$selectorSuccess);
    }


    /**
     * Function to change State of a Category
     *
     * @param   string $categoryName Name of the Category
     * @param   string $state State of the Category
     *
     * @return void
     */
    public function changeCategoryState($categoryName, $state = 'unpublish')
    {
        $I = $this;
        $I->amOnPage(\CategoryManagerJ3Page::$URL);
        $I->searchCategory($categoryName);
        $I->wait(3);
        $I->see($categoryName, \CategoryManagerJ3Page::$categoryResultRow);
        $I->click(\CategoryManagerJ3Page::$checkAll);

        if ($state == 'unpublish') {
            $I->click(\CategoryManagerJ3Page::$categoryStatePath);
        } else {
            $I->click(\CategoryManagerJ3Page::$categoryStatePath);
        }
    }

    /**
     * Function to Search for a Category
     *
     * @param   string $categoryName Name of the Category
     * @param   string $functionName Name of the function After Which search is being Called
     *
     * @return void
     */
    public function searchCategory($categoryName)
    {
        $I = $this;
        $I->wantTo('Search the Category');
        $I->amOnPage(\CategoryManagerJ3Page::$URL);
        $I->waitForText(\CategoryManagerJ3Page::$pageManageName, 30, \CategoryManagerJ3Page::$headPageName);
        $I->filterListBySearching($categoryName);
    }

    /**
     * Function to get State of the Category
     *
     * @param   String $categoryName Name of the Category
     *
     * @return string
     */
    public function getCategoryState($categoryName)
    {
        $I = $this;
        $I->amOnPage(\CategoryManagerJ3Page::$URL);
        $I->searchCategory($categoryName);
        $I->wait(3);
        $I->see($categoryName, \CategoryManagerJ3Page::$categoryResultRow);
        $text = $I->grabAttributeFrom(\CategoryManagerJ3Page::$categoryStatePath, 'onclick');

        if (strpos($text, 'unpublish') > 0) {
            $result = 'published';
        }

        if (strpos($text, 'publish') > 0) {
            $result = 'unpublished';
        }

        return $result;
    }

    public function deleteWithoutChoice()
    {
        $I = $this;
        $I->amOnPage(\CategoryManagerJ3Page::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->click(\CategoryManagerJ3Page::$deleteButton);
        $I->acceptPopup();
        $I->waitForElement(\CategoryManagerJ3Page::$categoryFilter, 30);
    }

    /**
     * Function to Delete a Category
     *
     * @param   String $categoryName Name of the Category
     *
     * @return void
     */
    public function deleteCategory($categoryName)
    {
        $I = $this;
        $I->amOnPage(\CategoryManagerJ3Page::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->searchCategory($categoryName);
        $I->checkAllResults();
        $I->click(\CategoryManagerJ3Page::$deleteButton);
        $I->acceptPopup();
        $I->waitForText(\CategoryManagerJ3Page::$messageDeleteSuccess, 60, \CategoryManagerJ3Page::$selectorSuccess);
        $I->see(\CategoryManagerJ3Page::$messageDeleteSuccess, \CategoryManagerJ3Page::$selectorSuccess);
        $I->fillField(\CategoryManagerJ3Page::$categoryFilter, $categoryName);
        $I->pressKey(\CategoryManagerJ3Page::$categoryFilter, \Facebook\WebDriver\WebDriverKeys::ENTER);
        $I->dontSee($categoryName, \CategoryManagerJ3Page::$categoryResultRow);
    }

    public function deleteAllCategory()
    {
        $I = $this;
        $I->amOnPage(\CategoryManagerJ3Page::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->click(\CategoryManagerJ3Page::$checkAllCategory);
        $I->click(\CategoryManagerJ3Page::$deleteButton);
        $I->acceptPopup();
        $I->waitForText(\CategoryManagerJ3Page::$messageError, 30, \CategoryManagerJ3Page::$selectorError);

    }


    public function publishWithoutChoice()
    {
        $I = $this;
        $I->amOnPage(\CategoryManagerJ3Page::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->click(\CategoryManagerJ3Page::$publishButton);
        $I->acceptPopup();
//        $I->waitForElement(\CategoryManagerJ3Page::$categoryFilter, 30);
    }


    public function publishAllCategory()
    {
        $I = $this;
        $I->amOnPage(\CategoryManagerJ3Page::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->click(\CategoryManagerJ3Page::$checkAllCategory);
        $I->click(\CategoryManagerJ3Page::$publishButton);
        $I->waitForText(\CategoryManagerJ3Page::$messageSuccess, 30, \CategoryManagerJ3Page::$selectorSuccess);
    }

    public function unpublishWithoutChoice()
    {
        $I = $this;
        $I->amOnPage(\CategoryManagerJ3Page::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->click(\CategoryManagerJ3Page::$unpublishButton);
        $I->acceptPopup();
    }

    public function unpublishAllCategories()
    {
        $I = $this;
        $I->amOnPage(\CategoryManagerJ3Page::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->click(\CategoryManagerJ3Page::$checkAllCategory);
        $I->click(\CategoryManagerJ3Page::$unpublishButton);
        $I->waitForText(\CategoryManagerJ3Page::$messageSuccess, 30, \CategoryManagerJ3Page::$selectorSuccess);
    }

    public function checkinWithoutChoice()
    {
        $I = $this;
        $I->amOnPage(\CategoryManagerJ3Page::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->click(\CategoryManagerJ3Page::$checkInButton);
        $I->acceptPopup();
    }


    public function checkinAllCategories()
    {
        $I = $this;
        $I->amOnPage(\CategoryManagerJ3Page::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->click(\CategoryManagerJ3Page::$checkAllCategory);
        $I->click(\CategoryManagerJ3Page::$checkInButton);
        $I->waitForText(\CategoryManagerJ3Page::$messageSuccess, 30, \CategoryManagerJ3Page::$selectorSuccess);
    }


}
