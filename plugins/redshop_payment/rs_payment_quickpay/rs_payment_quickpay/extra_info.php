<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

$uri  = JURI::getInstance();
$url  = $uri->root();
$user = JFactory::getUser();
$db   = JFactory::getDbo();

require_once JPATH_SITE . '/administrator/components/com_redshop/helpers/redshop.cfg.php';
JLoader::import('loadhelpers', JPATH_SITE . '/components/com_redshop');
JLoader::load('RedshopHelperAdminOrder');
JLoader::load('RedshopHelperHelper');

$sql = "SELECT op.*,o.order_total,o.user_id,o.order_tax,o.order_shipping, o.order_number FROM " . $this->_table_prefix . "order_payment AS op LEFT JOIN " . $this->_table_prefix . "orders AS o ON op.order_id = o.order_id  WHERE o.order_id='" . $data['order_id'] . "'";
$db->setQuery($sql);
$order_details = $db->loadObjectList();

$cart_type = $this->_params->get("cardtypes", array());

if (count($cart_type) > 0 && count($cart_type) < 2)
{
	$cart_type    = array();
	$cart_type[0] = $this->_params->get("cardtypes");
}

$oricart_type = array();

if (in_array('DANKORT', $cart_type) || in_array('ALL', $cart_type))
{
	$oricart_type[] = 'dankort';
}

if (in_array('VD', $cart_type) || in_array('ALL', $cart_type))
{
	$oricart_type[] = 'visa-dk';
}

if (in_array('VE', $cart_type) || in_array('ALL', $cart_type))
{
	$oricart_type[] = 'visa-electron';
}

if (in_array('MCDK', $cart_type) || in_array('ALL', $cart_type))
{
	$oricart_type[] = 'mastercard-dk';
}

if (in_array('MC', $cart_type) || in_array('ALL', $cart_type))
{
	$oricart_type[] = 'mastercard';
}

if (in_array('VEDK', $cart_type) || in_array('ALL', $cart_type))
{
	$oricart_type[] = 'visa-electron-dk';
}

if (in_array('JCB', $cart_type) || in_array('ALL', $cart_type))
{
	$oricart_type[] = 'jcb';
}

if (in_array('DDK', $cart_type) || in_array('ALL', $cart_type))
{
	$oricart_type[] = 'diners-dk';
}

if (in_array('MDK', $cart_type) || in_array('ALL', $cart_type))
{
	$oricart_type[] = '3d-maestro-dk';
}

if (in_array('AEDK', $cart_type) || in_array('ALL', $cart_type))
{
	$oricart_type[] = 'american-express-dk';
}

if (in_array('DINERS', $cart_type) || in_array('ALL', $cart_type))
{
	$oricart_type[] = 'diners';
}

if (in_array('AE', $cart_type) || in_array('ALL', $cart_type))
{
	$oricart_type[] = 'american-express';
}

if (in_array('MAESTRO', $cart_type) || in_array('ALL', $cart_type))
{
	$oricart_type[] = '3d-maestro';
}

if (in_array('FORBRUGSFORENINGEN', $cart_type) || in_array('ALL', $cart_type))
{
	$oricart_type[] = 'fbg1886';
}

if (in_array('VISA', $cart_type) || in_array('ALL', $cart_type))
{
	$oricart_type[] = 'visa';
}

if (in_array('NORDEA', $cart_type) || in_array('ALL', $cart_type))
{
	$oricart_type[] = 'nordea-dk';
}

if (in_array('DB', $cart_type) || in_array('ALL', $cart_type))
{
	$oricart_type[] = 'danske-dk';
}

if (in_array('edankort', $cart_type) || in_array('ALL', $cart_type))
{
	$oricart_type[] = 'edankort';
}

if (in_array('MASTERCARDDEBETCARD', $cart_type) || in_array('ALL', $cart_type))
{
	$oricart_type[] = 'mastercard-debet-dk';
}

if (count($oricart_type) > 0)
{
	$cart_type = implode(',', $oricart_type);
}

$Itemid			   = JFactory::getApplication()->input->getInt('Itemid');
$protocol          = '6';
$msgtype           = 'authorize';
$merchant_id       = $this->_params->get("quickpay_customer_id");
$testmode          = $this->_params->get("is_test");
$md5word           = $this->_params->get("quickpay_paymentkey");
$quickpay_language = $this->_params->get("language");

$qp_order_id       = $order_details[0]->order_number;
$order_amount      = round($order_details[0]->order_total, 2);
$order_amount      = $order_details[0]->order_total * 100;
$currency_code     = 'DKK';
$ok_page           = JURI::base() . "index.php?option=com_redshop&view=order_detail&layout=receipt&Itemid=$Itemid&oid=" . $data['order_id'];
$error_page        = JURI::base() . "index.php?tmpl=component&option=com_redshop&view=order_detail&controller=order_detail&Itemid=" . $Itemid . "&task=notify_payment&payment_plugin=rs_payment_quickpay&orderid=" . $data['order_id'];
$result_page       = JURI::base() . "index.php?tmpl=component&option=com_redshop&view=order_detail&controller=order_detail&Itemid=" . $Itemid . "&task=notify_payment&payment_plugin=rs_payment_quickpay&orderid=" . $data['order_id'];

$cardtypelock      = $cart_type;
$description       = 'test';
$autocapture       = $this->_params->get("autocapture");
$ipaddress         = $_SERVER['REMOTE_ADDR'];
$md5check          = md5($protocol . $msgtype . $merchant_id . $quickpay_language . $qp_order_id . $order_amount . $currency_code . $ok_page . $error_page . $result_page . $autocapture . $cardtypelock . $description . $testmode . $md5word);

?>

<form action="https://secure.quickpay.dk/form/" method="post" name="frmQuickpay" id="frmQuickpay">
	<input type="hidden" name="protocol" value="6"/>
	<input type="hidden" name="msgtype" value="<?php echo $msgtype; ?>"/>
	<input type="hidden" name="merchant" value="<?php echo $merchant_id; ?>"/>
	<input type="hidden" name="language" value="<?php echo $quickpay_language; ?>"/>
	<input type="hidden" name="ordernumber" value="<?php echo $qp_order_id; ?>"/>
	<input type="hidden" name="amount" value="<?php echo $order_amount; ?>"/>
	<input type="hidden" name="currency" value="<?php echo $currency_code; ?>"/>
	<input type="hidden" name="continueurl" value="<?php echo $ok_page; ?>"/>
	<input type="hidden" name="cancelurl" value="<?php echo $error_page; ?>"/>
	<input type="hidden" name="callbackurl" value="<?php echo $result_page; ?>"/>
	<input type="hidden" name="autocapture" value="<?php echo $autocapture; ?>"/>
	<input type="hidden" name="cardtypelock" value="<?php echo $cardtypelock; ?>"/>
	<input type="hidden" name="testmode" value="<?php echo $testmode; ?>"/>
	<input type="hidden" name="description" value="<?php echo $description; ?>">
	<input type="hidden" name="md5check" value="<?php echo $md5check; ?>"/>
</form>
<script>
	document.getElementById("frmQuickpay").submit();
</script>
