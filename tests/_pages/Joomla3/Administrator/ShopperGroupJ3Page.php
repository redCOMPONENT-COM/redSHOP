<?php
/**
 * @package     redSHOP
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class ShopperGroupManagerJ3Page
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  1.4.0
 */
class ShopperGroupJ3Page extends AdminJ3Page
{
	//name page

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $namePageManagement = 'Shopper Group Management';

	//URL

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $URL = 'administrator/index.php?option=com_redshop&view=shoppergroups';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $URLEdit = '/administrator/index.php?option=com_redshop&view=shopper_group_detail&task=edit&cid[]=';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $buttonNewXpath = '//button[@class=\'btn btn-small button-new btn-success\']';

	//Id

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $shopperName = "#shopper_group_name";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $shopperGroupType = "#select2-chosen-1";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $shopperType = "#s2id_autogen1_search";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $customerType = "#select2-chosen-2";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $customerTypeSearch = "#s2id_autogen2_search";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $shopperGroupPortalYes = "#shopper_group_portal_1-lbl";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $shopperGroupPortalNo = "#shopper_group_portal_0-lbl";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $shippingYes = "#default_shipping_1-lbl";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $shippingNo = "#default_shipping_0-lbl";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $shippingRate = "#default_shipping_rate";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $shippingCheckout = "#shopper_group_cart_checkout_itemid";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $vatNo = "#show_price_without_vat_0-lbl";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $vatYes = "#show_price_without_vat_1-lbl";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $showPrice = "#s2id_show_price";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $showPriceSearch = "#s2id_autogen6_search";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $catalogId = "#s2id_use_as_catalog";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $catalogSearch = "#s2id_autogen7_search";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $quoationNo = "#shopper_group_quotation_mode_0-lbl";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $quotationYes = "#shopper_group_quotation_mode_1-lbl";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $publishNo = "#published_0-lbl";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $publishYes = "#published_1-lbl";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $categoryFiled = "#s2id_shopper_group_categories";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static  $categoryFill = "//input[@id='s2id_autogen3']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $shopperFirst = " //input[@id='cb0']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $shopperFours = "//input[@id='cb3']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $shopperFirstStatus =  "//tr[1]/td[5]/a";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $nameShopperGroupsFirst = "//tr[1]/td[3]/a";

	//message

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $saveSuccess = 'Shopper Group Detail Saved';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $deleteSuccess = 'Shopper Group can not be deleted.';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $unpublishSuccess = 'Shopper Group Detail Unpublished Successfully';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $publishSuccess = 'Shopper Group Detail Published Successfully';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $cannotDelete = 'Shopper Group can not be deleted.';

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $searchField = '#filter';

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $searchButton = '//button[@onclick="document.adminForm.submit();"]';

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $deleteShopperSuccess = 'Shopper Group Detail Deleted Successfully';

	/**
	 * Function to get the path for Search Shopper Group
	 *
	 * @param String $typeSearch in Shopper Group Name
	 *
	 * @return string
	 * @since 1.4.0
	 */
	public function returnSearch($typeSearch)
	{
		$path = "//span[contains(text(), '" . $typeSearch . "')]";
		return $path;
	}

	/**
	 * @param $shoppergroupname
	 * @return string
	 * @since 2.1.2
	 */
	public function xPathShoppergroupName($shoppergroupname)
	{
		$path = "//a[contains(text(), '" . $shoppergroupname . "')]";
		return $path;
	}
}
