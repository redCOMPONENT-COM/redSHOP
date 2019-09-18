<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class WishListPage
 * @since 2.1.3
 */
class WishListPage extends FrontEndProductManagerJoomla3Page
{
    /**
     * @var string
     * @since 2.1.3
     */
    public static $wishListPageURL = '/index.php?option=com_redshop&view=wishlist&layout=viewwishlist';

    /**
     * @var string
     * @since 2.1.3
     */
    public static $iframeWishListName = 'wishlist-iframe';

    /**
     * @var string
     * @since 2.1.3
     */
    public static $iframeWishList = '//iframe[@name="wishlist-iframe"]';

    /**
     * @var string
     * @since 2.1.3
     */
    public static $addToWishListLogin = '//div[@class = "wishlist_link"]/a';

    /**
     * @var string
     * @since 2.1.3
     */
    public static $addToWishListNoLogin = '//div[@class = "wishlist_link"]/form/a';

    /**
     * @var string
     * @since 2.1.3
     */
    public static $addToWishList = '//input[@value ="Add to Wishlist"]';

    /**
     * @var string
     * @since 2.1.3
     */
    public static $checkNewWishList = '//input[@id="chkNewwishlist"]';

    /**
     * @var string
     * @since 2.1.3
     */
    public static $wishListNameField = "#txtWishlistname";

    /**
     * @var string
     * @since 2.1.3
     */
    public static $saveWishListButton = '//input[@value="Save Wishlist"]';

    /**
     * @var string
     * @since 2.1.3
     */
    public static $removeOnWishList = '//a[text()="Remove Product"]';

    /**
     * @var string
     * @since 2.1.3
     */
    public static $messageAddWishListSuccess = 'Product Added To Wishlist';

    /**
     * @var string
     * @since 2.1.3
     */
    public static $messageAddWishListSuccessPopup = "Product added to wishlist successfully";

    /**
     * @var string
     * @since 2.1.3
     */
    public static $messageRemoveProductWishList = 'Product Deleted From Wishlist Successfully';

    /**
     * @var string
     * @since 2.1.3
     */
    public static $selectorAddWishListSuccess = '.wishlistmsg';

    /**
     * @var string
     * @since 2.1.3
     */
    public static $productTitle = '.product_title';

    /**
     * Function to get Path in Product Detail
     *
     * @param String $productName Name of the product
     *
     * @return string
     * @since 2.1.3
     */
    public function productTitle($productName)
    {
        $xpath = "//h1[text()='" . $productName . "']";

        return $xpath;
    }

    /**
     * @param $wishListName
     * @return string
     * @since 2.1.3
     */
    public function wishListName($wishListName)
    {
        $path = "//a[text() = '" . $wishListName . "']";

        return $path;
    }
}