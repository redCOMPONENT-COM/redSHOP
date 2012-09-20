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
?>
<form action="<?php echo JRoute::_('index.php?option='.$option.'&view=password'); ?>" method="post">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td colspan="2" height="40">
				<p><?php echo JText::_('RESET_PASSWORD_DESCRIPTION'); ?></p>
			</td>
		</tr>
		<tr>
			<td height="40">
				<label for="email" class="hasTip" title="<?php echo JText::_('RESET_PASSWORD_MAIL_TIP_TITLE'); ?>::<?php echo JText::_('RESET_PASSWORD_MAIL_TIP_TEXT'); ?>"><?php echo JText::_('EMAIL_ADDRESS'); ?>:</label>
			</td>
			<td>
				<input class="inputbox" type="text" id="email" name="email" />
			</td>
		</tr>
	</table>
	<input type="hidden" name="task" id="task" value="reset">
	<input type="submit" name="submit" value="<?php echo JText::_('RESET_PASSWORD_BUTTON'); ?>" class="button">
</form>