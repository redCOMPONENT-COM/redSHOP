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

    public static $categorySearch = ['id' => 's2id_category_id'];
    
    public static $categorySearchField = ['id' => 's2id_autogen2_search'];
    
    public static $searchStatusId = ['id' => 's2id_product_sort'];
    
    public static $searchStatusField = ['id' => 's2id_autogen3_search'];
    
    public static $namePageXpath = ['xpath' => "//h1"];

    public static $productFilter = ['id' => 'keyword'];

    public static $discountStart = ['id' => "discount_stratdate"];

    public static $discountEnd = ['id' => "discount_enddate"];

    public static $discountPrice = ['id' => "discount_price"];

    public static $minimumPerProduct = ['id' => "minimum_per_product_total"];

    public static $minimumQuantity = ['id' => "min_order_product_quantity"];

    public static $maximumQuantity = ['id' => "max_order_product_quantity"];

    public static $productNumber = ['id'=>'product_number'];

    public static $productPrice = ['id'=>'product_price'];

    public static $productName  =   ['id' => "product_name"];
    
    public static $categoryId = ['id' => "s2id_product_category"];
    
    public static $categoryFile = ['id' => 's2id_autogen4'];

    public static $categoryInput = ['xpath' => "//div[@id='s2id_product_category']//ul/li//input"];


    public static $checkAllProducts = "//input[@onclick='Joomla.checkAll(this)']";

    //stockroom for product
	public static $stockroomTab = ['xpath'=>'//form[@id=\'adminForm\']/div[1]/div[1]/div/div/ul/li[7]/a'];
    
	public static $quantityInStock = ['xpath'=>'//table[@id=\'accessory_table\']/tbody/tr/td[2]/input[1]'];

	public static $preOrderStock = ['xpath'=>'//table[@id=\'accessory_table\']/tbody/tr/td[4]/input[1]'];

	public static $messageSaveSuccess = "Product details saved";
    
    public static $messageDeleteProductSuccess = 'Product deleted successfully';
    
    public static $messageCopySuccess = 'Product Copied';
    
    public static $messageCancel  =  'Product detail editing cancelled';
    
    // button 
    public static $buttonAssignNewCategory = 'Assign new Category';
    
    public static $buttonRemoveCategory = 'Remove Category';

    public static $buttonDeleteAttribute = 'Delete attribute';
    
    public static $buttonProductAttribute = 'Product Attributes';
    
    
    //tab 
     public static $attributeTab = ['xpath' => '//h3[text()=\'Product Attributes\']'];
    
    public static $addAttribute = '+ Add Attribute parameter';
    
    public static $attributeNameFirst = ['xpath' => '//input[@name="attribute[1][name]"]'];
    
    public static $attributeNamePropertyFirst = ['xpath'=>'//input[@name="attribute[1][property][0][name]"]'];
    
    public static $attributePricePropertyFirst = ['xpath'=>'//input[@name="attribute[1][property][0][price]"]'];
    
    // tab acc
    public static $accessoryTab = 'Accessory/Related Product';
    
    public static $accessoriesValue= ['xpath' => "//h3[text()='Accessories']"];
    
    public static $relatedProduct = ['xpath' => "//h3[text()='Related product']"];
    
}
