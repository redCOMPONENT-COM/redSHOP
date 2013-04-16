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
$Itemid = JRequest::getVar('Itemid');?>
<form action="" method="post">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td colspan="2" height="40">
				<p><?php echo JText::_('COM_REDSHOP_RESET_PASSWORD_DESCRIPTION'); ?></p>
			</td>
		</tr>
		<tr>
			<td height="40">
				<label for="email" class="hasTip"
				       title="<?php echo JText::_('COM_REDSHOP_RESET_PASSWORD_MAIL_TIP_TITLE'); ?>
				       ::<?php echo JText::_('COM_REDSHOP_RESET_PASSWORD_MAIL_TIP_TEXT'); ?>">
					<?php echo JText::_('COM_REDSHOP_EMAIL_ADDRESS'); ?>
					:</label>
			</td>
			<td>
				<input class="inputbox" type="text" id="email" name="email"/>
			</td>
		</tr>
	</table>
	<input type="hidden" name="task" id="task" value="reset">
	<input type="hidden" name="Itemid" id="Itemid" value="<?php echo $Itemid; ?>">
	<input type="submit" name="submit" value="<?php echo JText::_('COM_REDSHOP_RESET_PASSWORD_BUTTON'); ?>"
	       class="button">
</form>