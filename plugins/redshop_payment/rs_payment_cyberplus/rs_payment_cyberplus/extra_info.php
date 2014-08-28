<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

require_once JPATH_SITE . '/administrator/components/com_redshop/helpers/redshop.cfg.php';
JLoader::import('loadhelpers', JPATH_SITE . '/components/com_redshop');
JLoader::load('RedshopHelperHelper');
JLoader::load('RedshopHelperCurrency');

$objOrder         = new order_functions;
$objconfiguration = new Redconfiguration;
$CurrencyHelper   = new CurrencyHelper;

$user             = JFactory::getUser();
$shipping_address = $objOrder->getOrderShippingUserInfo($data['order_id']);
$redhelper        = new redhelper;
$db               = JFactory::getDbo();
$task             = JRequest::getVar('task');
$app              = JFactory::getApplication();

$sql = "SELECT op.*,o.order_total,o.user_id,o.order_tax,o.order_subtotal,o.order_shipping,o.order_number,o.payment_discount FROM #__redshop_order_payment AS op LEFT JOIN #__redshop_orders AS o ON op.order_id = o.order_id  WHERE o.order_id='" . $data['order_id'] . "'";
$db->setQuery($sql);
$order_details = $db->loadObjectList();

// Buyer details
$buyeremail     = $data['billinginfo']->user_email;
$buyerfirstname = $data['billinginfo']->firstname;
$buyerlastname  = $data['billinginfo']->lastname;
$CN             = $buyerfirstname . " " . $buyerlastname;
$ownerZIP       = $data['billinginfo']->zipcode;
$owneraddress   = $data['billinginfo']->address;
$ownercty       = $data['billinginfo']->city;
$country        = $data['billinginfo']->country_2_code;
$phone          = $data['billinginfo']->phone;
$cartId         = $data['order_id'];

$site_id            = $this->params->get("site_id");
$certificate_number = $this->params->get("certificate_number");
$is_test            = $this->params->get("is_test");

$order_subtotal = number_format($order_details[0]->order_total, 2, '.', '');

$key = $certificate_number;

// Initialization of parameters
$params = array(); // entry form of the parameters table
$params['vads_site_id'] = $site_id;
$params['vads_amount'] = 100 * $order_subtotal;

// In cents
$params['vads_currency'] = $CurrencyHelper->get_iso_code(CURRENCY_CODE);

// ISO 4217 standard
if ($is_test == 1)
{
	$params['vads_ctx_mode'] = "TEST";
}
else
{
	$params['vads_ctx_mode'] = "PRODUCTION";
}

$params['vads_page_action'] = "PAYMENT";
$params['vads_action_mode'] = "INTERACTIVE";

// Card entry performed by the platform
$params['vads_payment_config'] = "SINGLE";
$params['vads_version']        = "V2";
$params['vads_cust_id']        = $user->id;
$params['vads_cust_name']      = $CN;
$params['vads_cust_phone']     = $phone;
$params['vads_cust_city']      = $ownercty;
$params['vads_cust_zip']       = $ownerZIP;
$params['vads_cust_address']   = $owneraddress;
$params['vads_cust_country']   = $country;
$params['vads_cust_email']     = $buyeremail;

// Example of trans_id generation based on transaction date
$ts                         = time();
$params['vads_trans_date']  = gmdate("YmdHis", $ts);
$params['vads_trans_id']    = gmdate("His", $ts);
$params['vads_return_mode'] = "POST";
$params['vads_url_success'] = JURI::base() . "index.php?option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_cyberplus&Itemid=" . $_REQUEST['Itemid'] . "&orderid=" . $data['order_id'];
$params['vads_url_return']  = JURI::base() . "index.php?option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_cyberplus&Itemid=" . $_REQUEST['Itemid'] . "&orderid=" . $data['order_id'];
$params['vads_url_refused'] = JURI::base() . "index.php?option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_cyberplus&Itemid=" . $_REQUEST['Itemid'] . "&orderid=" . $data['order_id'];
$params['vads_url_cancel']  = JURI::base() . "index.php?option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_cyberplus&Itemid=" . $_REQUEST['Itemid'] . "&orderid=" . $data['order_id'];

// Signature generation
// Sorting of parameters in alphabetical order
ksort($params);

$contenu_signature = "";

foreach ($params as $nom => $valeur)
{
	$contenu_signature .= $valeur . "+";
}

$contenu_signature .= $key;

// Certificate is added at the end
$params['signature'] = sha1($contenu_signature);

?>

<form method="POST" action="https://systempay.cyberpluspaiement.com/vads-payment/" name="cyberfrm" id="cyberfrm">
	<?php
	foreach ($params as $nom => $valeur)
	{
		echo '<input type="hidden" name="' . $nom . '" value="' . $valeur . '" />';
	}
	?>

</form>

<script type='text/javascript'>document.cyberfrm.submit();</script>
