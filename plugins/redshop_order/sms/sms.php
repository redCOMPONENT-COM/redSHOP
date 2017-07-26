<?php
/**
 * @package     RedShop
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

// Import library dependencies
jimport('joomla.plugin.plugin');
JLoader::import('redshop.library');

/**
 * Plugins redSHOP SMS
 *
 * @since  1.0
 */
class PlgRedshop_OrderSms extends JPlugin
{
	/**
	 * Constructor - note in Joomla 2.5 PHP4.x is no longer supported so we can use this.
	 *
	 * @param   object  $subject  The object to observe
	 * @param   array   $config   An array that holds the plugin configuration
	 */
	public function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

	/**
	 * store alert function
	 *
	 * @param   object  $data       Order data
	 * @param   string  $newStatus  Order status
	 *
	 * @return void
	 */
	public function onAfterOrderStatusUpdate($data, $newStatus)
	{
		$statusList = $this->params->get('status', '');

		if (!in_array($newStatus, $statusList) || $data->order_status == $newStatus)
		{
			return;
		}

		$apiKey    = $this->params->get('api_key', '');
		$apiSecret = $this->params->get('api_secret', '');
		$type      = $this->params->get('type', '6');
		$content   = $this->params->get('content', '');
		$shopName  = RedShop::getConfig()->get('SHOP_NAME');
		$billing   = RedshopHelperOrder::getOrderBillingUserInfo($data->order_id);
		$status    = RedshopHelperOrder::getOrderStatusTitle($newStatus);

		if (strpos($content, "{order_id}") !== false)
		{
			$content = str_replace("{order_id}", $data->order_id, $content);
		}

		if (strpos($content, "{shop_name}") !== false)
		{
			$content = str_replace("{shop_name}", $shopName, $content);
		}

		if (strpos($content, "{status}") !== false)
		{
			$content = str_replace("{status}", $status, $content);
		}

		$content = urlencode($content);

		$apiUrl = "http://rest.esms.vn/MainService.svc/json/SendMultipleMessage_V4_get?Phone=" . $billing->phone . "&ApiKey=" . $apiKey . "&SecretKey=" . $apiSecret . "&Content=" . $content . "&SmsType=" . $type;

		$curl = curl_init($apiUrl);
		curl_setopt($curl, CURLOPT_FAILONERROR, true);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$json = curl_exec($curl);
		$result = json_decode($json, true);

		if ($result['CodeResult'] == 100)
		{
			JFactory::getApplication()->enqueueMessage(JText::_('PLG_ORDER_SMS_SEND_SMS_SUCCESSFUL'));
		}
		else
		{
			JFactory::getApplication()->enqueueMessage($result['ErrorMessage']);
		}
	}
}
