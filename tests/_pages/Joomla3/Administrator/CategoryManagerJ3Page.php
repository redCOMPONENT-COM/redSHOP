<?php
/**
 * @package     redSHOP
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class CategoryManagerJ3Page
 *
 * @since  1.4.0
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 */
class CategoryManagerJ3Page extends AdminJ3Page
{
	// Include url of current page

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $URL = '/administrator/index.php?option=com_redshop&view=categories';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $URLEdit = '/administrator/index.php?option=com_redshop&view=category&layout=edit&id=';

	//page name

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $pageManageName = "Category Management";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $headPageName = "//h1";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $categoryName = "#jform_name";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $categoryFilter = "#filter_search";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $categoryTemplateDropDown = "//div[@id='filter_category_template']/a";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $categoryId = "//tbody/tr/td[9]";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $categoryStatePath = "//tbody/tr/td[7]/a";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $checkAll = "//input[@id='cb0']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $categoryTemplateIDDropDown = "//div[@id='s2id_filter_category_template']/a";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $categoryNoPage = "#jform_products_per_page";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $parentCategory = "//div[@id='s2id_jform_parent_id']/a";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $choiceCategoryParent = "//div[@id='select2-result-label-13']/a";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $accessories = "//div[@id='s2id_category_accessory_search']/a";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $accessoriesFill = "#s2id_autogen1";

	/**
	 * @var array
	 * @since 1.4.0
	 */
	public static $tabAccessory = ['link' => "Accessories"];

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $accessorySearch = "//div[@id='s2id_category_accessory_search']//a";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $searchFirst = "#s2id_autogen1_search";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $getAccessory = "//h3[text()='Accessories']";

	//template

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $template = "//div/div/div[@id='s2id_jform_more_template']/ul";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $choiceTemplate = '//ul[@class="select2-results"]/li[2]/div[@class="select2-result-label"]';

	//button

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $saveCloseButton = "//button[@onclick=\"Joomla.submitbutton('category.save');\"]";

	/**
	 * Function to get the Path for Template ID
	 *
	 * @param   String $templateIDName Name of the Template
	 *
	 * @return string
	 * @since 1.4.0
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
	 * @since 1.4.0
	 */
	public function categoryTemplate($templateName)
	{
		$path = "//div[@id='filter_category_template']/div/ul/li[contains(text(), '" . $templateName . "')]";

		return $path;
	}

	/**
	 * Function to get the Path for $accessoryName
	 *
	 * @param  $accessoryName
	 *
	 * @return array
	 * @since 1.4.0
	 */
	public function xPathAccessory($accessoryName)
	{
		$path = ['xpath' => "//span[contains(text(), '" . $accessoryName . "')]"];
		return $path;
	}
}
