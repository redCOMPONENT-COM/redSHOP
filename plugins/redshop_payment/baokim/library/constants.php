<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
$plugin = JPluginHelper::getPlugin('redshop_payment', 'baokim');
$params = new JRegistry($plugin->params);
$pluginParams = $params->get('data');

if (!empty($pluginParams))
{
	// Configure account
	// Email Bảo kim
	define('EMAIL_BUSINESS', $pluginParams->baokim_email);

	// Mã website tích hợp
	define('MERCHANT_ID', $pluginParams->baokim_merchant_id);

	// Mật khẩu
	define('SECURE_PASS', $pluginParams->baokim_merchant_password);

	// Cấu hình tài khoản tích hợp
	// API USER
	define('API_USER', $pluginParams->baokim_api_username);

	// API PASSWORD
	define('API_PWD', $pluginParams->baokim_api_password);

	// Private key
	define('PRIVATE_KEY_BAOKIM', $pluginParams->baokim_private_key);

	if ($pluginParams->isTest == 1)
	{
		define('BAOKIM_URL', 'http://kiemthu.baokim.vn');
	}
	else
	{
		define('BAOKIM_URL', 'http://baokim.vn');
	}
}
else
{
	// Configure account
	// Email Bảo kim
	define('EMAIL_BUSINESS', $params->get('baokim_email'));

	// Mã website tích hợp
	define('MERCHANT_ID', $params->get('baokim_merchant_id'));

	// Mật khẩu
	define('SECURE_PASS', $params->get('baokim_merchant_password'));

	// Cấu hình tài khoản tích hợp
	// API USER
	define('API_USER', $params->get('baokim_api_username'));

	// API PASSWORD
	define('API_PWD', $params->get('baokim_api_password'));

	// Private key
	define('PRIVATE_KEY_BAOKIM', $params->get('baokim_private_key'));

	if ($params->get('isTest') == 1)
	{
		define('BAOKIM_URL', 'http://kiemthu.baokim.vn');
	}
	else
	{
		define('BAOKIM_URL', 'http://baokim.vn');
	}
}

define('BAOKIM_API_SELLER_INFO', '/payment/rest/payment_pro_api/get_seller_info');
define('BAOKIM_API_PAY_BY_CARD', '/payment/rest/payment_pro_api/pay_by_card');
define('BAOKIM_API_PAYMENT', '/payment/order/version11');

// Phương thức thanh toán bằng thẻ nội địa
define('PAYMENT_METHOD_TYPE_LOCAL_CARD', 1);

// Phương thức thanh toán bằng thẻ tín dụng quốc tế
define('PAYMENT_METHOD_TYPE_CREDIT_CARD', 2);

// Dịch vụ chuyển khoản online của các ngân hàng
define('PAYMENT_METHOD_TYPE_INTERNET_BANKING', 3);

// Dịch vụ chuyển khoản ATM
define('PAYMENT_METHOD_TYPE_ATM_TRANSFER', 4);

// Dịch vụ chuyển khoản truyền thống giữa các ngân hàng
define('PAYMENT_METHOD_TYPE_BANK_TRANSFER', 5);
