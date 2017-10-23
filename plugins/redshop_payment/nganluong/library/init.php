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
 * NganLuong payment
 *
 * @package     Redshop.Plugins
 * @subpackage  NganLuong
 * @since       2.0.0
 */
class NL_Checkout
{
	/**
	 * Địa chỉ thanh toán hoá đơn của NgânLượng.vn
	 *
	 * @var  string
	 *
	 * @since  2.0
	 */
	public $nganluongUrl = 'https://www.nganluong.vn/checkout.php';

	/**
	 * Mã website của bạn đăng ký trong chức năng tích hợp thanh toán của NgânLượng.vn.
	 *
	 * @var  string
	 *
	 * @since  2.0
	 */
	public $merchantSiteCode = '275';

	/**
	 * Mật khẩu giao tiếp giữa website của bạn và NgânLượng.vn.Mã website của bạn đăng ký trong chức năng tích hợp thanh toán của NgânLượng.vn.
	 *
	 * @var  string
	 *
	 * @since  2.0
	 */
	public $securePass = '123456';

	/**
	 * Nếu bạn thay đổi mật khẩu giao tiếp trong quản trị website của chức năng tích hợp thanh toán trên NgânLượng.vn, vui lòng update lại mật khẩu này trên website của bạn
	 *
	 * @var  string
	 *
	 * @since  2.0
	 */
	public $affiliateCode = '';

	/**
	 * HÀM TẠO ĐƯỜNG LINK THANH TOÁN QUA NGÂNLƯỢNG.VN VỚI THAM SỐ MỞ RỘNG
	 *
	 * @param   string  $returnUrl        Đường link dùng để cập nhật tình trạng hoá đơn tại website của bạn khi người mua thanh toán thành công tại  NgânLượng.vn
	 * @param   string  $receiver         Địa chỉ Email chính của tài khoản NgânLượng.vn của người bán dùng nhận tiền bán hàng
	 * @param   string  $transactionInfo  Tham số bổ sung, bạn có thể dùng để lưu các tham số tuỳ ý để cập nhật thông tin khi NgânLượng.vn trả kết quả về
	 * @param   string  $orderCode        Mã hoá đơn hoặc tên sản phẩm
	 * @param   int     $price            Tổng tiền hoá đơn/sản phẩm, chưa kể phí vận chuyển, giảm giá, thuế.
	 * @param   string  $currency         Loại tiền tệ, nhận một trong các giá trị 'vnd', 'usd'. Mặc định đồng tiền thanh toán là 'vnd'
	 * @param   int     $quantity         Số lượng sản phẩm
	 * @param   int     $tax              Thuế
	 * @param   int     $discount         Giảm giá
	 * @param   int     $feeCal           Nhận giá trị 0 hoặc 1. Do trên hệ thống NgânLượng.vn cho phép chủ tài khoản cấu hình cho nhập/thay đổi phí lúc thanh toán hay không. Nếu website của bạn đã có phí vận chuyển và không cho sửa thì đặt tham số này = 0
	 * @param   int     $feeShipping      Phí vận chuyển
	 * @param   string  $orderDescription Mô tả về sản phẩm, đơn hàng
	 * @param   string  $buyerInfo        Thông tin người mua
	 * @param   string  $affiliateCode    Mã đối tác tham gia chương trình liên kết của NgânLượng.vn
	 *
	 * @return string
	 */
	public function buildCheckoutUrlExpand($returnUrl, $receiver, $transactionInfo, $orderCode, $price, $currency = 'vnd', $quantity = 1, $tax = 0, $discount = 0, $feeCal = 0, $feeShipping = 0, $orderDescription = '', $buyerInfo = '', $affiliateCode = '')
	{
		if ($affiliateCode == "")
		{
			$affiliateCode = $this->affiliateCode;
		}

		$arrParam = array(
			'merchant_site_code' => strval($this->merchantSiteCode),
			'return_url'         => strval(strtolower($returnUrl)),
			'receiver'           => strval($receiver),
			'transaction_info'   => strval($transactionInfo),
			'order_code'         => strval($orderCode),
			'price'              => strval($price),
			'currency'           => strval($currency),
			'quantity'           => strval($quantity),
			'tax'                => strval($tax),
			'discount'           => strval($discount),
			'fee_cal'            => strval($feeCal),
			'fee_shipping'       => strval($feeShipping),
			'order_description'  => strval($orderDescription),
			'buyer_info'         => strval($buyerInfo),
			'affiliate_code'     => strval($affiliateCode)
		);

		$secureCode              = implode(' ', $arrParam) . ' ' . $this->securePass;
		$arrParam['secure_code'] = md5($secureCode);
		$redirectUrl             = $this->nganluongUrl;

		if (strpos($redirectUrl, '?') === false)
		{
			$redirectUrl .= '?';
		}
		elseif (substr($redirectUrl, strlen($redirectUrl) - 1, 1) != '?' && strpos($redirectUrl, '&') === false)
		{
			$redirectUrl .= '&';
		}

		$url = '';

		foreach ($arrParam as $key => $value)
		{
			$value = urlencode($value);

			if ($url == '')
			{
				$url .= $key . '=' . $value;
			}
			else
			{
				$url .= '&' . $key . '=' . $value;
			}
		}

		return $redirectUrl . $url;
	}

