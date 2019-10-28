<?php
/**
 * @package     redSHOP
 * @subpackage  Page
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

class StripePaymentPage extends UserManagerJoomla3Page
{

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $paymentStripe = "//div[@id='stripe']//label[1]";

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $nameIframeStripe = 'stripe_checkout_app';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $cardNumberIframe = '//input[@placeholder="Card number"]';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $dateIframe = '//input[@placeholder="MM / YY"]';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $cvcIframe = '//input[@placeholder="CVC"]';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $submitIframe = '//button[@class="Button-animationWrapper-child--primary Button"]';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $messagePopupStripe = 'failed to load.';
}