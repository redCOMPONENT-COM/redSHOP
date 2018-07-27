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
 * @since  2.4
 */
class CustomFieldManagerJoomla3Page
{
    /**
     * @var string
     */
    public static $URL = '/administrator/index.php?option=com_redshop&view=fields';

    /**
     * @var string
     */
    public static $URLNew = '';

    /**
     * @var string
     */
    public static $fieldName = "//input[@id='jform_name']";

    /**
     * @var string
     */
    public static $fieldTitle = "//input[@id='jform_title']";

    /**
     * @var string
     */
    public static $fieldTypeDropDown = "#s2id_jform_type";

    /**
     * @var string
     */
    public static $fieldTypeSearch = "#s2id_autogen1_search";

    /**
     * @var string
     */
    public static $fieldSectionDropDown = "#s2id_jform_section";

    /**
     * @var string
     */
    public static $fieldSectionSearch = "#s2id_autogen2_search";

    /**
     * @var string
     */
    public static $filterSearch = "#filter_search";

    /**
     * @var string
     */
    public static $fieldTypeSearchField = "//div[@id='jform_type_chzn']/div/div/input";

    /**
     * @var string
     */
    public static $fieldSectionSearchField = "//div[@id='jform_section_chzn']/div/div/input";

    /**
     * @var string
     */
    public static $fieldSuccessMessage = 'Item saved.';

    /**
     * @var string
     */
    public static $fieldMessagesLocation = "//div[@id='system-message-container']/div/p";

    /**
     * @var string
     */
    public static $firstResultRow = "//div[@id='editcell']/table/tbody/tr[1]";

    /**
     * @var string
     */
    public static $selectFirst = "//input[@id='cb0']";

    /**
     * @var string
     */
    public static $fieldStatePath = "//div[@id='editcell']/table/tbody/tr[1]/td[8]/a";

    /**
     * @var string
     */
    public static $optionValueField = "//input[@name='extra_value[]']";

    //message

    /**
     * @var string
     */
    public static $messageSaveSuccess='Item saved.';


    //selector

    /**
     * @var string
     */
    public static $selectorSuccess = '.alert-success';

    /**
     * @var string
     */
    public static $selectorError = '.alert-danger';

    /**
     * @var string
     */
    public static $selectorNamePage = '.page-title';



    /**
     * Function to get the path for Field Type
     *
     * @param   String $type Type of Field to be Added
     *
     * @return string
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
     */
    public function xPathChoice($typeChoice)
    {
        $path = "//span[contains(text(), '" . $typeChoice . "')]";
        return $path;
    }


    //button

    /**
     * @var string
     */
    public static $newButton = "New";

    /**
     * @var string
     */
    public static $saveButton = "Save";

    /**
     * @var string
     */
    public static $unpublishButton = "Unpublish";

    /**
     * @var string
     */
    public static $publishButton = "Publish";

    /**
     * @var string
     */
    public static $saveCloseButton = "Save & Close";

    /**
     * @var string
     */
    public static $deleteButton = "Delete";

    /**
     * @var string
     */
    public static $editButton = "Edit";

    /**
     * @var string
     */
    public static $saveNewButton = "Save & New";

    /**
     * @var string
     */
    public static $cancelButton = "Cancel";

    /**
     * @var string
     */
    public static $checkInButton = "Check-in";

}
