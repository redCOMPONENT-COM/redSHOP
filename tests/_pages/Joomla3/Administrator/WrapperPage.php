<?php
/**
 * @package     redSHOP
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class WrapperManagePage
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  1.4.0
 */
class WrapperPage extends AdminJ3Page
{
	/**
	 * @var array
	 * @since 1.4.0
	 */
	public static $search = "#filter";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $URL = '/administrator/index.php?option=com_redshop&view=wrapper';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $nameWrapperPage = ['Wrapping'];

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $titleCreatePage = ['Wrapping:'];

	/**
	 * @var array
	 * @since 1.4.0
	 */
	public static $wrapperName = "#wrapper_name";

	/**
	 * @var array
	 * @since 1.4.0
	 */
	public static $wrapperPrice = "#wrapper_price";

	/**
	 * @var integer
	 * @since 1.4.0
	 */
	public $price;

	/**
	 * @var array
	 * @since 1.4.0
	 */
	public static $categoryID = "#s2id_autogen2";

	/**
	 * @var array
	 * @since 1.4.0
	 */
	public static $chooseCategoryID = ".select2-result-label";

	/**
	 * @var array
	 * @since 1.4.0
	 */
	public static $nameProducts = "#s2id_container_product";

	/**
	 * @var array
	 * @since 1.4.0
	 */
	public static $chooseProductID = ".select2-match";

	/**
	 * @var array
	 * @since 1.4.0
	 */
	public static $useProduct = "#wrapper_use_to_all1";

	/**
	 * @var array
	 * @since 1.4.0
	 */
	public static $wrapperImage = "#wrapper_image";

	/**
	 * @var string
	 * @since 2.1.2.2
	 */
	public static $messageCheckInvalidPrice = 'Wrapping Price not valid';
}