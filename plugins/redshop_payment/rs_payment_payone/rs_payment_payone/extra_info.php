<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license   GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 *            Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */
require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/redshop.cfg.php';
JLoader::import('loadhelpers', JPATH_SITE . '/components/com_redshop');
JLoader::load('RedshopHelperAdminOrder');
$objOrder = new order_functions;
$redhelper = new redhelper;
$db = JFactory::getDbo();
$user = JFActory::getUser();

$order_id = $data['order_id'];
$Itemid = $_REQUEST['Itemid'];
//Authnet vars to send
$formdata = array(
	'x_version'          => '3.1',

	// Customer Name and Billing Address
	//'customerid'
	'first_name'         => substr($data['billingaddress']->firstname, 0, 50),
	'last_name'          => substr($data['billingaddress']->lastname, 0, 50),
	'company'            => substr($data['billingaddress']->company, 0, 50),
	'street'             => substr($data['billingaddress']->address, 0, 60),
	'addresschecktype'   => 'no',
	'city'               => substr($data['billingaddress']->city, 0, 40),
	'consumerscoretype'  => 'IB',
	'state'              => substr($data['billingaddress']->state_code, 0, 40),
	'zip'                => substr($data['billingaddress']->zipcode, 0, 20),
	'country'            => 'DE',
	'telephonenumber'    => substr($data['billingaddress']->phone, 0, 25),
	//'country' => substr($data['billingaddress']->country_code, 0, 60),

	//'ip' => substr($data['billingaddress']->fax, 0, 25),

	// Customer Shipping Address
	'shipping_firstname' => substr($data['shippingaddress']->firstname, 0, 50),
	'shipping_last_name' => substr($data['shippingaddress']->lastname, 0, 50),
	//'shipping_company' => substr($data['billingaddress']->company, 0, 50),
	'shipping_address'   => substr($data['shippingaddress']->address, 0, 60),
	'shipping_city'      => substr($data['shippingaddress']->city, 0, 40),
	'shipping_state'     => substr($data['shippingaddress']->state_code, 0, 40),
	'shipping_zip'       => substr($data['shippingaddress']->zipcode, 0, 20),
	'shipping_country'   => substr($data['shippingaddress']->country_code, 0, 60),

	// Additional Customer Data
	'customerid'         => $data['billingaddress']->user_id,
	'userid'             => $data['billingaddress']->user_id,
	'ip'                 => $_SERVER["REMOTE_ADDR"],
	//	'x_customer_tax_id' => $dbbt->f("tax_id"),

	// Invoice Information
	'invoiceid'          => substr($order_number, 0, 20),
	'x_description'      => JText::_('COM_REDSHOP_AUTHORIZENET_ORDER_PRINT_PO_LBL'),

	'cardpan'            => $_SESSION['ccdata']['order_payment_number'],
	'cardtype'           => $_SESSION['ccdata']['creditcard_code'],
	'cardcvc2'           => $_SESSION['ccdata']['credit_card_code'],
	'cardexpiredate'     => ($_SESSION['ccdata']['order_payment_expire_year']) . ($_SESSION['ccdata']['order_payment_expire_month']),

	// Transaction Data
	'request'            => 'authorization',
	'reference'          => substr($order_number, 0, 20),
	'amount'             => $order_total,
	'currency'           => 'GB',
	//'currency' => $currency,

);

//build the post string
$poststring = array();

$poststring = array_merge($formdata, $poststring);

/*foreach($formdata AS $key => $val){
	$poststring .= urlencode($key) . "=" . urlencode($val) . "&";
}*/
$apiurl = "https://api.pay1.de/post-gateway/";
$payone_encoding = "UTF-8";
$payone_clearingtype = 'cc';

$poststring['portalid'] = $this->_params->get("payone_portal_id");
$poststring['mid'] = $this->_params->get("merchant_id");
$poststring['aid'] = $this->_params->get("payone_account_id");
$poststring['mode'] = $this->_params->get("is_test");
$poststring['encoding'] = $payone_encoding;
$poststring['key'] = md5($this->_params->get("payone_portal_keyid"));
$poststring['clearingtype'] = $payone_clearingtype;

$query = http_build_query($poststring);

$ch = curl_init($apiurl);

if (!empty($poststring['request']))
{
}
else
{
	return false;
}

//curl_setopt($ch, CURLOPT_URL, PAYONE_API_URL);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 45);

$result = curl_exec($ch);

if (curl_error($ch))
{
	$response[] = "errormessage=" . curl_errno($ch) . ": " . curl_error($ch);
	$response[] = "this is a critical connection error!";
}
else
{
	$response = explode("\n", $result);
}

curl_close($ch);

if (is_array($response))
{
	foreach ($response as $linenum => $line)
	{
		$pos = strpos($line, "=");

		if ($pos > 0)
		{
			$output[substr($line, 0, $pos)] = trim(substr($line, $pos + 1));
		}
		elseif (strlen($line) > 0)
		{
			$output[$linenum] = $line;
		}
	}
}

if (!$response)
{
	return false;
}

if ($output['status'] == 'APPROVED')
{
	$values->order_status_code = $verify_status;
	$values->order_payment_status_code = 'PAID';
	$values->log = JText::_('COM_REDSHOP_ORDER_PLACED');
	$values->msg = JText::_('COM_REDSHOP_ORDER_PLACED');
}
else
{
	$values->order_status_code = $invalid_status;
	$values->order_payment_status_code = 'UNPAID';
	$values->log = JText::_('COM_REDSHOP_ORDER_NOT_PLACED.');
	$values->msg = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
}

$values->transaction_id = $output['txid'];
$values->order_id = $order_id;
$objOrder->changeorderstatus($values);


?>