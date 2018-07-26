<?php


class MassDiscountManagerPage extends AdminJ3Page
{
	public static $URL = '/administrator/index.php?option=com_redshop&view=mass_discounts';

	public static $URLNew = '/administrator/index.php?option=com_redshop&view=mass_discount&layout=edit';

	public static $name = "#jform_name";

	public static $valueAmount = "#jform_amount";

	public static $pathNameProduct = "#s2id_autogen3";

	public static $categoryField = "#s2id_jform_category_id";

	public static $dayStart = "#jform_start_date";

	public static $dayEnd = "#jform_end_date";

	public static $checkFirstItems = "//input[@id='cb0']";

	public static $MassDiscountFilter = "#filter_search";

	public static $MassDicountResultRow = "//div[@class='table-responsive']/table/tbody/tr/td[3]/a";

	public static $categoryForm = "//div[@id='s2id_jform_category_id']//ul/li";

	public static $categoryFormInput = "//div[@id='s2id_jform_category_id']//ul/li//input";

	public static $discountForm = "//div[@id='s2id_jform_discount_product']//ul/li";

	public static $discountFormInput = "//div[@id='s2id_jform_discount_product']//ul/li//input";

	public static $dateCalender = '#jform_start_date_img';

	public static $startDateIcon = "#jform_start_date_img";

	public static $endDateIcon = "#jform_end_date_img";

	public static $getToday = "//td[contains(@class, 'selected')]";

	public static $fieldStartDate = "#jform_start_date";

	public static $fieldEndDate = "#jform_end_date";


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

	public static $selectorSuccess = ".alert-success";

	public static $selectorError = ".alert-danger";


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
		$path = "//span[contains(text(), '" . $type . "')]";
		return $path;
	}

}