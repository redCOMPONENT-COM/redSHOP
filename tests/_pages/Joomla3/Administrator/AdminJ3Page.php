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
	 */
	public static $installURL = '/administrator/index.php?option=com_installer';

	/**
	 * @var string
	 */
	public static $moduleURL = '/administrator/index.php?option=com_modules';

	/**
	 * @var array
	 */
	public static $link = ['link' => 'Install from URL'];

	/**
	 * @var string
	 */
	public static $urlID = "#install_url";

	/**
	 * @var array
	 */
	public static $installButton = "#installbutton_url";

	/**
	 * @var array
	 */
	public static $installDemoContent = "#btn-demo-content";

	/**
	 * @var array
	 */
	public static $buttonStatic = "//body//div[2]//section//div//div//div//div//p[3]/a[3]";

	/**
	 * @var string
	 */
	public static $namePage = "";

	/**
	 * @var string
	 */
	public static $url = 'index.php?option=com_redshop';

	/**
	 * @var string
	 */
	public static $messageHead = "Message";

	/**
	 * @var string
	 */
	public static $messageError = "Error";

	/**
	 * @var string
	 */
	public static $messageItemSaveSuccess = "Item saved.";

	/**
	 * @var string
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
	 */
	public static $messageUnpublishSuccess = 'successfully unpublished';

	/**
	 * @var string
	 */
	public static $messagePublishSuccess = 'successfully published';

	/**
	 * @var string
	 */
	public static $messageCheckInSuccess = 'successfully checked in';

	/**
	 * @var string
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
	public static $checkAllXpath = "//thead//input[@name='checkall-toggle' or @name='toggle']";

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

// Include url of current page
// Fontend checkout first name

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
	public static $checkoutFinalStep = "//input[@id='checkout_final']";

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
	public static $paymentPayPad = "//input[@id='rs_payment_paypal1']";

	/**
	 * @var array
	 * @since 2.1.2
	 */
	public static $paymentId = ['rs_payment_paypal'];

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $bankTransfer = "//input[@id='rs_payment_banktransfer0']";

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
	public static $shippingRate = "//span[@id='spnShippingrate']";

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
	public static $buttonSelect = "Select";

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
	 * @return array
	 * @since 2.1.2
	 */
	public static function returnChoice($value)
	{
		return ['xpath' => "//span[contains(text(), '" . $value . "')]"];
	}

	/**
	 * Function get value
	 * @param String $value Value string
	 *
	 * @return array
	 * @since 2.1.2
	 */
	public static function xPathATag($value)
	{
		return ['xpath' => "//a[contains(text(), '" . $value . "')]"];
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
}
