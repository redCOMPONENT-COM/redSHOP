<?php
/**
 * @package     redSHOP
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class OrderStatusManagerPage
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  2.4
 */
class OrderStatusManagerPage extends AdminJ3Page
{
	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $URL = '/administrator/index.php?option=com_redshop&view=order_statuses';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $filterSearch = "//input[@id='filter_search']";

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $editButton = ".btn-edit-item";

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $buttonReset = "Reset";

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $orderstatusName = "#jform_order_status_name";

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $orderstatusCode = "#jform_order_status_code";

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $messageNameFieldRequired = "Field required: Name";

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $messageCodeFieldRequired = "Field required: Code";

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $messageDeleteFail = "Order Status can't be delete because it used somewhere";
}