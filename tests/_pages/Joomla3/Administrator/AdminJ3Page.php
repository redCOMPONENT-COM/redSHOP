<?php
/**
 * @package     redSHOP
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Abstract Class Core J3 Page
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  2.1.2
 */
abstract class AdminJ3Page
{
	/**
	 * @var string
	 * @since  2.1.2
	 */
	public static $installURL = '/administrator/index.php?option=com_installer';

	/**
	 * @var string
	 * @since  2.1.2
	 */
	public static $moduleURL = '/administrator/index.php?option=com_modules';

	/**
	 * @var string
	 * @since 2.1.6
	 */
	public static $configUrl = "administrator/index.php?option=com_config";

	/**
	 * @var string
	 * @since 2.1.6
	 */
	public static $siteTab = "//ul[@class='nav nav-tabs']//a[contains(text(),'Site')]";

	/**
	 * @var string
	 * @since 2.1.6
	 */
	public static $seoNO = "//fieldset[@id='jform_sef']//label[contains(text(),'No')]";

	/**
	 * @var string
	 * @since 2.1.6
	 */
	public static $saveCloseButton = "//button[@onclick=\"Joomla.submitbutton('config.save.application.save');\"]";

	/**
	 * @var string
	 * @since 2.1.6
	 */
	public static $messageSaveConfigSuccess = "Configuration saved.";

	/**
	 * @var array
	 * @since  2.1.2
	 */
	public static $link = ['link' => 'Install from URL'];

	/**
	 * @var string
	 * @since  2.1.2
	 */
	public static $urlID = "#install_url";

	/**
	 * @var array
	 * @since  2.1.2
	 */
	public static $installButton = "#installbutton_url";

	/**
	 * @var array
	 * @since  2.1.2
	 */
	public static $installDemoContent = "#btn-demo-content";

	/**
	 * @var string
	 * @since  2.1.2
	 */
	public static $namePage = "";

	/**
	 * @var string
	 * @since  2.1.2
	 */
	public static $url = 'index.php?option=com_redshop';

	/**
	 * @var string
	 * @since  2.1.2
	 */
	public static $messageHead = "Message";

	/**
	 * @var string
	 * @since  2.1.2
	 */
	public static $messageError = "Error";

	/**
	 * @var string
	 * @since  2.1.2
	 */
	public static $messageItemSaveSuccess = "Item saved.";

	/**
	 * @var string
	 * @since  2.1.2
	 */
	public static $messageDeleteSuccess = "successfully deleted";

	/**
	 * @var string
	 * @since 2.1.5
	 */
	public static $messageNoItemOnTable = "No Matching Results";

	/**
	 * @var string
	 * @since 2.1.5
	 */
	public static $selectorAlert = ".alert-no-items";

	/**
	 * @var string
	 * @since  2.1.2
	 */
	public static $messageUnpublishSuccess = 'successfully unpublished';

	/**
	 * @var string
	 * @since  2.1.2
	 */
	public static $messagePublishSuccess = 'successfully published';

	/**
	 * @var string
	 * @since  2.1.2
	 */
	public static $messageCheckInSuccess = 'successfully checked in';

	/**
	 * @var string
	 * @since  2.1.2
	 */
	public static $messageInstallSuccess = 'installed successfully';

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $messageInstallModuleSuccess = 'Installation of the module was successful.';

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $messageInstallPluginSuccess = 'Installation of the plugin was successful.';

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $messageDemoContentSuccess = 'Data Installed Successfully';

	/**
	 * @var array
	 * @since 2.1.2
	 */
	public static $checkAllXpath = "//input[@name='checkall-toggle' or @name='toggle']";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $resultRow = "//tbody/tr[1]";

	/**
	 * @var array
	 * @since 2.1.2
	 */
	public static $checkInButtonList = "//a[contains(concat(\' \', @class, \' \'), \'hasPopover\')]";

	/**
	 * @var array
	 * @since 2.1.2
	 */
	public static $resetButton = "//input[@id='reset']";

	/**
	 * @var array
	 * @since 2.1.2
	 */
	public static $searchField = "#filter_search";

