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
    public static $URL = '/administrator/index.php?option=com_redshop&view=user';

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

    public static $saveErrorEmailAlready = "Email already exists";

    public static $saveErrorUserAlready = "Username already exists";

    public static $firstResultRow = "//div[@id='editcell']//table[1]//tbody/tr[1]";

    public static $selectFirst = "//input[@id='cb0']";

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
