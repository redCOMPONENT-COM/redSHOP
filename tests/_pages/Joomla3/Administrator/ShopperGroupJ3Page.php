<?php

/**
 * Shopper Groups
 */
class ShopperGroupJ3Page extends AdminJ3Page
{

    //name page
    public static $namePageManagement = 'Shopper Group Management';


    //URL
    public static $URL = '/administrator/index.php?option=com_redshop&view=shopper_group';

    public static $URLEdit = '/administrator/index.php?option=com_redshop&view=shopper_group_detail&task=edit&cid[]=';

    //Id
    public static $shopperName = "#shopper_group_name";

    public static $shopperGroupType = "#select2-chosen-1";

    public static $shopperType = "#s2id_autogen1_search";

    public static $customerType = "#select2-chosen-2";

    public static $customerTypeSearch = "#s2id_autogen2_search";

    public static $shopperGroupPortalYes = "#shopper_group_portal1-lbl";

	public static $shopperGroupPortalNo = "#shopper_group_portal0-lbl";

    public static $shippingYes = "#default_shipping1-lbl";

    public static $shippingNo = "#default_shipping0-lbl";

    public static $shippingRate = "#default_shipping_rate";


    public static $shippingCheckout = "#shopper_group_cart_checkout_itemid";

    public static $vatNo = "#show_price_without_vat0-lbl";

    public static $vatYes = "#show_price_without_vat1-lbl";

    public static $showPrice = "#s2id_show_price";

    public static $showPriceSearch = "#s2id_autogen6_search";

    public static $catalogId = "#s2id_use_as_catalog";

    public static $catalogSearch = "#s2id_autogen7_search";

    public static $quoationNo = "#shopper_group_quotation_mode0-lbl";

    public static $quotationYes = "#shopper_group_quotation_mode1-lbl";

    public static $publishNo = "#published0-lbl";

    public static $publishYes = "#published1-lbl";

    public static $categoryFiled = "#s2id_shopper_group_categories";

//    public static $categoryFill = ['xpath' => "//div[@id='s2id_shopper_group_categories']//ul/li//input"];
    
    public static  $categoryFill = "//input[@id='s2id_autogen3']";

    public static $shopperFirst = " //input[@id='cb0']";

    public static $shopperFours = "//input[@id='cb3']";

    public static $shopperFirstStatus =  "//tr[1]/td[5]/a";

    public static $nameShopperGroupsFirst = "//tr[1]/td[3]/a";

    //message
    public static $saveSuccess = 'Shopper Group Detail Saved';

    public static $deleteSuccess = 'Shopper Group can not be deleted.';

    public static $unpublishSuccess = 'Shopper Group Detail Unpublished Successfully';

    public static $publishSuccess = 'Shopper Group Detail Published Successfully';

    public static $cannotDelete = 'Shopper Group can not be deleted.';

    public function returnSearch($typeSearch)
    {
        $path = "//span[contains(text(), '" . $typeSearch . "')]";
        return $path;
    }


}