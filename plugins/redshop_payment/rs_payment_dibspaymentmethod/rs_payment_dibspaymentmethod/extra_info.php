<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

$uri = JURI::getInstance();
$url = $uri->root();
$user = JFactory::getUser();
$db = JFactory::getDbo();

require_once JPATH_BASE . '/administrator/components/com_redshop/helpers/order.php';
require_once JPATH_COMPONENT . '/helpers/helper.php';
require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/redshop.cfg.php';

$sql = "SELECT op.*,o.order_total,o.user_id,o.order_tax,o.order_shipping FROM " . $this->_table_prefix . "order_payment AS op LEFT JOIN " . $this->_table_prefix . "orders AS o ON op.order_id = o.order_id  WHERE o.order_id='" . $data['order_id'] . "'";
$db->setQuery($sql);
$order_details = $db->loadObjectList();
$request = JRequest::get('REQUEST');
$task = $request['task'];

$db = JFactory::getDbo();

$q = "SELECT * FROM " . $this->_table_prefix . "order_item WHERE order_id=" . $data['order_id'];
$db->setQuery($q);
$rs = $db->loadObjectlist();

//Authnet vars to send
$formdata = array(
	'merchant'          => $this->_params->get("seller_id"),
	'orderid'           => $data['order_id'],
	'currency'          => $this->_params->get("dibs_currency"),
	//'accepturl' => JURI::base()."index.php?option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&status=ok&payment_plugin=rs_payment_dibspaymentmethod&orderid=".$data['order_id'],
	//'cancelurl' => JURI::base()."index.php?option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&status=cancel&payment_plugin=rs_payment_dibspaymentmethod&orderid=".$data['order_id'],
	'ip'                => $_SERVER['REMOTE_ADDR'],

	// Customer Shipping Address

	'delivery1.Name'    => $data['shippinginfo']->firstname . " " . $data['shippinginfo']->lastname,
	'delivery2.Address' => $data['shippinginfo']->address . "," . $data['shippinginfo']->city . "," . $data['shippinginfo']->state_code . "," . $data['shippinginfo']->country_code,

	//order detail
	'ordline0-1'        => 'Product Id',
	'ordline0-2'        => 'Product Name',
	'ordline0-3'        => 'Quantity',
	'ordline0-4'        => 'Price',

	// Extra parameters
	'flexlang'          => $this->_params->get("dibs_languages"),
	'pay_type'          => $this->_params->get("dibs_pay_type"),
	'flexwin_color'     => $this->_params->get("dibs_color"),
	'flexwin_decorator' => $this->_params->get("dibs_flexwin_decorator"),
	'md5key1'           => $this->_params->get("dibs_md5key1"),
	'md5key2'           => $this->_params->get("dibs_md5key2"),
	'dibs_uniqueid'     => $this->_params->get("dibs_uniqueid"),
	'forcecurrency'     => $this->_params->get("dibs_forcecurrency")
);

for ($p = 0; $p < count($rs); $p++)
{
	$formdata['ordline' . ($p + 1) . '-1'] = $rs[$p]->product_id;
	$formdata['ordline' . ($p + 1) . '-2'] = $rs[$p]->order_item_name;
	//  $formdata['ordline'.($p+1).'-2'] = "å ä ö ";
	$formdata['ordline' . ($p + 1) . '-3'] = $rs[$p]->product_quantity;
	$formdata['ordline' . ($p + 1) . '-4'] = $rs[$p]->product_item_price;
}

/* extra info */

if ($this->_params->get("is_test") == "1")
	$formdata['test'] = "yes";

$version = "2";
//$dibsurl = "https://payment.architrade.com/paymentweb/start.action";
$dibsurl = "https://payment.architrade.com/payment/start.pml";
$currencyClass = new CurrencyHelper;
$formdata['amount'] = $currencyClass->convert($order_details[0]->order_total, '', $this->_params->get("dibs_currency"));
$formdata['amount'] = number_format($formdata['amount'], 2, '.', '') * 100;

if ($formdata['flexlang'] == "Auto")
{
	$dibs_lang_arr = array('Denmark'       => 'da',
	                       'Sweden'        => 'sv',
	                       'Norway'        => 'no',
	                       'Finland'       => 'fi',
	                       'Germany'       => 'de',
	                       'Netherlands'   => 'nl',
	                       'France'        => 'fr',
	                       'Spain'         => 'es',
	                       'Italy'         => 'it',
	                       'Faroe Islands' => 'fo');

	if ($lang != "" && isset($lang))
	{
		$formdata["lang"] = $lang;
	}
	else
	{
		$lang = 'en';
		$formdata["lang"] = $lang;
	}
}

if ($formdata['flexlang'] != "Auto")
{
	$formdata["lang"] = $formdata['flexlang'];
}

if ($formdata["flexwin_decorator"] != "Own Decorator")
{
	$formdata["decorator"] = $formdata["flexwin_decorator"];
	$formdata["color"] = $formdata["flexwin_color"];
}

if ($formdata["md5key1"] != "" && $formdata["md5key2"] != "")
{
	$md5key = md5($formdata["md5key2"] . md5($formdata["md5key1"] . 'merchant=' . $formdata["merchant"] . '&orderid=' . $data['order_id'] . '&currency=' . $formdata['currency'] . '&amount=' . $formdata['amount']));
	$formdata["md5key"] = $md5key;
	$formdata["dibs_uniqueid"] = 'yes';
}

//build the post string
$poststring = '';

/* 	$query_string = "?";
		foreach( $formdata as $name => $value ) {
		$query_string .= $name ."=" . urlencode($value) ."&";
		  }

$app->redirect( $dibsurl . $query_string );*/

?>
<form action="<?php echo $dibsurl ?>" id='dibscheckout' name="dibscheckout" target="myNewWin" method="post">
	<?php foreach ($formdata as $name => $value)
	{ ?>
		<input type="hidden" name="<?php echo $name ?>" value="<?php echo urlencode($value) ?>"/>
	<?php } ?>
	<?php echo $poststring; ?>
	<?php
	$accepturl = JURI::base() . "index.php?option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_dibspaymentmethod&orderid=" . $data['order_id'];
	$cancelurl = JURI::base() . "index.php?option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_dibspaymentmethod&orderid=" . $data['order_id'];

	?>
	<input type="hidden" name="accepturl" value="<?php echo $accepturl; ?>"/>
	<input type="hidden" name="cancelurl" value="<?php echo $cancelurl; ?>"/>

</form>

<script>
	function redirectOutput() {
		var w = window.open('', 'Popup_Window', "width=700,height=500,toolbar=1");
		document.dibscheckout.target = 'Popup_Window';
		document.dibscheckout.submit();
		return true;
	}
</script>
<script type="text/javascript">
	window.onload = redirectOutput;
	//function pay(){
	//	alert("dasdas");
	//    window.open("","myNewWin","width=700,height=500,toolbar=1");
	//    var a = window.setTimeout("document.dibscheckout.submit();",500);
	//}
</script>
