<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

JLoader::import('redshop.library');

$objOrder         = order_functions::getInstance();
$objconfiguration = Redconfiguration::getInstance();
$user             = JFactory::getUser();
$shipping_address = RedshopHelperOrder::getOrderShippingUserInfo($data['order_id']);
$redhelper        = redhelper::getInstance();
$db               = JFactory::getDbo();
$user             = JFActory::getUser();
$task             = JRequest::getVar('task');
$app              = JFactory::getApplication();

$sql = "SELECT op.*,o.order_total,o.user_id,o.order_tax,o.order_subtotal,o.order_shipping,o.order_number,o.payment_discount FROM #__redshop_order_payment AS op LEFT JOIN #__redshop_orders AS o ON op.order_id = o.order_id  WHERE o.order_id='" . $data['order_id'] . "'";
$db->setQuery($sql);
$order_details = $db->loadObjectList();

$buyeremail     = $data['billinginfo']->user_email;
$buyerfirstname = $data['billinginfo']->firstname;
$buyerlastname  = $data['billinginfo']->lastname;
$CN             = $buyerfirstname . "&nbsp;" . $buyerlastname;
$ownerZIP       = $data['billinginfo']->zipcode;
$owneraddress   = $data['billinginfo']->address;
$ownercty       = $data['billinginfo']->city;

if ($this->params->get("is_test") == '1')
{
	$postfinanceurl = "https://e-payment.postfinance.ch/ncol/test/orderstandard.asp";
}
else
{
	$postfinanceurl = "https://e-payment.postfinance.ch/ncol/prod/orderstandard.asp";
}

$order->order_subtotal = round(RedshopHelperCurrency::convert($order_details[0]->order_total, '', 'USD'), 2) * 100;

$post_variables = Array(
	"orderID"      => $data['order_id'],
	"currency"     => "USD",
	"PSPID"        => $this->params->get("postpayment_shopid"),
	"amount"       => $order->order_subtotal,
	"language"     => "en_US",
	"CN"           => $CN,
	"EMAIL"        => $buyeremail,
	"ownerZIP"     => $ownerZIP,
	"owneraddress" => $owneraddress,
	"ownercty"     => $ownercty,
	"accepturl"    => JURI::base() . "index.php?option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_postfinance",
	"declineurl"   => JURI::base() . "index.php?option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_postfinance",
	"exceptionurl" => "http://www.google.com",
	"cancelurl"    => JURI::base() . "index.php"
);

?>
    <form id='postfinanacefrm' name='postfinanacefrm' action='<?php echo $postfinanceurl; ?>' method='post'>
        <input type="button" onclick="sendPostFinanace()" value="Submit">
		<?php foreach ($post_variables as $name => $value): ?>
            <input type='hidden' name='<?php echo $name; ?>' value='<?php echo $value; ?>'/>
		<?php endforeach; ?>
    </form>
<?php
JFactory::getDocument()->addScriptDeclaration('
	window.onload = function(){
		sendPostFinanace();
	};

	var sendPostFinanace = function(){
		document.postfinanacefrm.submit();
	};
');
