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
    public static $pageManagementName = "User Management";

    public static $URL = '/administrator/index.php?option=com_redshop&view=quotation';
    
    public static $userId = "#s2id_user_id";

    public static $userSearch = "#s2id_autogen1_search";

    public static $productId = "#s2id_product1";

    public static $productsSearch = "#s2id_autogen2_search";
    
    public static $newProductLink = ['link' => 'New'];

    public static $quanlityFirst = "#quantityproduct1";

    public static $quantityp1 = "#quantityp1";

    public static $quotationStatusDropDown = "#s2id_quotation_status";

    public static $quotationStatusSearch = "#s2id_autogen2_search";
    
    public static $quotationId = "#//tr[@class=\'row0\']/td[3]/a";

    public static $quotationStatus = "#//tr[@class=\'row0\']/td[6]";
    //button

    public static $buttonSend = "Send";


    //selector

    public static $selectorSuccess = '.alert-success';

    public static $selectorError = '.alert-danger';

    public static $selectorNamePage = '.page-title';


    //message
    public static $messageSaveSuccess = "Quotation detail saved";

    public static $messageDeleteSuccess = "Quotation detail deleted successfully";


    public function xPathSearch($userName)
    {
        $path = "//span[contains(text(), '" . $userName . "')]";
        return $path;
    }
}
