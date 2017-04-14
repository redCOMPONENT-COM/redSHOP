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
	/**
	 * Function  to Create a New Category
	 *
	 * @param   String  $categoryName  Name of the Category
	 *
	 * @return void
	 */
	public function addCategory($categoryName)
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

	/**
	 * Function to Update a Category
	 *
	 * @param   String  $categoryName  Name of the Category
	 * @param   String  $updatedName   Updated Name of the Category
	 *
	 * @return void
	 */
	public function updateCategory($categoryName, $updatedName)
	{
		$I = $this;
		$I->amOnPage(\CategoryManagerJ3Page::$URL);
		$I->fillField(\CategoryManagerJ3Page::$categoryFilter, $categoryName);
		$I->pressKey(\CategoryManagerJ3Page::$categoryFilter, \Facebook\WebDriver\WebDriverKeys::TAB);
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
	 * @param   string  $categoryName  Name of the Category
	 * @param   string  $state         State of the Category
	 *
	 * @return void
	 */
	public function changeCategoryState($categoryName, $state = 'unpublish')
	{
		$I = $this;
		$I->amOnPage(\CategoryManagerJ3Page::$URL);
		$I->fillField(\CategoryManagerJ3Page::$categoryFilter, $categoryName);
		$I->pressKey(\CategoryManagerJ3Page::$categoryFilter, \Facebook\WebDriver\WebDriverKeys::TAB);
		$I->wait(3);
		$I->see($categoryName, \CategoryManagerJ3Page::$categoryResultRow);
		$I->click(\CategoryManagerJ3Page::$checkAll);

		if ($state == 'unpublish')
		{
			$I->click(\CategoryManagerJ3Page::$categoryStatePath);
		}
		else
		{
			$I->click(\CategoryManagerJ3Page::$categoryStatePath);
		}
	}

	/**
	 * Function to Search for a Category
	 *
	 * @param   string  $categoryName  Name of the Category
	 * @param   string  $functionName  Name of the function After Which search is being Called
	 *
	 * @return void
	 */
	public function searchCategory($categoryName, $functionName = 'Search')
	{
		$I = $this;
		$I->amOnPage(\CategoryManagerJ3Page::$URL);
		$I->waitForElement(\CategoryManagerJ3Page::$categoryFilter, 30);
		$I->fillField(\CategoryManagerJ3Page::$categoryFilter, $categoryName);
		$I->pressKey(\CategoryManagerJ3Page::$categoryFilter, \Facebook\WebDriver\WebDriverKeys::TAB);
		$I->wait(3);

		if ($functionName == 'Search')
		{
			$I->see($categoryName, \CategoryManagerJ3Page::$categoryResultRow);
		}
		else
		{
			$I->dontSee($categoryName, \CategoryManagerJ3Page::$categoryResultRow);
		}
	}

	/**
	 * Function to get State of the Category
	 *
	 * @param   String  $categoryName  Name of the Category
	 *
	 * @return string
	 */
	public function getCategoryState($categoryName)
	{
		$I = $this;
		$I->amOnPage(\CategoryManagerJ3Page::$URL);
		$I->waitForElement(\CategoryManagerJ3Page::$categoryFilter, 30);
		$I->fillField(\CategoryManagerJ3Page::$categoryFilter, $categoryName);
		$I->pressKey(\CategoryManagerJ3Page::$categoryFilter, \Facebook\WebDriver\WebDriverKeys::TAB);
		$I->wait(3);
		$I->see($categoryName, \CategoryManagerJ3Page::$categoryResultRow);
		$text = $I->grabAttributeFrom(\CategoryManagerJ3Page::$categoryStatePath, 'onclick');

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
	 * Function to Delete a Category
	 *
	 * @param   String  $categoryName  Name of the Category
	 *
	 * @return void
	 */
	public function deleteCategory($categoryName)
	{
		$I = $this;
		$I->amOnPage(\CategoryManagerJ3Page::$URL);
		$I->checkForPhpNoticesOrWarnings();
		$I->searchSupplier($categoryName);
		$I->checkAllResults();
		$I->click("Delete");
		$I->acceptPopup();
		$I->waitForText("1 item successfully deleted", 60, '.alert-success');
		$I->see("1 item successfully deleted", '.alert-success');
		$I->fillField(\CategoryManagerJ3Page::$categoryFilter, $categoryName);
		$I->pressKey(\CategoryManagerJ3Page::$categoryFilter, \Facebook\WebDriver\WebDriverKeys::ENTER);
		$I->dontSee($categoryName, \CategoryManagerJ3Page::$categoryResultRow);
	}
}
