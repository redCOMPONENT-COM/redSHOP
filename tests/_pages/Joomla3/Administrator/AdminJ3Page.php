<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Abstract Class Core J3 Page
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  1.4
 */
abstract class AdminJ3Page
{
    /**
     * @var string
     */
    public static $installURL = '/administrator/index.php?option=com_installer';

    /**
     * @var array
     */
    public static $link = ['link' => 'Install from URL'];

    /*
     *
     */
    public static $urlID = ['id' => 'install_url'];

    /**
     * @var array
     */
    public static $installButton = ['id' => 'installbutton_url'];

    /**
     * @var array
     */
    public static $installDemoContent = ['id' => 'btn-demo-content'];

	/**
	 * @var array
	 */
	public static $buttonStatic = ['xpath' => "//body//div[2]//section//div//div//div//div//p[3]/a[3]"];

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
     */
	public static $messageDemoContentSuccess = 'Data Installed Successfully';

	/**
	 * @var array
	 */
	public static $checkAllXpath = ['xpath' => "//thead//input[@name='checkall-toggle' or @name='toggle']"];

	/**
	 * @var string
	 */
	public static $resultRow = "//tbody/tr[1]";

    /**
     * @var array
     */
	public static $checkInButtonList = ['xpath' => '//a[contains(concat(\' \', @class, \' \'), \'hasPopover\')]'];
//        ['xpath' => '//a[@class=\'btn btn-small btn-sm btn-checkin hasPopover\']'];


	/**
	 * @var array
	 */
	public static $searchField = ['id' => 'filter_search'];

	/**
	 * @var array
	 */
	public static $idFieldName = ['id' => 'jform_name'];

	/**
	 * @var string
	 */
	public static $namePath = "//div[@class='table-responsive']/table/tbody/tr/td[2]";

	/**
	 * @var array
	 */
	public static $listId = ['id' => 's2id_list_limit'];

	/**
	 * @var array
	 */
	public static $listSearchId = ['id' => 's2id_autogen1_search'];

	/**
	 * Unpublish button.
	 *
	 * @var string
	 */
	public static $statePath = ['xpath' => '//a[contains(@class, \'btn-state-item\')]'];

	/**
	 * @var array
	 */
	public static $stateCheckInPathBlock = ['xpath' => '//a[contains(@class, \'btn-checkin\')]'];

	/**
	 * @var array
	 */
	public static $stateCheckInPath = ['xpath' => '//a[contains(@class, \'btn-edit-item\')]'];

	/**
	 * @var array
	 */
	public static $headPage = ['xpath' => "//h1"];

	/**
	 * @var string
	 */
	public static $selectorSuccess = ".alert-success";

	/**
	 * @var string
	 */
	public static $selectorError = ".alert-error";

	/**
	 * @var string
	 */
	public static $selectorMissing = '.alert-danger';

	/**
	 * @var string
	 */
	public static $selectorMessage = '.alert-message';

	/**
	 * @var string
	 */
	public static $selectorPageTitle = '.page-title';

	/**
	 * @var string
	 */
	public static $selectorHeading = '.alert-heading';
	
	/**
	 * @var string
	 */
	public static $selectorToolBar = '.btn-toolbar';

    /**
     * @var array
     */
	public static $idInstallSuccess =  ['id' => 'system-message-container'];

	/**
	 * @var string
	 */
	public static $buttonSaveClose = "Save & Close";

	/**
	 * @var string
	 */
	public static $buttonSave = "Save";

	/**
	 * @var string
	 */
	public static $buttonSaveNew = "Save & New";

	/**
	 * @var string
	 */
	public static $buttonDelete = "Delete";

	/**
	 * @var string
	 */
	public static $buttonNew = "New";

	/**
	 * @var string
	 */
	public static $buttonCancel = "Cancel";

	/**
	 * @var string
	 */
	public static $buttonReview = 'Preview';

	/**
	 * @var string
	 */
	public static $buttonPublish = "Publish";

	/**
	 * @var string
	 */
	public static $buttonUnpublish = "Unpublish";

	/**
	 * @var string
	 */
	public static $buttonEdit = "Edit";

	/**
	 * @var string
	 */
	public static $buttonCopy = 'Copy';

	/**
	 * @var string
	 */
	public static $buttonReset = "Reset";

	/**
	 * @var string
	 */
	public static $buttonCheckIn = "Check-in";

	/**
	 * @var string
	 */
	public static $buttonClose = "Close";

