<?php

/**
 * Class PriceProductJoomla3Page
 *
 * @since  2.4
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 */
class PriceProductJoomla3Page extends AdminJ3Page
{
    public static $URL = '/administrator/index.php?option=com_redshop&view=product&layout=listing';

    public static $discount = ['name' => "discount_price[]"];

    public static $priceProduct = "#product_price_44";

    public static $quantityStart = "//*[@id=\"price_quantity_start\"]";

    public static $quantityEnd = "#price_quantity_end";

    public static $priceDefault = ['name' => "price[]"];

    public static $saveButton = "//a[contains(@href,'savediscountprice')]";

    public static $quantityStartPopup = "//td[contains(text(), 'Default Private')]/../td[2]/input";

    public static $quantityEndPopup = "//td[contains(text(), 'Default Private')]/../td[3]/input";

    public static $namePage = "Product Management";

//    public static $selectorPage = ".page-title";


}