<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class plgRedshop_paymentrs_payment_dotpay extends JPlugin
{
	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_dotpay')
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

	public function onNotifyPaymentrs_payment_dotpay($element, $request)
	{
		if ($element != 'rs_payment_dotpay')
		{
			return;
		}

		$db       = JFactory::getDbo();
		$request  = JRequest::get('request');
		$id       = mysql_real_escape_string($request["id"]);
		$order_id = $request['order_id'];
		$t_id     = mysql_real_escape_string($request["t_id"]);
		$control  = mysql_real_escape_string($request['control']);
		$amount   = mysql_real_escape_string($request['amount']);
		$email    = mysql_real_escape_string($request['email']);
		$t_status = mysql_real_escape_string($request['t_status']);
		$md5      = mysql_real_escape_string($request['md5']);

		JPlugin::loadLanguage('com_redshop');

		$verify_status     = $this->params->get('verify_status', '');
		$invalid_status    = $this->params->get('invalid_status', '');
		$cancel_status     = $this->params->get('cancel_status', '');
		$dotpay_key        = $this->params->get('dotpay_pin', '');

		if (isset($request["service"]))
		{
			$service = mysql_real_escape_string($request["service"]);
		}
		else
		{
			$service = null;
		}

		if (isset($request["code"]))
		{
			$code = mysql_real_escape_string($request["code"]);
		}
		else
		{
			$code = null;
		}

		if (isset($request["username"]))
		{
			$username = mysql_real_escape_string($request["username"]);
		}
		else
		{
			$username = null;
		}

		if (isset($request["password"]))
		{
			$password = mysql_real_escape_string($request["password"]);
		}
		else
		{
			$password = null;
		}

		$obl_md5 = md5(
			"" . $dotpay_key . ":" . $id . ":" . $control . ":" . $t_id . ":" . $amount . ":" . $email
				. ":" . $service . ":" . $code . ":" . $username . ":" . $password . ":" . $t_status . ""
		);

		if ($md5 != $obl_md5)
		{
			$values->transaction_id            = '';
			$values->order_id                  = $order_id;
			$values->order_status_code         = $cancel_status;
			$values->order_payment_status_code = 'UNPAID';
			$values->log                       = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
			$values->msg                       = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
		}
		else
		{
			if ($t_status == 1)
			{
				$values->order_status_code         = $invalid_status;
				$values->order_payment_status_code = 'UNPAID';
				$values->log                       = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
				$values->msg                       = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
			}
			elseif ($t_status == 2)
			{
				$values->order_id                  = $order_id;
				$values->order_status_code         = $verify_status;
				$values->order_payment_status_code = 'PAID';
				$values->log                       = JText::_('COM_REDSHOP_ORDER_PLACED');
				$values->msg                       = JText::_('COM_REDSHOP_ORDER_PLACED');
			}
			else
			{
				$values->order_status_code         = $verify_status;
				$values->order_payment_status_code = 'PAID';
				$values->log                       = JText::_('COM_REDSHOP_ORDER_PLACED');
				$values->msg                       = JText::_('COM_REDSHOP_ORDER_PLACED');
			}
		}

		$values->transaction_id = $t_id;
		$values->order_id       = $order_id;

		return $values;
	}

	public function onCapture_Paymentrs_payment_dotpay($element, $data)
	{
		return;
	}
}
