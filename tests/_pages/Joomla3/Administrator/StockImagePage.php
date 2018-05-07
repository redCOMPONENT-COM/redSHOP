<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
/**
 * Class StockImagePage
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  1.4
 */

class StockImagePage extends AdminJ3Page
{
    //create new
    public static $URL = '/administrator/index.php?option=com_redshop&view=stockimage';

    /**
     * @var string
     */
    public static $namePage = 'Stock Image Management';

    /**
     * @var string
     */
    public static $titleCreatePage = 'Stock Amount Image';

    /**
     * @var array
     */
    public static $fieldStockName = ['id' => 'stock_amount_image_tooltip'];

    /**
     * @var array
     */
    public static $fieldSearchStock = ['id' => 's2id_autogen1_search'];

    /**
     * @var array
     */
    public static $chooseStock = ['class' => 'select2-result-label'];

    /**
     * @var array
     */
    public static $fieldDropStock = ['id' => 's2id_stockroom_id'];

    /**
     * @var array
     */
    public static $fieldSearchAmount = ['id' => 's2id_autogen2_search'];

    /**
     * @var array
     */
    public static $chooseAmount = ['class' => 'select2-result-label'];

    /**
     * @var array
     */
    public static $fieldDropAmount = ['id' => 's2id_stock_option'];

    /**
     * @var array
     */
    public static $fieldQuantity = ['id' => 'stock_quantity'];

    /**
     * @var array
     */
    public static $search = ['id' => 'filter'];

}
