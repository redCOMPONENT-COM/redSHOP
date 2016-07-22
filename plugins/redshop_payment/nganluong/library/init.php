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
 * NganLuong payment
 *
 * @package     Redshop.Plugins
 * @subpackage  NganLuong
 * @since       1.6
 */
class NL_CheckOutV3
{
	public $url_api           = 'https://www.nganluong.vn/checkout.api.nganluong.post.php';

	public $merchant_id       = '';

	public $merchant_password = '';

	public $receiver_email    = '';

	public $cur_code          = 'vnd';

	function __construct($merchant_id, $merchant_password, $receiver_email, $url_api)
	{
		$this->version           = '3.1';
		$this->url_api           = $url_api;
		$this->merchant_id       = $merchant_id;
		$this->merchant_password = $merchant_password;
		$this->receiver_email    = $receiver_email;
	}

	function GetTransactionDetail($token)
	{
		$params = array(
			'merchant_id'       => $this->merchant_id ,
			'merchant_password' => MD5($this->merchant_password),
			'version'           => $this->version,
			'function'          => 'GetTransactionDetail',
			'token'             => $token
		);

		$post_field = '';

		foreach ($params as $key => $value)
		{
			if ($post_field != '')
				$post_field .= '&';
			$post_field .= $key . "=" . $value;
		}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->url_api);
		curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_field);
		$result = curl_exec($ch);
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$error = curl_error($ch);

		if ($result != '' && $status == 200)
		{
			$nl_result  = simplexml_load_string($result);

			return $nl_result;
		}

		return false;
	}

	function VisaCheckout($order_code, $total_amount, $payment_type, $order_description, $tax_amount, $fee_shipping, $discount_amount, $return_url, $cancel_url, $buyer_fullname, $buyer_email, $buyer_mobile, $buyer_address, $array_items, $bank_code)
	{
		$params = array(
				'cur_code'          =>	$this->cur_code,
				'function'          => 'SetExpressCheckout',
				'version'           => $this->version,
				'merchant_id'       => $this->merchant_id,
				'receiver_email'    => $this->receiver_email,
				'merchant_password' => MD5($this->merchant_password),
				'order_code'        => $order_code,
				'total_amount'      => $total_amount,
				'payment_method'    => 'VISA',
				'bank_code'         => $bank_code,
				'payment_type'      => $payment_type,
				'order_description' => $order_description,
				'tax_amount'        => $tax_amount,
				'fee_shipping'      => $fee_shipping,
				'discount_amount'   => $discount_amount,
				'return_url'        => $return_url,
				'cancel_url'        => $cancel_url,
				'buyer_fullname'    => $buyer_fullname,
				'buyer_email'       => $buyer_email,
				'buyer_mobile'      => $buyer_mobile,
				'buyer_address'     => $buyer_address,
				'total_item'        => count($array_items)
			);
			$post_field = '';

			foreach ($params as $key => $value)
			{
				if ($post_field != '')
					$post_field .= '&';
				$post_field .= $key . "=" . $value;
			}

			if (count($array_items) > 0)
			{
				foreach ($array_items as $array_item)
				{
					foreach ($array_item as $key => $value)
					{
						if ($post_field != '')
							$post_field .= '&';
						$post_field .= $key . "=" . $value;
					}
				}
			}

		$nl_result = $this->CheckoutCall($post_field);

		return $nl_result;
	}

	function BankCheckout($order_code, $total_amount, $bank_code, $payment_type, $order_description, $tax_amount, $fee_shipping, $discount_amount, $return_url, $cancel_url, $buyer_fullname, $buyer_email, $buyer_mobile, $buyer_address, $array_items)
	{
		$params = array(
				'cur_code'          =>	$this->cur_code,
				'function'          => 'SetExpressCheckout',
				'version'           => $this->version,
				'merchant_id'       => $this->merchant_id,
				'receiver_email'    => $this->receiver_email,
				'merchant_password' => MD5($this->merchant_password),
				'order_code'        => $order_code,
				'total_amount'      => $total_amount,
				'payment_method'    => 'ATM_ONLINE',
				'bank_code'         => $bank_code,
				'payment_type'      => $payment_type,
				'order_description' => $order_description,
				'tax_amount'        => $tax_amount,
				'fee_shipping'      => $fee_shipping,
				'discount_amount'   => $discount_amount,
				'return_url'        => $return_url,
				'cancel_url'        => $cancel_url,
				'buyer_fullname'    => $buyer_fullname,
				'buyer_email'       => $buyer_email,
				'buyer_mobile'      => $buyer_mobile,
				'buyer_address'     => $buyer_address,
				'total_item'        => count($array_items)
			);

			$post_field = '';

			foreach ($params as $key => $value)
			{
				if ($post_field != '')
					$post_field .= '&';
				$post_field .= $key . "=" . $value;
			}

			if (count($array_items) > 0)
			{
				foreach ($array_items as $array_item)
				{
					foreach ($array_item as $key => $value)
					{
						if ($post_field != '')
							$post_field .= '&';
						$post_field .= $key . "=" . $value;
					}
				}
			}

		$nl_result = $this->CheckoutCall($post_field);

		return $nl_result;
	}

	function BankOfflineCheckout($order_code, $total_amount, $bank_code, $payment_type, $order_description, $tax_amount, $fee_shipping, $discount_amount, $return_url, $cancel_url, $buyer_fullname, $buyer_email, $buyer_mobile, $buyer_address, $array_items)
	{
		$params = array(
				'cur_code'          =>	$this->cur_code,
				'function'          => 'SetExpressCheckout',
				'version'           => $this->version,
				'merchant_id'       => $this->merchant_id,
				'receiver_email'    => $this->receiver_email,
				'merchant_password' => MD5($this->merchant_password),
				'order_code'        => $order_code,
				'total_amount'      => $total_amount,
				'payment_method'    => 'ATM_OFFLINE',
				'bank_code'         => $bank_code,
				'payment_type'      => $payment_type,
				'order_description' => $order_description,
				'tax_amount'        => $tax_amount,
				'fee_shipping'      => $fee_shipping,
				'discount_amount'   => $discount_amount,
				'return_url'        => $return_url,
				'cancel_url'        => $cancel_url,
				'buyer_fullname'    => $buyer_fullname,
				'buyer_email'       => $buyer_email,
				'buyer_mobile'      => $buyer_mobile,
				'buyer_address'     => $buyer_address,
				'total_item'        => count($array_items)
			);

			$post_field = '';

			foreach ($params as $key => $value)
			{
				if ($post_field != '')
					$post_field .= '&';
				$post_field .= $key . "=" . $value;
			}

			if (count($array_items) > 0)
			{
				foreach ($array_items as $array_item)
				{
					foreach ($array_item as $key => $value)
					{
						if ($post_field != '')
							$post_field .= '&';
						$post_field .= $key . "=" . $value;
					}
				}
			}

		$nl_result = $this->CheckoutCall($post_field);

		return $nl_result;
	}

	function officeBankCheckout($order_code, $total_amount, $bank_code, $payment_type, $order_description, $tax_amount, $fee_shipping, $discount_amount, $return_url, $cancel_url, $buyer_fullname, $buyer_email, $buyer_mobile, $buyer_address, $array_items)
	{
		$params = array(
				'cur_code'          => $this->cur_code,
				'function'          => 'SetExpressCheckout',
				'version'           => $this->version,
				'merchant_id'       => $this->merchant_id,
				'receiver_email'    => $this->receiver_email,
				'merchant_password' => MD5($this->merchant_password),
				'order_code'        => $order_code,
				'total_amount'      => $total_amount,
				'payment_method'    => 'NH_OFFLINE',
				'bank_code'         => $bank_code,
				'payment_type'      => $payment_type,
				'order_description' => $order_description,
				'tax_amount'        => $tax_amount,
				'fee_shipping'      => $fee_shipping,
				'discount_amount'   => $discount_amount,
				'return_url'        => $return_url,
				'cancel_url'        => $cancel_url,
				'buyer_fullname'    => $buyer_fullname,
				'buyer_email'       => $buyer_email,
				'buyer_mobile'      => $buyer_mobile,
				'buyer_address'     => $buyer_address,
				'total_item'        => count($array_items)
			);

			$post_field = '';

			foreach ($params as $key => $value)
			{
				if ($post_field != '')
					$post_field .= '&';
				$post_field .= $key . "=" . $value;
			}

			if (count($array_items) > 0)
			{
				foreach ($array_items as $array_item)
				{
					foreach ($array_item as $key => $value)
					{
						if ($post_field != '')
							$post_field .= '&';
						$post_field .= $key . "=" . $value;
					}
				}
			}

		$nl_result = $this->CheckoutCall($post_field);

		return $nl_result;
	}

	function TTVPCheckout($order_code, $total_amount, $bank_code, $payment_type, $order_description, $tax_amount, $fee_shipping, $discount_amount, $return_url, $cancel_url, $buyer_fullname, $buyer_email, $buyer_mobile, $buyer_address, $array_items)
	{
		$params = array(
				'cur_code'          =>	$this->cur_code,
				'function'          => 'SetExpressCheckout',
				'version'           => $this->version,
				'merchant_id'       => $this->merchant_id,
				'receiver_email'    => $this->receiver_email,
				'merchant_password' => MD5($this->merchant_password),
				'order_code'        => $order_code,
				'total_amount'      => $total_amount,
				'payment_method'    => 'ATM_ONLINE',
				'bank_code'         => $bank_code,
				'payment_type'      => $payment_type,
				'order_description' => $order_description,
				'tax_amount'        => $tax_amount,
				'fee_shipping'      => $fee_shipping,
				'discount_amount'   => $discount_amount,
				'return_url'        => $return_url,
				'cancel_url'        => $cancel_url,
				'buyer_fullname'    => $buyer_fullname,
				'buyer_email'       => $buyer_email,
				'buyer_mobile'      => $buyer_mobile,
				'buyer_address'     => $buyer_address,
				'total_item'        => count($array_items)
			);

			$post_field = '';

			foreach ($params as $key => $value)
			{
				if ($post_field != '')
					$post_field .= '&';
				$post_field .= $key . "=" . $value;
			}

			if (count($array_items) > 0)
			{
				foreach ($array_items as $array_item)
				{
					foreach ($array_item as $key => $value)
					{
						if ($post_field != '')
							$post_field .= '&';
						$post_field .= $key . "=" . $value;
					}
				}
			}

		$nl_result = $this->CheckoutCall($post_field);

		return $nl_result;
	}

	function NLCheckout($order_code, $total_amount, $payment_type, $order_description, $tax_amount, $fee_shipping, $discount_amount, $return_url, $cancel_url, $buyer_fullname, $buyer_email, $buyer_mobile, $buyer_address, $array_items)
	{
		$params = array(
				'cur_code'          => $this->cur_code,
				'function'          => 'SetExpressCheckout',
				'version'           => $this->version,
				'merchant_id'       => $this->merchant_id,
				'receiver_email'    => $this->receiver_email,
				'merchant_password' => MD5($this->merchant_password),
				'order_code'        => $order_code,
				'total_amount'      => $total_amount,
				'payment_method'    => 'NL',
				'payment_type'      => $payment_type,
				'order_description' => $order_description,
				'tax_amount'        => $tax_amount,
				'fee_shipping'      => $fee_shipping,
				'discount_amount'   => $discount_amount,
				'return_url'        => $return_url,
				'cancel_url'        => $cancel_url,
				'buyer_fullname'    => $buyer_fullname,
				'buyer_email'       => $buyer_email,
				'buyer_mobile'      => $buyer_mobile,
				'buyer_address'     => $buyer_address,
				'total_item'        => count($array_items)
			);
			$post_field = '';

			foreach ($params as $key => $value)
			{
				if ($post_field != '')
					$post_field .= '&';
				$post_field .= $key . "=" . $value;
			}

			if (count($array_items) > 0)
			{
				foreach ($array_items as $array_item)
				{
					foreach ($array_item as $key => $value)
					{
						if ($post_field != '')
							$post_field .= '&';
						$post_field .= $key . "=" . $value;
					}
				}
			}

		$nl_result = $this->CheckoutCall($post_field);

		return $nl_result;
	}

	function IBCheckout($order_code, $total_amount, $bank_code, $payment_type, $order_description, $tax_amount, $fee_shipping, $discount_amount, $return_url, $cancel_url, $buyer_fullname, $buyer_email, $buyer_mobile, $buyer_address, $array_items)
	{
		$params = array(
			'cur_code'          => $this->cur_code,
			'function'          => 'SetExpressCheckout',
			'version'           => $this->version,
			'merchant_id'       => $this->merchant_id,
			'receiver_email'    => $this->receiver_email,
			'merchant_password' => MD5($this->merchant_password),
			'order_code'        => $order_code,
			'total_amount'      => $total_amount,
			'payment_method'    => 'IB_ONLINE',
			'bank_code'         => $bank_code,
			'payment_type'      => $payment_type,
			'order_description' => $order_description,
			'tax_amount'        => $tax_amount,
			'fee_shipping'      => $fee_shipping,
			'discount_amount'   => $discount_amount,
			'return_url'        => $return_url,
			'cancel_url'        => $cancel_url,
			'buyer_fullname'    => $buyer_fullname,
			'buyer_email'       => $buyer_email,
			'buyer_mobile'      => $buyer_mobile,
			'buyer_address'     => $buyer_address,
			'total_item'        => count($array_items)
		);
		$post_field = '';

		foreach ($params as $key => $value)
		{
			if ($post_field != '')
				$post_field .= '&';
			$post_field .= $key . "=" . $value;
		}

		if (count($array_items) > 0)
		{
			foreach ($array_items as $array_item)
			{
				foreach ($array_item as $key => $value)
				{
					if ($post_field != '')
						$post_field .= '&';
					$post_field .= $key . "=" . $value;
				}
			}
		}

		$nl_result = $this->CheckoutCall($post_field);

		return $nl_result;
	}

	function CheckoutCall($post_field)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->url_api);
		curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_field);
		$result = curl_exec($ch);
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$error = curl_error($ch);

		if ($result != '' && $status == 200)
		{
			$xml_result = str_replace('&', '&amp;', (string) $result);
			$nl_result  = simplexml_load_string($xml_result);
			$nl_result->error_message = $this->GetErrorMessage($nl_result->error_code);
		}
		else
			$nl_result->error_message = $error;

		return $nl_result;
	}

	function GetErrorMessage($error_code)
	{
		$arrCode = array(
		'00' => 'Thành công',
		'99' => 'Lỗi chưa xác minh',
		'06' => 'Mã merchant không tồn tại hoặc bị khóa',
		'02' => 'Địa chỉ IP truy cập bị từ chối',
		'03' => 'Mã checksum không chính xác, truy cập bị từ chối',
		'04' => 'Tên hàm API do merchant gọi tới không hợp lệ (không tồn tại)',
		'05' => 'Sai version của API',
		'07' => 'Sai mật khẩu của merchant',
		'08' => 'Địa chỉ email tài khoản nhận tiền không tồn tại',
		'09' => 'Tài khoản nhận tiền đang bị phong tỏa giao dịch',
		'10' => 'Mã đơn hàng không hợp lệ',
		'11' => 'Số tiền giao dịch lớn hơn hoặc nhỏ hơn quy định',
		'12' => 'Loại tiền tệ không hợp lệ',
		'29' => 'Token không tồn tại',
		'80' => 'Không thêm được đơn hàng',
		'81' => 'Đơn hàng chưa được thanh toán',
		'110' => 'Địa chỉ email tài khoản nhận tiền không phải email chính',
		'111' => 'Tài khoản nhận tiền đang bị khóa',
		'113' => 'Tài khoản nhận tiền chưa cấu hình là người bán nội dung số',
		'114' => 'Giao dịch đang thực hiện, chưa kết thúc',
		'115' => 'Giao dịch bị hủy',
		'118' => 'tax_amount không hợp lệ',
		'119' => 'discount_amount không hợp lệ',
		'120' => 'fee_shipping không hợp lệ',
		'121' => 'return_url không hợp lệ',
		'122' => 'cancel_url không hợp lệ',
		'123' => 'items không hợp lệ',
		'124' => 'transaction_info không hợp lệ',
		'125' => 'quantity không hợp lệ',
		'126' => 'order_description không hợp lệ',
		'127' => 'affiliate_code không hợp lệ',
		'128' => 'time_limit không hợp lệ',
		'129' => 'buyer_fullname không hợp lệ',
		'130' => 'buyer_email không hợp lệ',
		'131' => 'buyer_mobile không hợp lệ',
		'132' => 'buyer_address không hợp lệ',
		'133' => 'total_item không hợp lệ',
		'134' => 'payment_method, bank_code không hợp lệ',
		'135' => 'Lỗi kết nối tới hệ thống ngân hàng',
		'140' => 'Đơn hàng không hỗ trợ thanh toán trả góp',);

		return $arrCode[(string) $error_code];
	}
}
