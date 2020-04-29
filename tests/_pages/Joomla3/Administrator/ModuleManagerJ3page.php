<?php
/**
 * @package     redSHOP
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class ModuleManagerJ3page
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  1.4.0
 */
class ModuleManagerJ3page extends AdminJ3Page
{
	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $modulesTitle = "Modules (Site)";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $fieldName = '#jform_title';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $position = "//span[contains(text(),'Type or Select a Position')]";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $fieldPosition = "//div[@id='jform_position_chzn']//input[@type='text']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $labelSearchTypeField = "//div[@id='jform_position_chzn']//input[@type='text']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $searchTypeFieldYes = "//label[@for='jform_params_showSearchTypeField0']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $searchTypeFieldNo = "//label[@for='jform_params_showSearchTypeField1']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $searchFieldYes = "//label[@for='jform_params_showSearchField0']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $searchFieldNo = "//label[@for='jform_params_showSearchField1']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $categoryFieldYes = "//label[@for='jform_params_showCategory0']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $categoryFieldNo  = "//label[@for='jform_params_showCategory1']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $manufacturerFieldYes  = "//label[@for='jform_params_showManufacturer0']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $manufacturerFieldNo  = "//label[@for='jform_params_showManufacturer1']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $productSearchTitleYes  = "//label[@for='jform_params_showProductsearchtitle0']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $productSearchTitleNo  = "//label[@for='jform_params_showProductsearchtitle1']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $keywordTitleYes  ="//label[@for='jform_params_showKeywordtitle0']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $keywordTitleNo  = "//label[@for='jform_params_showKeywordtitle1']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $messageSaveModuleSuccess  = 'Module saved';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $buttonSearch = "//div[@class='btn-wrapper input-append']//button[@type='submit']";
}
