<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

require_once JPATH_SITE . '/plugins/redshop_payment/rs_payment_authorize_dpm/rs_payment_authorize_dpm/authorize_lib/AuthorizeNet.php';
require_once JPATH_SITE . '/plugins/redshop_payment/rs_payment_authorize_dpm/rs_payment_authorize_dpm/authorize_lib/AuthorizeNet.php';
require_once JPATH_SITE . '/plugins/redshop_payment/rs_payment_authorize_dpm/rs_payment_authorize_dpm/authorize_lib/AuthorizeNet.php';

class plgRedshop_paymentrs_payment_authorize_dpm extends JPlugin
{
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_authorize_dpm')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		$request = JRequest::get('request');

		if ($request['stap'] == 2)
		{
			$this->authorizeData($element, $data);
		}

		$app = JFactory::getApplication();
		include JPATH_SITE . '/plugins/redshop_payment/' . $plugin . '/' . $plugin . '/extra_info.php';
	}

	public function authorizeData($element, $data)
	{
		if ($element != 'rs_payment_authorize_dpm')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		$app      = JFactory::getApplication();
		$Itemid   = JRequest::getVar('Itemid');

		$trans_id = $this->params->get("transaction_id");
		$is_test  = $this->params->get("is_test");

		// Where the user will end up.
		$redirect_url = JURI::base()
			. "index.php?option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_authorize_dpm&Itemid=$Itemid&orderid="
			. $data['order_id'];
		$api_login_id = $this->params->get("access_id");

		// Your MD5 Setting
		$md5_setting = $this->params->get("md5_key");
		$response    = new AuthorizeNetSIM($api_login_id, $md5_setting);

		if ($response->isAuthorizeNet())
		{
			if ($response->approved)
			{
				// Do your processing here.
				$redirect_url .= '&response_code=1&transaction_id=' . $response->transaction_id;
			}
			else
			{
				$redirect_url .= '&response_code=' . $response->response_code . '&response_reason_text=' . $response->response_reason_text;
			}
		}
		else
		{
			$redirect_url .= '&response_code=' . $response->response_code . '&response_reason_text=' . $response->response_reason_text;
		}

		echo AuthorizeNetDPM::getRelayResponseSnippet($redirect_url);
	}

	public function onNotifyPaymentrs_payment_authorize_dpm($element, $request)
	{
		if ($element != 'rs_payment_authorize_dpm')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		$db      = JFactory::getDbo();
		$request = JRequest::get('request');
		$Itemid  = $request["Itemid"];
		$user    = JFActory::getUser();
		$user_id = $user->id;

		// Result Response
		$tid             = $request['transaction_id'];
		$response_code   = htmlentities($request['response_code']);
		$response_reason = htmlentities($request['response_reason_text']);
		$order_id        = $request["orderid"];

		JPlugin::loadLanguage('com_redshop');

		$verify_status  = $this->params->get('verify_status', '');
		$invalid_status = $this->params->get('invalid_status', '');
		$cancel_status  = $this->params->get('cancel_status', '');

		if (isset($tid) && $response_code == 1)
		{
			$values->order_status_code = $verify_status;
			$values->order_payment_status_code = 'Paid';
			$values->log = JTEXT::_('COM_REDSHOP_ORDER_PLACED');
			$values->msg = JTEXT::_('COM_REDSHOP_ORDER_PLACED');
		}
		else
		{
			$values->order_status_code = $invalid_status;
			$values->order_payment_status_code = 'Unpaid';
			$values->log = $response_reason;
			$values->msg = $response_reason;
		}

		$values->transaction_id = $tid;
		$values->order_id       = $order_id;

		return $values;
	}
}
