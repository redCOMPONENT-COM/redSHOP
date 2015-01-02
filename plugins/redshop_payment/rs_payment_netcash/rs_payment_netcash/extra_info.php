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
$option = JRequest::getVar('option');

$netcash_username        = $this->params->get("username");
$netcash_password        = $this->params->get("password");
$netcash_pin             = $this->params->get("pin");
$netcash_terminal_number = $this->params->get("terminal_number");
$gateway_popup           = $this->params->get("gateway_popup");

?>
<form method="POST" action="https://gateway.netcash.co.za/vvonline/ccnetcash.asp" name="netcash" id="netcash">
	<input type="hidden" name="m_1" value="<?php echo $netcash_username; ?>">
	<input type="hidden" name="m_2" value="<?php echo $netcash_password; ?>">
	<input type="hidden" name="m_3" value="<?php echo $netcash_pin; ?>">
	<input type="hidden" name="p1" value="<?php echo $netcash_terminal_number; ?>">
	<input type="hidden" name="p2" value="<?php echo $data['order_id'] ?>">
	<input type="hidden" name="p3"
	       value="<?php echo JText::_('COM_REDSHOP_ORDER_ID') ?>&nbsp;<?php echo $data['order_id'] ?>">
	<input type="hidden" name="p4" value="<?php echo $data['carttotal']; ?>">
	<input type="hidden" name="p10"
	       value="<?php echo JURI::base() ?>index.php?option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_netcash&orderid=<?php echo $data['order_id'] ?>">
	<input type="hidden" name="Budget" value="<?php echo $gateway_popup ?>">
	<input type="hidden" name="m_9" value="<?php echo $user->email ?>">
	<input type="hidden" name="m_10"
	       value="<?php echo JURI::base() ?>index.php?option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_netcash&orderid=<?php echo $data['order_id'] ?>">
	<input type="submit" value="">
</form>

<script>
	document.netcash.submit();
</script>
