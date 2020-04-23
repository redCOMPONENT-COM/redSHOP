<?php
/**
 * @package     redSHOP
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class CustomFieldManagerJoomla3Page
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since 1.4.0
 */
class CustomFieldManagerJoomla3Page
{
	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $URL = '/administrator/index.php?option=com_redshop&view=fields';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $URLNew = '';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $fieldName = "//input[@id='jform_name']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $fieldTitle = "//input[@id='jform_title']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $fieldTypeDropDown = "#s2id_jform_type";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $fieldTypeSearch = "#s2id_autogen1_search";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $fieldSectionDropDown = "#s2id_jform_section";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $fieldSectionSearch = "#s2id_autogen2_search";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $fieldTypeSearchField = "//div[@id='jform_type_chzn']/div/div/input";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $fieldSectionSearchField = "//div[@id='jform_section_chzn']/div/div/input";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $fieldSuccessMessage = 'Item saved.';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $fieldMessagesLocation = "//div[@id='system-message-container']/div/p";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $firstResultRow = "//div[@id='editcell']/table/tbody/tr[1]";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $selectFirst = "//input[@id='cb0']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $fieldStatePath = "//div[@id='editcell']/table/tbody/tr[1]/td[8]/a";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $optionValueField = "//input[@name='extra_value[]']";

	//message

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $messageSaveSuccess='Item saved.';

	//selector

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $selectorSuccess = '.alert-success';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $selectorError = '.alert-danger';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $selectorNamePage = '.page-title';

	/**
	 * Function to get the path for Field Type
	 *
	 * @param   String $type Type of Field to be Added
	 *
	 * @return string
	 * @since 1.4.0
	 */
	public function fieldType($type)
	{
		$path = "//div[@id='jform_type_chzn']/div/ul/li//em[contains(text(),'" . $type . "')]";

		return $path;
	}

	/**
	 * Function to get the path for Field Section
	 *
	 * @param   String $section Section of Field to be Added
	 *
	 * @return string
	 * @since 1.4.0
	 */
	public function fieldSection($section)
	{
		$path = "//div[@id='jform_section_chzn']/div/ul/li//em[contains(text(),'" . $section . "')]";

		return $path;
	}

	/**
	 *
	 * Function to get the Path for $typeChoice
	 *
	 * @param $typeChoice
	 *
	 * @return string
	 * @since 1.4.0
	 */
	public function xPathChoice($typeChoice)
	{
		$path = "//span[contains(text(), '" . $typeChoice . "')]";
		return $path;
	}

	//button

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $newButton = "New";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $saveButton = "Save";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $unpublishButton = "Unpublish";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $publishButton = "Publish";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $saveCloseButton = "Save & Close";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $deleteButton = "Delete";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $editButton = "Edit";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $saveNewButton = "Save & New";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $cancelButton = "Cancel";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $checkInButton = "Check-in";
}
