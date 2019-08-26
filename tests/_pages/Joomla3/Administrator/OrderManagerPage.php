<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class OrderManagerPage
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  2.4
 */
class OrderManagerPage extends AdminJ3Page
{
    /**
     * @var string
     */
    public static $URL = '/administrator/index.php?option=com_redshop&view=order';

    /**
     * @var string
     * @since 2.1.2
     */
    public static $titlePage = ".page-title";

    /**
     * @var string
     */
    public static $userId = "//div[@id='s2id_user_id']/a";

    /**
     * @var string
     */
    public static $userSearch = "//input[@id='s2id_autogen1_search']";

    /**
     * @var string
     */
    public static $address = "#address";

    /**
     * @var string
     */
    public static $zipcode = "#zipcode";

    /**
     * @var string
     */
    public static $fistName = "//input[@id='firstname']";

    /**
     * @var string
     */
    public static $city = "#city";

    /**
     * @var string
     */
    public static $phone = "#phone";

    /**
     * @var string
     */
    public static $close = "#toolbar-cancel";

    /**
     * @var string
     */
    public static $filter = "#filter";

    /**
     * @var string
     */
    public static $applyUser = "#toolbar-apply";

    /**
     * @var string
     */
    public static $productId = "#s2id_product1";

    /**
     * @var string
     */
    public static $productsSearch = "#s2id_autogen2_search";

    /**
     * @var string
     */
    public static $quanlityFirst = "#quantityproduct1";

    /**
     * @var string
     */
    public static $quantityp1 = "#quantity";

    /**
     * @var string
     */
    public static $nameProductSuccess = "#order_product_detail_3";

    /**
     * @var string
     */
    public static $statusOrder = "#s2id_status";

    /**
     * @var string
     */
    public static $statusSearch = "#s2id_autogen2_search";

    /**
     * @var string
     */
    public static $statusPaymentStatus = "#s2id_order_paymentstatus";

    /**
     * @var string
     */
    public static $statusPaymentSearch = "#s2id_autogen3_search";

    /**
     * @var array
     */
    public static $nameButtonStatus = ['name' => 'order_status'];

    /**
     * @var string
     */
    public static $deleteFirst = "//input[@id='cb0']";
	
	/**
	 * @var string
	 */

    public static $iconEdit = '//a[@title="Edit order"]';

    /**
     * @var string
     */
    public static $nameXpath = "//td[4]/a";

    /**
     * @var string
     */
    public static $fieldAttribute = "//select[@class='inputbox']";

    /**
     * @var string
     */
    public static $valueAttribute = "//select[@class='inputbox']/option[2]";

    /**
     * @var string
     */
    public static $adminFinalPriceEnd = "#tdtotalprdproduct1";

    /**
     * @var string
     * @since 2.1.2
     */
    public static $discountUpdate = "#update_discount";

    /**
     * @var string
     * @since 2.1.2
     */
    public static $specialUpdate = "#special_discount";

    /**
     * @var string
     */
    public static $buttonSavePay = "Save + Pay";

    /**
     * @var string
     * @since 2.1.2
     */
    public static $orderID = "//div[@class='table-responsive']//td[3]//a[1]";

    /**
     * @var string
     */
    public static $messageSaveSuccess = "Order Status Successfully Saved For Order Number 1";

    /**
     * @var string
     */
    public static $messageDeleteSuccess = "Order detail deleted successfully";

    /**
     * Function to get Path $userName in Order item
     *
     * @param $userName
     *
     * @return string
     */
    public function returnSearch($userName)
    {
        $path = "//span[contains(text(), '" . $userName . "')]";
        return $path;
    }

    /**
     * Function to get Path $idOder in Order detail
     * @param $idOrder
     * @since 2.1.2
     * @return string
     */
    public function returnButtonUpdateDiscount ($idOrder)
    {
        $path = "//a[@onclick= \"javascript:validateDiscount('#update_discount$idOrder');\"]";
        return $path;
    }

    /**
     * Function to get Path $idOder in Order detail
     * @param $idOrder
     * @since 2.1.2
     * @return string
     */
    public function returnButtonSpecialDiscount ($idOrder)
    {
        $path = "//a[@onclick= \"javascript:validateDiscount('#special_discount$idOrder');\"]";
        return $path;
    }
}
