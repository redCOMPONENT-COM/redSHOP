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
	<legend><?php echo JText::_('COM_REDSHOP_GENERAL'); ?></legend>
	<table width="100%" cellpadding="0" cellspacing="0">
		<tr valign="top">
			<td width="50%">
				<fieldset class="adminform">
					<?php echo $this->loadTemplate('settings');?>
				</fieldset>
				<fieldset class="adminform">
					<?php echo $this->loadTemplate('modules');?>
				</fieldset>

			</td>
			<td width="50%">
				<fieldset class="adminform">
					<?php echo $this->loadTemplate('general_layout_settings');?>
				</fieldset>
			</td>
		</tr>
	</table>
</fieldset>
