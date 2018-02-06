<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2018 redCOMPONENT.com. All rights reserved.
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
	 */
	public function addCategoryChild($categoryParent, $categoryName, $noPage)
	{
		$tester = $this;
		$tester->amOnPage(CategoryPage::$url);
		$tester->click(CategoryPage::$buttonNew);
		$tester->fillField(CategoryPage::$idFieldName, $categoryName);
		$tester->click(CategoryPage::$parentCategory);
		$tester->click(CategoryPage::$choiceTemplate);
		$tester->fillField(CategoryPage::$categoryNoPage, $noPage);
		$tester->click(CategoryPage::$template);
		$tester->click(CategoryPage::$choiceTemplate);
		$tester->click(CategoryPage::$buttonSave);
		$categoryParent = '- ' . $categoryParent;
		$tester->see($categoryParent);
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
		$tester->searchItem($categoryName);
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
}
