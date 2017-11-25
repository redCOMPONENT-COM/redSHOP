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
 * @since  1.4
 */
class QuotationManagerPage extends  AdminJ3Page
{

    //page name
    public static $pageManagementName = "User Management";

    public static $URL = '/administrator/index.php?option=com_redshop&view=quotation';


    public static $userId = ['id' => 's2id_user_id'];

    public static $userSearch = ['id' => 's2id_autogen1_search'];

    public static $productId = ['id' => 's2id_product1'];

    public static $productsSearch = ['id' => 's2id_autogen2_search'];

    public static $quanlityFirst = ['id' => 'quantityproduct1'];

    public static $quantityp1 = ['id' => 'quantityp1'];


    public static $quotationId = "//tr[@class='row0']/td[3]";

    //message
    public static $messageSaveSuccess = "Quotation detail saved";

    public static $messageDeleteSuccess = "Quotation detail deleted successfully";


    public function xPathSearch($userName)
    {
        $path = ['xpath' => "//span[contains(text(), '" . $userName . "')]"];
        return $path;
    }
}
