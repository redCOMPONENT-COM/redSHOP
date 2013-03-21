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
$uri =& JURI::getInstance();
$url = $uri->root();
$user = JFactory::getUser();
$db = JFactory::getDBO();

require_once (JPATH_BASE . DS . 'administrator' . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'order.php');
require_once(JPATH_COMPONENT . DS . 'helpers' . DS . 'helper.php');
require_once(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'redshop.cfg.php');
?>
<?php

$firstname = $data['billinginfo']->firstname;
$lastname = $data['billinginfo']->lastname;
$country_code = $data['billinginfo']->country_code;
$zipcode = $data['billinginfo']->zipcode;
$city = $data['billinginfo']->city;
$state_code = $data['billinginfo']->state_code;
$address = $data['billinginfo']->address;
$phone = $data['billinginfo']->phone;
?>

<?php
if (isset($_GET["status"]) && ($_GET["status"] == "OK"))
	echo "<span style=\"font-weight: bold\">Dziękujemy za dokonanie wpłaty przy użyciu serwisu Dotpay</span><br /><br />";
else
{
	?>

	<strong>To make a payment, click on the image below:</strong>
	<form action="https://ssl.dotpay.pl" method="post" id="dotpay">
		<div style="text-align: center; margin-top: 25px; margin-bottom: 25px;">
			<input type="image" name="submit"
			       src="<?php echo JURI::base() ?>plugins/redshop_payment/rs_payment_dotpay/dotpay.jpg" border="0"
			       alt="Zapłać przez Dotpay">
		</div>
		<input type="hidden" name="id" value="<?php echo $this->_params->get("dotpay_customer_id"); ?>"/>
		<input type="hidden" name="amount" value="<?php echo $data['carttotal']; ?>"/>
		<input type="hidden" name="currency" value="<?php echo CURRENCY_CODE; ?>"/>
		<input type="hidden" name="description" value="Order payment - id: <?php echo $data['order_id']; ?>"/>
		<input type="hidden" name="lang" value="pl"/>
		<input type="hidden" name="email" value="<?php echo($user_email); ?>"/>
		<input type="hidden" name="firstname" value="<?php echo $firstname; ?>"/>
		<input type="hidden" name="lastname" value="<?php echo $lastname; ?>"/>
		<input type="hidden" name="control" value="<?php echo $data['order_id']; ?>"/>
		<input type="hidden" name="URL"
		       value="<?php echo JURI::base(); ?>index.php?option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_dotpay&orderid=<?php echo $data['order_id'] ?>"/>
		<input type="hidden" name="URLC"
		       value="<?php echo JURI::base(); ?>index.php?option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_dotpay&orderid=<?php echo $data['order_id'] ?>"/>
		<input type="hidden" name="country" value="<?php echo $country_code; ?>"/>
		<input type="hidden" name="city" value="<?php echo $city; ?>"/>
		<input type="hidden" name="postcode" value="<?php echo $zipcode; ?>"/>
		<input type="hidden" name="street" value="<?php echo $address; ?>"/>
		<input type="hidden" name="street_n1" value="<?php echo(@$user_address2); ?>"/>
		<input type="hidden" name="phone" value="<?php echo $phone; ?>"/>
	</form>
	<script>
		document.getElementById("dotpay").submit();
	</script>
<?php
}
?>