<?php
/**
 * @package     redSHOP
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class ProductManagerPage
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  1.4.0
 */
class ProductManagerPage extends AdminJ3Page
{
	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $namePage = "Product Management";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $URL = 'administrator/index.php?option=com_redshop&view=product';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $username = '#modlgn-username';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $password = '#modlgn-passwd';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $categorySearch = "#s2id_category_id";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $categorySearchField = "#s2id_autogen2_search";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $searchStatusId = "#s2id_product_sort";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $searchStatusField = "#s2id_autogen4_search";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $namePageXpath = "//h1";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $productFilter = "#keyword";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $discountStart = "#discount_stratdate";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $discountEnd = "#discount_enddate";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $discountPrice = "#discount_price";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $minimumPerProduct = "#minimum_per_product_total";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $minimumQuantity = "#min_order_product_quantity";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $maximumQuantity = "#max_order_product_quantity";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $productNumber = "#product_number";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $productPrice =  "#product_price";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $productName = "#product_name";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $categoryId = "#s2id_product_category";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $categoryFile = "#s2id_autogen4";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $vatDropdownList = "//div[@id='s2id_product_tax_group_id']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $buttonCheckOut = '//input[@value="Checkout"]';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $buttonLogin = '//button [@name="Submit"]';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $iconEdit = '//a[@title="Edit order"]';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $giftCode = "#coupon_input";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $vatSearchField = "#s2id_autogen8_search";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $stockroomTab = "//a[contains(text(), 'Stockroom')]";

	/**
	 * @var array
	 * @since 1.4.0
	 */
	public static $quantityInStock = "//input[@name='quantity[]']";

	/**
	 * @var array
	 * @since 1.4.0
	 */
	public static $preOrderStock = "//input[@name='preorder_stock[]']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $messageSaveSuccess = "Product details saved";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $messageDeleteProductSuccess = 'Product deleted successfully';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $messageCopySuccess = 'Product Copied';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $messageCancel  =  'Product detail editing cancelled';

	// button

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $buttonAssignNewCategory = 'Assign new Category';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $buttonRemoveCategory = 'Remove Category';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $buttonDeleteAttribute = 'Delete attribute';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $buttonProductAttribute = ["link" => 'Product Attributes'];

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $quantity = "//span[@class='update_cart']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $priceTotalOrderFrontend = "//div[@class='form-group total']//div[@class='col-sm-6']";

	//tab

	/**
	 * @var string
	 * @since 1.4.0
	 */
	 public static $attributeTab = "//h3[text()='Product Attributes']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $addAttribute = '+ Add Attribute parameter';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $addAttributeValue = '+ Add Attribute value';

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $addPriceButton = '.button-new';

	/**
	 * Function to get Path $position in Add Attribute Name
	 *
	 * @param $position
	 *
	 * @return array
	 * @since 1.4.0
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
	 * @since 1.4.0
	 */
	public function attributeNameProperty($position)
	{
		$xpath = ['xpath'=>'//input[@name="attribute[' . $position . '][property][0][name]"]'];

		return $xpath;
	}

	/**
	 * Function to get Path $position in Attribute Name Property
	 *
	 * @param $positionParameter
	 * @param $positionAttribute
	 *
	 * @return string
	 * @since 1.4.0
	 */
	public function attributeNameAttribute($positionParameter, $positionAttribute)
	{
		$xpath = '//input[@name="attribute[' . $positionParameter . '][property]['.$positionAttribute.'][name]"]';

		return $xpath;
	}

	/**
	 * Function to get Path $position in Attribute Price Property
	 *
	 * @param $positionParameter
	 * @param $positionAttribute
	 *
	 * @return string
	 * @since 1.4.0
	 */
	public function attributePricePropertyAttribute($positionParameter, $positionAttribute)
	{
		$xpath = '//input[@name="attribute[' . $positionParameter . '][property]['.$positionAttribute.'][price]"]';

		return $xpath;
	}

	/**
	 * @param $position
	 * @return string
	 * @since 1.4.0
	 */
	public function attributePriceProperty($position)
	{
		$xpath = '//input[@name="attribute[' . $position . '][property][0][price]"]';

		return $xpath;
	}

