<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2018 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
/**
 * Class StockImagePage
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  2.4
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
	public static $fieldStockName = "#stock_amount_image_tooltip";

	/**
	 * @var array
	 */
	public static $fieldSearchStock = "#s2id_autogen1_search";

	/**
	 * @var array
	 */
	public static $chooseStock = ".select2-result-label";

	/**
	 * @var array
	 */
	public static $fieldDropStock = "#s2id_stockroom_id";

	/**
	 * @var array
	 */
	public static $fieldSearchAmount = "#s2id_autogen2_search";

	/**
	 * @var array
	 */
	public static $chooseAmount = ".select2-result-label";

	/**
	 * @var array
	 */
	public static $fieldDropAmount = "#s2id_stock_option";

	/**
	 * @var array
	 */
	public static $fieldQuantity = "#stock_quantity";

	/**
	 * @var array
	 */
	public static $search = "#filter";

}
