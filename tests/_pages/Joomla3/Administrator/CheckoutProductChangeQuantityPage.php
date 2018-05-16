<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2018 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class Checkout Product Change Quantity Page
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  2.1
 */
class CheckoutProductChangeQuantityPage extends AdminJ3Page
{
    /**
     * @var string
     */
    public static $URL = "/administrator/index.php?option=com_redshop&view=configuration";

    /**
     * @var string
     */
    public static $Cart = 'Cart / Checkout';

    /**
     * @var array
     */
    public static $changeQuantityButton = ['id' => 'quantity_text_display1-lbl'];

    /**
     * @var string
     */
    public static $cartPageUrL = "/index.php?option=com_redshop&view=cart";

    /**
     * @var string
     */
    public static $Home = 'Home';

    /**
     * @var string
     */
    public static $Product = 'Product';

    /**
     * @var array
     */
    public static $ProductTitle = ['class' => 'category_main_title'];

    /**
     * @var array
     */
    public static $pageCategoryFrontend = ['class' => 'redshopcomponent'];

    /**
     * @var array
     */
    public static $categoryTitle = ['class' => 'category_front_inside'];

    /**
     * @var array
     */
    public static $quantityField = ['id' => 'quantitybox0'];

    /**
     * @var array
     */
    public static $updateCartButton = ['xpath' => "//img[@onclick=\"document.update_cart0.task.value='update';document.update_cart0.submit();\"]"];
}