	/**
	 * @var array
	 * @since 2.1.2
	 */
	public static $idFieldName = "#jform_name";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $namePath = "//div[@class='table-responsive']/table/tbody/tr/td[2]";

	/**
	 * @var array
	 * @since 2.1.2
	 */
	public static $listId = "#s2id_list_limit";

	/**
	 * @var array
	 * @since 2.1.2
	 */
	public static $listSearchId = "#s2id_autogen1_search";

	/**
	 * Unpublish button.
	 *
	 * @var string
	 * @since 2.1.2
	 */
	public static $statePath = "//a[contains(@class, 'btn-state-item')]";

	/**
	 * @var array
	 * @since 2.1.2
	 */
	public static $stateCheckInPathBlock = "//a[contains(@class, 'btn-checkin')]";

	/**
	 * @var array
	 * @since 2.1.2
	 */
	public static $stateCheckInPath = "//a[contains(@class, 'btn-edit-item')]";

	/**
	 * @var array
	 * @since 2.1.2
	 */
	public static $headPage = "//h1";

	/**
	 * @var array
	 * @since 2.1.3
	 */
	public static $h3 = "//h3";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $selectorSuccess = ".alert-success";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $selectorError = ".alert-error";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $selectorMissing = '.alert-danger';

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $selectorMessage = '.alert-message';

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $selectorPageTitle = '.page-title';

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $selectorHeading = '.alert-heading';

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $selectorToolBar = '.btn-toolbar';

	/**
	 * @var array
	 * @since 2.1.2
	 */
	public static $idInstallSuccess =  "#system-message-container";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $buttonSaveClose = "Save & Close";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $buttonSave = "Save";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $buttonSaveNew = "Save & New";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $buttonDelete = "Delete";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $buttonNew = "New";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $buttonCancel = "Cancel";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $buttonReview = 'Preview';

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $buttonPublish = "Publish";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $buttonUnpublish = "Unpublish";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $buttonEdit = "Edit";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $buttonCopy = 'Copy';

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $buttonReset = "Reset";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $buttonCheckIn = "Check-in";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $buttonClose = "Close";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $buttonSaveCopy = "Save & Copy";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $URLLoginAdmin = '/administrator/index.php';

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $cartPageUrL="index.php?option=com_redshop&view=cart";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $categoryDiv = "//div[@id='redshopcomponent']";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $productList = "//div[@id='redcatproducts']";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $addToCart = "//span[contains(text(), 'Add to cart')]";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $alertMessageDiv = "//div[@class='alert alert-success']";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $alertSuccessMessage = "Product has been added to your cart.";

	/**
	 * @var array
	 * @since 2.1.2
	 */
	public static $productFirst = "//div[@class='product_name']/a";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $fieldName = "Field required: Name";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $addressEmail = "#private-email1";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $addressFirstName = "//input[@id='firstname']";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $addressLastName = "//input[@id='lastname']";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $addressAddress = "//input[@id='address']";

	/**
	 * @var string
	 */
	public static $addressPostalCode = "//input[@id='zipcode']";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $addressCity = "//input[@id='city']";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $addressCountry = "//select[@id='country_code']";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $addressState = "//select[@id='state_code']";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $addressPhone = "//input[@id='phone']";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $checkoutFinalStep = "#checkout_final";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $checkoutButton = "//input[@value='Checkout']";

	/**
	 * @var array
	 * @since 2.1.2
	 */
	public static $saveInfoUser = "//input[@value='Save']";

	/**
	 * @var array
	 * @since 2.1.2
	 */
	public static $paymentPayPad = "//input[@value='rs_payment_paypal']";

	/**
	 * @var array
	 * @since 2.1.2
	 */
	public static $paymentId = ['rs_payment_paypal'];

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $bankTransfer = "//input[@value='rs_payment_banktransfer']";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $bankTransferId = "rs_payment_banktransfer0";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $jqueryBankTransfer = "jQuery('#rs_payment_banktransfer0').click()";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $scriftClickTransfer = 'document.getElementById("rs_payment_banktransfer0").checked = true;';

