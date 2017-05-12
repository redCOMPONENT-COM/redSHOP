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
        $I->click("New");
        $I->verifyNotices(false, $this->checkForNotices(), 'Category Manager Page New');
        $I->checkForPhpNoticesOrWarnings();
        $I->fillField(\CategoryManagerJ3Page::$categoryName, $categoryName);
        $I->click('//*[@id="s2id_jform_more_template"]/ul');
        $I->click('//ul[@class="select2-results"]/li[2]/div[@class="select2-result-label"]');
        $I->click("Save");
        $I->waitForElement(\CategoryManagerJ3Page::$categoryName, 30);
    }


    /** Function add Category and click "Save" button
     * @param $categoryName
     */
    public function addCategoryName($categoryName)
    {
        $I = $this;
        $I->amOnPage(\CategoryManagerJ3Page::$URL);
        $I->click("New");
        $I->verifyNotices(false, $this->checkForNotices(), 'Category Manager Page New');
        $I->checkForPhpNoticesOrWarnings();
        $I->fillField(\CategoryManagerJ3Page::$categoryName, $categoryName);
        $I->click('//*[@id="s2id_jform_more_template"]/ul');
        $I->click('//ul[@class="select2-results"]/li[2]/div[@class="select2-result-label"]');
        $I->click("Save");
        $I->waitForElement(\CategoryManagerJ3Page::$categoryName, 30);
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
        $I->click("New");
        $I->verifyNotices(false, $this->checkForNotices(), 'Category Manager Page New');
        $I->checkForPhpNoticesOrWarnings();
        $I->fillField(\CategoryManagerJ3Page::$categoryName, $categoryName);
        $I->click('//*[@id="s2id_jform_more_template"]/ul');
        $I->click('//ul[@class="select2-results"]/li[2]/div[@class="select2-result-label"]');
        $I->click("Save & Close");
        $I->waitForElement(\CategoryManagerJ3Page::$categoryFilter, 30);
    }

    /** Create category Save and New button
     * @param $categoryName
     * @param $noPage
     */

    public function addCategorySaveNew($categoryName, $noPage)
    {
        $I = $this;
        $I->amOnPage(\CategoryManagerJ3Page::$URL);
        $I->click("New");
        $I->verifyNotices(false, $this->checkForNotices(), 'Category Manager Page New');
        $I->checkForPhpNoticesOrWarnings();
        $I->fillField(\CategoryManagerJ3Page::$categoryName, $categoryName);
        $I->fillField(\CategoryManagerJ3Page::$categoryNoPage, $noPage);
        $I->click('//*[@id="s2id_jform_more_template"]/ul');
        $I->click('//ul[@class="select2-results"]/li[2]/div[@class="select2-result-label"]');
        $I->click("Save & New");
        $I->waitForElement(\CategoryManagerJ3Page::$categoryName, 30);
    }

    /**
     *  Function check Cancel button
     */
    public function addCategoryCancel()
    {
        $I = $this;
        $I->amOnPage(\CategoryManagerJ3Page::$URL);
        $I->click("New");
        $I->verifyNotices(false, $this->checkForNotices(), 'Category Manager Page New');
        $I->checkForPhpNoticesOrWarnings();
        $I->click("Cancel");
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
        $I->click("New");
        $I->verifyNotices(false, $this->checkForNotices(), 'Category Manager Page New');
        $I->checkForPhpNoticesOrWarnings();
        $I->fillField(\CategoryManagerJ3Page::$categoryName, $categoryName);
        $I->click(\CategoryManagerJ3Page::$parentCategory);
        $I->wait(3);
        $I->fillField(\CategoryManagerJ3Page::$categoryNoPage, $noPage);
        $I->click('//*[@id="s2id_jform_more_template"]/ul');
        $I->click('//ul[@class="select2-results"]/li[2]/div[@class="select2-result-label"]');
        $I->click("Save & Close");
        $I->waitForElement(\CategoryManagerJ3Page::$categoryFilter, 30);
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
        $I->click(\CategoryManagerJ3Page::$checkAll);
        $I->click(['link' => $categoryName]);
        $I->waitForElement(\CategoryManagerJ3Page::$categoryName, 30);
        $I->verifyNotices(false, $this->checkForNotices(), 'Category Manager Edit');
        $I->fillField(\CategoryManagerJ3Page::$categoryName, $updatedName);
        $I->click("Save");
        $I->waitForElement(\CategoryManagerJ3Page::$categoryName, 30);
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
        $I->click(\CategoryManagerJ3Page::$checkAll);
        $I->click(['link' => $categoryName]);
        $I->waitForElement(\CategoryManagerJ3Page::$categoryName, 30);
        $I->verifyNotices(false, $this->checkForNotices(), 'Category Manager Edit');
        $I->fillField(\CategoryManagerJ3Page::$categoryName, $updatedName);
        $I->click("Save & Close");
        $I->waitForElement(\CategoryManagerJ3Page::$categoryFilter, 30);
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
        $I->waitForText('Category Management', 30, ['xpath' => "//h1"]);
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



    public function deleteWithoutChoice(){
        $I = $this;
        $I->amOnPage(\CategoryManagerJ3Page::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->click("Delete");
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
        $I->click("Delete");
        $I->acceptPopup();
        $I->waitForText("1 item successfully deleted", 60, '.alert-success');
        $I->see("1 item successfully deleted", '.alert-success');
        $I->fillField(\CategoryManagerJ3Page::$categoryFilter, $categoryName);
        $I->pressKey(\CategoryManagerJ3Page::$categoryFilter, \Facebook\WebDriver\WebDriverKeys::ENTER);
        $I->dontSee($categoryName, \CategoryManagerJ3Page::$categoryResultRow);
    }

    public function deleteAllCategory(){
        $I = $this;
        $I->amOnPage(\CategoryManagerJ3Page::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->click(\CategoryManagerJ3Page::$checkAllCategory);
        $I->click("Delete");
        $I->acceptPopup();
        $I->waitForText("Error", 60, '/html/body/div/div/div/section[1]/div[3]/div/div[1]/div/h4');

    }




    public function publishWithoutChoice(){
        $I = $this;
        $I->amOnPage(\CategoryManagerJ3Page::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->click("Publish");
        $I->waitForElement(\CategoryManagerJ3Page::$categoryFilter, 30);
    }

    

    public function publishAllCategory(){
        $I = $this;
        $I->amOnPage(\CategoryManagerJ3Page::$URL);
        $I->checkForPhpNoticesOrWarnings();
        $I->click(\CategoryManagerJ3Page::$checkAllCategory);
        $I->click("Publish");
        $I->waitForText("Message", 60, '/html/body/div/div/div/section[1]/div[3]/div/div[1]/div/h4');
    }


}
