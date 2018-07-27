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
    /**
     * @var string
     */
    public static $URL = '/administrator/index.php?option=com_redshop&view=product&layout=listing';

    /**
     * @var array
     */
    public static $discount = ['name' => "discount_price[]"];

    /**
     * @var string
     */
    public static $priceProduct = "#product_price_44";

    /**
     * @var string
     */
    public static $quantityStart = "//*[@id=\"price_quantity_start\"]";

    /**
     * @var string
     */
    public static $quantityEnd = "#price_quantity_end";

    /**
     * @var array
     */
    public static $priceDefault = ['name' => "price[]"];

    /**
     * @var string
     */
    public static $saveButton = "//a[contains(@href,'savediscountprice')]";

    /**
     * @var string
     */
    public static $quantityStartPopup = "//td[contains(text(), 'Default Private')]/../td[2]/input";

    /**
     * @var string
     */
    public static $quantityEndPopup = "//td[contains(text(), 'Default Private')]/../td[3]/input";

    /**
     * @var string
     */
    public static $namePage = "Product Management";

//    public static $selectorPage = ".page-title";


}