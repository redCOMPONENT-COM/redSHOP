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
    public static $URL = '/administrator/index.php?option=com_redshop&view=categories';

    public static $URLNew = 'administrator/index.php?option=com_redshop&view=category&layout=edit';

    public static $URLEdit = '/administrator/index.php?option=com_redshop&view=category&layout=edit&id=';


    //page name
    public static $pageManageName = "Category Management";


    public static $headPageName = ['xpath' => "//h1"];

    public static $categoryName = "#jform_name";

//	public static $categoryPage="#"

    public static $categoryFilter = ['id' => 'filter_search'];

    public static $categoryTemplateDropDown = "//div[@id='filter_category_template']/a";

    public static $categorySearch = "//button[@onclick=\"document.adminForm.submit();\"]";

    public static $categoryResultRow = "//div[@class='table-responsive']/table/tbody/tr/td[5]";

    public static $categoryId = "//div[@class='table-responsive']/table/tbody/tr/td[9]";

    public static $categoryCheckInRow = "//div[@class='table-responsive']/table/tbody/tr/td[3]";

    public static $categoryStatePath = "//tbody/tr/td[7]/a";

    public static $checkAll = "//input[@id='cb0']";

    public static $checkAllCategory = "//input[@onclick='Joomla.checkAll(this)']";

    public static $categoryTemplateIDDropDown = "//div[@id='s2id_filter_category_template']/a";

    public static $categoryNoPage = "#jform_products_per_page";

    public static $parentCategory = "//div[@id='s2id_jform_parent_id']/a";
    public static $choiceCategoryParent = "//div[@id='select2-result-label-13']/a";

    public static $accessories = "//div[@id='s2id_category_accessory_search']/a";

    public static $accessoriesFill = "#s2id_autogen1";

    public static $categoryManagement = "/html/body/div/div/div/section[1]/div[1]/h1";

    public static $tabAccessory = ['link' => "Accessories"];

    public static $accessorySearch = ['xpath' => '//div[@id="s2id_category_accessory_search"]//a'];

    public static $searchFirst = ['id' => "s2id_autogen1_search"];

    public static $getAccessory = ['xpath' => "//h3[text()='Accessories']"];


    //templatep

    public static $template = "//div/div/div[@id='s2id_jform_more_template']/ul";

    public static $choiceTemplate = '//ul[@class="select2-results"]/li[2]/div[@class="select2-result-label"]';

    //selector

    public static $selectorSuccess = '.alert-success';

    public static $selectorError = '.alert-danger';

    public static $selectorNamePage = '.page-title';


    //message
    public static $messageSaveSuccess = "Item saved.";

    public static $messageError = "Error";

    public static $messageSuccess = "Message";

    public static $messageDeleteSuccess = "1 item successfully deleted";

    //button

    public static $newButton = "New";

    public static $saveButton = "Save";

    public static $unpublishButton = "Unpublish";

    public static $publishButton = "Publish";

    public static $saveCloseButton = "Save & Close";

    public static $deleteButton = "Delete";

    public static $editButton = "Edit";

    public static $saveNewButton = "Save & New";

    public static $cancelButton = "Cancel";

    public static $checkInButton = "Check-in";



    /**
     * Function to get the Path for Template ID
     *
     * @param   String $templateIDName Name of the Template
     *
     * @return string
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
     */
    public function categoryTemplate($templateName)
    {
        $path = "//div[@id='filter_category_template']/div/ul/li[contains(text(), '" . $templateName . "')]";

        return $path;
    }

    public function xPathAccessory($accessoryName)
    {
        $path = ['xpath' => "//span[contains(text(), '" . $accessoryName . "')]"];
        return $path;
    }
}
