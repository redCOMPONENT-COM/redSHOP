<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

JLoader::import('LoadHelpers', JPATH_SITE . '/components/com_redshop');
JLoader::load('RedshopHelperHelper');
require_once JPATH_SITE . '/administrator/components/com_redshop/helpers/redshop.cfg.php';
$objOrder = new order_functions;

$objconfiguration = new Redconfiguration;

// get currency

function get_iso_code($code)
{
	switch ($code)
	{
		case "ADP":
			return "020";
			break;
		case "AED":
			return "784";
			break;
		case "AFA":
			return "004";
			break;
		case "ALL":
			return "008";
			break;
		case "AMD":
			return "051";
			break;
		case "ANG":
			return "532";
			break;
		case "AOA":
			return "973";
			break;
		case "ARS":
			return "032";
			break;
		case "AUD":
			return "036";
			break;
		case "AWG":
			return "533";
			break;
		case "AZM":
			return "031";
			break;
		case "BAM":
			return "977";
			break;
		case "BBD":
			return "052";
			break;
		case "BDT":
			return "050";
			break;
		case "BGL":
			return "100";
			break;
		case "BGN":
			return "975";
			break;
		case "BHD":
			return "048";
			break;
		case "BIF":
			return "108";
			break;
		case "BMD":
			return "060";
			break;
		case "BND":
			return "096";
			break;
		case "BOB":
			return "068";
			break;
		case "BOV":
			return "984";
			break;
		case "BRL":
			return "986";
			break;
		case "BSD":
			return "044";
			break;
		case "BTN":
			return "064";
			break;
		case "BWP":
			return "072";
			break;
		case "BYR":
			return "974";
			break;
		case "BZD":
			return "084";
			break;
		case "CAD":
			return "124";
			break;
		case "CDF":
			return "976";
			break;
		case "CHF":
			return "756";
			break;
		case "CLF":
			return "990";
			break;
		case "CLP":
			return "152";
			break;
		case "CNY":
			return "156";
			break;
		case "COP":
			return "170";
			break;
		case "CRC":
			return "188";
			break;
		case "CUP":
			return "192";
			break;
		case "CVE":
			return "132";
			break;
		case "CYP":
			return "196";
			break;
		case "CZK":
			return "203";
			break;
		case "DJF":
			return "262";
			break;
		case "DKK":
			return "208";
			break;
		case "DOP":
			return "214";
			break;
		case "DZD":
			return "012";
			break;
		case "ECS":
			return "218";
			break;
		case "ECV":
			return "983";
			break;
		case "EEK":
			return "233";
			break;
		case "EGP":
			return "818";
			break;
		case "ERN":
			return "232";
			break;
		case "ETB":
			return "230";
			break;
		case "EUR":
			return "978";
			break;
		case "FJD":
			return "242";
			break;
		case "FKP":
			return "238";
			break;
		case "GBP":
			return "826";
			break;
		case "GEL":
			return "981";
			break;
		case "GHC":
			return "288";
			break;
		case "GIP":
			return "292";
			break;
		case "GMD":
			return "270";
			break;
		case "GNF":
			return "324";
			break;
		case "GTQ":
			return "320";
			break;
		case "GWP":
			return "624";
			break;
		case "GYD":
			return "328";
			break;
		case "HKD":
			return "344";
			break;
		case "HNL":
			return "340";
			break;
		case "HRK":
			return "191";
			break;
		case "HTG":
			return "332";
			break;
		case "HUF":
			return "348";
			break;
		case "IDR":
			return "360";
			break;
		case "ILS":
			return "376";
			break;
		case "INR":
			return "356";
			break;
		case "IQD":
			return "368";
			break;
		case "IRR":
			return "364";
			break;
		case "ISK":
			return "352";
			break;
		case "JMD":
			return "388";
			break;
		case "JOD":
			return "400";
			break;
		case "JPY":
			return "392";
			break;
		case "KES":
			return "404";
			break;
		case "KGS":
			return "417";
			break;
		case "KHR":
			return "116";
			break;
		case "KMF":
			return "174";
			break;
		case "KPW":
			return "408";
			break;
		case "KRW":
			return "410";
			break;
		case "KWD":
			return "414";
			break;
		case "KYD":
			return "136";
			break;
		case "KZT":
			return "398";
			break;
		case "LAK":
			return "418";
			break;
		case "LBP":
			return "422";
			break;
		case "LKR":
			return "144";
			break;
		case "LRD":
			return "430";
			break;
		case "LSL":
			return "426";
			break;
		case "LTL":
			return "440";
			break;
		case "LVL":
			return "428";
			break;
		case "LYD":
			return "434";
			break;
		case "MAD":
			return "504";
			break;
		case "MDL":
			return "498";
			break;
		case "MGF":
			return "450";
			break;
		case "MKD":
			return "807";
			break;
		case "MMK":
			return "104";
			break;
		case "MNT":
			return "496";
			break;
		case "MOP":
			return "446";
			break;
		case "MRO":
			return "478";
			break;
		case "MTL":
			return "470";
			break;
		case "MUR":
			return "480";
			break;
		case "MVR":
			return "462";
			break;
		case "MWK":
			return "454";
			break;
		case "MXN":
			return "484";
			break;
		case "MXV":
			return "979";
			break;
		case "MYR":
			return "458";
			break;
		case "MZM":
			return "508";
			break;
		case "NAD":
			return "516";
			break;
		case "NGN":
			return "566";
			break;
		case "NIO":
			return "558";
			break;
		case "NOK":
			return "578";
			break;
		case "NPR":
			return "524";
			break;
		case "NZD":
			return "554";
			break;
		case "OMR":
			return "512";
			break;
		case "PAB":
			return "590";
			break;
		case "PEN":
			return "604";
			break;
		case "PGK":
			return "598";
			break;
		case "PHP":
			return "608";
			break;
		case "PKR":
			return "586";
			break;
		case "PLN":
			return "985";
			break;
		case "PYG":
			return "600";
			break;
		case "QAR":
			return "634";
			break;
		case "ROL":
			return "642";
			break;
		case "RUB":
			return "643";
			break;
		case "RUR":
			return "810";
			break;
		case "RWF":
			return "646";
			break;
		case "SAR":
			return "682";
			break;
		case "SBD":
			return "090";
			break;
		case "SCR":
			return "690";
			break;
		case "SDD":
			return "736";
			break;
		case "SEK":
			return "752";
			break;
		case "SGD":
			return "702";
			break;
		case "SHP":
			return "654";
			break;
		case "SIT":
			return "705";
			break;
		case "SKK":
			return "703";
			break;
		case "SLL":
			return "694";
			break;
		case "SOS":
			return "706";
			break;
		case "SRG":
			return "740";
			break;
		case "STD":
			return "678";
			break;
		case "SVC":
			return "222";
			break;
		case "SYP":
			return "760";
			break;
		case "SZL":
			return "748";
			break;
		case "THB":
			return "764";
			break;
		case "TJS":
			return "972";
			break;
		case "TMM":
			return "795";
			break;
		case "TND":
			return "788";
			break;
		case "TOP":
			return "776";
			break;
		case "TPE":
			return "626";
			break;
		case "TRL":
			return "792";
			break;
		case "TRY":
			return "949";
			break;
		case "TTD":
			return "780";
			break;
		case "TWD":
			return "901";
			break;
		case "TZS":
			return "834";
			break;
		case "UAH":
			return "980";
			break;
		case "UGX":
			return "800";
			break;
		case "USD":
			return "840";
			break;
		case "UYU":
			return "858";
			break;
		case "UZS":
			return "860";
			break;
		case "VEB":
			return "862";
			break;
		case "VND":
			return "704";
			break;
		case "VUV":
			return "548";
			break;
		case "XAF":
			return "950";
			break;
		case "XCD":
			return "951";
			break;
		case "XOF":
			return "952";
			break;
		case "XPF":
			return "953";
			break;
		case "YER":
			return "886";
			break;
		case "YUM":
			return "891";
			break;
		case "ZAR":
			return "710";
			break;
		case "ZMK":
			return "894";
			break;
		case "ZWD":
			return "716";
			break;
	}
	return "XXX"; // return invalid code if the currency is not found
}


