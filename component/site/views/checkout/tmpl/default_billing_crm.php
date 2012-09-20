<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 * Developed by email@recomponent.com - redCOMPONENT.com
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
defined ( '_JEXEC' ) or die ( 'restricted access' );

$option = JRequest::getCmd ( 'option','redshop' );

$model = $this->getModel ( 'checkout' );

$uri = & JURI::getInstance ();
$url = $uri->root ();

$redhelper = new redhelper();
$Itemid = $redhelper->getCheckoutItemid ();
if ($Itemid == 0)
	$Itemid = JRequest::getVar ( 'Itemid' );

$session = & JFactory::getSession ();

$order_functions = new order_functions();
$extra_field = new extra_field ();

$billingaddresses = $model->billingaddresses ();

?>
<table class="admintable">
<?php
if ($billingaddresses->is_company == 1) {
?>
<tr>
	<td width="100" align="left"><label><?php echo JText::_ ( 'COMPANY_NAME' );?>:</label></td>
	<td><?php echo $billingaddresses->company_name;?></td>
</tr>
<?php
}?>
<tr>
	<td width="100" align="left"><label><?php echo JText::_ ( 'FIRSTNAME' );?>:</label></td>
	<td><?php echo $billingaddresses->firstname;?></td>
</tr>
<tr>
	<td width="100" align="left"><label><?php echo JText::_ ( 'LASTNAME' );?>:</label></td>
	<td><?php echo $billingaddresses->lastname;?></td>
</tr>
<?php
if ($billingaddresses->address != "") {
?>
<tr>
	<td width="100" align="left"><label for="address"><?php echo JText::_ ( 'ADDRESS' );?>:</label></td>
	<td><?php echo $billingaddresses->address;?></td>
</tr>
<?php
}

if ($billingaddresses->zipcode != "") {
?>
<tr>
	<td width="100" align="left"><label for="city"><?php echo JText::_ ( 'ZIP' );?>:</label></td>
	<td><?php echo $billingaddresses->zipcode;?></td>
</tr>
<?php
}

if ($billingaddresses->city != "") {
?>
<tr>
	<td width="100" align="left"><label for="city"><?php echo JText::_ ( 'CITY' );?>:</label></td>
	<td><?php echo $billingaddresses->city;?></td>
</tr>
<?php
}

if (trim ( $billingaddresses->country_code ) != "" && trim ( $billingaddresses->country_code )) {
?>
<tr>
	<td width="100" align="left"><label for="contact_info"><?php echo JText::_ ( 'COUNTRY' );?>:</label></td>
	<td><?php echo JText::_ ( $order_functions->getCountryName( $billingaddresses->country_code ) );?></td>
</tr>
<?php
}
$state=$order_functions->getStateName( $billingaddresses->state_code, $billingaddresses->country_code );
if ($state!="") {
?>
<tr>
	<td width="100" align="left"><label for="address"><?php echo JText::_ ( 'STATE' );?>:</label></td>
	<td><?php echo $state;?></td>
</tr>
<?php
}

if ($billingaddresses->phone != "") {
?>
<tr>
	<td width="100" align="left"><label for="city"><?php echo JText::_ ( 'PHONE' );?>:</label></td>
	<td><?php echo $billingaddresses->phone;?></td>
</tr>
<?php
}
if ($billingaddresses->debitor_mobile_phone != 0) {
?>
<tr>
	<td width="100" align="left"><label for="city"><?php echo JText::_ ( 'MOBILE_PHONE' );?>:</label></td>
	<td><?php echo $billingaddresses->debitor_mobile_phone;?></td>
</tr>
<?php
}
if ($billingaddresses->user_email != "") {
?>
<tr>
	<td width="100" align="left"><label><?php echo JText::_ ( 'EMAIL' );?>:</label></td>
	<td><?php echo $billingaddresses->user_email ? $billingaddresses->user_email : $user->email;?></td>
</tr>
<?php
}
if ($billingaddresses->is_company == 1)
{	?>
<tr><td width="100" align="left"><label><?php echo JText::_ ( 'EAN_NUMBER' );?>:</label></td>
	<td><?php echo $billingaddresses->ean_number;?></td></tr>
<!-- <tr><td width="100" align="left"><label><?php echo JText::_ ( 'REQUISITION_NUMBER' );?>:</label></td>
	<td><?php echo $billingaddresses->requisition_number;?></td></tr>-->
<?php
}
if ($billingaddresses->is_company == 1 && USE_TAX_EXEMPT == 1){
?>
<tr>
	<td width="100" align="left"><label for="vat_number"><?php echo JText::_ ( 'VAT_NUMBER' );?>:</label></td>
	<td><?php echo $billingaddresses->vat_number;?></td>
</tr>
<?php if(SHOW_TAX_EXEMPT_INFRONT){ ?>
<tr>
	<td width="100" align="left"><label for="tax_exempt"><?php echo JText::_ ( 'TAX_EXEMPT' );?>:</label></td>
	<td><?php
		if ($billingaddresses->tax_exempt == 1) {
			echo JText::_( 'YES' );
		} else {
			echo JText::_( 'NO' );
		}
		?>
	</td>
</tr>
<?php }?>
<?php
}
if ($billingaddresses->debitor_money_transfer_number >0){
?>

<tr>
	<td width="100" align="left"><label for="debitor_money_transfer_number"><?php echo JText::_ ( 'DEBITOR_MONEY_TRANSFER_NUMBER' );?>:</label></td>
	<td><?php echo $billingaddresses->debitor_money_transfer_number;?></td>
</tr>

<?php
}

if ($billingaddresses->is_company == 1) {
	echo $extrafields = $extra_field->list_all_field_display ( 8, $billingaddresses->users_info_id );
} else {
	echo $extrafields = $extra_field->list_all_field_display ( 7, $billingaddresses->users_info_id );
}
?>
</table>