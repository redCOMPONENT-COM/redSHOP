<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
include 'call_restful.php';

class BaoKimPaymentPro
{
	/**
	 * Call API GET_SELLER_INFO
	 *  + Create bank list show to frontend
	 *
	 * @internal param $method_code
	 * @return string
	 */
	public function get_seller_info()
	{
		$param = array(
			'business' => EMAIL_BUSINESS,
		);
		$call_restfull = new CallRestful;
		$call_API = $call_restfull->call_API("GET", $param, BAOKIM_API_SELLER_INFO);

		if (is_array($call_API))
		{
			if (isset($call_API['error']))
			{
				echo  "<strong style='color:red'>call_API" . json_encode($call_API['error']) . "- code:" . $call_API['status'] . "</strong> - " . "System error. Please contact to administrator";
				die;
			}
		}

		$seller_info = json_decode($call_API, true);

		if (!empty($seller_info['error']))
		{
			echo "<strong style='color:red'>eller_info" . json_encode($seller_info['error']) . "</strong> - " . "System error. Please contact to administrator";
			die;
		}

		$banks = $seller_info['bank_payment_methods'];

		return $banks;
	}


	/**
	 * Call API PAY_BY_CARD
	 *  + Get Order info
	 *  + Sent order, action payment
	 *
	 * @param $orderid
	 * @return mixed
	 */
	public function pay_by_card($data)
	{
		$base_url                         = JURI::base();
		$url_success                      = $data['return'];
		$url_cancel                       = $data['cancel'];
		$url_detail                       = $data['detail'];
		$order_id                         = $data['order_id'];
		$total_amount                     = str_replace('.', '', $data['total_amount']);

		$params['business']               = strval(EMAIL_BUSINESS);
		$params['bank_payment_method_id'] = intval($data['bank_payment_method_id']);
		$params['transaction_mode_id']    = $data['transaction_mode_id'];
		$params['escrow_timeout']         = $data['escrow_timeout'];

		$params['order_id']               = $order_id;
		$params['total_amount']           = $total_amount;
		$params['shipping_fee']           = $data['shipping_fee'];
		$params['tax_fee']                = $data['tax_fee'];
		$params['currency_code']          = Redshop::getConfig()->get('CURRENCY_CODE');

		$params['url_success']            = $url_success;
		$params['url_cancel']             = $url_cancel;
		$params['url_detail']             = $url_detail;

		$params['order_description']      = 'Thanh toán đơn hàng từ Website ' . $base_url . ' với mã đơn hàng ' . $order_id;
		$params['payer_name']             = $data['payer_name'];
		$params['payer_email']            = $data['payer_email'];
		$params['payer_phone_no']         = $data['payer_phone_no'];
		$params['payer_address']          = $data['address'];

		$call_restfull = new CallRestful;
		$result        = json_decode($call_restfull->call_API("POST", $params, BAOKIM_API_PAY_BY_CARD), true);

		return $result;
	}

	public function generateBankImage($banks, $payment_method_type)
	{
		$html = '';

		foreach ($banks as $bank)
		{
			if ($bank['payment_method_type'] == $payment_method_type)
			{
				$html .= '<li><img class="img-bank" id="' . $bank['id'] . '" src="' . $bank['logo_url'] . '" title="' . $bank['name'] . '"/></li>';
			}
		}

		return $html;
	}
}
