<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class UserManagerJoomla3Page
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  2.4
 */
class UserManagerJoomla3Page
{
    /**
     * @var string
     */
    public static $namePage = "User Management";

    /**
     * @var string
     */
    public static $pageNotice = "User Manager Page";

    /**
     * @var string
     */
    public static $URL = '/administrator/index.php?option=com_redshop&view=user';

    /**
     * @var string
     */
    public static $URLJoomla = '/administrator/index.php?option=com_users&view=users';

    /**
     * @var string
     */
    public static $userJoomla = "//table[@id='userList']//tbody/tr[1]";

    /**
     * @var string
     */
    public static $filter = "#filter";

    /**
     * @var string
     */
    public static $URLShipping = '/administrator/index.php?option=com_redshop&view=user_detail&task=edit&shipping=1&info_id=1&cid[]=0';

    /**
     * @var string
     */
    public static $generalUserInformationTab = "//a[contains(text(), 'General User Information')]";

    /**
     * @var string
     */
    public static $billingInformationTab = "//a[contains(text(), 'Billing Information')]";

    /**
     * @var string
     */
    public static $shippingInformation = "//a[contains(text(),'Shipping Information')]";

    /**
     * @var string
     */
    public static $userName = "//input[@id='username']";

    /**
     * @var string
     */
    public static $newPassword = "//input[@id='password']";

    /**
     * @var string
     */
    public static $confirmNewPassword = "//input[@id='password2']";

    /**
     * @var string
     */
    public static $email = "//input[@id='email']";

    /**
     * @var string
     */
    public static $groupRadioButton = "//form[@id='adminForm']/div[1]/dd[1]/div/fieldset/div/table/tbody/tr[6]/td[2]";

    /**
     * @var string
     */
    public static $shopperGroupDropDown = "//div[@id='s2id_shopper_group_id']/a";

    /**
     * @var string
     */
    public static $firstName = "//input[@id='firstname']";

    /**
     * @var string
     */
    public static $lastName = "//input[@id='lastname']";

    /**
     * @var string
     */
    public static $address = "//input[@id='address']";

    /**
     * @var string
     */
    public static $phone = "//input[@id='phone']";

    /**
     * @var string
     */
    public static $zipcode = "//input[@id='zipcode']";

    /**
     * @var string
     */
    public static $city = "//input[@id='city']";

    /**
     * @var string
     */
    public static $userSuccessMessage = 'User detail saved';

    /**
     * @var string
     */
    public static $saveErrorEmailAlready = "Email already exists ";

    /**
     * @var string
     */
    public static $saveErrorUserAlready = "Username already exists";

    /**
     * @var string
     */
    public static $saveError = "Error";

    /**
     * @var string
     */
    public static $emailInvalid = "Please enter a valid email address";

    /**
     * @var string
     */
    public static $firstResultRow = "//div[@id='editcell']//table[1]//tbody/tr[1]";

    /**
     * @var string
     */
    public static $selectFirst = "//input[@id='cb0']";

    /**
     * @var string
     */
    public static $headPage = "//h1";


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


    //selector

    /**
     * @var string
     */
    public static $xPathError = "//div[@id='system-message']/div/div/p[1]";

    /**
     * @var string
     */
    public static $errorUserReady = "//div[@id='system-message']/div/h4";

    /**
     * @var string
     */
    public static $selectorPageManagement = '.page-title';


    //page title

    /**
     * @var string
     */
    public static $pageDetail = "First Name";

    /**
     * @var string
     */
    public static $pageDetailSelector = '.title';

    //get link user

    /**
     * @var array
     */
    public static $linkUser = ['link' => 'ID'];


    /**
     * Function to get the path for Shopper Group
     *
     * @param   String $shopperGroup Group of Shopper
     *
     * @return string
     */
    public function shopperGroup($shopperGroup)
    {
        $path = "//div[@class='select2-result-label'][contains(text(), '" . $shopperGroup . "')]";

        return $path;
    }
}
