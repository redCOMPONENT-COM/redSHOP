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

/*** access Joomla's configuration file ***/
$my_path = dirname(__FILE__);

if (file_exists($my_path . "/../../../configuration.php"))
{
	$absolute_path = dirname($my_path . "/../../../configuration.php");
	require_once $my_path . "/../../../configuration.php";
}
else
{
	die("Joomla Configuration File not found!");
}

$absolute_path = realpath($absolute_path);

// Set flag that this is a parent file
define('_JEXEC', 1);

define('JPATH_BASE', $absolute_path);

define('DS', DIRECTORY_SEPARATOR);

require_once JPATH_BASE . '/includes/defines.php';
require_once JPATH_BASE . '/includes/framework.php';

JDEBUG ? $_PROFILER->mark('afterLoad') : null;

/**
 * CREATE THE APPLICATION
 *
 * NOTE :
 */
$app = JFactory::getApplication();

// Initialize the framework
$app->initialise();

require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/redshop.cfg.php';
JLoader::import('loadhelpers', JPATH_SITE . '/components/com_redshop');
JLoader::load('RedshopHelperAdminOrder');
require_once JPATH_BASE . '/plugins/redshop_payment/rs_payment_payment_express/PxPay_Curl.inc.php';

$objOrder = new order_functions;

// load system plugin group
JPluginHelper::importPlugin('system');

// print_r($_REQUEST);die();
/*** END of Joomla config ***/
// Now check we have a Crypt field passed to this page
$request = JRequest::get('REQUEST');
$Itemid = $request["Itemid"];
$enc_hex = $request["result"];

JPlugin::loadLanguage('com_redshop');
$bbs_parameters = getparameters('rs_payment_payment_express');
$paymentinfo = $bbs_parameters[0];

$paymentparams = new JRegistry($paymentinfo->params);

$verify_status = $paymentparams->get('verify_status', '');
$px_post_username = $paymentparams->get('px_post_username', '');
$px_post_label_key = $paymentparams->get('px_post_label_key', '');
$invalid_status = $paymentparams->get('invalid_status', '');
$auth_type = $paymentparams->get('auth_type', '');
$debug_mode = $paymentparams->get('debug_mode', 0);

#getResponse method in PxPay object returns PxPayResponse object
$PxPay_Url = "https://sec2.paymentexpress.com/pxpay/pxaccess.aspx";
$pxpay = new PxPay_Curl($PxPay_Url, $px_post_username, $px_post_label_key);

#which encapsulates all the response data
$rsp = $pxpay->getResponse($enc_hex);

# the following are the fields available in the PxPayResponse object
$Success = $rsp->getSuccess(); # =1 when request succeeds
$AmountSettlement = $rsp->getAmountSettlement();
$AuthCode = $rsp->getAuthCode(); # from bank
$CardName = $rsp->getCardName(); # e.g. "Visa"
$CardNumber = $rsp->getCardNumber(); # Truncated card number
$DateExpiry = $rsp->getDateExpiry(); # in mmyy format
$DpsBillingId = $rsp->getDpsBillingId();
$BillingId = $rsp->getBillingId();
$CardHolderName = $rsp->getCardHolderName();
$DpsTxnRef = $rsp->getDpsTxnRef();
$TxnType = $rsp->getTxnType();
$TxnData1 = $rsp->getTxnData1();
$TxnData2 = $rsp->getTxnData2();
$TxnData3 = $rsp->getTxnData3();
$CurrencySettlement = $rsp->getCurrencySettlement();
$ClientInfo = $rsp->getClientInfo(); # The IP address of the user who submitted the transaction
$TxnId = $rsp->getTxnId();
$CurrencyInput = $rsp->getCurrencyInput();
$EmailAddress = $rsp->getEmailAddress();
$MerchantReference = $rsp->getMerchantReference();
$ResponseText = $rsp->getResponseText();
$TxnMac = $rsp->getTxnMac(); # An indic

$order_id = $BillingId;
// UPDATE THE ORDER STATUS to 'CONFIRMED'
if ($rsp->getSuccess() == "1")
{
	// SUCCESS: UPDATE THE ORDER STATUS to 'CONFIRMED'
	$values->order_status_code = $verify_status;
	$values->order_payment_status_code = 'Paid';

	if ($debug_mode == 1)
	{
		$values->log = $ResponseText;
		$values->msg = $ResponseText;
	}
	else
	{
		$values->log = JText::_('COM_REDSHOP_ORDER_PLACED');
		$values->msg = JText::_('COM_REDSHOP_ORDER_PLACED');
	}

	$values->order_id = $order_id;
	$values->transaction_id = $DpsTxnRef;
}
else
{
	// FAILED: UPDATE THE ORDER STATUS to 'PENDING'
	$values->order_status_code = $invalid_status;
	$values->order_payment_status_code = 'Unpaid';

	if ($debug_mode == 1)
	{
		$values->log = $ResponseText;
		$values->msg = $ResponseText;
	}
	else
	{
		$values->log = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
		$values->msg = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
	}

	$values->order_id = $order_id;
	$values->transaction_id = $DpsTxnRef;
}

$objOrder->changeorderstatus($values);
$uri = JURI::getInstance();
$url = JURI::base();
$explode = explode("plugins", $url);

$redirect_url = $explode[0] . "index.php?option=com_redshop&view=order_detail&Itemid=$Itemid&oid=" . $order_id;
$app->redirect($redirect_url, $values->msg);

function getparameters($payment)
{
	$db = JFactory::getDbo();
	$sql = "SELECT * FROM #__extensions WHERE `element`='" . $payment . "'";
	$db->setQuery($sql);
	$params = $db->loadObjectList();

	return $params;
}
