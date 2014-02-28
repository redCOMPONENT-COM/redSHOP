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
$uri = JURI::getInstance();
$url = $uri->root();
$user = JFactory::getUser();
$db = JFactory::getDbo();
$option = JRequest::getVar('option');

$netcash_username = $this->_params->get("username");
$netcash_password = $this->_params->get("password");
$netcash_pin = $this->_params->get("pin");
$netcash_terminal_number = $this->_params->get("terminal_number");
$gateway_popup = $this->_params->get("gateway_popup");

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