<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class ProductManagerPage
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  1.4
 */
class ProductManagerPage
{



    public static $URL = 'administrator/index.php?option=com_redshop&view=product';

    public static $URLNew='/administrator/index.php?option=com_redshop&view=product_detail&layout=edit';
    public static $productFilter = ['id' => 'keyword'];
    public static $productName = "#product_name";
    public static $discountStart = ['id' => "discount_stratdate"];
    public static $discountEnd = ['id' => "discount_enddate"];
    public static $discountPrice = ['id' => "discount_price"];
    public static $minimumPerProduct = ['id' => "minimum_per_product_total"];
    public static $minimumQuantity = ['id' => "min_order_product_quantity"];
    public static $maximumQuantity = ['id' => "max_order_product_quantity"];

    public static $checkAllProducts="//input[@onclick='Joomla.checkAll(this)']";


}
