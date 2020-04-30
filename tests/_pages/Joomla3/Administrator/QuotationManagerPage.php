<?php
/**
 * @package     redSHOP
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class QuotationManagerPage
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  1.4.0
 */
class QuotationManagerPage extends AdminJ3Page
{
	//page name

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $pageManagementName = "User Management";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $URL = '/administrator/index.php?option=com_redshop&view=quotation';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $userId = "#s2id_user_id";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $userSearch = "#s2id_autogen1_search";

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
	 * @var array
	 * @since 1.4.0
	 */
	public static $newProductLink = ['link' => 'New'];

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $quanlityFirst = "#quantityproduct1";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $quantityp1 = "#quantityp1";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $quotationStatusDropDown = "#s2id_quotation_status";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $quotationStatusSearch = "#s2id_autogen2_search";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $quotationId = "//tr[@class='row0']/td[3]/a";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $quotationStatus = "//tr[@class='row0']/td[6]";

	//button

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $buttonSend = "Send";

	//message

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $messageSaveSuccess = "Quotation detail saved";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $messageDeleteSuccess = "Quotation detail deleted successfully";

	/**
	 * @var string
	 * since 2.1.2
	 */
	public static $email = '//input[@id="user_email"]';

	/**
	 * @var string
	 * since 2.1.2
	 */
	public static $enterPhoneNumber = 'Please enter phone number';

	/**
	 * Function to get the path for User Name
	 *
	 * @param  String $userName is Name login in account
	 *
	 * @return string
	 * @since 1.4.0
	 */
	public function xPathSearch($userName)
	{
		$path = "//span[contains(text(), '" . $userName . "')]";
		return $path;
	}
}