	/**
	 * @var array
	 * @since 2.1.2
	 */
	public static $acceptTerms = "(//label[@class=\"checkbox\"])[2]";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $priceTotal = "//div[@class='form-group'][1]//div[1]";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $priceDiscount = "//div[@class='form-group'][2]//div[1]";

	/**
	 * @var array
	 * @since 2.1.2
	 */
	public static $priceVAT = "//div[@class='form-group'][3]//div[1]";

	/**
	 * @var array
	 * @since 2.1.2
	 */
	public static $priceEnd = "#spnTotal";

	/**
	 * @var array
	 * @since 2.1.2
	 */
	public static $shippingRate = "#spnShippingrate";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $messageErrorFieldRequired = 'Field required';

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $menuItemURL = '/administrator/index.php?option=com_menus&view=menus';

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $menuTitle   = 'Menus';

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $menuItemsTitle   = 'Menus: Items';

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $menuNewItemTitle   = 'Menus: New Item';

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $menuItemType   = 'Menu Item Type';

	/**
	 * Menu item title
	 * @var string
	 * @since 2.1.2
	 */
	public static $menItemTitle = "#jform_title";

	/**
	 * @var   string
	 * @since 2.1.2
	 */
	public static $buttonSelect = "//button[@data-target='#menuTypeModal']";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $messageMenuItemSuccess = 'Menu item saved';

	/**
	 * Menu Type Modal
	 * @var string
	 * @since 2.1.2
	 */
	public static $menuTypeModal = "#menuTypeModal";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $h1 =  array('css' => 'h1');

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $labelLanguage = "Language";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $addAccessory = '//input[@totalattributs="0"]';

	/**
	 * @var string
	 * @since 3.0.2
	 */
	public static $select2Results = "//ul[@class='select2-results']/li[1]/div";

	/**
	 * @var string
	 * @since 3.0.2
	 */
	public static $select2Drop = "//div[@id='select2-drop']//ul[@class='select2-results']/li[1]/div";

	/**
	 * @var string
	 * @since 3.0.2
	 */
	public static $tabOption = "//a[contains(text(),'Options')]";

	/**
	 * @var string
	 * @since 3.0.2
	 */
	public static $labelSelectManufacturer = "Select Manufacturer";

	/**
	 * @var string
	 * @since 3.0.3
	 */
	public static $messageDeleteInPopup = "Are you sure want to delete these items?";

	/**
	 * @param $menuCategory
	 * @return array
	 * @since 2.1.2
	 */
	public static function getMenuCategory($menuCategory)
	{
		$menuCate = ["link" => $menuCategory];

		return $menuCate;
	}

	/**
	 * Function get value
	 *
	 * @param   string  $value  Value string
	 *
	 * @return string
	 * @since 2.1.2
	 */
	public static function returnChoice($value)
	{
		return "//span[contains(text(), '" . $value . "')]";
	}

	/**
	 * Function get value
	 * @param String $value Value string
	 *
	 * @return string
	 * @since 2.1.2
	 */
	public static function xPathATag($value)
	{
		return "//a[contains(text(), '" . $value . "')]";
	}

	/**
	 * Function get ID
	 *
	 * @param String $id
	 *
	 * @return string
	 * @since 2.1.2
	 */
	public static function radioCheckID($id)
	{
		return "document.getElementById('".$id."').checked = true;";
	}

	/**
	 * @param $menuItem
	 * @return string
	 * @since 2.1.2
	 */
	public static function returnMenuItem($menuItem)
	{
		$path = "//a[contains(text()[normalize-space()], '$menuItem')]";
		return $path;
	}

	/**
	 * @return string
	 * @since 3.0.2
	 */
	public static function jQueryIframeMenuType()
	{
		return 'jQuery(".iframe").attr("name", "Menu Item Type")';
	}

	/**
	 * @param $elementId
	 * @param $text
	 * @return string
	 * @since 3.0.2
	 */
	public static function jQuerySearch($elementId, $text)
	{
		return 'jQuery("#' . $elementId . '").select2("search", "' . $text . '")';
	}
}
