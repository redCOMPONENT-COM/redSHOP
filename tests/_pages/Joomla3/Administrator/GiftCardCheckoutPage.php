<?php

/**
 *
 * GiftCard checkout product at frontend
 *
 * @since  2.4
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 */
class GiftCardCheckoutPage extends AdminJ3Page
{
	public static $pageCart = "/index.php?option=com_redshop&view=giftcard";

	public static $reciverName = "#reciver_name";

	public static $cartPageUrL = "index.php?option=com_redshop&view=cart";

	public static $addressLink = ['link' => "Add address"];

	public static $couponInput = "#coupon_input";

	public static $couponButton = "#coupon_button";

	public static $reciverEmail = "#reciver_email";
	
	public static $messageValid = "The discount code is valid";
	
	public static $messageInvalid = "The discount code is not valid";



}