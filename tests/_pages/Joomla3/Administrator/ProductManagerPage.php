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
class ProductManagerPage extends AdminJ3Page
{


	public static $namePage="Product Management";

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

    public static $productNumber= ['id'=>'product_number'];

    public static $productPrice=['id'=>'product_price'];

    public static $category=['xpath' => "//div[@id='s2id_product_category']"];

    public static $categoryInput=['xpath' => "//input[@id='s2id_autogen4']"];


    //stockroom for product
	public static $stockroomTab =['xpath'=>'//ul[@class=\'tabconfig nav nav-pills nav-stacked\']/li[7]'];

	public static $quantityInStock=['xpath'=>'//table[@id=\'accessory_table\']/tbody/tr/td[2]/input[1]'];

	public static $preOrderStock=['xpath'=>'//table[@id=\'accessory_table\']/tbody/tr/td[4]/input[1]'];

	public static $messageSaveSuccess="Product details saved";

}