	/**
	 * Function to get Path $position in Attribute PreSelect
	 *
	 * @param $position
	 *
	 * @return string
	 * @since 1.4.0
	 */
	public function attributePreSelect($position)
	{
		$xpath = '//input[@name="attribute[' . $position . '][property][0][default_sel]"]';

		return $xpath;
	}

	// tab acc

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $accessoryTab = "Accessory/Related Product";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $accessoriesValue= "//h3[text()='Accessories']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $relatedProduct = "//h3[text()='Related product']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $accessorySearchID = "#s2id_product_accessory_search";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $accessSearchField = "#s2id_autogen3_search";

	// relate product

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $relatedProductId = "#s2id_related_product";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $productDiscontionueYes = "//input[@id='expired1']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $productDiscontinueNo = "//input[@id='expired0']";

	/**
	 * @var string
	 * @since 1.4.0
	 *
	 * Product not for Sale in Frontend
	 */
	public static $selectCategory = ".select2-match";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $saleYes = "//input[@id='not_for_sale1']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $saleNo = "#not_for_sale0";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $showPriceYes = "//input[@id='not_for_sale_showprice1']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $showPriceNo = "#not_for_sale_showprice0";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $categoryID = ".category_front_inside";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $productID = ".category_box_inside";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $priceFrontend = ".category_product_price";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $productRelated ='//input[@id="s2id_autogen1"]';

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $fileUpload = '//input[@type=\'file\' and @multiple="multiple"]';

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $tabSEO = "SEO";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $titleSEO ="//input[@id='pagetitle']";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $headingSEO ="//input[@id='pageheading']";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $xpathSaveClose = '//button[@onclick="Joomla.submitbutton(\'save\');"]';

	/**
	 * @var string
	 * @since 2.1.4
	 */
	public static $buttonSaveAsCopy = "//button[@onclick=\"Joomla.submitbutton('save2copy');\"]";

	/**
	 * @var string
	 * @since 2.1.4
	 */
	public static $labelAttributeRequired = "//input[@name='attribute[0][required]']";

	/**
	 * @var string
	 * @since 2.1.4
	 */
	public static $labelMultipleSelection = "//input[@name='attribute[0][allow_multiple_selection]']";

	/**
	 * @var string
	 * @since 2.1.4
	 */
	public static $labelHideAttributePrice = "//input[@name='attribute[0][hide_attribute_price]']";

	/**
	 * @var string
	 * @since 2.1.4
	 */
	public static $labelPublished = "//input[@name='attribute[0][published]']";

	/**
	 * @var string
	 * @since 2.1.4
	 */
	public static $productSecond = "//input[@id='cb1']";

	/**
	 * @var string
	 * @since 2.1.4
	 */
	public static $additionalInformation = "//h3[contains(text(),'Additional Information')]";

	/**
	 * @var string
	 * @since 2.1.4
	 */
	public static $productParentID = "#s2id_product_parent_id";

	/**
	 * @param $position
	 * @param $x
	 * @param $y
	 * @return string
	 * @since 2.1.3
	 */
	public function subNameProperty($position, $x, $y)
	{
		$xpath = "//input[@name='attribute[$position][property][$x][subproperty][$y][name]']";

		return $xpath;
	}

	/**
	 * @param $position
	 * @param $x
	 * @param $y
	 * @return string
	 * @since 2.1.3
	 */
	public function subPriceProperty($position, $x, $y)
	{
		$xpath = "//input[@name='attribute[$position][property][$x][subproperty][$y][price]']";

		return $xpath;
	}

	/**
	 * @param $position
	 * @param $x
	 * @return string
	 * @since 2.1.3
	 */
	public function nameSubProperty($position, $x)
	{
		$xpath = "//input[@name='attribute[$position][property][$x][subproperty][title]']";

		return $xpath;
	}

	/**
	 * @param $x
	 * @return string
	 * @since 2.1.3
	 */
	public function buttonAddSubProperty($x)
	{
		$xpath = "(//a[@class ='btn btn-success add_subproperty btn-small'])[$x]";

		return $xpath;
	}

	/**
	 * @param $productParent
	 * @return string
	 * @since 2.1.4
	 */
	public function returnProductParent($productParent)
	{
		$xpath = "//div/span[contains(text(), '$productParent')]";

		return $xpath;
	}

	/**
	 * @param $option
	 * @return string
	 * @since 3.0.2
	 */
	public function productDiscontinued($option)
	{
		return $path = "//input[@id='expired".$option."']";
	}
}
