<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class plgRedshop_paymentrs_payment_payer extends JPlugin
{
	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_payer')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		$app = JFactory::getApplication();

		include JPATH_SITE . '/plugins/redshop_payment/' . $plugin . '/' . $plugin . '/extra_info.php';
	}

	/**
	 *  Plugin onNotifyPayment method with the same name as the event will be called automatically.
	 */
	public function onNotifyPaymentrs_payment_payer($element, $request)
	{
		ob_clean();

		if ($element != 'rs_payment_payer')
		{
			return false;
		}

		$order_id       = $request['orderid'];
		$verify_status  = $this->params->get('verify_status', '');
		$invalid_status = $this->params->get('invalid_status', '');
		$values         = new stdClass;

		// Loads Payers API.
		include JPATH_SITE . '/plugins/redshop_payment/' . $element . '/' . $element . '/payread_post_api.php';

		// Creates an object from Payers API.
		$postAPI = new payread_post_api;

		$postAPI->setAgent($this->params->get("agent_id"));
		$postAPI->setKeys($this->params->get("payer_key1"), $this->params->get("payer_key2"));

		if ($postAPI->is_valid_ip())
		{
			// Checks if the IP address comes from Payer else return false!
			if ($postAPI->is_valid_callback())
			{
				// Check if the keys match (the hash) else return false!
				$values->order_status_code = $verify_status;
				$values->order_payment_status_code = 'Paid';
				$values->log = JText::_('COM_REDSHOP_ORDER_PLACED');
				$values->msg = JText::_('COM_REDSHOP_ORDER_PLACED');
			}
		}
		else
		{
			$values->order_status_code = $invalid_status;
			$values->order_payment_status_code = 'Unpaid';
			$values->log = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
			$values->msg = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
		}

		$values->order_id = $order_id;

		return $values;
	}
}
