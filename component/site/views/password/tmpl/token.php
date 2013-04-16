<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
JHTML::_('behavior.tooltip');
$Itemid = JRequest::getVar('Itemid');
?>
<form action="" method="post">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td colspan="2" height="40">
				<p><?php echo JText::_('COM_REDSHOP_RESET_PASSWORD_TOKEN_DESCRIPTION'); ?></p>
			</td>
		</tr>
		<tr>
			<td height="40">
				<label for="token"
					title="<?php echo JText::_('COM_REDSHOP_ENTER_TOKEN_MESSAGE'); ?>::
						<?php echo JText::_('COM_REDSHOP_RESET_PASSWORD_TOKEN_TIP_TEXT'); ?>">
						<?php echo JText::_('COM_REDSHOP_TOKEN'); ?>
					:</label>
			</td>
			<td>
				<input class="inputbox" type="text" id="token" name="token"/>
			</td>
		</tr>
	</table>
	<input type="hidden" name="task" id="task" value="changepassword">
	<input type="hidden" name="Itemid" id="Itemid" value="<?php echo $Itemid; ?>">
	<input type="submit" name="submit" value="<?php echo JText::_('COM_REDSHOP_SUBMIT'); ?>" class="button">
</form>