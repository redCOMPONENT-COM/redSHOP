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
 * @since  2.4
 */
class ProductManagerPage extends AdminJ3Page
{


	public static $namePage="Product Management";

    public static $URL = 'administrator/index.php?option=com_redshop&view=product';

    public static $categorySearch = "#s2id_category_id";
    
    public static $categorySearchField = "#s2id_autogen2_search";
    
    public static $searchStatusId = "#s2id_product_sort";
    
    public static $searchStatusField = "#s2id_autogen3_search";
    
    public static $namePageXpath = "//h1";

    public static $productFilter = "#keyword";

    public static $discountStart = "#discount_stratdate";

    public static $discountEnd = "#discount_enddate";

    public static $discountPrice = "#discount_price";

    public static $minimumPerProduct = "#minimum_per_product_total";

    public static $minimumQuantity = "#min_order_product_quantity";

    public static $maximumQuantity = "#max_order_product_quantity";

    public static $productNumber = "#product_number";

    public static $productPrice =  "#product_price";

    public static $productName = "#product_name";
    
    public static $categoryId = "#s2id_product_category";
    
    public static $categoryFile = "#s2id_autogen4";

    public static $vatDropdownList = "//div[@id=\'s2id_product_tax_group_id\']";

    public static $vatSearchField = "#s2id_autogen8_search";
    
//    public static 

    //stockroom for product
    public static $stockroomTab = "//a[contains(text(), \'Stockroom\')]";

    /**
     * @var array
     */

    public static $quantityInStock = ['xpath'=>'//input[@name="quantity[]"]'];

	public static $preOrderStock = ['xpath'=>'//input[@name="preorder_stock[]"]'];

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
     public static $attributeTab = "//h3[text()=\'Product Attributes\']";
    
    public static $addAttribute = '+ Add Attribute parameter';
    

	/**
	 * @param $position
	 */
    public function addAttributeName($position)
    {
    	$xpath = ['xpath' => '//input[@name="attribute['.$position.'][name]"]'];

    	return $xpath;
    }

    public function attributeNameProperty($position)
    {
    	$xpath = ['xpath'=>'//input[@name="attribute[' . $position . '][property][0][name]"]'];

    	return $xpath;
    }

	public function attributePriceProperty($position)
	{
		$xpath = ['xpath'=>'//input[@name="attribute[' . $position . '][property][0][price]"]'];

		return $xpath;
	}

	public function attributePreSelect($position)
    {
        $xpath = ['xpath'=>'//input[@name="attribute[' . $position . '][property][0][default_sel]"]'];

        return $xpath;
    }

    // tab acc
    public static $accessoryTab = 'Accessory/Related Product';
    
    public static $accessoriesValue= "//h3[text()='Accessories']";
    
    public static $relatedProduct = "//h3[text()='Related product']";
    
    public static $accessorySearchID = "#s2id_product_accessory_search";
    
    public static $accessSearchField = "#s2id_autogen3_search";

    // relate product
    public static $relatedProductId = "#s2id_related_product";
   
    
}
