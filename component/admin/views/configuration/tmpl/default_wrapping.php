<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

?>

<legend><?php echo JText::_('COM_REDSHOP_WRAPPING_MANAGEMENT'); ?></legend>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_WRAPPER_THUMB_WIDTH_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_WRAPPER_THUMB_WIDTH'); ?>">
		<label><?php echo JText::_('COM_REDSHOP_DEFAULT_WRAPPER_THUMB_WIDTH_HEIGHT');?></label>
	</span>
	<input type="text" name="default_wrapper_thumb_width" value="<?php echo $this->config->get('DEFAULT_WRAPPER_THUMB_WIDTH'); ?>"/>
	<input type="text" name="default_wrapper_thumb_height" value="<?php echo $this->config->get('DEFAULT_WRAPPER_THUMB_HEIGHT'); ?>"/>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
	       title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_AUTO_SCROLL_FOR_WRAPPER_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_AUTO_SCROLL_FOR_WRAPPER'); ?>">
		<label><?php echo JText::_('COM_REDSHOP_AUTO_SCROLL_FOR_WRAPPER_LBL'); ?><label>
	</span>
	<?php echo $this->lists ['auto_scroll_wrapper']; ?>
</div>
