<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class DiscountProductPage
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  2.1.0
 */
class CategoryPage extends AdminJ3Page
{
	public static $url = '/administrator/index.php?option=com_redshop&view=categories';

	// Page name
	public static $namePage = "Category Management";

	public static $categoryFilter = ['id' => 'filter_search'];

	public static $categoryTemplateDropDown = "//div[@id='filter_category_template']/a";

	public static $categoryId = "//tbody/tr/td[9]";

	public static $categoryStatePath = "//tbody/tr/td[7]/a";

	public static $categoryTemplateIDDropDown = "//div[@id='s2id_filter_category_template']/a";

	public static $categoryNoPage = "#jform_products_per_page";

	public static $parentCategory = "//div[@id='s2id_jform_parent_id']/a";

	public static $choiceCategoryParent = "//div[@id='select2-result-label-13']/a";

	public static $accessories = "//div[@id='s2id_category_accessory_search']/a";

	public static $accessoriesFill = "#s2id_autogen1";

	public static $tabAccessory = ['link' => "Accessories"];

	public static $accessorySearch = ['xpath' => '//div[@id="s2id_category_accessory_search"]//a'];

	public static $searchFirst = ['id' => "s2id_autogen1_search"];

	public static $getAccessory = ['xpath' => "//h3[text()='Accessories']"];


	//templatep

	public static $template = "//div/div/div[@id='s2id_jform_more_template']/ul";

	public static $choiceTemplate = '//ul[@class="select2-results"]/li[2]/div[@class="select2-result-label"]';


	public static $messageErrorDeleteCategoryHasChildCategoriesOrProducts = "kindly remove those";

	//button

	/**
	 * Function to get the Path for Template ID
	 *
	 * @param   String $templateIDName Name of the Template
	 *
	 * @return string
	 */
	public function categoryTemplateID($templateIDName)
	{
		$path = "//div[@id='s2id_filter_category_template']/div/ul/li[contains(text(), '" . $templateIDName . "')]";

		return $path;
	}

	/**
	 * Function to get the Path for Template
	 *
	 * @param   String $templateName Name of the Template
	 *
	 * @return string
	 */
	public function categoryTemplate($templateName)
	{
		$path = "//div[@id='filter_category_template']/div/ul/li[contains(text(), '" . $templateName . "')]";

		return $path;
	}

	public function xPathAccessory($accessoryName)
	{
		$path = ['xpath' => "//span[contains(text(), '" . $accessoryName . "')]"];

		return $path;
	}
}