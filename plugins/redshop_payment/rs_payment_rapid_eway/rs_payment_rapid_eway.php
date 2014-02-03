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
class plgRedshop_paymentrs_payment_rapid_eway extends JPlugin
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
	public function plgRedshop_paymentrs_payment_rapid_eway(&$subject)
	{
		// Load plugin parameters
		parent::__construct($subject);
		$this->_table_prefix = '#__redshop_';
		$this->_plugin = JPluginHelper::getPlugin('redshop_payment', 'rs_payment_rapid_eway');
		$this->_params = new JRegistry($this->_plugin->params);
	}

	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_rapid_eway')
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

	function onNotifyPaymentrs_payment_rapid_eway($element, $request)
	{
		if ($element != 'rs_payment_rapid_eway')
		{
			return;
		}

		$user = JFActory::getUser();
		$user_id = $user->id;
		// Get Plugin params
		$verify_status = $this->_params->get('verify_status', '');
		$invalid_status = $this->_params->get('invalid_status', '');
		$auth_type = $this->_params->get('auth_type', '');
		$eWAYusername = $this->_params->get("username");
		$eWAYpassword = $this->_params->get("password");
		$test_mode = $this->_params->get("test_mode");

		$AccessCode = $request["AccessCode"];
		$api_path = JPATH_SITE . '/plugins/redshop_payment/' . $element . '/' . $element . '/Rapid.php';
		include $api_path;
		$service = new RapidAPI;
		//Call RapidAPI to get the result
		$service->setTestMode($test_mode);
		$service->getAuthorizeData($eWAYusername, $eWAYpassword);
		$req = new GetAccessCodeResultRequest;
		$req->AccessCode = $AccessCode;
		$result = $service->GetAccessCodeResult($req);
		$order_id = $request['orderid'];
		//Check if any error returns
		if (isset($result->Errors))
		{
			//Get Error Messages from Error Code. Error Code Mappings are in the Config.ini file
			$ErrorArray = explode(",", $result->Errors);
			$lblError = "";

			foreach ($ErrorArray as $error)
			{
				$lblError .= $service->APIConfig[$error] . "<br>";
			}
		}

		$values = new stdClass;

		if (isset($lblError) && $response->ResponseCode != 00)
		{
			$values->order_status_code = $invalid_status;
			$values->order_payment_status_code = 'Unpaid';

			if ($lblError != "")
			{
				$values->log = $lblError;
				$values->msg = $lblError;
			}
			else
			{
				$values->log = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
				$values->msg = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
			}
		}
		else
		{
			$tid = $result->TransactionID;
			$transaction_id = $tid;
			$values->order_status_code = $verify_status;
			$values->order_payment_status_code = 'Paid';
			$values->log = JText::_('COM_REDSHOP_ORDER_PLACED');
			$values->msg = JText::_('COM_REDSHOP_ORDER_PLACED');
		}

		$values->transaction_id = $tid;
		$values->order_id = $order_id;

		return $values;
	}

	function orderPaymentNotYetUpdated($dbConn, $order_id, $tid)
	{
		$db = JFactory::getDbo();
		$res = false;
		$query = "SELECT COUNT(*) FROM " . $this->_table_prefix . "order_payment WHERE `order_id` = '" . $db->getEscaped($order_id) . "' and order_payment_trans_id = '" . $db->getEscaped($tid) . "'";
		$db->setQuery($query);
		$order_payment = $db->loadResult();

		if ($order_payment == 0)
		{
			$res = true;
		}

		return $res;
	}

	function onCapture_Paymentrs_payment_rapid_eway($element, $data)
	{
		return;
	}
}
