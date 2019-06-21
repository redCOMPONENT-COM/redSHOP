<?php
/**
 * @package     redSHOP
 * @subpackage  Page
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class FrontEndEWAYPaymentPage
 * @since 2.1.2
 */
class FrontEndEWAYPaymentPage extends FrontEndProductManagerJoomla3Page
{
	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $paymentEWAY = "//div[@id='rs_payment_eway']//label//input";

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $cardName = "#order_payment_name";
	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $cardNumber = "#order_payment_number";
	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $cardCode = "#credit_card_code";
	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $selectExpireMonth = "//select[@id='order_payment_expire_month']";
	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $selectExpireYear = "//select[@id='order_payment_expire_year']";
	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $typeCard = "//input[@value='VISA']";
}