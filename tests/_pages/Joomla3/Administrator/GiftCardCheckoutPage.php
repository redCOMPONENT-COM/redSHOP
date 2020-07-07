<?php
/**
 * @package     redSHOP
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class GiftCardCheckoutPage
 * @since 1.4.0
 */
class GiftCardCheckoutPage extends AdminJ3Page
{
	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $pageCart = "/index.php?option=com_redshop&view=giftcard";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $reciverName = "#reciver_name";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $cartPageUrL = "index.php?option=com_redshop&view=cart";

	/**
	 * @var array
	 * @since 1.4.0
	 */
	public static $addressLink = ['link' => "Add address"];

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $couponInput = "#coupon_input";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $couponButton = "#coupon_button";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $reciverEmail = "#reciver_email";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $messageValid = "The discount code is valid";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $messageInvalid = "The discount code is not valid";
}