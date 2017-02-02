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

<div class="panel panel-primary form-vertical">
    <div class="panel-heading">
        <h3><?php echo JText::_('COM_REDSHOP_MANUFACTURER_IMAGE_SETTINGS'); ?></h3>
    </div>
    <div class="panel-body">
        <div class="form-group">
            <label for="load_redshop_style" class="hasTip"
                   title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_MANUFACTURER_THUMB_WIDTH') ?>::<?php echo JText::_('COM_REDSHOP_MANUFACTURER_THUMB_WIDTH_LBL') ?>">
				<?php echo JText::_('COM_REDSHOP_MANUFACTURER_THUMB_WIDTH_HEIGHT'); ?>
            </label>
            <div class="row">
                <div class="col-md-6">
                    <input type="number" name="manufacturer_thumb_width" id="manufacturer_thumb_width" class="form-control"
                       value="<?php echo $this->config->get('MANUFACTURER_THUMB_WIDTH'); ?>" />
                </div>
                <div class="col-md-6">
                    <input type="number" name="manufacturer_thumb_height" id="manufacturer_thumb_height" class="form-control"
                       value="<?php echo $this->config->get('MANUFACTURER_THUMB_HEIGHT'); ?>" />
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="load_redshop_style" class="hasTip"
                   title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_MANUFACTURER_PRODUCT_THUMB_WIDTH_LBL') ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_MANUFACTURER_PRODUCT_THUMB_WIDTH') ?>">
			    <?php echo JText::_('COM_REDSHOP_TOOLTIP_MANUFACTURER_PRODUCT_THUMB_WIDTH'); ?>
            </label>
            <div class="row">
                <div class="col-md-6">
                    <input type="number" name="manufacturer_product_thumb_width" id="manufacturer_product_thumb_width" class="form-control"
                           value="<?php echo $this->config->get('MANUFACTURER_PRODUCT_THUMB_WIDTH'); ?>" />
                </div>
                <div class="col-md-6">
                    <input type="number" name="manufacturer_product_thumb_height" id="manufacturer_product_thumb_height" class="form-control"
                           value="<?php echo $this->config->get('MANUFACTURER_PRODUCT_THUMB_HEIGHT'); ?>" />
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="load_redshop_style" class="hasTip"
                   title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_MANUFACTURER_PRODUCT_THUMB_WIDTH_LBL_TWO_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_MANUFACTURER_PRODUCT_THUMB_WIDTH_LBL_TWO'); ?>">
			    <?php echo JText::_('COM_REDSHOP_MANUFACTURER_PRODUCT_THUMB_WIDTH_HEIGHT_TWO'); ?>
            </label>
            <div class="row">
                <div class="col-md-6">
                    <input type="number" name="manufacturer_product_thumb_width_2" id="manufacturer_product_thumb_width_2" class="form-control"
                           value="<?php echo $this->config->get('MANUFACTURER_PRODUCT_THUMB_WIDTH_2'); ?>" />
                </div>
                <div class="col-md-6">
                    <input type="number" name="manufacturer_product_thumb_height_2" id="manufacturer_product_thumb_height_2" class="form-control"
                           value="<?php echo $this->config->get('MANUFACTURER_PRODUCT_THUMB_HEIGHT_2'); ?>" />
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="load_redshop_style" class="hasTip"
                   title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_MANUFACTURER_PRODUCT_THUMB_WIDTH_THREE'); ?>::<?php echo JText::_('COM_REDSHOP_MANUFACTURER_PRODUCT_THUMB_WIDTH_LBL_THREE'); ?>">
			    <?php echo JText::_('COM_REDSHOP_MANUFACTURER_PRODUCT_THUMB_WIDTH_LBL_THREE'); ?>
            </label>
            <div class="row">
                <div class="col-md-6">
                    <input type="number" name="manufacturer_product_thumb_width_3" id="manufacturer_product_thumb_width_3" class="form-control"
                           value="<?php echo $this->config->get('MANUFACTURER_PRODUCT_THUMB_WIDTH_3'); ?>" />
                </div>
                <div class="col-md-6">
                    <input type="number" name="manufacturer_product_thumb_height_3" id="manufacturer_product_thumb_height_3" class="form-control"
                           value="<?php echo $this->config->get('MANUFACTURER_PRODUCT_THUMB_HEIGHT_3'); ?>" />
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="watermark_manufacturer_image" class="hasTip"
                   title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_WATERMARK_MANUFACTURER_IMAGE'); ?>::<?php echo JText::_('COM_REDSHOP_WATERMARK_MANUFACTURER_IMAGE_LBL'); ?>">
			    <?php echo JText::_('COM_REDSHOP_WATERMARK_MANUFACTURER_IMAGE_LBL'); ?>
            </label>
		    <?php echo $this->lists['watermark_manufacturer_image'] ?>
        </div>
        <div class="form-group">
            <label for="watermark_manufacturer_thumb_image" class="hasTip"
                   title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_WATERMARK_MANUFACTURER_THUMB_IMAGE'); ?>::<?php echo JText::_('COM_REDSHOP_WATERMARK_MANUFACTURER_THUMB_IMAGE_LBL'); ?>">
			    <?php echo JText::_('COM_REDSHOP_WATERMARK_MANUFACTURER_THUMB_IMAGE_LBL'); ?>
            </label>
		    <?php echo $this->lists['watermark_manufacturer_thumb_image'] ?>
        </div>
    </div>
</div>
