<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

$my_path = dirname(__FILE__);

if (file_exists($my_path . "/../../../../configuration.php"))
{
	$absolute_path = dirname($my_path . "/../../../../configuration.php");
	require_once $my_path . "/../../../../configuration.php";
}
elseif (file_exists($my_path . "/../../configuration.php"))
{
	$absolute_path = dirname($my_path . "/../../configuration.php");
	require_once $my_path . "/../../configuration.php";
}
elseif (file_exists($my_path . "/configuration.php"))
{
	$absolute_path = dirname($my_path . "/configuration.php");
	require_once $my_path . "/configuration.php";
}
else
{
	echo "Joomla Configuration File not found!";
	die;
}

$absolute_path = realpath($absolute_path);

define ('_JEXEC', 1);
define ('JPATH_BASE', $absolute_path);
define ('DS', DIRECTORY_SEPARATOR);
define ('JPATH_COMPONENT_ADMINISTRATOR', JPATH_BASE . '/administrator/components/com_redshop');
define ('JPATH_COMPONENT', JPATH_BASE . '/components/com_redshop');

// Load the framework

require_once $absolute_path . '/includes/defines.php';
require_once $absolute_path . '/includes/framework.php';

// create the mainframe object
$app = JFactory::getApplication();

// Initialize the framework
$app->initialise();

// load system plugin group
JPluginHelper::importPlugin('system');

// trigger the onBeforeStart events
//$app->triggerEvent ( 'onBeforeStart' );
//$lang = JFactory::getLanguage ();
//$mosConfig_lang = $GLOBALS ['mosConfig_lang'] = strtolower ( $lang->getBackwardLang () );
// Adjust the live site path

/*** END of Joomla config ***/

// redshop language file
JPlugin::loadLanguage('com_redshop');

$request = JRequest::get('request');

JLoader::import('redshop.library');
JLoader::load('RedshopHelperAdminOrder');
$objOrder = new order_functions;

$app = JFactory::getApplication();
$ewayuk_parameters = getparameters('rs_payment_ewayuk');
$paymentinfo = $ewayuk_parameters[0];
$paymentparams = new JRegistry($paymentinfo->params);

$verify_status = $paymentparams->get('verify_status', '');
$invalid_status = $paymentparams->get('invalid_status', '');
$UserName = $paymentparams->get('username', '');
$CustomerID = $paymentparams->get('customer_id', '');
$debug_mode = $paymentparams->get('debug_mode', 0);

$querystring = "CustomerID=" . $CustomerID . "&UserName=" . $UserName . "&AccessPaymentCode=" . $_REQUEST['AccessPaymentCode'];
//echo $posturl="https://www.ewaygateway.com/Gateway/UK/Results.aspx?".$querystring;
$posturl = "https://payment.ewaygateway.com/Result/?" . $querystring;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $posturl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
//if (CURL_PROXY_REQUIRED == 'True')
{
	$proxy_tunnel_flag = (defined('CURL_PROXY_TUNNEL_FLAG') && strtoupper(CURL_PROXY_TUNNEL_FLAG) == 'FALSE') ? false : true;
	curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, $proxy_tunnel_flag);
	curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
	//curl_setopt ($ch, CURLOPT_PROXY, CURL_PROXY_SERVER_DETAILS);
}

$response = curl_exec($ch);
#print_r($response);
$responsecode = fetch_data($response, '<responsecode>', '</responsecode>');
$trxnnumber = fetch_data($response, '<trxnnumber>', '</trxnnumber>');
$auth_code = fetch_data($response, '<authcode>', '</authcode>');
$order_id = fetch_data($response, '<merchantoption1>', '</merchantoption1>');
$trxnstatus = fetch_data($response, '<trxnstatus>', '</trxnstatus>');
$trxnresponsemessage = fetch_data($response, '<trxnresponsemessage>', '</trxnresponsemessage>');

// Response Success Message
if ($responsecode == "00" || $responsecode == "08" || $responsecode == "10" || $responsecode == "11" || $responsecode == "16")
{
	$values->order_status_code = $verify_status;
	$values->order_payment_status_code = 'Paid';

	if ($debug_mode == 1)
	{
		$values->log = JText::_('COM_REDSHOP_ORDER_PLACED') . "  " . $trxnresponsemessage;
		$values->msg = JText::_('COM_REDSHOP_ORDER_PLACED') . "  " . $trxnresponsemessage;
	}
	else
	{
		$values->log = JText::_('COM_REDSHOP_ORDER_PLACED');
		$values->msg = JText::_('COM_REDSHOP_ORDER_PLACED');
	}

	$values->order_id = $order_id;
	$values->transaction_id = $auth_code;
}
else
{
	$values->order_status_code = $invalid_status;
	$values->order_payment_status_code = 'Unpaid';

	if ($debug_mode == 1)
	{
		$values->log = JText::_('COM_REDSHOP_ORDER_NOT_PLACED') . "  " . $trxnresponsemessage;
		$values->msg = JText::_('COM_REDSHOP_ORDER_NOT_PLACED') . "  " . $trxnresponsemessage;
	}
	else
	{
		$values->log = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
		$values->msg = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
	}

	$values->order_id = $order_id;
	$values->transaction_id = '';
}

$objOrder->changeorderstatus($values);
$uri = explode('plugins', JURI::base());
$app->redirect($uri[0] . "index.php?option=com_redshop&view=order_detail&oid=" . $order_id, $values->msg);

function getparameters($payment)
{
	$db = JFactory::getDbo();
	$sql = "SELECT * FROM #__extensions WHERE `element`='" . $payment . "'";
	$db->setQuery($sql);
	$params = $db->loadObjectList();

	return $params;
}

function fetch_data($string, $start_tag, $end_tag)
{
	$position = stripos($string, $start_tag);

	$str = substr($string, $position);

	$str_second = substr($str, strlen($start_tag));

	$second_positon = stripos($str_second, $end_tag);

	$str_third = substr($str_second, 0, $second_positon);

	$fetch_data = trim($str_third);

	return $fetch_data;
}


?>
