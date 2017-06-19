<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class CouponManagerJ3Page
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  1.4
 */
class CouponManagerJ3Page
{
    public static $URL = '/administrator/index.php?option=com_redshop&view=coupon';

    public static $couponCode = "//input[@id='coupon_code']";

    public static $couponValue = "//input[@id='coupon_value']";

    public static $couponLeft = "//input[@id='coupon_left']";

    public static $startDate = "#start_date";

    public static $endDate = "#end_date";

    public static $couponValueInDropDown = "//div[@id='s2id_percent_or_total']/a";

    public static $couponTypeDropdown="//div[@id='s2id_coupon_type']/a";

    public static $couponTypeDropDown = "//div[@id='coupon_type_chzn']/a";

    public static $userDropDown="//div[@id='s2id_userid']/a";

    public static $selectFirst = "//input[@id='cb0']";

    public static $choiAllCoupons = "//input[@onclick='Joomla.checkAll(this)']";


    public static $couponResultRow = "//table[@id='articleList']/tbody/tr[2]";


    // //table[@class='test-redshop-table']//tr[@class='test-redshop-table'][1]//td[@class='test-redshop-coupon-checkall']
    public static $firstResultRow = ['class' => "test-redshop-table-row"];


    public static $couponState = "//div[@id='editcell']/div[2]/table/tbody/tr/td[9]/a";

    /**
     * Function to get path for CouponValueIn
     *
     * @param   String $couponValue Value of the Coupon
     *
     * @return string
     */
    public function couponValueIn($couponValue)
    {
        $path = "//div[@id='select2-drop']//ul//li//div[contains(text(), '" . $couponValue . "')]";

        return $path;
    }

    /**
     * Function to get path for CouponType
     *
     * @param   String $couponType Value of the Coupon
     *
     * @return string
     */
    public function couponType($couponType)
    {
        $path = "//div[@id='select2-drop']//ul//li//div[contains(text(), '" . $couponType . "')]";
        return $path;
    }

    public function userValue($nameUser){
        $path = "//div[@id='select2-drop']//ul//li//div[contains(text(), '" . $nameUser . "')]";
        return $path;
    }

}
