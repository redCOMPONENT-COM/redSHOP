<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class GiftCardManagerPage
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  1.4
 */
class GiftCardManagerPage extends AdminJ3Page
{
	/**
	 * @var string
	 */
	public static $namePageManagement = 'Gift Card Management';

	/**
	 * @var string
	 */
	public static $URL = '/administrator/index.php?option=com_redshop&view=giftcards';

	/**
	 * @var string
	 */
	public static $URLNew = '/administrator/index.php?option=com_redshop&view=giftcard&layout=edit';

	/**
	 * @var string
	 */
	public static $URLEdit = '/administrator/index.php?option=com_redshop&view=giftcard&layout=edit&giftcard_id=';

	/**
	 * @var string
	 */
	public static $giftCardName = "//input[@id='jform_giftcard_name']";

	/**
	 * @var string
	 */
	public static $giftCardPrice = "//input[@id='jform_giftcard_price']";

	/**
	 * @var string
	 */
	public static $giftCardValue = "//input[@id='jform_giftcard_value']";

	/**
	 * @var string
	 */
	public static $giftCardValidity = "//input[@id='jform_giftcard_validity']";

	/**
	 * @var array
	 */
	public static $statePathGift = ['xpath'=>"//div[@class='btn-group']/a"];

	/**
	 * @var string
	 */
	public static $giftCardResultRow = "//table[@id='articleList']/tbody/tr[1]";

	/**
	 * @var string
	 */
	public static $firstResult = "//input[@id='cb0']";

	/**
	 * @var array
	 */
	public static $errorValid = ['xpath' => "//div[@id='system-message-container']/div/div"];

	/**
	 * @var array
	 */
	public static $getGiftCard = ['xpath' => "//input[@id='cb0']"];

	/**
	 * @var array
	 */
	public static $giftCardId = ['xpath' => "//tr[@class='row0']//td[9]"];

	/**
	 * @var string
	 */
	public static $messageSuccessUnpublish = "items successfully unpublished";

	/**
	 * @var string
	 */
	public static $messageUnpublishSuccess = "1 item successfully unpublished";

	/**
	 * @var string
	 */
	public static $messagePublishSuccess = "1 item successfully published";

	/**
	 * @var string
	 */
	public static $messageDeleteSuccess = "1 item successfully deleted";

	/**
	 * @var string
	 */
	public static $messageInvalidName = 'Invalid field: Gift Card Name';

	/**
	 * @var string
	 */
	public static $messageInvalidPrice = 'Invalid field:  Gift Card Price ';

	/**
	 * @var string
	 */
	public static $messageInvalidGiftCart = 'Invalid field:  Gift Card Value ';

	/**
	 * @var string
	 */
	public static $messageInvalidCart = 'Invalid field:  Gift Card Validity';
}
