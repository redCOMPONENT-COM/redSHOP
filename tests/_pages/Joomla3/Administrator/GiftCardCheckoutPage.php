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

    /**
     * @var string
     */
	public static $pageCart = "/index.php?option=com_redshop&view=giftcard";

    /**
     * @var string
     */
	public static $reciverName = "#reciver_name";

    /**
     * @var string
     */
	public static $cartPageUrL = "index.php?option=com_redshop&view=cart";

    /**
     * @var array
     */
	public static $addressLink = ['link' => "Add address"];

    /**
     * @var string
     */
	public static $couponInput = "#coupon_input";

    /**
     * @var string
     */
	public static $couponButton = "#coupon_button";

    /**
     * @var string
     */
	public static $reciverEmail = "#reciver_email";

    /**
     * @var string
     */
	public static $messageValid = "The discount code is valid";

    /**
     * @var string
     */
	public static $messageInvalid = "The discount code is not valid";



}