<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class CustomFieldManagerJoomla3Page
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  1.4
 */
class CustomFieldManagerJoomla3Page
{
	public static $URL = '/administrator/index.php?option=com_redshop&view=fields';

	public static $fieldName = "#field_name";

	public static $fieldTitle = "//*[@id='field_title']";

	public static $fieldTypeDropDown = "//*[@id='select2-chosen-1']";

	public static $searchFilter = "//*[@id='filter']";	

	public static $fieldTypeSearchField = "//*[@id='s2id_autogen1_search']";

	public static $fieldSectionDropDown = "//*[@id='select2-chosen-2']";
	
	public static $checkAll = "//tbody/tr[1]/td[3]/a";

	public static $fieldSectionSearchField = "//div[@id='field_section_chzn']/div/div/input";

	public static $fieldSuccessMessage = 'Field details saved';

	public static $fieldMessagesLocation = "//*[@class='alert-message']";

	public static $firstResultRow = "//tbody/tr[1]/td[3]/a";

	public static $selectFirst = "//tbody/tr[1]/td[3]/a";

	public static $fieldStatePath = "//tbody/tr[1]/td[8]/a";

	public static $optionValueField = "//input[@name='extra_value[]']";

	/**
	 * Function to get the path for Field Type
	 *
	 * @param   String  $type  Type of Field to be Added
	 *
	 * @return string
	 */
	public function fieldType($type)
	{
		$path = "//*[@id='select2-results-1']";
		return $path;
	}

	/**
	 * Function to get the path for Field Section
	 *
	 * @param   String  $section  Section of Field to be Added
	 *
	 * @return string
	 */
	public function fieldSection($section)
	{
		$path = "//*[@id='select2-results-2']";

		return $path;
	}
}
