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
					<table class="admintable">
						<tr>
							<td class="config_param"><?php echo JText::_('COM_REDSHOP_STOCKROOM_SETTINGS'); ?></td>
						</tr>
						<tr>
							<td width="100" align="right" class="key">
					<span class="editlinktip hasTip"
					      title="<?php echo JText::_('COM_REDSHOP_USE_BLANK_AS_INFINITE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_USE_BLANK_AS_INFINITE_LBL'); ?>">
					<label
						for="container"><?php echo JText::_('COM_REDSHOP_USE_BLANK_AS_INFINITE_LBL');?></label></span>
							</td>
							<td><?php echo $this->lists ['use_blank_as_infinite'];?></td>
						</tr>
						<tr>
							<td width="100" align="right" class="key">
				<span class="editlinktip hasTip"
				      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEDAULT_STOCKROOM_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEDAULT_STOCKROOM'); ?>">
				<label
					for="default_stockroom"><?php echo JText::_('COM_REDSHOP_DEDAULT_STOCKROOM_LBL');?></label></span>
							</td>
							<td><?php echo $this->lists ['default_stockroom'];?></td>
						</tr>
						<tr>
							<td width="100" align="right" class="key">
				<span class="editlinktip hasTip"
				      title="<?php echo JText::_('COM_REDSHOP_DEFAULT_STOCKAMOUNT_IMAGE_THUMB_WIDTH_HEIGHT_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_STOCKAMOUNT_IMAGE_THUMB_WIDTH_HEIGHT_LBL'); ?>">
				<label
					for="default_stockroom"><?php echo JText::_('COM_REDSHOP_DEFAULT_STOCKAMOUNT_IMAGE_THUMB_WIDTH_HEIGHT_LBL');?></label></span>
							</td>
							<td><input type="text" name="default_stockamount_thumb_width"
							           value="<?php echo DEFAULT_STOCKAMOUNT_THUMB_WIDTH; ?>"/>
								<input type="text" name="default_stockamount_thumb_height"
								       value="<?php echo DEFAULT_STOCKAMOUNT_THUMB_HEIGHT; ?>"/></td>
						</tr>
					</table>
				</fieldset>
			</td>
			<td width="50%">
			</td>
		</tr>
	</table>
</div>
