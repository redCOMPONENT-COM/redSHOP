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
 * @since  1.4
 */
class UserManagerJoomla3Page
{
    public static $namePage = "User Management";

    public static $pageNotice = "User Manager Page";

    public static $URL = '/administrator/index.php?option=com_redshop&view=user';

    public static $URLJoomla = '/administrator/index.php?option=com_users&view=users';

    public static $userJoomla = ['xpath' => "//table[@id='userList']//tbody/tr[1]"];

    public static $filter = ['id' => 'filter'];

    public static $URLShipping = '/administrator/index.php?option=com_redshop&view=user_detail&task=edit&shipping=1&info_id=1&cid[]=0';

    public static $generalUserInformationTab = "//a[contains(text(), 'General User Information')]";

    public static $billingInformationTab = "//a[contains(text(), 'Billing Information')]";

    public static $shippingInformation = "//a[contains(text(),'Shipping Information')]";

    public static $userName = "//input[@id='username']";

    public static $newPassword = "//input[@id='password']";

    public static $confirmNewPassword = "//input[@id='password2']";

    public static $email = "//input[@id='email']";

    public static $groupRadioButton = "//form[@id='adminForm']/div[1]/dd[1]/div/fieldset/div/table/tbody/tr[6]/td[2]";

    public static $shopperGroupDropDown = "//div[@id='s2id_shopper_group_id']/a";

    public static $firstName = "//input[@id='firstname']";

    public static $lastName = "//input[@id='lastname']";

    public static $address = "//input[@id='address']";

    public static $phone = "//input[@id='phone']";

    public static $zipcode = "//input[@id='zipcode']";

    public static $city = "//input[@id='city']";

    public static $userSuccessMessage = 'User detail saved';

    public static $saveErrorEmailAlready = "Email already exists ";

    public static $saveErrorUserAlready = "Username already exists";

    public static $saveError = "Error";

    public static $emailInvalid = "Please enter a valid email address";

    public static $firstResultRow = "//div[@id='editcell']//table[1]//tbody/tr[1]";

    public static $selectFirst = "//input[@id='cb0']";

    public static $headPage = ['xpath' => "//h1"];


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

    //selector
    public static $selectorSuccess = '.alert-success';

    public static $selectorError = '.alert-danger';

    public static $selectorNamePage = '.page-title';


    //selector


    public static $xPathError = ['xpath' => "//div[@id='system-message']/div/div/p[1]"];

    public static $errorUserReady = ['xpath' => "//div[@id='system-message']/div/h4"];

    public static $selectorPageManagement = '.page-title';


    //page title

    public static $pageDetail = "First Name";

    public static $pageDetailSelector = '.title';

    //get link user

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
