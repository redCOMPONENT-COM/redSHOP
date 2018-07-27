<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class QuotationManagerPage
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  2.4
 */
class QuotationManagerPage extends AdminJ3Page
{
    //page name

    /**
     * @var string
     */
    public static $pageManagementName = "User Management";

    /**
     * @var string
     */
    public static $URL = '/administrator/index.php?option=com_redshop&view=quotation';

    /**
     * @var string
     */
    public static $userId = "#s2id_user_id";

    /**
     * @var string
     */
    public static $userSearch = "#s2id_autogen1_search";

    /**
     * @var string
     */
    public static $productId = "#s2id_product1";

    /**
     * @var string
     */
    public static $productsSearch = "#s2id_autogen2_search";

    /**
     * @var array
     */
    public static $newProductLink = ['link' => 'New'];
    /**
     * @var string
     */
    public static $quanlityFirst = "#quantityproduct1";

    /**
     * @var string
     */
    public static $quantityp1 = "#quantityp1";

    /**
     * @var string
     */
    public static $quotationStatusDropDown = "#s2id_quotation_status";

    /**
     * @var string
     */
    public static $quotationStatusSearch = "#s2id_autogen2_search";

    /**
     * @var string
     */
    public static $quotationId = "#//tr[@class=\'row0\']/td[3]/a";

    /**
     * @var string
     */
    public static $quotationStatus = "#//tr[@class=\'row0\']/td[6]";
    //button

    /**
     * @var string
     */
    public static $buttonSend = "Send";


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

    //message

    /**
     * @var string
     */
    public static $messageSaveSuccess = "Quotation detail saved";

    /**
     * @var string
     */
    public static $messageDeleteSuccess = "Quotation detail deleted successfully";

    /**
     * Function to get the path for User Name
     *
     * @param  String $userName is Name login in account
     *
     * @return string
     */
    public function xPathSearch($userName)
    {
        $path = "//span[contains(text(), '" . $userName . "')]";
        return $path;
    }
}
