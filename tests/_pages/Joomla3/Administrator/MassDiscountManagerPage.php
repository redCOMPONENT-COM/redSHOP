<?php


class MassDiscountManagerPage
{
    public static $URL = '/administrator/index.php?option=com_redshop&view=mass_discounts';

    public static $URLNew='/administrator/index.php?option=com_redshop&view=mass_discount&layout=edit';

    public static $name = "#jform_name";

    public static $valueAmount = "#jform_amount";

    public static $pathNameProduct = "#s2id_autogen3";

    public static $categoryField = "#s2id_jform_category_id";

    public static $dayStart = "#jform_start_date";

    public static $dayEnd = "#jform_end_date";

    public static $checkFirstItems = "//input[@id='cb0']";

    public static $MassDiscountFilter = "#filter_search";

    public static $MassDicountResultRow = "//div[@class='table-responsive']/table/tbody/tr/td[3]/a";

    public static $categoryForm = ['xpath' => "//div[@id='s2id_jform_category_id']//ul/li"];

    public static $categoryFormInput = ['xpath' => "//div[@id='s2id_jform_category_id']//ul/li//input"];

    public static $discountForm = ['xpath' => "//div[@id='s2id_jform_discount_product']//ul/li"];

    public static $discountFormInput = ['xpath' => "//div[@id='s2id_jform_discount_product']//ul/li//input"];

    public static $dateCalender='#jform_start_date_img';

    public static $startDateIcon=['id' => "jform_start_date_img"];

    public static $endDateIcon=['id' => "jform_end_date_img"];

    public static $getToday=['xpath' => "//td[contains(@class, 'selected')]"];


    //page name

    public static $pageEdit = "Mass Discount  Edit";

    public static $pageNew = "product mass discount new";

    //Message

    public static $saveOneSuccess = "Item saved.";

    public static $fieldName = "Field required: Name";

    public static $saveError = "Save failed with the following error";

    public static $messageSuccess = "Message";

    public static $messageError = "Error";
    //selector

    public static $selectorSuccess = ['class' => 'alert-success'];

    public static $selectorError = ['class' => 'alert-danger'];


    //button
    public static $newButton = "New";

    public static $saveButton = "Save";

    public static $unpublishButton = "Unpublish";

    public static $publishButton = "Publish";

    public static $saveCloseButton = "Save & Close";

    public static $deleteButton = "Delete";

    public static $editButton = "Edit";

    public static $cancelButton = "Cancel";

    public static $closeButton = "Close";

    public static $addButton = "Add";


    public function returnXpath($type)
    {
        $path = ['xpath' => "//span[contains(text(), '" . $type . "')]"];
        return $path;
    }

}