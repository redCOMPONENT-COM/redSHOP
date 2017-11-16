<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

require_once 'constants.php';

class BaoKimPayment
{
	/**
	 * Cấu hình phương thức thanh toán với các tham số
	 * E-mail Bảo Kim - E-mail tài khoản bạn đăng ký với BaoKim.vn.
	 * Merchant ID - là “mã website” được Baokim cấp khi bạn đăng ký tích hợp.
	 * Mã bảo mật - là “mật khẩu” được Baokim cấp khi bạn đăng ký tích hợp
	 * Vd : 12f31c74fgd002b1
	 * Server Bảo Kim
	 * Trang ​Kiểm thử - server để test thử phương thức thanh. .toán
	 * Trang thực tế - Server thực tế thực hiện thanh toán.
	 * https://www.baokim.vn/payment/order/version11' => ('Trang thực tế'),
	 * http://kiemthu.baokim.vn/payment/order/version11' => ('Trang kiểm thử')
	 * Chọn Save configuration để áp dụng thay đổi
	 * Hàm xây dựng url chuyển đến BaoKim.vn thực hiện thanh toán, trong đó có tham số mã hóa (còn gọi là public key)
	 * @param $order_id                Mã đơn hàng
	 * @param $business            Email tài khoản người bán
	 * @param $total_amount            Giá trị đơn hàng
	 * @param $shipping_fee            Phí vận chuyển
	 * @param $tax_fee                Thuế
	 * @param $order_description    Mô tả đơn hàng
	 * @param $url_success            Url trả về khi thanh toán thành công
	 * @param $url_cancel            Url trả về khi hủy thanh toán
	 * @param $url_detail            Url chi tiết đơn hàng
	 * @param null $payer_name
	 * @param null $payer_email
	 * @param null $payer_phone_no
	 * @param null $shipping_address
	 * @return url cần tạo
	 */
	public function createRequestUrl($data)
	{
		$total_amount = str_replace('.', '', $data['total_amount']);
		$base_url     = substr(JURI::base(), 0, -1);
		$url_success  = $base_url . JRoute::_(str_replace(JURI::base(), '', $data['return']), true);
		$url_cancel   = $base_url . JRoute::_(str_replace(JURI::base(), '', $data['cancel']), true);
		$url_detail   = $base_url . $data['detail'];
		$order_id     = $data['order_id'];

		// Mảng các tham số chuyển tới baokim.vn
		$params = array(
			'merchant_id'       =>	strval(MERCHANT_ID),
			'order_id'          =>	strval($order_id),
			'business'          =>	strval(EMAIL_BUSINESS),
			'total_amount'      =>	strval($total_amount),
			'shipping_fee'      =>	strval($data['shipping_fee']),
			'tax_fee'           =>	strval($data['tax_fee']),
			'order_description' =>	strval('Thanh toán đơn hàng từ Website ' . $base_url . ' với mã đơn hàng ' . $order_id),
			'url_success'       =>	strtolower($url_success),
			'url_cancel'        =>	strtolower($url_cancel),
			'url_detail'        =>	strtolower($url_detail),
			'payer_name'        =>	strval($data['payer_name']),
			'payer_email'       =>	strval($data['payer_email']),
			'payer_phone_no'    =>	strval($data['payer_phone_no']),
			'shipping_address'  =>	strval($data['address']),
			'currency'          =>	strval(Redshop::getConfig()->get('CURRENCY_CODE')),
		);
		ksort($params);

		$params['checksum'] = hash_hmac('SHA1', implode('', $params), SECURE_PASS);

		// Kiểm tra  biến $redirect_url xem có '?' không, nếu không có thì bổ sung vào
		$redirect_url = BAOKIM_URL . BAOKIM_API_PAYMENT;

		if (strpos($redirect_url, '?') === false)
		{
			$redirect_url .= '?';
		}
		elseif (substr($redirect_url, strlen($redirect_url) - 1, 1) != '?' && strpos($redirect_url, '&') === false)
		{
			// Nếu biến $redirect_url có '?' nhưng không kết thúc bằng '?' và có chứa dấu '&' thì bổ sung vào cuối
			$redirect_url .= '&';
		}

		// Tạo đoạn url chứa tham số
		$url_params = '';

		foreach ($params as $key => $value)
		{
			if ($url_params == '')
				$url_params .= $key . '=' . urlencode($value);
			else
				$url_params .= '&' . $key . '=' . urlencode($value);
		}

		return $redirect_url . $url_params;
	}

	/**
	 * Hàm thực hiện xác minh tính chính xác thông tin trả về từ BaoKim.vn
	 * @param $url_params chứa tham số trả về trên url
	 * @return true nếu thông tin là chính xác, false nếu thông tin không chính xác
	 */
	public function verifyResponseUrl($url_params = array())
	{
		if (empty($url_params['checksum']))
		{
			echo "invalid parameters: checksum is missing";

			return false;
		}

		$checksum = $url_params['checksum'];
		unset($url_params['checksum']);

		ksort($url_params);

		if (strcasecmp($checksum, hash_hmac('SHA1', implode('', $url_params), SECURE_PASS)) === 0)
			return true;
		else
			return false;
	}
}
