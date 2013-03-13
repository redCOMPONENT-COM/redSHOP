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
<div id="config-document">
	<table width="100%" cellpadding="0" cellspacing="0">
		<tr valign="top">
			<td width="50%">
				<fieldset class="adminform">
					<legend><?php echo JText::_('COM_REDSHOP_GOOGLE_ANALYATICS'); ?></legend>
					<?php echo $this->loadTemplate('analytics');?>
				</fieldset>
				<fieldset class="adminform">
					<legend><?php echo JText::_('COM_REDSHOP_CLICKATELL'); ?></legend>
					<?php echo $this->loadTemplate('clicktell');?>
				</fieldset>
			</td>
			<td width="50%">
				<fieldset class="adminform">
					<legend><?php echo JText::_('COM_REDSHOP_POST_DENMART'); ?></legend>
					<?php echo $this->loadTemplate('postdk');?>
				</fieldset>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<fieldset class="adminform">
					<legend><?php echo JText::_('COM_REDSHOP_ECONOMIC'); ?></legend>
					<?php echo $this->loadTemplate('economic');?>
				</fieldset>
			</td>
		</tr>
	</table>
</div>
