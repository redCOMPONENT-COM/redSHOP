<?php
/**
 * @package     redSHOP
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class GiftCardManagerPage
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  1.4.0
 */
class GiftCardManagerPage extends AdminJ3Page
{
	//name page

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $namePageManagement = 'Gift Card Management';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $URL = '/administrator/index.php?option=com_redshop&view=giftcards';

	/**
	 * @var array
	 * @since 1.4.0
	 */
	public static $giftCardCancelButton = ['xpath' => "//button[@onclick=\"Joomla.submitbutton('giftcard.cancel');\"]"];

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $giftCardName = "//input[@id='jform_giftcard_name']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $giftCardPrice = "//input[@id='jform_giftcard_price']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $giftCardValue = "//input[@id='jform_giftcard_value']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $giftCardValidity = "//input[@id='jform_giftcard_validity']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $giftCardResultRow = "//table[@id='articleList']/tbody/tr[1]";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $firstResult = "//input[@id='cb0']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $getCartStatus = "//div[@class='btn-group']/a";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $errorValid = "//div[@id='system-message-container']/div/div";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $getGiftCard = "//input[@id='cb0']";

	//message

	/**
	 * @since 1.4.0
	 * @var string
	 */
	public static $messageSuccessUnpublish="items successfully unpublished";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $messageUnpublishSuccess="1 item successfully unpublished";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $messagePublishSuccess="1 item successfully published";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $messageDeleteSuccess = "1 item successfully deleted";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $messageInvalidName = 'Invalid field: Gift Card Name';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $messageInvalidPrice = 'Invalid field:  Gift Card Price ';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $messageInvalidGiftCart = 'Invalid field:  Gift Card Value ';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $messageInvalidCart = 'Invalid field:  Gift Card Validity';
}
