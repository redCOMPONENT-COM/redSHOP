<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Class PlgRedshop_PaymentDibsDx
 *
 * @since  1.5
 */
class PlgRedshop_PaymentDibsDx extends JPlugin
{
	/**
	 * Constructor
	 *
	 * @param   object  &$subject  The object to observe
	 * @param   array   $config    An optional associative array of configuration settings.
	 * Recognized key values include 'name', 'group', 'params', 'language'
	 * (this list is not meant to be comprehensive).
	 */
	public function __construct(&$subject, $config = array())
	{
		JPlugin::loadLanguage('plg_redshop_payment_dibsdx');
		parent::__construct($subject, $config);
	}

	/**
	 * onPrePayment
	 *
	 * @param   string  $element  Name element
	 * @param   array   $data     Request data
	 *
	 * @return  void
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'dibsdx')
		{
			return;
		}

		$app = JFactory::getApplication();

		include JPATH_SITE . '/plugins/redshop_payment/' . $element . '/' . $element . '/extra_info.php';
	}

	/**
	 * onNotifyPaymentdibsdx
	 *
	 * @param   string  $element  Name element
	 * @param   array   $request  Request data
	 *
	 * @return  stdClass|void
	 */
	public function onNotifyPaymentdibsdx($element, $request)
	{
		if ($element != 'dibsdx')
		{
			return;
		}

		$api_path = JPATH_SITE . '/plugins/redshop_payment/' . $element . '/' . $element . '/dibs_hmac.php';
		include $api_path;
		$dibs_hmac = new dibs_hmac;

		JPlugin::loadLanguage('com_redshop');
		$db      = JFactory::getDbo();
		$request = JFactory::getApplication()->input->post;

		$verify_status  = $this->params->get('verify_status', '');
		$invalid_status = $this->params->get('invalid_status', '');

		$order_id = $request->getString('orderId');

		// Put your HMAC key below.
		$HmacKey = $this->params->get('hmac_key');
		$values  = new stdClass;

		// Calculate the MAC for the form key-values posted from DIBS.
		if ($request)
		{
			// Getting the array of post values is done this way to maintain compatibility with J2.5. J3 supports using simply `$post->getArray()`
			$MAC = $dibs_hmac->calculateMac($request->getArray(array_flip(array_keys($_POST))), $HmacKey);

			if ($request->getString('MAC') == $MAC && $request->getString('status') == "ACCEPTED")
			{
				$tid = $request->get('transaction');

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
		}

		$values->transaction_id = $tid;
		$values->order_id = $order_id;

		return $values;
	}

	/**
	 * orderPaymentNotYetUpdated
	 *
	 * @param   JDatabase  $dbConn    Name element
	 * @param   int        $order_id  Order ID
	 * @param   int        $tid       Transaction ID
	 *
	 * @return  stdClass|void
	 */
	public function orderPaymentNotYetUpdated($dbConn, $order_id, $tid)
	{
		$db = JFactory::getDbo();
		$res = false;
		$query = "SELECT COUNT(*) `qty` FROM `#__redshop_order_payment` WHERE `order_id` = '"
			. $db->getEscaped($order_id) . "' and order_payment_trans_id = '" . $db->getEscaped($tid) . "'";
		$db->setQuery($query);
		$order_payment = $db->loadResult();

		if ($order_payment == 0)
		{
			$res = true;
		}

		return $res;
	}

	/**
	 * onCapture_Paymentdibsdx
	 *
	 * @param   string  $element  Name element
	 * @param   array   $data     Request data
	 *
	 * @return  stdClass
	 */
	public function onCapture_Paymentdibsdx($element, $data)
	{
		if ($element != 'dibsdx')
		{
			return;
		}

		$db = JFactory::getDbo();

		JPlugin::loadLanguage('com_redshop');

		$order_id  = $data['order_id'];
		$dibsurl   = "https://payment.architrade.com/cgi-bin/capture.cgi?";
		$orderid   = $data['order_id'];
		$hmac_key  = $this->params->get("hmac_key");

		$api_path  = JPATH_SITE . '/plugins/redshop_payment/' . $element . '/' . $element . '/dibs_hmac.php';
		include $api_path;
		$dibs_hmac = new dibs_hmac;

		$formdata = array(
			'merchant' => $this->params->get("seller_id"),
			'amount'   => $data['order_amount'],
			'transact' => $data["order_transactionid"],
			'orderid'  => $data['order_id']
		);

		$mac_key = $dibs_hmac->calculateMac($formdata, $hmac_key);
		$dibsurl .= "merchant=" . urlencode($this->params->get("seller_id")) . "&amount=" . urlencode($data['order_amount']) . "&transact=" . $data["order_transactionid"] . "&orderid=" . $data['order_id'] . "&force=yes&textreply=yes&mac=" . $mac_key;
		$data    = $dibsurl;
		$ch      = curl_init($data);

		// 	Execute
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$data           = curl_exec($ch);
		$data           = explode('&', $data);
		$capture_status = explode('=', $data[0]);

		if ($capture_status[1] == 'ACCEPTED')
		{
			$values->responsestatus = 'Success';
			$message                = JText::_('COM_REDSHOP_TRANSACTION_APPROVED');
		}
		else
		{
			$values->responsestatus = 'Fail';
			$message                = JText::_('COM_REDSHOP_TRANSACTION_DECLINE');
		}

		$values->message = $message;

		return $values;
	}
}
