<?php

/**
 * Class ShopperGroupManagerJ3Page
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  2.4
 */
class ShopperGroupJ3Page extends AdminJ3Page
{

    //name page
    /**
     * @var string
     */
    public static $namePageManagement = 'Shopper Group Management';

    //URL

    /**
     * @var string
     */
    public static $URL = '/administrator/index.php?option=com_redshop&view=shopper_group';

    /**
     * @var string
     */
    public static $URLEdit = '/administrator/index.php?option=com_redshop&view=shopper_group_detail&task=edit&cid[]=';

    //Id

    /**
     * @var string
     */
    public static $shopperName = "#shopper_group_name";

    /**
     * @var string
     */
    public static $shopperGroupType = "#select2-chosen-1";

    /**
     * @var string
     */
    public static $shopperType = "#s2id_autogen1_search";

    /**
     * @var string
     */
    public static $customerType = "#select2-chosen-2";

    /**
     * @var string
     */
    public static $customerTypeSearch = "#s2id_autogen2_search";

    /**
     * @var string
     */
    public static $shopperGroupPortalYes = "#shopper_group_portal_1-lbl";

    /**
     * @var string
     */
    public static $shopperGroupPortalNo = "#shopper_group_portal_0-lbl";

    /**
     * @var string
     */
    public static $shippingYes = "#default_shipping_1-lbl";

    /**
     * @var string
     */
    public static $shippingNo = "#default_shipping_0-lbl";

    /**
     * @var string
     */
    public static $shippingRate = "#default_shipping_rate";

    /**
     * @var string
     */
    public static $shippingCheckout = "#shopper_group_cart_checkout_itemid";

    /**
     * @var string
     */
    public static $vatNo = "#show_price_without_vat_0-lbl";

    /**
     * @var string
     */
    public static $vatYes = "#show_price_without_vat_1-lbl";

    /**
     * @var string
     */
    public static $showPrice = "#s2id_show_price";

    /**
     * @var string
     */
    public static $showPriceSearch = "#s2id_autogen6_search";

    /**
     * @var string
     */
    public static $catalogId = "#s2id_use_as_catalog";

    /**
     * @var string
     */
    public static $catalogSearch = "#s2id_autogen7_search";

    /**
     * @var string
     */
    public static $quoationNo = "#shopper_group_quotation_mode_0-lbl";

    /**
     * @var string
     */
    public static $quotationYes = "#shopper_group_quotation_mode_1-lbl";

    /**
     * @var string
     */
    public static $publishNo = "#published_0-lbl";

    /**
     * @var string
     */
    public static $publishYes = "#published_1-lbl";

    /**
     * @var string
     */
    public static $categoryFiled = "#s2id_shopper_group_categories";

//    public static $categoryFill = ['xpath' => "//div[@id='s2id_shopper_group_categories']//ul/li//input"];

    /**
     * @var string
     */
    public static  $categoryFill = "//input[@id='s2id_autogen3']";

    /**
     * @var string
     */
    public static $shopperFirst = " //input[@id='cb0']";

    /**
     * @var string
     */
    public static $shopperFours = "//input[@id='cb3']";

    /**
     * @var string
     */
    public static $shopperFirstStatus =  "//tr[1]/td[5]/a";

    /**
     * @var string
     */
    public static $nameShopperGroupsFirst = "//tr[1]/td[3]/a";

    //message

    /**
     * @var string
     */
    public static $saveSuccess = 'Shopper Group Detail Saved';

    /**
     * @var string
     */
    public static $deleteSuccess = 'Shopper Group can not be deleted.';

    /**
     * @var string
     */
    public static $unpublishSuccess = 'Shopper Group Detail Unpublished Successfully';

    /**
     * @var string
     */
    public static $publishSuccess = 'Shopper Group Detail Published Successfully';

    /**
     * @var string
     */
    public static $cannotDelete = 'Shopper Group can not be deleted.';

    /**
     * Function to get the path for Search Shopper Group
     *
     * @param String $typeSearch in Shopper Group Name
     *
     * @return string
     */
    public function returnSearch($typeSearch)
    {
        $path = "//span[contains(text(), '" . $typeSearch . "')]";
        return $path;
    }


}