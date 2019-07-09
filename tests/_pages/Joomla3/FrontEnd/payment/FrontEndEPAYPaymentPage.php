<?php
/**
 * @package     redSHOP
 * @subpackage  Page
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class Frontend2PaymentPage
 * @since 2.1.2
 */
class FrontEndEPAYPaymentPage extends FrontEndProductManagerJoomla3Page
{
	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $kotholder = '.epay_cardholder_input';

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $iconVisa = '//img[@id="ctl00_MainContent_WindowUC1_ctl00_ctl00_Logo3"]';

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $submit = '//input[@id="ctl00_MainContent_WindowUC1_ctl00_ctl00_btnSubmitForm"]';

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $kuntest = 'maerstraining test';

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $gotoPayment = '//input[@value="Go to payment"]';

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $mesOrderPlaced = 'Order placed.';

}