$user = JFactory::getUser();
$shipping_address = $objOrder->getOrderShippingUserInfo($data['order_id']);

$redhelper = new redhelper;
$db = JFactory::getDbo();

$task = JRequest::getVar('task');
$app = JFactory::getApplication();

$sql = "SELECT op.*,o.order_total,o.user_id,o.order_tax,o.order_subtotal,o.order_shipping,o.order_number,o.payment_discount FROM " . $this->_table_prefix . "order_payment AS op LEFT JOIN " . $this->_table_prefix . "orders AS o ON op.order_id = o.order_id  WHERE o.order_id='" . $data['order_id'] . "'";
$db->setQuery($sql);
$order_details = $db->loadObjectList();

// buyer details

$buyeremail = $data['billinginfo']->user_email;
$buyerfirstname = $data['billinginfo']->firstname;
$buyerlastname = $data['billinginfo']->lastname;
$CN = $buyerfirstname . " " . $buyerlastname;
$ownerZIP = $data['billinginfo']->zipcode;
$owneraddress = $data['billinginfo']->address;
$ownercty = $data['billinginfo']->city;
$country = $data['billinginfo']->country_2_code;
$phone = $data['billinginfo']->phone;
$cartId = $data['order_id'];
// End

$site_id = $this->_params->get("site_id");
$certificate_number = $this->_params->get("certificate_number");
$is_test = $this->_params->get("is_test");

