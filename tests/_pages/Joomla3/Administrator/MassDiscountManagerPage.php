<?php


class MassDiscountManagerPage extends AdminJ3Page
{
    /**
     * @var string
     */
	public static $URL = '/administrator/index.php?option=com_redshop&view=mass_discounts';

    /**
     * @var string
     */
	public static $URLNew = '/administrator/index.php?option=com_redshop&view=mass_discount&layout=edit';

    /**
     * @var string
     */
	public static $name = "#jform_name";

    /**
     * @var string
     */
	public static $valueAmount = "#jform_amount";

    /**
     * @var string
     */
	public static $pathNameProduct = "#s2id_autogen3";

    /**
     * @var string
     */
	public static $categoryField = "#s2id_jform_category_id";

    /**
     * @var string
     */
	public static $dayStart = "#jform_start_date";

    /**
     * @var string
     */
	public static $dayEnd = "#jform_end_date";

    /**
     * @var string
     */
	public static $checkFirstItems = "//input[@id='cb0']";

    /**
     * @var string
     */
	public static $MassDiscountFilter = "#filter_search";

    /**
     * @var string
     */
	public static $MassDicountResultRow = "//div[@class='table-responsive']/table/tbody/tr/td[3]/a";

    /**
     * @var string
     */
	public static $categoryForm = "//div[@id='s2id_jform_category_id']//ul/li";

    /**
     * @var string
     */
	public static $categoryFormInput = "//div[@id='s2id_jform_category_id']//ul/li//input";

    /**
     * @var string
     */
	public static $discountForm = "//div[@id='s2id_jform_discount_product']//ul/li";

    /**
     * @var string
     */
	public static $discountFormInput = "//div[@id='s2id_jform_discount_product']//ul/li//input";

    /**
     * @var string
     */
	public static $dateCalender = '#jform_start_date_img';

    /**
     * @var string
     */
	public static $startDateIcon = "#jform_start_date_img";

    /**
     * @var string
     */
	public static $endDateIcon = "#jform_end_date_img";

    /**
     * @var string
     */
	public static $getToday = "//td[contains(@class, 'selected')]";

    /**
     * @var string
     */
	public static $fieldStartDate = "#jform_start_date";

    /**
     * @var string
     */
	public static $fieldEndDate = "#jform_end_date";


	//page name

    /**
     * @var string
     */
	public static $pageEdit = "Mass Discount  Edit";

    /**
     * @var string
     */
	public static $pageNew = "product mass discount new";

	//Message

    /**
     * @var string
     */
	public static $saveOneSuccess = "Item saved.";

    /**
     * @var string
     */
	public static $fieldName = "Field required: Name";

    /**
     * @var string
     */
	public static $saveError = "Save failed with the following error";

    /**
     * @var string
     */
	public static $messageSuccess = "Message";

    /**
     * @var string
     */
	public static $messageError = "Error";
	//selector

    /**
     * @var string
     */
	public static $selectorSuccess = ".alert-success";

    /**
     * @var string
     */
	public static $selectorError = ".alert-danger";


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
	public static $cancelButton = "Cancel";

    /**
     * @var string
     */
	public static $closeButton = "Close";

    /**
     * @var string
     */
	public static $addButton = "Add";

    /**
     * Function to get Path for $type in Mass Discount
     *
     * @param $type
     *
     * @return string
     */
	public function returnXpath($type)
	{
		$path = "//span[contains(text(), '" . $type . "')]";
		return $path;
	}

}