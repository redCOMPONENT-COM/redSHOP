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
defined ('_JEXEC') or die ('restricted access');
JHTML::_('behavior.tooltip');
$Itemid = JRequest::getVar('Itemid');
$uid = JRequest::getInt('uid');
?>
<script language="javascript" type="text/javascript">
function validate()
{
	var form = document.adminForm;

	if ( ((form.password.value != "") || (form.password2.value != "")) && (form.password.value != form.password2.value) )
	{
		alert( "<?php echo JText::_('COM_REDSHOP_PASSWORD_DONOT_MATCH', true ); ?>" );
		return false;
	}
	else
	{
		form.submit();

	}
}
</script>

<form name="adminForm" id="adminForm" action="" method="post">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td colspan="2" height="40">
				<p><?php echo JText::_('COM_REDSHOP_RESET_PASSWORD_NEWPASSWORD_DESCRIPTION'); ?></p>
			</td>
		</tr>
		<tr>
			<td height="40">
				<label for="password" title="<?php echo JText::_('COM_REDSHOP_ENTER_PASSWORD_MESSAGE'); ?>::<?php echo JText::_('COM_REDSHOP_RESET_GETPASSWORD_TOKEN_TIP_TEXT'); ?>"><?php echo JText::_('COM_REDSHOP_PASSWORD'); ?>:</label>
			</td>
			<td>
				<input id="password" name="password" type="password" />
			</td>
		</tr>
		<tr>
			<td height="40">
				<label for="password2" title="<?php echo JText::_('COM_REDSHOP_ENTER_VERIFY_PASSWORD_MESSAGE'); ?>::<?php echo JText::_('COM_REDSHOP_RESET_GETVERIFYPASSWORD_TOKEN_TIP_TEXT'); ?>"><?php echo JText::_('COM_REDSHOP_VERIFY_PASSWORD'); ?>:</label>
			</td>
			<td>
				<input id="password2" name="password2" type="password" />
			</td>
		</tr>
	</table>
	<input type="hidden" name="task" id="task" value="setpassword">
	<input type="hidden" name="uid" id="uid" value="<?php echo $uid ?>">
	<input type="hidden" name="Itemid" id="Itemid" value="<?php echo $Itemid; ?>">
	<input type="submit" name="submit" value="<?php echo JText::_('COM_REDSHOP_SUBMIT'); ?>" onclick="return validate();" class="button">
</form>