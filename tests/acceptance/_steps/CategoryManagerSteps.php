<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;
use Codeception\Module\WebDriver;

/**
 * Class CategoryManagerSteps
 *
 * @package  AcceptanceTester
 *
 * @since    1.4
 */
class CategoryManagerSteps extends \AcceptanceTester
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
		$I->amOnPage(\CategoryManagerPage::$URL);
		$I->click("New");
		$I->fillField(\CategoryManagerPage::$categoryName, $categoryName);
		$I->selectOption(\CategoryManagerPage::$categoryTemplateId, "compare_product");
		$I->selectOption(\CategoryManagerPage::$categoryTemplate, "list");
		$I->click("Save & Close");
		$I->waitForElement(\CategoryManagerPage::$categoryFilter, 30);
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
		$I->amOnPage(\CategoryManagerPage::$URL);
		$I->fillField(\CategoryManagerPage::$categoryFilter, $categoryName);
		$I->click(\CategoryManagerPage::$categorySearch);
		$I->see($categoryName, \CategoryManagerPage::$categoryResultRow);
		$I->click(\CategoryManagerPage::$checkAll);
		$I->click($categoryName);
		$I->fillField(\CategoryManagerPage::$categoryName, $updatedName);
		$I->click("Save & Close");
		$I->waitForElement(\CategoryManagerPage::$categoryFilter, 30);
	}

	/**
	 * Function to change State of a Category
	 *
	 * @param   string  $categoryName  Name of the Category
	 * @param   string  $state         State of the Category
	 *
	 * @return void
	 */
	public function changeState($categoryName, $state = 'unpublish')
	{
		$I = $this;
		$I->amOnPage(\CategoryManagerPage::$URL);
		$I->fillField(\CategoryManagerPage::$categoryFilter, $categoryName);
		$I->click(\CategoryManagerPage::$categorySearch);
		$I->see($categoryName, \CategoryManagerPage::$categoryResultRow);
		$I->click(\CategoryManagerPage::$checkAll);

		if ($state == 'unpublish')
		{
			$I->click("Unpublish");
		}
		else
		{
			$I->click("Publish");
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
		$I->amOnPage(\CategoryManagerPage::$URL);
		$I->fillField(\CategoryManagerPage::$categoryFilter, $categoryName);
		$I->click(\CategoryManagerPage::$categorySearch);

		if ($functionName == 'Search')
		{
			$I->see($categoryName, \CategoryManagerPage::$categoryResultRow);
		}
		else
		{
			$I->dontSee($categoryName, \CategoryManagerPage::$categoryResultRow);
		}
	}

	/**
	 * Function to get State of the Category
	 *
	 * @param   String  $categoryName  Name of the Category
	 *
	 * @return string
	 */
	public function getState($categoryName)
	{
		$I = $this;
		$I->amOnPage(\CategoryManagerPage::$URL);
		$I->fillField(\CategoryManagerPage::$categoryFilter, $categoryName);
		$I->click(\CategoryManagerPage::$categorySearch);
		$I->see($categoryName, \CategoryManagerPage::$categoryResultRow);
		$text = $I->grabAttributeFrom(\CategoryManagerPage::$categoryStatePath, 'onclick');

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
		$I->amOnPage(\CategoryManagerPage::$URL);
		$I->fillField(\CategoryManagerPage::$categoryFilter, $categoryName);
		$I->click(\CategoryManagerPage::$categorySearch);
		$I->see($categoryName, \CategoryManagerPage::$categoryResultRow);
		$I->click(\CategoryManagerPage::$checkAll);
		$I->click("Delete");
		$I->acceptPopup();
	}
}
