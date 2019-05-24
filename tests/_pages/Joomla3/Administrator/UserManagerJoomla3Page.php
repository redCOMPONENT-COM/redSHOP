<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class UserManagerJoomla3Page
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since 2.1.2
 */
class UserManagerJoomla3Page
{
    /**
     * @var string
     * @since 2.1.2
     */
    public static $namePage = "User Management";

    /**
     * @var string
     * @since 2.1.2
     */
    public static $pageNotice = "User Manager Page";

    /**
     * @var string
     * @since 2.1.2
     */
    public static $URL = '/administrator/index.php?option=com_redshop&view=user';

    /**
     * @var string
     * @since 2.1.2
     */
    public static $URLJoomla = '/administrator/index.php?option=com_users&view=users';

    /**
     * @var string
     * @since 2.1.2
     */
    public static $userJoomla = "//table[@id='userList']//tbody/tr[1]";

    /**
     * @var string
     * @since 2.1.2
     */
    public static $filter = "#filter";

    /**
     * @var string
     * @since 2.1.2
     */
    public static $URLShipping = '/administrator/index.php?option=com_redshop&view=user_detail&task=edit&shipping=1&info_id=1&cid[]=0';

    /**
     * @var string
     * @since 2.1.2
     */
    public static $generalUserInformationTab = "//a[contains(text(), 'General User Information')]";

    /**
     * @var string
     * @since 2.1.2
     */
    public static $billingInformationTab = "//a[contains(text(), 'Billing Information')]";

    /**
     * @var string
     * @since 2.1.2
     */
    public static $shippingInformation = "//a[contains(text(),'Shipping Information')]";

    /**
     * @var string
     * @since 2.1.2
     */
    public static $userName = "//input[@id='username']";

    /**
     * @var string
     * @since 2.1.2
     */
    public static $newPassword = "//input[@id='password']";

    /**
     * @var string
     * @since 2.1.2
     */
    public static $confirmNewPassword = "//input[@id='password2']";

    /**
     * @var string
     * @since 2.1.2
     */
    public static $email = "//input[@id='email']";

    /**
     * @var string
     * @since 2.1.2
     */
    public static $groupRadioButton = "//form[@id='adminForm']/div[1]/dd[1]/div/fieldset/div/table/tbody/tr[6]/td[2]";

    /**
     * @var string
     * @since 2.1.2
     */
    public static $shopperGroupDropDown = "//div[@id='s2id_shopper_group_id']/a";

    /**
     * @var string
     * @since 2.1.2
     */
    public static $firstName = "//input[@id='firstname']";

    /**
     * @var string
     * @since 2.1.2
     */
    public static $lastName = "//input[@id='lastname']";

    /**
     * @var string
     * @since 2.1.2
     */
    public static $address = "//input[@id='address']";

    /**
     * @var string
     * @since 2.1.2
     */
    public static $phone = "//input[@id='phone']";

    /**
     * @var string
     * @since 2.1.2
     */
    public static $zipcode = "//input[@id='zipcode']";

    /**
     * @var string
     * @since 2.1.2
     */
    public static $city = "//input[@id='city']";

    /**
     * @var string
     * @since 2.1.2
     */
    public static $userSuccessMessage = 'User detail saved';

    /**
     * @var string
     * @since 2.1.2
     */
    public static $saveErrorEmailAlready = "Email already exists ";

    /**
     * @var string
     * @since 2.1.2
     */
    public static $saveErrorUserAlready = "Username already exists";

    /**
     * @var string
     * @since 2.1.2
     */
    public static $saveError = "Error";

    /**
     * @var string
     * @since 2.1.2
     */
    public static $emailInvalid = "The email address you entered is invalid. Please enter another email address.";

    /**
     * @var string
     * @since 2.1.2
     */
    public static $firstResultRow = "//div[@id='editcell']//table[1]//tbody/tr[1]";

    /**
     * @var string
     * @since 2.1.2
     */
    public static $selectFirst = "//input[@id='cb0']";

    /**
     * @var string
     * @since 2.1.2
     */
    public static $headPage = "//h1";


    //button

    /**
     * @var string
     * @since 2.1.2
     */
    public static $newButton = "New";

    /**
     * @var string
     * @since 2.1.2
     */
    public static $resetButton = ".reset";

    /**
     * @var string
     * @since 2.1.2
     */
    public static $saveButton = "Save";

    /**
     * @var string
     * @since 2.1.2
     */
    public static $unpublishButton = "Unpublish";

    /**
     * @var string
     * @since 2.1.2
     */
    public static $publishButton = "Publish";

    /**
     * @var string
     * @since 2.1.2
     */
    public static $saveCloseButton = "Save & Close";

    /**
     * @var string
     * @since 2.1.2
     */
    public static $deleteButton = "Delete";

    /**
     * @var string
     * @since 2.1.2
     */
    public static $editButton = "Edit";

    /**
     * @var string
     * @since 2.1.2
     */
    public static $cancelButton = "Cancel";

    /**
     * @var string
     * @since 2.1.2
     */
    public static $closeButton = "Close";

    /**
     * @var string
     * @since 2.1.2
     */
    public static $addButton = "Add";

    //selector

    /**
     * @var string
     * @since 2.1.2
     */
    public static $selectorSuccess = '.alert-success';

    /**
     * @var string
     * @since 2.1.2
     */
    public static $selectorError = '.alert-danger';

    /**
     * @var string
     * @since 2.1.2
     */
    public static $selectorNamePage = '.page-title';


    //selector

    /**
     * @var string
     * @since 2.1.2
     */
    public static $xPathError = "//div[@id='system-message']/div/div/p[1]";

    /**
     * @var string
     * @since 2.1.2
     */
    public static $errorUserReady = "//div[@id='system-message']/div/h4";

    /**
     * @var string
     * @since 2.1.2
     */
    public static $selectorPageManagement = '.page-title';


    //page title

    /**
     * @var string
     * @since 2.1.2
     */
    public static $pageDetail = "First Name";

    /**
     * @var string
     * @since 2.1.2
     */
    public static $pageDetailSelector = '.title';

    //get link user

    /**
     * @var array
     * @since 2.1.2
     */
    public static $linkUser = ['link' => 'ID'];

    /**
     * @var string
     * @since 2.1.2
     */
    public static $chooseEmail = '#sendEmail1';

    /**
     * @var string
     * @since 2.1.2
     */
    public static $btnPlaceOder = "//button[@class='btn btn-small button-redshop_order32']";

    /**
     * Function to get the path for Shopper Group
     *
     * @param   String $shopperGroup Group of Shopper
     *
     * @return string
     * @since 2.1.2
     */
    public function shopperGroup($shopperGroup)
    {
        $path = "//div[@class='select2-result-label'][contains(text(), '" . $shopperGroup . "')]";

        return $path;
    }
}
