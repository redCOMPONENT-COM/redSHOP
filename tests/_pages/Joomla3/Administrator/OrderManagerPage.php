<?php
/**
 * @package     redSHOP
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class OrderManagerPage
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  1.4.0
 */
class OrderManagerPage extends AdminJ3Page
{
	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $URL = '/administrator/index.php?option=com_redshop&view=order';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $titlePage = 'Order';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $userId = "//div[@id='s2id_user_id']/a";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $userSearch = "//input[@id='s2id_autogen1_search']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $address = "#address";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $zipcode = "#zipcode";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $fistName = "//input[@id='firstname']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $city = "#city";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $phone = "#phone";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $close = "#toolbar-cancel";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $filter = "#filter";

	/**
	 * @var string
	 * @since 2.1.5
	 */
	public static $shippingInfor = "#updateshippingrate";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $applyUser = "#toolbar-apply";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $productId = "#s2id_product1";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $productsSearch = "#s2id_autogen2_search";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $quanlityFirst = "#quantityproduct1";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $quantityp1 = "#quantity";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $nameProductSuccess = "#order_product_detail_3";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $statusOrder = "#s2id_status";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $statusSearch = "#s2id_autogen2_search";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $statusPaymentStatus = "#s2id_order_paymentstatus";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $statusPaymentSearch = "#s2id_autogen3_search";

	/**
	 * @var array
	 * @since 1.4.0
	 */
	public static $nameButtonStatus = "//input[@name='order_status']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $deleteFirst = "//input[@id='cb0']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $iconEdit = '(//a[@title="Edit order"])[1]';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $nameXpath = "//td[4]/a";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $fieldAttribute = "//select[@class='inputbox']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $valueAttribute = "(//select[@class='inputbox']/option[2])[1]";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $adminFinalPriceEnd = "#tdtotalprdproduct1";

	//button

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $discountUpdate = "//input[@id='update_discount']";

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $specialUpdate = "//input[@id='special_discount']";

	/**
	 * @var string
	 * @since 2.1.4
	 */
	public static $bankTranferPayment = "//td[@id='tdPayment']//label[1]";

	/**
	 * @var string
	 * @since 2.1.4
	 */
	public static $payPalPayment = "//td[@id='tdPayment']//label[2]";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $buttonSavePay = "Save + Pay";

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $orderID = "//div[@class='table-responsive']//td[3]//a[1]";

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $messageChangeOrderSuccess = "Order Status Successfully Saved For Order Number ";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $messageDeleteSuccess = "Order detail deleted successfully";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $buttonDeleteOder = '//div[@id="toolbar-delete"]';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $priceVAT = "#prdtaxproduct1";

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $priceProduct = "#prdpriceproduct1";

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $selectSubProperty = "//select[@onchange=\"javascript:calculateOfflineTotalPrice('product1', true);\"]";

	/**
	 * @var string
	 * @since 3.0.1
	 */
	public static $orderDetailTable = "//th[contains(text(),'Order Details')]";

	/**
	 * @var string
	 * @since 3.0.2
	 */
	public static $paymentTitle = "//h3[contains(text(),'Payment Method')]";

	/**
	 * @var string
	 * @since 3.0.2
	 */
	public static $checkboxPaymentPayPal = "(//input[@value ='rs_payment_paypal'])[1]";

	/**
	 * @var string
	 * @since 3.0.2
	 */
	public static $checkboxPaymentBanktransfer = "(//input[@value ='rs_payment_banktransfer'])[1]";

	/**
	 * @var string
	 * @since 3.0.2
	 */
	public static $updatePaymentMethod = "//form[@id='updatepaymentmethod']//input[@id='add']";

	/**
	 * @var string
	 * @since 3.0.2
	 */
	public static $messageUpdatePaymentSuccess = "Payment Method Updated";

	/**
	 * Function to get Path $userName in Order item
	 *
	 * @param $userName
	 *
	 * @return string
	 * @since 1.4.0
	 */
	public function returnSearch($userName)
	{
		$path = "//span[contains(text(), '" . $userName . "')]";
		return $path;
	}

	/**
	 * @param $code
	 * @return string
	 * @since 2.1.3
	 */
	public function xpathOrderStatus($code)
	{
		$xpath = ".order_status_".$code;

		return $xpath;
	}

	/**
	 * Function to get Path $idOder in Order detail
	 * @param $idOrder
	 * @since 2.1.3
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
	 * @since 2.1.3
	 * @return string
	 */
	public function returnButtonSpecialDiscount ($idOrder)
	{
		$path = "//a[@onclick= \"javascript:validateDiscount('#special_discount$idOrder');\"]";
		return $path;
	}

	/**
	 * @param $nameValue
	 * @return string
	 * @since 2.1.3
	 */
	public function returnXpathAttributeValue($nameValue)
	{
		$path = "(//select/option[contains(text(),'$nameValue')])[1]";
		return $path;
	}

	/**
	 * @param $nameAttribute
	 * @return string
	 * @since 2.1.3
	 */
	public function returnXpathAttributeName($nameAttribute)
	{
		$path = "//select[@attribute_name='$nameAttribute']";
		return $path;
	}
}
