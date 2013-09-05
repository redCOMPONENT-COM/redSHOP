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
$app->registerEvent( 'onPrePayment', 'plgRedshoppayment_payson' );*/
class plgRedshop_paymentrs_payment_payson extends JPlugin
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
	public function plgRedshop_paymentrs_payment_payson(&$subject)
	{
		// Load plugin parameters
		parent::__construct($subject);
		$this->_table_prefix = '#__redshop_';
		$this->_plugin = JPluginHelper::getPlugin('redshop_payment', 'rs_payment_payson');
		$this->_params = new JRegistry($this->_plugin->params);
	}

	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_payson')
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

	function onNotifyPaymentrs_payment_payson($element, $request)
	{
		if ($element != 'rs_payment_payson')
		{
			return;
		}

		$payson_parameters = $this->getparameters('rs_payment_payson');
		$paymentinfo = $payson_parameters[0];
		$paymentparams = new JRegistry($paymentinfo->params);

		$request = JRequest::get('request');
		$Itemid = $request["Itemid"];
		$order_id = $request["RefNr"];
		$okurl = JURI::base() . "index.php?option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_payson&orderid=" . $order_id;
		$paysonref = $request["Paysonref"];

		$fee = $request["Fee"];
		$md5key = $paymentparams->get('pays_md5', '');
		$verify_status = $paymentparams->get('verify_status', '');
		$invalid_status = $paymentparams->get('invalid_status', '');
		$cancel_status = $paymentparams->get('cancel_status', '');

		// validate md5

		$strTestMD5String = htmlspecialchars($okurl . $paysonref) . $md5key;
		$strMD5Hash = md5($strTestMD5String);

		//$compare_hash1 = md5($okurl.$paysonref.$md5key);
		$compare_hash1 = $strMD5Hash;
		$compare_hash2 = $request['MD5'];
		$values->transaction_id = $paysonref;
		$values->order_id = $order_id;

		if ($compare_hash1 != $compare_hash2)
		{
			$values->order_status_code = $invalid_status;
			$values->order_payment_status_code = 'Unpaid';
			$values->log = JText::_('COM_REDSHOP_PAYSON_CHECKOUT_FAILURE.');
			$values->msg = JText::_('COM_REDSHOP_PAYSON_CHECKOUT_FAILURE');

			return $values;
		}
		else
		{
			$values->order_status_code = $verify_status;
			$values->order_payment_status_code = 'Paid';
			$values->log = JText::_('COM_REDSHOP_ORDER_PLACED');
			$values->msg = JText::_('COM_REDSHOP_ORDER_PLACED');
		}

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

	function orderPaymentNotYetUpdated($order_id, $tid)
	{
		$db = JFactory::getDBO();
		$res = false;
		$query = "SELECT COUNT(*) `qty` FROM `#__redshop_order_payment` WHERE `order_id` = '" . $db->getEscaped($order_id) . "' and order_payment_trans_id = '" . $db->getEscaped($tid) . "'";
		$db->setQuery($query);
		$order_payment = $db->loadResult();

		if ($order_payment == 0)
		{
			$res = true;
		}

		return $res;
	}
}