<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class WrapperManagePage
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  1.4
 */
class WrapperPage extends AdminJ3Page
{
    /**
     * @var array
     */
    public static $search = ['id' => 'filter'];
    /**
     * @var string
     */
    public static $URL = '/administrator/index.php?option=com_redshop&view=wrapper';
    /**
     * @var string
     */
    public static $nameWrapperPage = ['Wrapping'];
    /**
     * @var string
     */
    public static $titleCreatePage = ['Wrapping: '];
    /**
     * @var array
     */
    public static $wrapperName = ['id' => 'wrapper_name'];
    /**
     * @var array
     */
    public static $wrapperPrice = ['id' => 'wrapper_price'];
    /**
    * @var integer
    */
    public $price;
    /**
     * @var array
     */
    public static $categoryID = ['id' => 's2id_autogen2'];
    /**
     * @var array
     */
    public static $chooseCategoryID = ['class' => 'select2-result-label'];
    /**
     * @var array
     */
    public static $nameProducts = ['id' => 's2id_container_product'];
//    public static $nameProducts = ['class' => 'select2-choices'];

    /**
     * @var array
     */
    public static $chooseProductID = ['class' => 'select2-match'];
    /**
     * @var array
     */
    public static $useProduct = ['id' => 'wrapper_use_to_all1'];
    /**
     * @var array
     */
    public static $wrapperImage = ['id' => 'wrapper_image'];

}