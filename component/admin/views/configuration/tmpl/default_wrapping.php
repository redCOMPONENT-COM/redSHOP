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
<table class="admintable">
	<tr>
		<td class="config_param"><?php echo JText::_('COM_REDSHOP_WRAPPING_MANAGEMENT'); ?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_WRAPPER_THUMB_WIDTH_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_WRAPPER_THUMB_WIDTH'); ?>">
		<?php echo JText::_('COM_REDSHOP_DEFAULT_WRAPPER_THUMB_WIDTH_HEIGHT');?></span></td>
		<td>
			<input type="text" name="default_wrapper_thumb_width" value="<?php echo DEFAULT_WRAPPER_THUMB_WIDTH; ?>"/>
			<input type="text" name="default_wrapper_thumb_height" value="<?php echo DEFAULT_WRAPPER_THUMB_HEIGHT; ?>"/>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
	 <span class="editlinktip hasTip"
	       title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_AUTO_SCROLL_FOR_WRAPPER_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_AUTO_SCROLL_FOR_WRAPPER'); ?>">
			<?php
			echo JText::_('COM_REDSHOP_AUTO_SCROLL_FOR_WRAPPER_LBL');
			?></td>
		<td><?php
			echo $this->lists ['auto_scroll_wrapper'];
			?>
		</td>
	</tr>
</table>
