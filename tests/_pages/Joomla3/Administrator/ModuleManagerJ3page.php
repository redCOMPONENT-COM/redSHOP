<?php
/**
 * @package     redSHOP
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class ModuleManagerJ3page
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  2.4
 */
class ModuleManagerJ3page extends AdminJ3Page
{
	/**
	 * @var string
	 */
	public static $modulesTitle = "Modules (Site)";
	/**
	 * @var string
	 */
	public static $fieldName = '#jform_title';

	/**
	 * @var string
	 */
	public static $position = "//span[contains(text(),'Type or Select a Position')]";

	/**
	 * @var string
	 */
	public static $fieldPosition = "//div[@id='jform_position_chzn']//input[@type='text']";

	/**
	 * @var string
	 */
	public static $labelSearchTypeField = "//div[@id='jform_position_chzn']//input[@type='text']";

	/**
	 * @var string
	 */
	public static $searchTypeFieldYes = "//label[@for='jform_params_showSearchTypeField0']";

	/**
	 * @var string
	 */
	public static $searchTypeFieldNo = "//label[@for='jform_params_showSearchTypeField1']";

	/**
	 * @var string
	 */
	public static $searchFieldYes = "//label[@for='jform_params_showSearchField0']";

	/**
	 * @var string
	 */
	public static $searchFieldNo = "//label[@for='jform_params_showSearchField1']";

	/**
	 * @var string
	 */
	public static $categoryFieldYes = "//label[@for='jform_params_showCategory0']";

	/**
	 * @var string
	 */
	public static $categoryFieldNo  = "//label[@for='jform_params_showCategory1']";

	/**
	 * @var string
	 */
	public static $manufacturerFieldYes  = "//label[@for='jform_params_showManufacturer0']";

	/**
	 * @var string
	 */
	public static $manufacturerFieldNo  = "//label[@for='jform_params_showManufacturer1']";

	/**
	 * @var string
	 */
	public static $productSearchTitleYes  = "//label[@for='jform_params_showProductsearchtitle0']";

	/**
	 * @var string
	 */
	public static $productSearchTitleNo  = "//label[@for='jform_params_showProductsearchtitle1']";

	/**
	 * @var string
	 */
	public static $keywordTitleYes  ="//label[@for='jform_params_showKeywordtitle0']";

	/**
	 * @var string
	 */
	public static $keywordTitleNo  = "//label[@for='jform_params_showKeywordtitle1']";

	/**
	 * @var string
	 */
	public static $messageSaveModuleSuccess  = 'Module saved';

	/**
	 * @var string
	 */
	public static $buttonSearch = "//div[@class='btn-wrapper input-append']//button[@type='submit']";
}
