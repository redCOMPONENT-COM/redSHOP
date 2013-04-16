<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

class plgRedshop_paymentrs_payment_payer extends JPlugin
{
	public $_table_prefix = null;

	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for
	 * plugins because func_get_args ( void ) returns a copy of all passed arguments
	 * NOT references.  This causes problems with cross-referencing necessary for the
	 * observer design pattern.
	 */
	public function plgRedshop_paymentrs_payment_payer(&$subject)
	{
		// Load plugin parameters
		parent::__construct($subject);
		$this->_table_prefix = '#__redshop_';
		$this->_plugin = JPluginHelper::getPlugin('redshop_payment', 'rs_payment_payer');
		$this->_params = new JRegistry($this->_plugin->params);
	}

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
		$paymentpath = JPATH_SITE . '/plugins/redshop_payment/' . $plugin . DS . $plugin . '/extra_info.php';
		include $paymentpath;
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

		$order_id = $request['orderid'];
		$verify_status = $this->_params->get('verify_status', '');
		$invalid_status = $this->_params->get('invalid_status', '');
		$values = new stdClass;

		// Loads Payers API.
		include JPATH_SITE . '/plugins/redshop_payment/' . $element . DS . $element . '/payread_post_api.php';

		// Creates an object from Payers API.
		$postAPI = new payread_post_api;

		$postAPI->setAgent($this->_params->get("agent_id"));
		$postAPI->setKeys($this->_params->get("payer_key1"), $this->_params->get("payer_key2"));

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
