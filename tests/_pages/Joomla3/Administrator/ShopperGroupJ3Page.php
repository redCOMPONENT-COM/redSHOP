<?php

/**
 * Created by PhpStorm.
 * User: nhung nguyen
 * Date: 7/21/2017
 * Time: 11:24 AM
 */
class ShopperGroupJ3Page
{

    //name page
    public static $namePageManagement = 'Shopper Group Management';


    //URL
    public static $URL = '/administrator/index.php?option=com_redshop&view=shopper_group';

    public static $URLNew = '/administrator/index.php?option=com_redshop&view=shopper_group_detail&layout=edit';

    public static $URLEdit = '/administrator/index.php?option=com_redshop&view=shopper_group_detail&task=edit&cid[]=';

    //Id
    public static $shopperName = ['id' => 'shopper_group_name'];

    public static $shopperGroupType = ['id' => 'select2-chosen-1'];

    public static $shopperType = ['id' => 's2id_autogen1_search'];

    public static $customerType = ['id' => 'select2-chosen-2'];

    public static $customerTypeSearch = ['id' => 's2id_autogen2_search'];

    public static $shopperGroupPortalYes = ['id' => 'shopper_group_portal1-lbl'];

    public static $shippingYes = ['id' => 'default_shipping1-lbl'];

    public static $shippingNo = ['id' => 'default_shipping0-lbl'];

    public static $shippingRate = ['id' => 'default_shipping_rate'];


    public static $shippingCheckout = ['id' => 'shopper_group_cart_checkout_itemid'];

    public static $vatNo = ['id' => 'show_price_without_vat0-lbl'];

    public static $vatYes = ['id' => 'show_price_without_vat1-lbl'];

    public static $showPrice = ['id' => 's2id_show_price'];

    public static $showPriceSearch = ['id' => 's2id_autogen6_search'];

    public static $catalogId = ['id' => 's2id_use_as_catalog'];

    public static $catalogSearch = ['id' => 's2id_autogen7_search'];

    public static $quoationNo = ['id' => 'shopper_group_quotation_mode0-lbl'];

    public static $quotationYes = ['id' => 'shopper_group_quotation_mode1-lbl'];

    public static $publishNo = ['id' => 'published0-lbl'];

    public static $publishYes = ['id' => 'published1-lbl'];

    public static $categoryFiled = ['id' => 's2id_shopper_group_categories'];

    public static $categoryFill = ['xpath' => "//div[@id='s2id_shopper_group_categories']//ul/li//input"];

    public static $shopperFirst = " //div[@class='table-responsive']/table/tbody/tr/td[2]/input[@id='cb0']";

    public static $shopperFours = " //div[@class='table-responsive']/table/tbody/tr/td[2]/input[@id='cb3']";

    public static $shopperFirstStatus = ['xpath' => "//form[@id='adminForm']/div[1]/div/table/tbody/tr[1]/td[5]/a"];

    public static $nameShopperGroupsFirst = ['xpath' => "//form[@id='adminForm']/div[1]/div/table/tbody/tr[1]/td[3]/a"];

    public static $xpathMessageSuccess = "//div[@id='system-message']/div/div/p";



    //selector

    public static $selectorSuccess = '.alert-success';

    public static $selectorError = '.alert-danger';

    public static $selectorNamePage = '.page-title';

    public static $selectorNameShopper = '.shopper_group_name';

    //button

    public static $newButton = "New";

    public static $saveButton = "Save";

    public static $unpublishButton = "Unpublish";

    public static $publishButton = "Publish";

    public static $saveCloseButton = "Save & Close";

    public static $deleteButton = "Delete";

    public static $editButton = "Edit";

    public static $saveNewButton = "Save & New";

    public static $cancelButton = "Cancel";

    public static $checkInButton = "Check-in";

    public static $closeButton = 'Close';

    //message
    public static $saveSuccess = 'Shopper Group Detail Saved';

    public static $deleteSuccess = 'Shopper Group can not be deleted.';

    public static $unpublishSuccess = 'Shopper Group Detail Unpublished Successfully';

    public static $publishSuccess = 'Shopper Group Detail Published Successfully';

    public static $cannotDelete = 'Shopper Group can not be deleted.';

    public function returnSearch($typeSearch)
    {
        $path = ['xpath' => "//span[contains(text(), '" . $typeSearch . "')]"];
        return $path;
    }


}