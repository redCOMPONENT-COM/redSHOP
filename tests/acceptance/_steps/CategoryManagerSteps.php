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
	// Include url of current page
	public static $URL = '/administrator/index.php?option=com_redshop&view=category';

	/**
	 * Basic route example for your current URL
	 * You can append any additional parameter to URL
	 * and use it in tests like: EditPage::route('/123-post');
	 *
	 * @return  void
	 */
	public static function route($param = "")
	{
		return static::$URL . $param;
	}

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
		$I->amOnPage($this->route());
		$I->click("New");
		$I->fillField("#category_name", $categoryName);
		$I->selectOption("#compare_template_id", "compare_product");
		$I->selectOption("#category_template", "list");
		$I->click("Save & Close");
		$I->waitForElement("//input[@id='category_main_filter']", 30);
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
		$I->amOnPage($this->route());
		$I->fillField("//input[@id='category_main_filter']", $categoryName);
		$I->click("//button[@onclick=\"document.adminForm.submit();\"]");
		$I->see($categoryName, "//div[@id='editcell']/table/tbody/tr/td[3]");
		$this->selectAll($I);
		$I->click($categoryName);
		$I->fillField("#category_name", $updatedName);
		$I->click("Save & Close");
		$I->waitForElement("//input[@id='category_main_filter']", 30);
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
		$I->amOnPage($this->route());
		$I->fillField("//input[@id='category_main_filter']", $categoryName);
		$I->click("//button[@onclick=\"document.adminForm.submit();\"]");
		$I->see($categoryName, "//div[@id='editcell']/table/tbody/tr/td[3]");
		$this->selectAll($I);

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
		$I->amOnPage($this->route());
		$I->fillField("//input[@id='category_main_filter']", $categoryName);
		$I->click("//button[@onclick=\"document.adminForm.submit();\"]");

		if ($functionName == 'Search')
		{
			$I->see($categoryName, "//div[@id='editcell']/table/tbody/tr/td[3]/a");
			$value = $I->grabTextFrom("//div[@id='editcell']/table/tbody/tr/td[3]/a");

			if ($value == $categoryName)
			{
				return true;
			}
		}
		else
		{
			$I->dontSee($categoryName, "//div[@id='editcell']/table/tbody/tr/td[3]/a");

			return false;
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
		$I->amOnPage($this->route());
		$I->fillField("//input[@id='category_main_filter']", $categoryName);
		$I->click("//button[@onclick=\"document.adminForm.submit();\"]");
		$I->see($categoryName, "//div[@id='editcell']/table/tbody/tr/td[3]");
		$text = $I->grabAttributeFrom("//tbody/tr/td[7]/a", 'onclick');

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
		$I->amOnPage($this->route());
		$I->fillField("//input[@id='category_main_filter']", $categoryName);
		$I->click("//button[@onclick=\"document.adminForm.submit();\"]");
		$I->see($categoryName, "//div[@id='editcell']/table/tbody/tr/td[3]");
		$this->selectAll($I);
		$I->click("Delete");
		$I->acceptPopup();
	}

	/**
	 * Function to Click on Check All
	 *
	 * @param   Object  $I  Current Scenario
	 *
	 * @return void
	 */
	public function selectAll($I)
	{
		$I->click("//input[@onclick='checkAll(1);']");
	}
}
