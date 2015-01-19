<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */


$uri  = JURI::getInstance();
$url  = $uri->root();
$user = JFactory::getUser();
$db   = JFactory::getDbo();
$config = JFactory::getConfig();
$Itemid = JFactory::getApplication()->input->getInt('Itemid');

require_once JPATH_SITE . '/administrator/components/com_redshop/helpers/redshop.cfg.php';
JLoader::import('redshop.library');
JLoader::load('RedshopHelperAdminOrder');
JLoader::load('RedshopHelperHelper');

$sql = "SELECT op.*,o.order_total,o.user_id,o.order_tax,o.order_shipping, o.order_number, o.order_id FROM #__redshop_order_payment AS op LEFT JOIN #__redshop_orders AS o ON op.order_id = o.order_id  WHERE o.order_id='" . $data['order_id'] . "'";
$db->setQuery($sql);
$order_details = $db->loadObjectList();

$cart_type = $this->params->get("cardtypes", array());

if (count($cart_type) > 0 && count($cart_type) < 2)
{
	$cart_type    = array();
	$cart_type[0] = $this->params->get("cardtypes");
}

$oricart_type = array();
$all = in_array('ALL', $cart_type);

if (in_array('DANKORT', $cart_type) || $all)
{
	$oricart_type[] = 'dankort';
}

if (in_array('VD', $cart_type) || $all)
{
	$oricart_type[] = 'visa-dk';
}

if (in_array('VE', $cart_type) || $all)
{
	$oricart_type[] = 'visa-electron';
}

if (in_array('MCDK', $cart_type) || $all)
{
	$oricart_type[] = 'mastercard-dk';
}

if (in_array('MC', $cart_type) || $all)
{
	$oricart_type[] = 'mastercard';
}

if (in_array('VEDK', $cart_type) || $all)
{
	$oricart_type[] = 'visa-electron-dk';
}

if (in_array('JCB', $cart_type) || $all)
{
	$oricart_type[] = 'jcb';
}

if (in_array('DDK', $cart_type) || $all)
{
	$oricart_type[] = 'diners-dk';
}

if (in_array('MDK', $cart_type) || $all)
{
	$oricart_type[] = '3d-maestro-dk';
}

if (in_array('AEDK', $cart_type) || $all)
{
	$oricart_type[] = 'american-express-dk';
}

if (in_array('DINERS', $cart_type) || $all)
{
	$oricart_type[] = 'diners';
}

if (in_array('AE', $cart_type) || $all)
{
	$oricart_type[] = 'american-express';
}

if (in_array('MAESTRO', $cart_type) || $all)
{
	$oricart_type[] = '3d-maestro';
}

if (in_array('FORBRUGSFORENINGEN', $cart_type) || $all)
{
	$oricart_type[] = 'fbg1886';
}

if (in_array('VISA', $cart_type) || $all)
{
	$oricart_type[] = 'visa';
}

if (in_array('NORDEA', $cart_type) || $all)
{
	$oricart_type[] = 'nordea-dk';
}

if (in_array('DB', $cart_type) || $all)
{
	$oricart_type[] = 'danske-dk';
}

if (in_array('edankort', $cart_type) || $all)
{
	$oricart_type[] = 'edankort';
}

if (in_array('MASTERCARDDEBETCARD', $cart_type) || $all)
{
	$oricart_type[] = 'mastercard-debet-dk';
}
if (in_array('PAII', $cart_type) || $all)
{
	$oricart_type[] = 'paii';
}

if (count($oricart_type) > 0)
{
	$cart_type = implode(',', $oricart_type);
}

$form = new stdClass();
$form->protocol		= '7';
$form->msgtype		= 'authorize';
$form->merchant		= $this->params->get("quickpay_customer_id");
$form->language		= $this->params->get("language");
$form->ordernumber 	= $order_details[0]->order_id;
$form->amount		= ($order_details[0]->order_total * 100);
$form->currency		= 'DKK';

$form->continueurl	= JURI::base() . "index.php?option=com_redshop&view=order_detail&layout=receipt&Itemid=".$Itemid."&oid=" . $data['order_id'];
$form->cancelurl	= JURI::base() . "index.php?tmpl=component&option=com_redshop&view=order_detail&controller=order_detail&Itemid=" . $Itemid . "&task=notify_payment&payment_plugin=rs_payment_quickpay&orderid=" . $data['order_id'];
$form->callbackurl	= JURI::base() . "index.php?tmpl=component&option=com_redshop&view=order_detail&controller=order_detail&Itemid=" . $Itemid . "&task=notify_payment&payment_plugin=rs_payment_quickpay&orderid=" . $data['order_id'];

$form->autocapture	= $this->params->get("autocapture",0);
$form->cardtypelock = $cart_type;
$form->testmode 	= $this->params->get("is_test");

/**
 * on test account i get errors in md5 if i use "autocapture".
 */
if($form->testmode)
{
	unset($form->autocapture);
}

$form->md5secret 	= $this->params->get("quickpay_paymentkey");
$form->md5check = md5(implode("",(array)$form));

if(in_array("paii",$oricart_type))
{
	$form->CUSTOM_reference_title = $config->get( 'sitename' );
	$form->CUSTOM_category = "SC21";
	$form->CUSTOM_product_id = "P03";
	$form->CUSTOM_vat_amount = $order_details[0]->order_tax;
}

unset($form->md5secret);
?>
<form action="https://secure.quickpay.dk/form/" method="post" id="frmQuickpay">
	<?php foreach($form As $name=>$value): ?>
		<input type="hidden" name="<?=$name;?>" value="<?php echo $value;?>" />
	<?php endforeach; ?>
</form>

<script>
	document.getElementById("frmQuickpay").submit();
</script>
