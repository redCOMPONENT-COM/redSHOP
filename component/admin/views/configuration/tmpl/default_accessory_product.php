<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

?>
<div class="row adminform">
    <div class="col-sm-6">
        <div class="box box-primary form-vertical">
            <div class="box-header with-border">
                <h3 class="text-primary center"><?php echo JText::_('COM_REDSHOP_ACCESSORY_PRODUCT_SETTINGS'); ?></h3>
            </div>
            <div class="box-body">
				<?php
				echo RedshopLayoutHelper::render(
					'config.config',
					array(
						'title' => JText::_('COM_REDSHOP_ACCESSORY_AS_PRODUCT_IN_CART_ENABLE_LBL'),
						'desc'  => JText::_('COM_REDSHOP_TOOLTIP_ACCESSORY_AS_PRODUCT_IN_CART_ENABLE_LBL'),
						'field' => $this->lists['accessory_as_product_in_cart_enable']
					)
				);
				echo RedshopLayoutHelper::render(
					'config.config',
					array(
						'title' => JText::_('COM_REDSHOP_ACCESSORY_PRODUCT_IN_LIGHTBOX_LBL'),
						'desc'  => JText::_('COM_REDSHOP_TOOLTIP_ACCESSORY_PRODUCT_IN_LIGHTBOX_LBL'),
						'field' => $this->lists['accessory_product_in_lightbox']
					)
				);
				echo RedshopLayoutHelper::render(
					'config.config',
					array(
						'title' => JText::_('COM_REDSHOP_ACCESSORY_PRODUCT_ORDERING_METHOD_LBL'),
						'desc'  => JText::_('COM_REDSHOP_TOOLTIP_ACCESSORY_PRODUCT_ORDERING_METHOD_LBL'),
						'field' => $this->lists['default_accessory_ordering_method']
					)
				);
				echo RedshopLayoutHelper::render(
					'config.config',
					array(
						'title' => JText::_('COM_REDSHOP_ACCESSORY_PRODUCT_DESC_MAX_CHARS_LBL'),
						'desc'  => JText::_('COM_REDSHOP_TOOLTIP_ACCESSORY_PRODUCT_DESC_MAX_CHARS_LBL'),
						'field' => '<input type="number" name="accessory_product_desc_max_chars" class="form-control"'
							. ' id="accessory_product_desc_max_chars" value="' . $this->config->get('ACCESSORY_PRODUCT_DESC_MAX_CHARS') . '" />'
					)
				);
				echo RedshopLayoutHelper::render(
					'config.config',
					array(
						'title' => JText::_('COM_REDSHOP_ACCESSORY_PRODUCT_DESC_END_SUFFIX_LBL'),
						'desc'  => JText::_('COM_REDSHOP_TOOLTIP_ACCESSORY_PRODUCT_DESC_END_SUFFIX_LBL'),
						'field' => '<input type="text" name="accessory_product_desc_end_suffix" class="form-control"'
							. ' id="accessory_product_desc_end_suffix" value="' . $this->config->get('ACCESSORY_PRODUCT_DESC_END_SUFFIX') . '" />'
					)
				);
				echo RedshopLayoutHelper::render(
					'config.config',
					array(
						'title' => JText::_('COM_REDSHOP_ACCESSORY_PRODUCT_TITLE_MAX_CHARS_LBL'),
						'desc'  => JText::_('COM_REDSHOP_TOOLTIP_ACCESSORY_PRODUCT_TITLE_MAX_CHARS_LBL'),
						'field' => '<input type="number" name="accessory_product_title_max_chars" class="form-control"'
							. ' id="accessory_product_title_max_chars" value="' . $this->config->get('ACCESSORY_PRODUCT_TITLE_MAX_CHARS') . '" />'
					)
				);
				echo RedshopLayoutHelper::render(
					'config.config',
					array(
						'title' => JText::_('COM_REDSHOP_ACCESSORY_PRODUCT_TITLE_END_SUFFIX_LBL'),
						'desc'  => JText::_('COM_REDSHOP_TOOLTIP_ACCESSORY_PRODUCT_TITLE_END_SUFFIX_LBL'),
						'line'  => false,
						'field' => '<input type="text" name="accessory_product_title_end_suffix" class="form-control"'
							. ' id="accessory_product_title_end_suffix" value="' . $this->config->get('ACCESSORY_PRODUCT_TITLE_END_SUFFIX') . '" />'
					)
				);
				?>
            </div>
        </div>
    </div>

    <div class="col-sm-6">
        <div class="box box-primary form-vertical">
            <div class="box-header with-border">
                <h3 class="text-primary center"><?php echo JText::_('COM_REDSHOP_ACCESSORY_PRODUCT_IMAGE_SETTINGS'); ?></h3>
            </div>
            <div class="box-body">
                <div class="form-group row-fluid">
                    <label class="col-md-4 hasPopover" data-content="<?php echo JText::_('COM_REDSHOP_TOOLTIP_ACCESSORY_THUMB_WIDTH_LBL'); ?>">
						<?php echo JText::_('COM_REDSHOP_ACCESSORY_THUMB_WIDTH_HEIGHT'); ?>
                    </label>
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-sm-6">
                                <input type="number" name="accessory_thumb_width" id="accessory_thumb_width" class="form-control"
                                       value="<?php echo $this->config->get('ACCESSORY_THUMB_WIDTH'); ?>"/>
                            </div>
                            <div class="col-sm-6">
                                <input type="number" name="accessory_thumb_height" id="accessory_thumb_height" class="form-control"
                                       value="<?php echo $this->config->get('ACCESSORY_THUMB_HEIGHT'); ?>"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row-fluid">
                    <label class="col-md-4 hasPopover"
                           data-content="<?php echo JText::_('COM_REDSHOP_TOOLTIP_ACCESSORY_THUMB_WIDTH_LBL_TWO'); ?>">
						<?php echo JText::_('COM_REDSHOP_ACCESSORY_THUMB_WIDTH_HEIGHT_TWO'); ?>
                    </label>
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-sm-6">
                                <input type="number" name="accessory_thumb_width_2" id="accessory_thumb_width_2" class="form-control"
                                       value="<?php echo $this->config->get('ACCESSORY_THUMB_WIDTH_2'); ?>"/>
                            </div>
                            <div class="col-sm-6">
                                <input type="number" name="accessory_thumb_height_2" id="accessory_thumb_height_2" class="form-control"
                                       value="<?php echo $this->config->get('ACCESSORY_THUMB_HEIGHT_2'); ?>"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row-fluid">
                    <label class="col-md-4 hasPopover"
                           data-content="<?php echo JText::_('COM_REDSHOP_TOOLTIP_ACCESSORY_THUMB_WIDTH_LBL_THREE'); ?>">
						<?php echo JText::_('COM_REDSHOP_ACCESSORY_THUMB_WIDTH_HEIGHT_THREE'); ?>
                    </label>
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-sm-6">
                                <input type="number" name="accessory_thumb_width_3" id="accessory_thumb_width_3" class="form-control"
                                       value="<?php echo $this->config->get('ACCESSORY_THUMB_WIDTH_3'); ?>"/>
                            </div>
                            <div class="col-sm-6">
                                <input type="number" name="accessory_thumb_height_3" id="accessory_thumb_height_3" class="form-control"
                                       value="<?php echo $this->config->get('ACCESSORY_THUMB_HEIGHT_3'); ?>"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
