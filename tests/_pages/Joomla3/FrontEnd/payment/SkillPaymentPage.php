<?php
/**
 * @package     redSHOP
 * @subpackage  Page
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class SkillPaymentPage
 * @since 2.1.3
 */
class SkillPaymentPage extends UserManagerJoomla3Page
{
	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $paymentMoneyBooker = "#rs_payment_moneybooker2";

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $inputCart = "#card_number";

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $inputMonth = "#expiry_month";

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $inputYear = "#expiry_year";

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $inputCvv = "#cvv_code";

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $inputFirstName = "#first_name";

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $inputLastName = "#last_name";

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $buttonPay = "#pay_button";
}