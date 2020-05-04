<?php
/**
 * @package     redSHOP
 * @subpackage  Page
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class FrontEndAjaxCartPage
 * @since 2.1.4
 */
class FrontEndAjaxCartPage extends FrontEndProductManagerJoomla3Page
{
	/**
	 * @var string
	 * @since 2.1.4
	 */
	public static $ajaxCart = "#ajax_cart_wrapper";

	/**
	 * @var string
	 * @since 2.1.4
	 */
	public static $btnContinueShopping = "//button[@name='continuecart']";

	/**
	 * @var string
	 * @since 2.1.4
	 */
	public static $btnViewCart = "//button[@name='viewcart']";

	/**
	 * @var string
	 * @since 2.1.4
	 */
	public static $tableCart = "//table[@class='table table-striped']";

	/**
	 * @var string
	 * @since 2.1.4
	 */
	public static $productOnCart = "//table[@class='table table-striped']/tbody/tr/td[1]";
}
