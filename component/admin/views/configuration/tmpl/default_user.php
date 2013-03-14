<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die ('Restricted access');

?>
<fieldset class="adminform">
	<legend><?php echo JText::_('COM_REDSHOP_USERS'); ?></legend>
	<table cellpadding="0" cellspacing="0">
		<tr valign="top">
			<td width="50%">
				<fieldset class="adminform">
					<?php echo $this->loadTemplate('registration');?>
				</fieldset>
			</td>
			<td></td>
		</tr>
		<tr>
			<td width="50%">
				<fieldset class="adminform">
					<?php echo $this->loadTemplate('shopper_group');?>
				</fieldset>
			</td>
			<td></td>
		</tr>
	</table>
</fieldset>
