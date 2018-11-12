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
 * @since  2.4
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 */
class CategoryManagerJ3Page extends AdminJ3Page
{
    // Include url of current page

    /**
     * @var string
     */
    public static $URL = '/administrator/index.php?option=com_redshop&view=categories';

    /**
     * @var string
     */
    public static $URLEdit = '/administrator/index.php?option=com_redshop&view=category&layout=edit&id=';

    //page name

    /**
     * @var string
     */
    public static $pageManageName = "Category Management";

    /**
     * @var string
     */
    public static $headPageName = "//h1";

    /**
     * @var string
     */
    public static $categoryName = "#jform_name";

    /**
     * @var string
     */
    public static $categoryFilter = "#filter_search";

    /**
     * @var string
     */
    public static $categoryTemplateDropDown = "//div[@id='filter_category_template']/a";

    /**
     * @var string
     */
    public static $categoryId = "//tbody/tr/td[9]";

    /**
     * @var string
     */
    public static $categoryStatePath = "//tbody/tr/td[7]/a";

    /**
     * @var string
     */
    public static $checkAll = "//input[@id='cb0']";

    /**
     * @var string
     */
    public static $categoryTemplateIDDropDown = "//div[@id='s2id_filter_category_template']/a";

    /**
     * @var string
     */
    public static $categoryNoPage = "#jform_products_per_page";

    /**
     * @var string
     */
    public static $parentCategory = "//div[@id='s2id_jform_parent_id']/a";

    /**
     * @var string
     */
    public static $choiceCategoryParent = "//div[@id='select2-result-label-13']/a";

    /**
     * @var string
     */
    public static $accessories = "//div[@id='s2id_category_accessory_search']/a";

    /**
     * @var string
     */
    public static $accessoriesFill = "#s2id_autogen1";

    /**
     * @var array
     */
    public static $tabAccessory = ['link' => "Accessories"];

    /**
     * @var string
     */
    public static $accessorySearch = "//div[@id='s2id_category_accessory_search']//a";

    /**
     * @var string
     */
    public static $searchFirst = "#s2id_autogen1_search";

    /**
     * @var string
     */
    public static $getAccessory = "//h3[text()='Accessories']";

    //templatep

    /**
     * @var string
     */
    public static $template = "//div/div/div[@id='s2id_jform_more_template']/ul";

    /**
     * @var string
     */
    public static $choiceTemplate = '//ul[@class="select2-results"]/li[2]/div[@class="select2-result-label"]';

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
     * @var string
     */
    public static $selectorHeading = '.alert-heading';


    //message

    /**
     * @var string
     */
    public static $messageSaveSuccess = "Item saved.";

    /**
     * @var string
     */
    public static $messageError = "Error";

    /**
     * @var string
     */
    public static $messageSuccess = "Message";

    /**
     * @var string
     */
    public static $messageDeleteSuccess = "1 item successfully deleted";

    /**
     * @var string
     */
    public static $messageErrorDeleteCategoryHasChildCategoriesOrProducts = "kindly remove those";

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

    /**
     * Function to get the Path for $accessoryName
     *
     * @param  $accessoryName
     *
     * @return array
     */
    public function xPathAccessory($accessoryName)
    {
        $path = ['xpath' => "//span[contains(text(), '" . $accessoryName . "')]"];
        return $path;
    }
}
