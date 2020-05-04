<?php
/**
 * @package     redSHOP
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class PAYMILLPaymentPage
 * @since 2.1.3
 */
class PAYMILLPaymentPage
{
	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $paymentPaymill = "//input[@value='rs_payment_paymill']";

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $inputCart = "#card-number";

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $inputExpiry = "#card-expiry";

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $holderName = "//input[@class='card-holdername span12']";

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $inputCvv = "//input[@class='card-cvc span12']";

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $buttonPay = '#paymill-submit-button';
}