<?php
/**
 * @package     redSHOP
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class StockImagePage
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  1.4.0
 */
class StockImagePage extends AdminJ3Page
{
	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $URL = '/administrator/index.php?option=com_redshop&view=stockimage';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $namePage = 'Stock Image Management';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $titleCreatePage = 'Stock Amount Image';

	/**
	 * @var array
	 * @since 1.4.0
	 */
	public static $fieldStockName = "#stock_amount_image_tooltip";

	/**
	 * @var array
	 * @since 1.4.0
	 */
	public static $fieldSearchStock = "#s2id_autogen1_search";

	/**
	 * @var array
	 * @since 1.4.0
	 */
	public static $chooseStock = ".select2-result-label";

	/**
	 * @var array
	 * @since 1.4.0
	 */
	public static $fieldDropStock = "#s2id_stockroom_id";

	/**
	 * @var array
	 * @since 1.4.0
	 */
	public static $fieldSearchAmount = "#s2id_autogen2_search";

	/**
	 * @var array
	 * @since 1.4.0
	 */
	public static $chooseAmount = ".select2-result-label";

	/**
	 * @var array
	 * @since 1.4.0
	 */
	public static $fieldDropAmount = "#s2id_stock_option";

	/**
	 * @var array
	 * @since 1.4.0
	 */
	public static $fieldQuantity = "#stock_quantity";

	/**
	 * @var array
	 * @since 1.4.0
	 */
	public static $search = "#filter";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $iconSearch = "//input[@value=\"Search\"]";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $messageDelete = "Stock Image detail deleted successfully";
}
