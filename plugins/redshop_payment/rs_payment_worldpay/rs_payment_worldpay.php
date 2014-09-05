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
/*$app = JFactory::getApplication();
$app->registerEvent( 'onPrePayment', 'plgRedshoppayment_authorize' );*/
JLoader::import('loadhelpers', JPATH_SITE . '/components/com_redshop');
JLoader::load('RedshopHelperAdminOrder');

class plgRedshop_paymentrs_payment_worldpay extends JPlugin
{
	var $_table_prefix = null;

	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for
	 * plugins because func_get_args ( void ) returns a copy of all passed arguments
	 * NOT references.  This causes problems with cross-referencing necessary for the
	 * observer design pattern.
	 */
	public function plgRedshop_paymentrs_payment_worldpay(&$subject)
	{
		// Load plugin parameters
		parent::__construct($subject);
		$this->_table_prefix = '#__redshop_';
		$this->_plugin = JPluginHelper::getPlugin('redshop_payment', 'rs_payment_worldpay');
		$this->_params = new JRegistry($this->_plugin->params);
	}

	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_worldpay')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		$app = JFactory::getApplication();
		$paymentpath = JPATH_SITE . '/plugins/redshop_payment/' . $plugin . '/' . $plugin . '/extra_info.php';
		include $paymentpath;
	}

	function onNotifyPaymentrs_payment_worldpay($element, $request)
	{
		if ($element != 'rs_payment_worldpay')
		{
			return;
		}

		$db = JFactory::getDbo();
		$request = JRequest::get('request');

		$order_id = $request['cartId'];
		$transStatus = $request['transStatus'];
		$rawAuthMessage = $request['rawAuthMessage'];
		$transId = $request['transId'];

		// get params from plugin parameters
		$verify_status = $this->_params->get("verify_status");
		$invalid_status = $this->_params->get("invalid_status");

		if ($transStatus == "Y")
		{
			// UPDATE THE ORDER STATUS to 'VALID'

			$values->order_status_code = $verify_status;
			$values->order_payment_status_code = 'Paid';
			$values->log = JText::_('COM_REDSHOP_ORDER_PLACED');
			$values->msg = JText::_('COM_REDSHOP_ORDER_PLACED');
			$values->transaction_id = $transId;
			$values->order_id = $order_id;
		}
		else
		{
			$values->order_status_code = $invalid_status;
			$values->order_payment_status_code = 'Unpaid';
			$values->log = JText::_('COM_REDSHOP_ORDER_NOT_PLACED.');
			$values->msg = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
			$msg = JText::_('COM_REDSHOP_PHPSHOP_PAYMENT_ERROR');
			$values->order_id = $order_id;
		}

		return $values;
	}

}