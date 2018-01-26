<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2018 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
use Step\AbstractStep;
use \CategoryPage as CategoryPage;
class CategorySteps extends AbstractStep
{
    use Step\Traits\CheckIn, Step\Traits\Publish, Step\Traits\Delete;

    /** Function create new Category is child of other category
     *
     * @param $categoryName
     * @param $NoPage
     */
    public function addCategoryChild($categoryParent, $categoryName, $noPage)
    {
        $I = $this;
        $I->amOnPage(CategoryPage::$url);
        $I->click(CategoryPage::$buttonNew);
        $I->fillField(CategoryPage::$idFieldName, $categoryName);
        $I->click(CategoryPage::$parentCategory);
        $I->click(CategoryPage::$choiceTemplate);
        $I->fillField(CategoryPage::$categoryNoPage, $noPage);
        $I->click(CategoryPage::$template);
        $I->click(CategoryPage::$choiceTemplate);
        $I->click(CategoryPage::$buttonSave);
        $categoryParent = '- '. $categoryParent;
        $I->see($categoryParent);
    }

    public function addCategoryAccessories($categoryName, $noPage, $productAccessories)
    {
        $I = $this;
        $I->amOnPage(CategoryPage::$url);
        $I->click(CategoryPage::$buttonNew);
        $I->fillField(CategoryPage::$idFieldName, $categoryName);
        $I->click(CategoryPage::$parentCategory);
        $I->click(CategoryPage::$choiceTemplate);
        $I->fillField(CategoryPage::$categoryNoPage, $noPage);
        $I->click(CategoryPage::$template);
        $I->click(CategoryPage::$choiceTemplate);
        $I->click(CategoryPage::$tabAccessory);
        $I->waitForElement(CategoryPage::$getAccessory, 60);
        $this->selectAccessories($productAccessories);
        $I->click(CategoryPage::$buttonSave);
        $I->waitForElement(CategoryPage::$categoryFilter, 30);
    }

    // That is the function for udpate category
    private function selectAccessories($accessoryName)
    {
        $I = $this;
        $I->click(CategoryPage::$accessorySearch);
        $I->waitForElement(CategoryPage::$searchFirst);
        $I->fillField(CategoryPage::$searchFirst, $accessoryName);
        $userCategoryPage = new CategoryPage();
        $I->waitForElement($userCategoryPage->xPathAccessory($accessoryName), 60);
        $I->click($userCategoryPage->xPathAccessory($accessoryName));
    }
}