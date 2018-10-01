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
class
ProductManagerPage extends AdminJ3Page
{

    /**
     * @var string
     */
	public static $namePage="Product Management";

    /**
     * @var string
     */
    public static $URL = 'administrator/index.php?option=com_redshop&view=product';
	
	/**
	 * @var string
	 */
    public static $username = '#modlgn-username';
	/**
	 * @var string
	 */
    public static $password = '#modlgn-passwd';

    /**
     * @var string
     */
    public static $categorySearch = "#s2id_category_id";


    /**
     * @var string
     */
    public static $categorySearchField = "#s2id_autogen2_search";

    /**
     * @var string
     */
    public static $searchStatusId = "#s2id_product_sort";

    /**
     * @var string
     */
    public static $searchStatusField = "#s2id_autogen3_search";

    /**
     * @var string
     */
    public static $namePageXpath = "//h1";

    /**
     * @var string
     */
    public static $productFilter = "#keyword";

    /**
     * @var string
     */
    public static $discountStart = "#discount_stratdate";

    /**
     * @var string
     */
    public static $discountEnd = "#discount_enddate";

    /**
     * @var string
     */
    public static $discountPrice = "#discount_price";

    /**
     * @var string
     */
    public static $minimumPerProduct = "#minimum_per_product_total";

    /**
     * @var string
     */
    public static $minimumQuantity = "#min_order_product_quantity";

    /**
     * @var string
     */
    public static $maximumQuantity = "#max_order_product_quantity";

    /**
     * @var string
     */
    public static $productNumber = "#product_number";

    /**
     * @var string
     */
    public static $productPrice =  "#product_price";

    /**
     * @var string
     */
    public static $productName = "#product_name";

    /**
     * @var string
     */
    public static $categoryId = "#s2id_product_category";

    /**
     * @var string
     */
    public static $categoryFile = "#s2id_autogen4";

    /**
     * @var string
     */
    public static $vatDropdownList = "//div[@id='s2id_product_tax_group_id']";
	/**
	 * @var string
	 */
    public static $buttonCheckOut = '//input[@value="Checkout"]';
	/**
	 * @var string
	 */
    public static $buttonLogin = '//button [@name="Submit"]';
    /**
     * @var string
     */
    public static $buttonLogout = '//input [@value="Log out"]';
	/**
	 * @var string
	 */
    public static $iconEdit = '//a[@title="Edit order"]';
	/**
	 * @var string
	 */
    public static $giftCode = "#coupon_input";

    /**
     * @var string
     */
    public static $vatSearchField = "#s2id_autogen8_search";


    /**
     * @var string
     */
    public static $stockroomTab = "//a[contains(text(), 'Stockroom')]";

    /**
     * @var array
     */

    public static $quantityInStock = "//input[@name='quantity[]']";

    /**
     * @var array
     */
	public static $preOrderStock = "//input[@name='preorder_stock[]']";

    /**
     * @var string
     */
	public static $messageSaveSuccess = "Product details saved";

    /**
     * @var string
     */
    public static $messageDeleteProductSuccess = 'Product deleted successfully';

    /**
     * @var string
     */
    public static $messageCopySuccess = 'Product Copied';

    /**
     * @var string
     */
    public static $messageCancel  =  'Product detail editing cancelled';

    /**
     * @var string
     */
    public static $messageErrorAddress = '#private-address-error';
    /**
     * @var string
     */
    public static $messagePostalCode = '#private-zipcode-error';
    /**
     * @var string
     */
    public static $messageCity = '#private-city-error';
    /**
     * @var string
     */
    public static $messagePhone = '#private-phone-error';
    /**
     * @var string
     */
    public static $customerAddress = '#private-address';
    /**
     * @var string
     */
    public static $customerPostalCode = '#private-zipcode';
    /**
     * @var string
     */
    public static $customerCity = '#private-city';
    /**
     * @var string
     */
    public static $customerPhone = '#private-phone';
    
    // button

    /**
     * @var string
     */
    public static $buttonAssignNewCategory = 'Assign new Category';

    /**
     * @var string
     */
    public static $buttonRemoveCategory = 'Remove Category';

    /**
     * @var string
     */
    public static $buttonDeleteAttribute = 'Delete attribute';

    /**
     * @var string
     */
    public static $buttonProductAttribute = 'Product Attributes';
	/**
	 * @var string
	 */
    public static $quantity = "//span[@class='update_cart']//label";
	/**
	 * @var string
	 */
	public static $priceTotalOrderFrontend = "//div[@class='form-group total']//div[@class='col-sm-6']";
    //tab

    /**
     * @var string
     */
     public static $attributeTab = "//h3[text()='Product Attributes']";

    /**
     * @var string
     */
    public static $addAttribute = '+ Add Attribute parameter';


    /**
     * Function to get Path $position in Add Attribute Name
     *
     * @param $position
     *
     * @return array
     */
    public function addAttributeName($position)
    {
    	$xpath = ['xpath' => '//input[@name="attribute['.$position.'][name]"]'];

    	return $xpath;
    }

    /**
     * Function to get Path $position in Attribute Name Property
     *
     * @param $position
     *
     * @return array
     */
    public function attributeNameProperty($position)
    {
    	$xpath = ['xpath'=>'//input[@name="attribute[' . $position . '][property][0][name]"]'];

    	return $xpath;
    }

    /**
     * Function to get Path $position in Attribute Price Property
     *
     * @param $position
     *
     * @return array
     */
	public function attributePriceProperty($position)
	{
		$xpath = ['xpath'=>'//input[@name="attribute[' . $position . '][property][0][price]"]'];

		return $xpath;
	}

    /**
     * Function to get Path $position in Attribute PreSelect
     *
     * @param $position
     *
     * @return array
     */
	public function attributePreSelect($position)
    {
        $xpath = ['xpath'=>'//input[@name="attribute[' . $position . '][property][0][default_sel]"]'];

        return $xpath;
    }

    // tab acc

    /**
     * @var string
     */
    public static $accessoryTab = "Accessory/Related Product";

    /**
     * @var string
     */
    public static $accessoriesValue= "//h3[text()='Accessories']";

    /**
     * @var string
     */
    public static $relatedProduct = "//h3[text()='Related product']";

    /**
     * @var string
     */
    public static $accessorySearchID = "#s2id_product_accessory_search";

    /**
     * @var string
     */
    public static $accessSearchField = "#s2id_autogen3_search";

    // relate product

    /**
     * @var string
     */
    public static $relatedProductId = "#s2id_related_product";

    /**
     * @var string
     *
     * Product not for Sale in Frontend
     */
    public static $selectCategory = ".select2-match";

    /**
     * @var string
     */
    public static $saleYes = "//input[@id='not_for_sale1']";

    /**
     * @var string
     */
    public static $saleNo = "#not_for_sale0";

    /**
     * @var string
     */
    public static $showPriceYes = "//input[@id='not_for_sale_showprice1']";

    /**
     * @var string
     */
    public static $showPriceNo = "#not_for_sale_showprice0";

    /**
     * @var string
     */
    public static $categoryID = ".category_front_inside";

    /**
     * @var string
     */
    public static $productID = ".category_box_inside";

    /**
     * @var string
     */
    public static $priceFrontend = ".category_product_price";
}