	/**
	 * HÀM TẠO ĐƯỜNG LINK THANH TOÁN QUA NGÂNLƯỢNG.VN VỚI THAM SỐ CƠ BẢN
	 *
	 * @param   string  $returnUrl        Đường link dùng để cập nhật tình trạng hoá đơn tại website của bạn khi người mua thanh toán thành công tại NgânLượng.vn
	 * @param   string  $receiver         Địa chỉ Email chính của tài khoản NgânLượng.vn của người bán dùng nhận tiền bán hàng
	 * @param   string  $transactionInfo  Tham số bổ sung, bạn có thể dùng để lưu các tham số tuỳ ý để cập nhật thông tin khi NgânLượng.vn trả kết quả về
	 * @param   string  $orderCode        Mã hoá đơn/Tên sản phẩm
	 * @param   int     $price            Tổng tiền phải thanh toán
	 *
	 * @return  string
	 */
	public function buildCheckoutUrl($returnUrl, $receiver, $transactionInfo, $orderCode, $price)
	{
		// Bước 1. Mảng các tham số chuyển tới nganluong.vn
		$arrParam = array(
			'merchant_site_code' => strval($this->merchantSiteCode),
			'return_url'         => strtolower(urlencode($returnUrl)),
			'receiver'           => strval($receiver),
			'transaction_info'   => strval($transactionInfo),
			'order_code'         => strval($orderCode),
			'price'              => strval($price)
		);

		$secureCode = implode(' ', $arrParam) . ' ' . $this->securePass;
		$arrParam['secure_code'] = md5($secureCode);

		// Bước 2. Kiểm tra  biến $redirectUrl xem có '?' không, nếu không có thì bổ sung vào
		$redirectUrl = $this->nganluongUrl;

		if (strpos($redirectUrl, '?') === false)
		{
			$redirectUrl .= '?';
		}
		elseif (substr($redirectUrl, strlen($redirectUrl) - 1, 1) != '?' && strpos($redirectUrl, '&') === false)
		{
			// Nếu biến $redirectUrl có '?' nhưng không kết thúc bằng '?' và có chứa dấu '&' thì bổ sung vào cuối
			$redirectUrl .= '&';
		}

		// Bước 3. tạo url
		$url = '';

		foreach ($arrParam as $key => $value)
		{
			if ($key != 'return_url')
			{
				$value = urlencode($value);
			}

			if ($url == '')
			{
				$url .= $key . '=' . $value;
			}
			else
			{
				$url .= '&' . $key . '=' . $value;
			}
		}

		return $redirectUrl . $url;
	}

	/**
	 * HÀM KIỂM TRA TÍNH ĐÚNG ĐẮN CỦA ĐƯỜNG LINK KẾT QUẢ TRẢ VỀ TỪ NGÂNLƯỢNG.VN
	 *
	 * @param   string  $transactionInfo  Thông tin về giao dịch, Giá trị do website gửi sang
	 * @param   string  $orderCode        Mã hoá đơn/tên sản phẩm
	 * @param   string  $price            Tổng tiền đã thanh toán
	 * @param   string  $paymentId        Mã giao dịch tại NgânLượng.vn
	 * @param   int     $paymentType      Hình thức thanh toán: 1 - Thanh toán ngay (tiền đã chuyển vào tài khoản NgânLượng.vn của người bán); 2 - Thanh toán Tạm giữ (tiền người mua đã thanh toán nhưng NgânLượng.vn đang giữ hộ)
	 * @param   string  $errorText        Giao dịch thanh toán có bị lỗi hay không. $error_text == "" là không có lỗi. Nếu có lỗi, mô tả lỗi được chứa trong $error_text
	 * @param   string  $secureCode       Mã checksum (mã kiểm tra)
	 *
	 * @return  boolean
	 */
	public function verifyPaymentUrl($transactionInfo, $orderCode, $price, $paymentId, $paymentType, $errorText, $secureCode)
	{
		// Tạo mã xác thực từ chủ web
		$str = '';
		$str .= ' ' . strval($transactionInfo);
		$str .= ' ' . strval($orderCode);
		$str .= ' ' . strval($price);
		$str .= ' ' . strval($paymentId);
		$str .= ' ' . strval($paymentType);
		$str .= ' ' . strval($errorText);
		$str .= ' ' . strval($this->merchantSiteCode);
		$str .= ' ' . strval($this->securePass);

		// Mã hóa các tham số
		$verifySecureCode = md5($str);

		// Xác thực mã của chủ web với mã trả về từ nganluong.vn
		return !($verifySecureCode !== $secureCode);
	}
}