	/**
	 * @var string
	 */
	public static $buttonSaveCopy = "Save & Copy";

// Include url of current page
// Fontend checkout first name

	/**
	 * @var string
	 */
	public static $URLLoginAdmin = '/administrator/index.php';

	/**
	 * @var string
	 */
	public static $cartPageUrL="index.php?option=com_redshop&view=cart";
	/**
	 * @var string
	 */
	public static $categoryDiv = "//div[@id='redshopcomponent']";
	/**
	 * @var string
	 */
	public static $productList = "//div[@id='redcatproducts']";
	/**
	 * @var string
	 */
	public static $addToCart = "//span[contains(text(), 'Add to cart')]";
	/**
	 * @var string
	 */
	public static $alertMessageDiv = "//div[@class='alert alert-success']";
	/**
	 * @var string
	 */
	public static $alertSuccessMessage = "Product has been added to your cart.";

	/**
	 * @var array
	 */
	public static $productFirst = ['xpath' => '//div[@class=\'product_name\']/a'];

	/**
	 * @var string
	 */
	public static $fieldName = "Field required: Name";

	/**
	 * @var string
	 */
	public static $addressEmail = "#private-email1";

	/**
	 * @var string
	 *
	 */
	public static $addressFirstName = "//input[@id='firstname']";

	/**
	 * @var string
	 *
	 */
	public static $addressLastName = "//input[@id='lastname']";

	/**
	 * @var string
	 */
	public static $addressAddress = "//input[@id='address']";

	/**
	 * @var string
	 */
	public static $addressPostalCode = "//input[@id='zipcode']";

	/**
	 * @var string
	 */
	public static $addressCity = "//input[@id='city']";

	/**
	 * @var string
	 */
	public static $addressCountry = "//select[@id='country_code']";

	/**
	 * @var string
	 */
	public static $addressState = "//select[@id='state_code']";

	/**
	 * @var string
	 */
	public static $addressPhone = "//input[@id='phone']";
	/**
	 * @var string
	 */
	public static $termAndConditions = "//input[@id='termscondition']";
	/**
	 * @var string
	 */
	public static $checkoutFinalStep = "//input[@id='checkout_final']";
	/**
	 * @var string
	 */
	public static $checkoutButton = "//input[@value='Checkout']";

	/**
	 * @var array
	 */
	public static $saveInfoUser = ['xpath'=> '//input[@name=\'submitbtn\']'];
	/**
	 * @var array
	 */
	public static $paymentPayPad = ['xpath' => "//input[@id='rs_payment_paypal1']"];

    /**
     * @var array
     */
	public static $paymentId = ['rs_payment_paypal1'];

	/**
	 * @var array
	 */
	public static $bankTransfer = ['xpath' => "//input[@id='rs_payment_banktransfer0']"];

    /**
     * @var string
     */
	public static $bankTransferId = 'rs_payment_banktransfer0';

    /**
     * @var string
     */
	public static $scriftClickTransfer = 'document.getElementById("rs_payment_banktransfer0").checked = true;';

	/**
	 * @var array
	 */
	public static $acceptTerms = ['xpath' => "//input[@id='termscondition']"];
	/**
	 * @var string
	 */

	public static $priceTotal = "//div[@class='form-group'][1]//div[1]";

	/**
	 * @var string
	 */
	public static $priceDiscount = "//div[@class='form-group'][2]//div[1]";

	/**
	 * @var array
	 */
	public static $priceVAT = ['xpath' => '//div[@class=\'form-group\'][3]//div[1]'];
	/**
	 * @var array
	 */
	public static $priceEnd = ['id' => 'spnTotal'];

	/**
	 * @var array
	 */
	public static $shippingRate = ['xpath' => '//span[@id=\'spnShippingrate\']'];

	/**
	 * @var string
	 */
	public static $messageErrorFieldRequired = 'Field required';

	/**
	 * Function get value
	 *
	 * @param   string  $value  Value string
	 *
	 * @return array
	 */
	public static function returnChoice($value)
	{
		return ['xpath' => "//span[contains(text(), '" . $value . "')]"];
	}

    /**
     * @param $value
     * @return array
     */
	public static function xPathATag($value)
    {
        return ['xpath' => "//a[contains(text(), '" . $value . "')]"];
    }

    /**
     * @param $id
     */
    public static function radioCheckID($id)
    {
	    return "document.getElementById('".$id."').checked = true;";
    }
}
