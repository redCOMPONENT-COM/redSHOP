<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license   GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 *            Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.plugin.plugin');
//$mainframe =& JFactory::getApplication();
//$mainframe->registerEvent( 'onPrePayment', 'plgRedshoprs_payment_bbs' );
require_once JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'order.php';
class plgredshop_paymentrs_payment_bbs extends JPlugin
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
	function plgredshop_paymentrs_payment_bbs(&$subject)
	{
		// load plugin parameters
		parent::__construct($subject);
		$this->_table_prefix = '#__redshop_';
		$this->_plugin = JPluginHelper::getPlugin('redshop_payment', 'rs_payment_bbs');
		$this->_params = new JRegistry($this->_plugin->params);

	}

	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_bbs')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		$mainframe =& JFactory::getApplication();
		$paymentpath = JPATH_SITE . DS . 'plugins' . DS . 'redshop_payment' . DS . $plugin . DS . $plugin . DS . 'extra_info.php';
		include($paymentpath);
	}

	function onNotifyPaymentrs_payment_bbs($element, $request)
	{
		if ($element != 'rs_payment_bbs')
		{
			return;
		}

		$db = jFactory::getDBO();
		$request = JRequest::get('request');
		$order_id = $request['orderid'];
		JPlugin::loadLanguage('com_redshop');

		$amazon_parameters = $this->getparameters('rs_payment_bbs');
		$paymentinfo = $amazon_parameters[0];
		$paymentparams = new JRegistry($paymentinfo->params);

		$access_id = $paymentparams->get('access_id', '');
		$token_id = $paymentparams->get('token_id', '');
		$is_test = $paymentparams->get('is_test', '');
		$verify_status = $paymentparams->get('verify_status', '');
		$invalid_status = $paymentparams->get('invalid_status', '');
		$auth_type = $paymentparams->get('auth_type', '');

		if ($is_test == "TRUE")
		{
			$bbsurl = "https://epayment-test.bbs.no/Netaxept/Process.aspx?";
		}
		else
		{
			$bbsurl = "https://epayment.bbs.no/Netaxept/Process.aspx?";
		}

		$bbsurl .= "merchantId=" . urlencode($access_id) . "&token=" . urlencode($token_id) . "&transactionId=" . $request["transactionId"] . "&operation=" . $auth_type;
		$data = $bbsurl;
		$ch = curl_init($data);
		// 	Execute
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$data = curl_exec($ch);
		$xml = new SimpleXMLElement($data);

		$AUTH_Responsecode = isset($xml->ResponseCode) ? $xml->ResponseCode : $xml->Result->ResponseCode;

		$BBS_msg = isset($xml->Result->ResponseText) ? $xml->Result->ResponseText : $BBS_msg;

		require_once JPATH_BASE . DS . 'administrator' . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'order.php';
//	 	require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_redshop'.DS.'helpers'.DS.'redshop.cfg.php';
		$objOrder = new order_functions;

		if (strtoupper($AUTH_Responsecode) == 'OK')
		{
			$tid = isset($xml->Result->transactionid) ? $xml->Result->transactionid : $request["transactionId"];

			if ($this->orderPaymentNotYetUpdated($db, $order_id, $tid))
			{
				$transaction_id = $tid;
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

		$values->transaction_id = $transaction_id;
		$values->order_id = $order_id;

		return $values;
	}

	function getparameters($payment)
	{
		$db = JFactory::getDBO();
		$sql = "SELECT * FROM #__extensions WHERE `element`='" . $payment . "'";
		$db->setQuery($sql);
		$params = $db->loadObjectList();

		return $params;
	}

	function orderPaymentNotYetUpdated($dbConn, $order_id, $tid)
	{
		$db = JFactory::getDBO();
		$res = false;
		$query = "SELECT COUNT(*) `qty` FROM " . $this->_table_prefix . "order_payment WHERE `order_id` = '" . $db->getEscaped($order_id) . "' and order_payment_trans_id = '" . $db->getEscaped($tid) . "'";
		$db->SetQuery($query);
		$order_payment = $db->loadResult();

		if ($order_payment == 0)
		{
			$res = true;
		}

		return $res;
	}

	function onCapture_Paymentrs_payment_bbs($element, $data)
	{
		if ($element != 'rs_payment_bbs')
		{
			return;
		}

		$order_id = $data['order_id'];
		require_once JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'order.php';
		$objOrder = new order_functions;
		$db = JFactory::getDBO();

		if ($this->_params->get("is_test") == "TRUE")
		{
			$bbsurl = "https://epayment-test.bbs.no/Netaxept/Process.aspx?";
			$bbsQueryurl = "https://epayment-test.bbs.no/Netaxept/Query.aspx?";
		}
		else
		{
			$bbsurl = "https://epayment.bbs.no/Netaxept/Process.aspx?";
			$bbsQueryurl = "https://epayment.bbs.no/Netaxept/Query.aspx?";
		}

		$bbsQueryurl .= "merchantId=" . urlencode($this->_params->get("access_id")) . "&token=" . urlencode($this->_params->get("token_id")) . "&transactionId=" . $data['order_transactionid'];

		// 	Create a curl handle to a non-existing location
		$ch = curl_init($bbsQueryurl);

		// 	Execute
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$Query_data = curl_exec($ch);

		$Query_data = new SimpleXMLElement($Query_data);

		$data = $bbsurl . "merchantId=" . urlencode($this->_params->get("access_id")) . "&token=" . urlencode($this->_params->get("token_id")) . "&transactionId=" . $data['order_transactionid'] . "&transactionAmount=" . $Query_data->OrderInformation->Total . "&operation=CAPTURE";

		// 	Create a curl handle to a non-existing location
		$ch = curl_init($data);
		// 	Execute
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$CAPT_data = curl_exec($ch);

		$CAPT_xml = new SimpleXMLElement($CAPT_data);

		$CAPT_Responsecode = $CAPT_xml->ResponseCode;

		$BBS_msg = isset($CAPT_xml->Result->ResponseText) ? $CAPT_xml->Result->ResponseText : $BBS_msg;

		if ($CAPT_Responsecode == 'OK')
		{
			$values->responsestatus = 'Success';
			$message = $BBS_msg ? $BBS_msg : JText::_('COM_REDSHOP_TRANSACTION_APPROVED');
		}
		else
		{
			$values->responsestatus = 'Fail';
			$message = $BBS_msg ? $BBS_msg : JText::_('COM_REDSHOP_TRANSACTION_DECLINE');
		}

		$values->message = $message;

		return $values;
	}

}