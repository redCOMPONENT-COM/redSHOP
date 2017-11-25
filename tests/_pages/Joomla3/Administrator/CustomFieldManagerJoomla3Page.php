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
class CustomFieldManagerJoomla3Page extends AdminJ3Page
{
    public static $URL = '/administrator/index.php?option=com_redshop&view=fields';

    public static $fieldName = "//input[@id='jform_name']";

    public static $fieldTitle = "//input[@id='jform_title']";

    public static $fieldTypeDropDown = ['id' => 's2id_jform_type'];

    public static $fieldTypeSearch = ['id' => 's2id_autogen1_search'];

    public static $fieldSectionDropDown = ['id' => 's2id_jform_section'];

    public static $fieldSectionSearch = ['id' => 's2id_autogen2_search'];

    public static $filterSearch = ['id' => 'filter_search'];

    public static $fieldSuccessMessage = 'Item saved.';

    public static $fieldMessagesLocation = "//div[@id='system-message-container']/div/p";

    public static $firstResultRow = "//div[@id='editcell']/table/tbody/tr[1]";

    public static $selectFirst = "//input[@id='cb0']";

	public static $fieldStatePath = ['xpath'=>'//a[contains(@class, \'btn-micro\')]'];

    public static $optionValueField = "//input[@name='extra_value[]']";


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

    public function xPathChoice($typeChoice)
    {
        $path = ['xpath' => "//span[contains(text(), '" . $typeChoice . "')]"];
        return $path;
    }

}
