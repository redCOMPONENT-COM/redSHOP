<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class CategoryManagerPage
 *
 * @since  1.4
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 */
class CategoryManagerPage
{
	// Include url of current page
	public static $URL = '/administrator/index.php?option=com_redshop&view=category';

	public static $categoryName = "#category_name";

	public static $categoryFilter = "//input[@id='category_main_filter']";

	public static $categoryTemplateId = "#compare_template_id";

	public static $categoryTemplate = "#category_template";

	public static $categorySearch = "//button[@onclick=\"document.adminForm.submit();\"]";

	public static $categoryResultRow = "//div[@id='editcell']/table/tbody/tr/td[3]/a";

	public static $categoryStatePath = "//tbody/tr/td[7]/a";

	public static $checkAll = "//input[@onclick='checkAll(1);']";
}
