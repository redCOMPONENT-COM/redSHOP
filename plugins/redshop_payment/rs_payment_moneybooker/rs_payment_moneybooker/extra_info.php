<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

$uri    = JURI::getInstance();
$url    = $uri->root();
$user   = JFactory::getUser();
$db     = JFactory::getDbo();
$user   = JFactory::getUser();
?>
<form action="https://www.moneybookers.com/app/payment.pl" method="post" id="frmmoneybooker" name="frmmoneybooker">
	<input type="hidden" name="pay_to_email" value="<?php echo $this->params->get("pay_to_email") ?>"/>
	<input type="hidden" name="transaction_id" value="<?php echo $data['order_id'] ?>"/>
	<input type="hidden" name="return_url"
	       value="<?php echo JURI::base() ?>index.php?option=com_redshop&view=order_detail&layout=receipt&oid=<?php echo $data['order_id'] ?>">
	<input type="hidden" name="cancel_url"
	       value="<?php echo JURI::base() ?>index.php?option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_moneybooker&orderid=<?php echo $data['order_id'] ?>">
	<input type="hidden" name="status_url"
	       value="<?php echo JURI::base() ?>index.php?option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_moneybooker&orderid=<?php echo $data['order_id'] ?>"/>
	<input type="hidden" name="language" value="<?php echo $this->params->get("moneybooker_languages") ?>"/>

	<input type="hidden" name="customer_id" value="<?php echo $user->id ?>"/>
	<input type="hidden" name="pay_from_email" value="<?php echo $user->email ?>"/>
	<input type="hidden" name="amount" value="<?php echo $data['carttotal']; ?>"/>

	<input type="hidden" name="currency" value="<?php echo Redshop::getConfig()->get('CURRENCY_CODE') ?>"/>
	<input type="hidden" name="firstname" value="<?php echo $data['billinginfo']->firstname ?>"/>
	<input type="hidden" name="lastname" value="<?php echo $data['billinginfo']->lastname ?>"/>
	<input type="hidden" name="address" value="<?php echo $data['billinginfo']->address ?>&#10<?php
	echo $data['billinginfo']->city?>"/>
	<input type="hidden" name="postal_code" value="<?php echo $data['billinginfo']->state_2_code ?>"/>
	<input type="hidden" name="city" value="<?php echo $data['billinginfo']->city ?>"/>
	<input type="hidden" name="state" value="<?php echo $data['billinginfo']->state_2_code ?>"/>
	<input type="submit" value="Pay!">
</form>

<script>
	document.frmmoneybooker.submit();
</script>
