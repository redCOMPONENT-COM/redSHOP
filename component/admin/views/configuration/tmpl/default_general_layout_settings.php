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
<div class="box box-primary form-vertical">
    <div class="box-header with-border">
        <h3><?php echo JText::_('COM_REDSHOP_GENERAL_LAYOUT_SETTING'); ?></h3>
    </div>
    <div class="box-body">
        <div class="form-group">
            <label for="load_redshop_style" class="hasTip"
                   title="<?php echo JText::_('COM_REDSHOP_CONFIG_LOAD_REDSHOP_STYLE_LBL') ?>::<?php echo JText::_('COM_REDSHOP_CONFIG_LOAD_REDSHOP_STYLE_DESC') ?>">
				<?php echo JText::_('COM_REDSHOP_CONFIG_LOAD_REDSHOP_STYLE_LBL'); ?>
            </label>
			<?php echo $this->lists['load_redshop_style'] ?>
        </div>
        <div class="form-group">
            <label for="media_allowed_mime_type" class="hasTip"
                   title="<?php echo JText::_('COM_REDSHOP_DEFAULT_ALLOWED_EXTENSION_TYPE_LBL') ?>::<?php echo JText::_('COM_REDSHOP_DEFAULT_ALLOWED_EXTENSION_TYPE_TOOLTIP') ?>">
				<?php echo JText::_('COM_REDSHOP_DEFAULT_ALLOWED_EXTENSION_TYPE_LBL'); ?>
            </label>
            <textarea name="media_allowed_mime_type" id="media_allowed_mime_type" cols="5" rows="5" class="form-control"><?php echo $this->config->get('MEDIA_ALLOWED_MIME_TYPE'); ?></textarea>
        </div>
        <div class="form-group">
            <label for="image_quality_output" class="hasTip"
                   title="<?php echo JText::_('COM_REDSHOP_DEFAULT_IMAGE_QUALITY_LBL') ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_IMAGE_QUALITY_LBL') ?>">
				<?php echo JText::_('COM_REDSHOP_DEFAULT_IMAGE_QUALITY_LBL'); ?>
            </label>
            <input type="text" name="image_quality_output" id="image_quality_output" class="form-control"
                   value="<?php echo $this->config->get('IMAGE_QUALITY_OUTPUT'); ?>"/>
        </div>
        <div class="form-group">
            <label for="use_image_size_swapping" class="hasTip"
                   title="<?php echo JText::_('COM_REDSHOP_CONFIG_IMAGE_PROCESSING_METHOD_LBL') ?>::<?php echo JText::_('COM_REDSHOP_CONFIG_IMAGE_PROCESSING_METHOD_DESC') ?>">
				<?php echo JText::_('COM_REDSHOP_CONFIG_IMAGE_PROCESSING_METHOD_LBL'); ?>
            </label>
			<?php echo $this->lists ['use_image_size_swapping']; ?>
        </div>
    </div>
</div>
