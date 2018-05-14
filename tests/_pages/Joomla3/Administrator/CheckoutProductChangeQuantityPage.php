<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2018 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
/**
 * Abstract Class Core J3 Page
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  2.1
 */
class CheckoutProductChangeQuantityPage extends AdminJ3Page
{
    //Frontend
    public static $URL = '/index.php?option=com_redshop';

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
    public static $AddToCart = ['class' => 'pdaddtocart_link'];

    /**
     * @var string
     */
    public static $MyCart = 'My Cart';

    /**
     * @var array
     */
    public static $quantityField = ['id' => 'quantitybox0'];

    /**
     * @var array
     */
    public static $updateCartButton = ['xpath' => "//img[@onclick=\"document.update_cart0.task.value='update';document.update_cart0.submit();\"]"];

    /**
     * @var array
     */
    public static $acceptTermCheckbox = ['id' => 'termscondition'];

    /**
     * @var array
     */
    public static $checkOutFinal = ['id' => 'checkoutfinal'];
}