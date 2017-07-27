<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class QuestionManagerJoomla3Page
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  1.4
 */
class QuestionManagerJoomla3Page
{
    public static $URL = '/administrator/index.php?option=com_redshop&view=questions';

    public static $userPhone = "//input[@id='jform_telephone']";

    public static $productNameDropDown = ['id' => "s2id_jform_product_id"];

    public static $productSearch = ['id' => "s2id_autogen1_search"];

    public static $address = ['id' => 'jform_address'];

    public static $productNameSearchField = "//input[@id='s2id_autogen1_search']";

    public static $toggleQuestionDescriptionEditor = "//div[@class='editor']//textarea[@id='question']/../div//div//a[@title='Toggle editor']";

    public static $question = "//textarea[@id='question']";

    public static $questionSuccessMessage = 'Question Detail Saved';

    public static $firstResultRow = "//div[@id='editcell']/table/tbody/tr[1]";

    public static $selectFirst = "//input[@id='cb0']";

    public static $questionStatePath = "//div[@id='editcell']//table//tbody/tr[1]/td[9]/a";

    /**
     * Function to get the path for Product Name
     *
     * @param   String $name Name of the Product for which question is to be posted
     *
     * @return string
     */

    public function productName($name)
    {
        $path = ['xpath' => "//span[contains(text(), '" . $name . "')]"];
        return $path;
    }

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

    public static $closeButton = "Close";
}
