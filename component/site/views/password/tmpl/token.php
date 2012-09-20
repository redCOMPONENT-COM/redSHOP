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
$option = JRequest::getVar('option');
$Itemid = JRequest::getVar('Itemid');
?>
<form action="<?php echo JRoute::_('index.php?option='.$option.'&view=password'); ?>" method="post">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td colspan="2" height="40">
				<p><?php echo JText::_('RESET_PASSWORD_TOKEN_DESCRIPTION'); ?></p>
			</td>
		</tr>
		<tr>
			<td height="40">
				<label for="token" title="<?php echo JText::_('ENTER_TOKEN_MESSAGE'); ?>::<?php echo JText::_('RESET_PASSWORD_TOKEN_TIP_TEXT'); ?>"><?php echo JText::_('TOKEN'); ?>:</label>
			</td>
			<td>
				<input class="inputbox" type="text" id="token" name="token" />
			</td>
		</tr>
	</table>
	<input type="hidden" name="task" id="task" value="changepassword">
	<input type="hidden" name="Itemid" id="Itemid" value="<?php echo $Itemid; ?>">
	<input type="submit" name="submit" value="submit" class="button">
</form>