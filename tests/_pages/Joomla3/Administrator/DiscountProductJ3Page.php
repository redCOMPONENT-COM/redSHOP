<?php


class DiscountProductJ3Page extends AdminJ3Page
{
    //name page
    public static $namePage = "Product Discount Management";

    public static $namePageDetail = "Discount";

    public static $URL = '/administrator/index.php?option=com_redshop&view=discount&layout=product';

    public static $productPrice = "#amount";

    public static $discountAmount = "#discount_amount";

    public static $startDate = "start_date";

    public static $endDate = "end_date";


    //Message when change success
    public static $messageUnpublishSuccess = "Discount Detail UnPublished Successfully";

    public static $messagePublishSuccess = "Discount Detail Published Successfully";

    public static $messageDeleteSuccess = "Discount Detail Deleted Successfully";

    public static $messageSaveDiscountSuccess = "Discount Detail Saved";

    public static $namePageDiscount = "Discount Page New";

    //selector
    public static $selectorSuccess = '.alert-success';

    public static $pageTitle = '.page-title';

    //page discount detail

    public static $condition = ['xpath' => '//div[@id="s2id_condition"]//a'];

    public static $conditionSearch = ['id' => "s2id_autogen1_search"];

    public static $discountType = ['xpath' => '//div[@id="s2id_discount_type"]//a'];

    public static $discountTypeSearch = ['id' => "s2id_autogen2_search"];

    public static $category = ['xpath' => "//div[@id='s2id_category_ids']//ul"];

    public static $categoryInput = ['id'=>'s2id_autogen3'];

    public static $shopperGroup = ['xpath' => "//div[@id='s2id_shopper_group_id']"];

    public static $shopperGroupInput = ['id' => "s2id_autogen4"];

    public function returnType($condition)
    {
        $path = "//span[contains(text(), '" . $condition . "')]";
        return $path;
    }


}