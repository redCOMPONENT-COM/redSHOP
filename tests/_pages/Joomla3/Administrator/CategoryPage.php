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
 * @since  2.4
 */
class CategoryPage extends AdminJ3Page
{
    /**
     * @var string
     */
	public static $url = '/administrator/index.php?option=com_redshop&view=categories';

	// Page name

    /**
     * @var string
     */
	public static $namePage = "Category Management";

    /**
     * @var string
     */
	public static $categoryFilter = "#filter_search";

    /**
     * @var string
     */
	public static $categoryTemplateDropDown = "//div[@id='filter_category_template']/a";

    /**
     * @var string
     */
	public static $categoryId = "//tbody/tr/td[9]";

    /**
     * @var string
     */
	public static $categoryStatePath = "//tbody/tr/td[7]/a";

    /**
     * @var string
     */
	public static $categoryTemplateIDDropDown = "//div[@id='s2id_filter_category_template']/a";

    /**
     * @var string
     */
	public static $categoryNoPage = "#jform_products_per_page";

    /**
     * @var string
     */
	public static $parentCategory = "//div[@id='s2id_jform_parent_id']/a";

    /**
     * @var string
     */
	public static $choiceCategoryParent = "//div[@id='select2-result-label-13']/a";

    /**
     * @var string
     */
	public static $accessories = "//div[@id='s2id_category_accessory_search']/a";

    /**
     * @var string
     */
	public static $accessoriesFill = "#s2id_autogen1";

    /**
     * @var array
     */
	public static $tabAccessory = ['link' => "Accessories"];

    /**
     * @var string
     */
	public static $accessorySearch = "//div[@id='s2id_category_accessory_search']//a";

    /**
     * @var string
     */
	public static $searchFirst = "#s2id_autogen1_search";

    /**
     * @var string
     */
	public static $getAccessory = "//h3[text()='Accessories']";


	//templatep

    /**
     * @var string
     */
	public static $template = "//div/div/div[@id='s2id_jform_more_template']/ul";

    /**
     * @var string
     */
	public static $choiceTemplate = '//ul[@class="select2-results"]/li[2]/div[@class="select2-result-label"]';

    /**
     * @var string
     */
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

    /**
     * Function to get the Path for $accessoryName
     *
     * @param $accessoryName
     *
     * @return string
     */
	public function xPathAccessory($accessoryName)
	{
		$path = "//span[contains(text(), '" . $accessoryName . "')]";

		return $path;
	}
}