$order_subtotal = number_format($order_details[0]->order_total, 2, '.', '');

$key = $certificate_number;
// Initialization of parameters
$params = array(); // entry form of the parameters table
$params['vads_site_id'] = $site_id;
$params['vads_amount'] = 100 * $order_subtotal;
// in cents
$params['vads_currency'] = get_iso_code(CURRENCY_CODE);
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
// card entry performed by the platform
$params['vads_payment_config'] = "SINGLE";
$params['vads_version'] = "V2";
$params['vads_cust_id'] = $user->id;
$params['vads_cust_name'] = $CN;
$params['vads_cust_phone'] = $phone;
$params['vads_cust_city'] = $ownercty;
$params['vads_cust_zip'] = $ownerZIP;
$params['vads_cust_address'] = $owneraddress;
$params['vads_cust_country'] = $country;
$params['vads_cust_email'] = $buyeremail;

// Example of trans_id generation based on transaction date
$ts = time();
$params['vads_trans_date'] = gmdate("YmdHis", $ts);
$params['vads_trans_id'] = gmdate("His", $ts);
$params['vads_return_mode'] = "POST";
$params['vads_url_success'] = JURI::base() . "index.php?option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_cyberplus&Itemid=" . $_REQUEST['Itemid'] . "&orderid=" . $data['order_id'];
$params['vads_url_return'] = JURI::base() . "index.php?option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_cyberplus&Itemid=" . $_REQUEST['Itemid'] . "&orderid=" . $data['order_id'];
$params['vads_url_refused'] = JURI::base() . "index.php?option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_cyberplus&Itemid=" . $_REQUEST['Itemid'] . "&orderid=" . $data['order_id'];
$params['vads_url_cancel'] = JURI::base() . "index.php?option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_cyberplus&Itemid=" . $_REQUEST['Itemid'] . "&orderid=" . $data['order_id'];
// Signature generation
ksort($params); // sorting of parameters in alphabetical order
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