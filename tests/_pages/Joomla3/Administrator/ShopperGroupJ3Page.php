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
	public static $URLEdit = '/administrator/index.php?option=com_redshop&view=shoppergroup&layout=edit&id=';

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
	public static $shopperName = "#jform_name";

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
	public static $shopperGroupPortalYes = "#jform_portal0";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $shopperGroupPortalNo = "#jform_portal1";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $shippingYes = "#jform_default_shipping0";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $shippingNo = "#jform_default_shipping1";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $shippingRate = "#jform_default_shipping_rate";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $shippingCheckout = "#jform_cart_checkout_itemid";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $vatNo = "#jform_show_price_without_vat1";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $vatYes = "#jform_show_price_without_vat0";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $showPrice = "#s2id_jform_show_price";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $showPriceSearch = "#s2id_autogen5_search";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $catalogId = "#s2id_jform_use_as_catalog";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $catalogSearch = "#s2id_autogen6_search";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $quotationNo = "#jform_quotation_mode1";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $quotationYes = "#jform_quotation_mode0";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $publishNo = "#jform_published1";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $publishYes = "#jform_published0";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $categoryFiled = "#s2id_jform_categories";

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
	public static $shopperFirstStatus =  "//tr[1]/td[7]/a";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $nameShopperGroupsFirst = "//tr[1]/td[5]/a";

	//message

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $saveSuccess = 'Item saved.';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $deleteSuccess = 'Shopper Group can not be deleted.';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $unpublishSuccess = 'successfully unpublished';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $publishSuccess = 'successfully published';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $cannotDelete = 'Shopper Group can not be deleted.';

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $searchField = '#filter_search';

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $searchButton = '//input[@value="Search"]';

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $deleteShopperSuccess = 'successfully deleted';

	/**
	 * @var string
	 * @since 3.0.3
	 */
	public static $messageMissingName = "Field required: Shopper Group Name";

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
