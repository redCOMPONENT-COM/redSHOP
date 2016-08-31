<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$uri = JURI::getInstance();
$url = $uri->root();
?>
<legend><?php echo JText::_('COM_REDSHOP_GENERAL_LAYOUT_SETTING'); ?></legend>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_CONFIG_LOAD_REDSHOP_STYLE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_CONFIG_LOAD_REDSHOP_STYLE_DESC'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_CONFIG_LOAD_REDSHOP_STYLE_LBL');?></label>
	</span>
	<?php echo $this->lists ['load_redshop_style'];?>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_DEFAULT_ALLOWED_EXTENSION_TYPE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_DEFAULT_ALLOWED_EXTENSION_TYPE_TOOLTIP'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_DEFAULT_ALLOWED_EXTENSION_TYPE_LBL');?></label></span>
	<textarea name="media_allowed_mime_type" cols="5" rows="5"><?php echo $this->config->get('MEDIA_ALLOWED_MIME_TYPE'); ?></textarea>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_DEFAULT_IMAGE_QUALITY_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_IMAGE_QUALITY_LBL'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_IMAGE_QUALITY_LBL');?></label></span>
	<input type="text" name="image_quality_output" id="image_quality_output"
		           value="<?php echo $this->config->get('IMAGE_QUALITY_OUTPUT'); ?>"/>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_CONFIG_IMAGE_PROCESSING_METHOD_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_CONFIG_IMAGE_PROCESSING_METHOD_DESC'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_CONFIG_IMAGE_PROCESSING_METHOD_LBL');?></label>
	</span>
	<?php echo $this->lists ['use_image_size_swapping'];?>
</div>
