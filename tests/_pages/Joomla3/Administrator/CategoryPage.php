<?php
/**
 * @package     redSHOP
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
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
	 * @since 2.1.2
	 */
	public static $url = '/administrator/index.php?option=com_redshop&view=categories';

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $urlFrontEnd = "/index.php?option=com_redshop&view=&view=category";

	// Page name

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $namePage = "Category Management";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $categoryFilter = "#filter_search";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $categoryTemplateDropDown = "//div[@id='filter_category_template']/a";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $categoryId = "//tbody/tr/td[9]";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $categoryStatePath = "//tbody/tr/td[7]/a";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $categoryTemplateIDDropDown = "//div[@id='s2id_filter_category_template']/a";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $categoryNoPage = "#jform_products_per_page";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $parentCategory = "//div[@id='s2id_jform_parent_id']/a";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $parentCategoryInput = "#s2id_autogen9_search";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $choiceCategoryParent = "//div[@id='select2-result-label-13']/a";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $accessories = "//div[@id='s2id_category_accessory_search']/a";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $accessoriesFill = "#s2id_autogen1";

	/**
	 * @var array
	 * @since 2.1.2
	 */
	public static $tabAccessory = ['link' => "Accessories"];

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $accessorySearch = "//div[@id='s2id_category_accessory_search']//a";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $searchFirst = "#s2id_autogen1_search";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $getAccessory = "//h3[text()='Accessories']";


	//templatep

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $template = "//div/div/div[@id='s2id_jform_more_template']/ul";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $template1 = '#s2id_jform_template';

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $choiceTemplate = '//ul[@class="select2-results"]/li[2]/div[@class="select2-result-label"]';

	/**
	 * @var string
	 */
	public static $messageErrorDeleteCategoryHasChildCategoriesOrProducts = "kindly remove those";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $fieldUploadImage = "//input[@type='file' and position() = 1]";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $tabSEO = "SEO";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $titlePage = "#jform_pagetitle";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $metaKey = "#jform_metakey";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $descriptionSEO = "#jform_metadesc";

	//button

	/**
	 * Function to get the Path for Template ID
	 *
	 * @param   String $templateIDName Name of the Template
	 *
	 * @return string
	 * @since 2.1.2
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
	 * @since 2.1.2
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
	 * @since 2.1.2
	 */
	public function xPathAccessory($accessoryName)
	{
		$path = "//span[contains(text(), '" . $accessoryName . "')]";

		return $path;
	}

	/**
	 * @param $categoryName
	 * @return string
	 * @since 2.1.2
	 */
	public function imageCategory($categoryName)
	{
		return "//img[@title='$categoryName']";
	}
}