<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Redshop Payment - DIBSDX
 *
 * @since  1.5
 */
class PlgRedshop_PaymentDibsDx extends JPlugin
{
	/**
	 * Affects constructor behavior. If true, language files will be loaded automatically.
	 *
	 * @var    boolean
	 * @since  3.1
	 */
	protected $autoloadLanguage = true;

	/**
	 * Constructor
	 *
	 * @param   object $subject   The object to observe
	 * @param   array  $config    An optional associative array of configuration settings.
	 *                            Recognized key values include 'name', 'group', 'params', 'language'
	 *                            (this list is not meant to be comprehensive).
	 */
	public function __construct(&$subject, $config = array())
	{
		JPlugin::loadLanguage('plg_redshop_payment_dibsdx');
		parent::__construct($subject, $config);
	}

	/**
	 * onPrePayment
	 *
	 * @param   string $element Name element
	 * @param   array  $data    Request data
	 *
	 * @return  void
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'dibsdx')
		{
			return;
		}

		include JPATH_SITE . '/plugins/redshop_payment/' . $element . '/' . $element . '/extra_info.php';
	}

	/**
	 * onNotifyPaymentdibsdx
	 *
	 * @param   string $element Name element
	 * @param   array  $request Request data
	 *
	 * @return  mixed
	 */
	public function onNotifyPaymentDibsdx($element, $request)
	{
		if ($element != 'dibsdx')
		{
			return;
		}

		require JPATH_SITE . '/plugins/redshop_payment/' . $element . '/' . $element . '/dibs_hmac.php';

		$dibsHmac = new Dibs_Hmac;

		JPlugin::loadLanguage('com_redshop');

		$db      = JFactory::getDbo();
		$request = JFactory::getApplication()->input->post;

		$verifyStatus  = $this->params->get('verify_status', '');
		$invalidStatus = $this->params->get('invalid_status', '');

		$orderId = $request->getString('orderId');

		// Put your HMAC key below.
		$hmacKey = $this->params->get('hmac_key');
		$values  = new stdClass;

		// Calculate the MAC for the form key-values posted from DIBS.
		if (!empty($request))
		{
			$macData = $dibsHmac->calculateMac($request->getArray(), $hmacKey);

			if ($request->getString('MAC') == $macData && $request->getString('status') == "ACCEPTED")
			{
				$tid = $request->get('transaction');

				if ($this->orderPaymentNotYetUpdated($db, $orderId, $tid))
				{
					$transactionId                     = $tid;
					$values->order_status_code         = $verifyStatus;
					$values->order_payment_status_code = 'Paid';
					$values->log                       = JText::_('COM_REDSHOP_ORDER_PLACED');
					$values->msg                       = JText::_('COM_REDSHOP_ORDER_PLACED');
				}
			}
			else
			{
				$values->order_status_code         = $invalidStatus;
				$values->order_payment_status_code = 'Unpaid';
				$values->log                       = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
				$values->msg                       = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
			}
		}

		$values->transaction_id = $tid;
		$values->order_id       = $orderId;

		return $values;
	}

	/**
	 * orderPaymentNotYetUpdated
	 *
	 * @param   JDatabase  $dbConn         Name element
	 * @param   int        $orderId        Order ID
	 * @param   int        $transactionId  Transaction ID
	 *
	 * @return  boolean
	 */
	public function orderPaymentNotYetUpdated($dbConn, $orderId, $transactionId)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('COUNT(*)')
			->from($db->qn('#__redshop_order_payment'))
			->where($db->qn('order_id') . ' = ' . (int) $orderId)
			->where($db->qn('order_payment_trans_id') . ' = ' . (int) $transactionId);

		$orderPayment = $db->setQuery($query)->loadResult();

		return $orderPayment == 0;
	}

	/**
	 * onCapture_Paymentdibsdx
	 *
	 * @param   string $element Name element
	 * @param   array  $data    Request data
	 *
	 * @return  stdClass
	 */
	public function onCapture_PaymentDibsdx($element, $data)
	{
		if ($element != 'dibsdx')
		{
			return;
		}

		JPlugin::loadLanguage('com_redshop');

		$dibsUrl = "https://payment.architrade.com/cgi-bin/capture.cgi?";
		$hmacKey = $this->params->get("hmac_key");

		include JPATH_SITE . '/plugins/redshop_payment/' . $element . '/' . $element . '/dibs_hmac.php';
		$dibsHmac = new Dibs_Hmac;

		$formData = array(
			'merchant' => $this->params->get("seller_id"),
			'amount'   => $data['order_amount'],
			'transact' => $data["order_transactionid"],
			'orderid'  => $data['order_id']
		);

		$macKey  = $dibsHmac->calculateMac($formData, $hmacKey);
		$dibsUrl .= "merchant=" . urlencode($this->params->get("seller_id")) . "&amount=" . urlencode($data['order_amount'])
			. "&transact=" . $data["order_transactionid"] . "&orderid=" . $data['order_id'] . "&force=yes&textreply=yes&mac=" . $macKey;
		$data    = $dibsUrl;
		$ch      = curl_init($data);
		$values  = new stdClass;

		// 	Execute
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$data          = curl_exec($ch);
		$data          = explode('&', $data);
		$captureStatus = explode('=', $data[0]);

		if ($captureStatus[1] == 'ACCEPTED')
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
