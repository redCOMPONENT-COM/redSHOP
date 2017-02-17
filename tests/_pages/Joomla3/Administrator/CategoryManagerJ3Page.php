<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class CategoryManagerJ3Page
 *
 * @since  1.4
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 */
class CategoryManagerJ3Page
{
	// Include url of current page
	public static $URL = '/administrator/index.php?option=com_redshop&view=category';

	public static $categoryName = "#category_name";

	public static $categoryFilter = ['id' => 'category_main_filter'];

	public static $categoryTemplateDropDown = "//div[@id='category_template_chzn']/a";

	public static $categorySearch = "//button[@onclick=\"document.adminForm.submit();\"]";

	public static $categoryResultRow = "//div[@id='editcell']/div[2]/table/tbody/tr/td[3]";

	public static $categoryStatePath = "//tbody/tr/td[7]/a";

	public static $checkAll = "//input[@id='cb0']";

	public static $categoryTemplateIDDropDown = "//div[@id='compare_template_id_chzn']/a";

	/**
	 * Function to get the Path for Template ID
	 *
	 * @param   String  $templateIDName  Name of the Template
	 *
	 * @return string
	 */
	public function categoryTemplateID($templateIDName)
	{
		$path = "//div[@id='compare_template_id_chzn']/div/ul/li[contains(text(), '" . $templateIDName . "')]";

		return $path;
	}

	/**
	 * Function to get the Path for Template
	 *
	 * @param   String  $templateName  Name of the Template
	 *
	 * @return string
	 */
	public function categoryTemplate($templateName)
	{
		$path = "//div[@id='category_template_chzn']/div/ul/li[contains(text(), '" . $templateName . "')]";

		return $path;
	}
}
