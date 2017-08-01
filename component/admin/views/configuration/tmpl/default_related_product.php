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
                <h3 class="text-primary center"><?php echo JText::_('COM_REDSHOP_RELATED_PRODUCT_SETTINGS'); ?></h3>
            </div>
            <div class="box-body">
				<?php
				echo RedshopLayoutHelper::render(
					'config.config',
					array(
						'title' => JText::_('COM_REDSHOP_TWOWAY_RELATED_PRODUCT_LBL'),
						'desc'  => JText::_('COM_REDSHOP_TOOLTIP_TWOWAY_RELATED_PRODUCT_LBL'),
						'field' => $this->lists['twoway_related_product']
					)
				);
				echo RedshopLayoutHelper::render(
					'config.config',
					array(
						'title' => JText::_('COM_REDSHOP_CHILDPRODUCT_DROPDOWN_LBL'),
						'desc'  => JText::_('COM_REDSHOP_TOOLTIP_CHILDPRODUCT_DROPDOWN_LBL'),
						'field' => $this->lists['childproduct_dropdown']
					)
				);
				echo RedshopLayoutHelper::render(
					'config.config',
					array(
						'title' => JText::_('COM_REDSHOP_PURCHASE_PARENT_WITH_CHILD_LBL'),
						'desc'  => JText::_('COM_REDSHOP_TOOLTIP_PURCHASE_PARENT_WITH_CHILD_LBL'),
						'field' => $this->lists['purchase_parent_with_child']
					)
				);
				echo RedshopLayoutHelper::render(
					'config.config',
					array(
						'title' => JText::_('COM_REDSHOP_RELATED_PRODUCT_ORDERING_METHOD_LBL'),
						'desc'  => JText::_('COM_REDSHOP_TOOLTIP_RELATED_PRODUCT_ORDERING_METHOD_LBL'),
						'field' => $this->lists['default_related_ordering_method']
					)
				);
				echo RedshopLayoutHelper::render(
					'config.config',
					array(
						'title' => JText::_('COM_REDSHOP_RELATED_PRODUCT_DESC_MAX_CHARS_LBL'),
						'desc'  => JText::_('COM_REDSHOP_TOOLTIP_RELATED_PRODUCT_DESC_MAX_CHARS_LBL'),
						'field' => '<input type="number" name="related_product_desc_max_chars" id="related_product_desc_max_chars" class="form-control"'
							. ' value="' . $this->config->get('RELATED_PRODUCT_DESC_MAX_CHARS') . '" />'
					)
				);
				echo RedshopLayoutHelper::render(
					'config.config',
					array(
						'title' => JText::_('COM_REDSHOP_RELATED_PRODUCT_DESC_END_SUFFIX_LBL'),
						'desc'  => JText::_('COM_REDSHOP_TOOLTIP_RELATED_PRODUCT_DESC_END_SUFFIX_LBL'),
						'field' => '<input type="text" name="related_product_desc_end_suffix" id="related_product_desc_end_suffix" class="form-control"'
							. ' value="' . $this->config->get('RELATED_PRODUCT_DESC_END_SUFFIX') . '" />'
					)
				);
				echo RedshopLayoutHelper::render(
					'config.config',
					array(
						'title' => JText::_('COM_REDSHOP_RELATED_PRODUCT_SHORT_DESC_MAX_CHARS_LBL'),
						'desc'  => JText::_('COM_REDSHOP_TOOLTIP_RELATED_PRODUCT_SHORT_DESC_MAX_CHARS_LBL'),
						'field' => '<input type="number" name="related_product_short_desc_max_chars" id="related_product_short_desc_max_chars"'
							. ' class="form-control" value="' . $this->config->get('RELATED_PRODUCT_SHORT_DESC_MAX_CHARS') . '" />'
					)
				);
				echo RedshopLayoutHelper::render(
					'config.config',
					array(
						'title' => JText::_('COM_REDSHOP_RELATED_PRODUCT_SHORT_DESC_END_SUFFIX_LBL'),
						'desc'  => JText::_('COM_REDSHOP_TOOLTIP_RELATED_PRODUCT_SHORT_DESC_END_SUFFIX_LBL'),
						'field' => '<input type="text" name="related_product_short_desc_end_suffix" id="related_product_short_desc_end_suffix"'
							. ' class="form-control" value="' . $this->config->get('RELATED_PRODUCT_SHORT_DESC_END_SUFFIX') . '" />'
					)
				);
				echo RedshopLayoutHelper::render(
					'config.config',
					array(
						'title' => JText::_('COM_REDSHOP_RELATED_PRODUCT_TITLE_MAX_CHARS_LBL'),
						'desc'  => JText::_('COM_REDSHOP_TOOLTIP_RELATED_PRODUCT_TITLE_MAX_CHARS_LBL'),
						'field' => '<input type="number" name="related_product_title_max_chars" id="related_product_title_max_chars"'
							. ' class="form-control" value="' . $this->config->get('RELATED_PRODUCT_TITLE_MAX_CHARS') . '" />'
					)
				);
				echo RedshopLayoutHelper::render(
					'config.config',
					array(
						'title' => JText::_('COM_REDSHOP_RELATED_PRODUCT_TITLE_END_SUFFIX_LBL'),
						'desc'  => JText::_('COM_REDSHOP_TOOLTIP_RELATED_PRODUCT_TITLE_END_SUFFIX_LBL'),
						'field' => '<input type="text" name="related_product_title_end_suffix" id="related_product_title_end_suffix"'
							. ' class="form-control" value="' . $this->config->get('RELATED_PRODUCT_TITLE_END_SUFFIX') . '" />'
					)
				);
				?>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="box box-primary form-vertical">
            <div class="box-header with-border">
                <h3 class="text-primary center"><?php echo JText::_('COM_REDSHOP_RELATED_PRODUCT_IMAGE_SETTINGS'); ?></h3>
            </div>
            <div class="box-body">
                <div class="form-group row-fluid">
                    <label class="col-md-4 hasPopover" data-content="<?php echo JText::_('COM_REDSHOP_TOOLTIP_RELATED_PRODUCT_THUMB_WIDTH'); ?>">
						<?php echo JText::_('COM_REDSHOP_RELATED_PRODUCT_THUMB_WIDTH_HEIGHT'); ?>
                    </label>
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-sm-6">
                                <input type="number" name="related_product_thumb_width" id="related_product_thumb_width" class="form-control"
                                       value="<?php echo $this->config->get('RELATED_PRODUCT_THUMB_WIDTH'); ?>"/>
                            </div>
                            <div class="col-sm-6">
                                <input type="number" name="related_product_thumb_height" id="related_product_thumb_height" class="form-control"
                                       value="<?php echo $this->config->get('RELATED_PRODUCT_THUMB_HEIGHT'); ?>"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row-fluid">
                    <label class="col-md-4 hasPopover"
                           data-content="<?php echo JText::_('COM_REDSHOP_TOOLTIP_RELATED_PRODUCT_THUMB_WIDTH_TWO'); ?>">
						<?php echo JText::_('COM_REDSHOP_RELATED_PRODUCT_THUMB_WIDTH_HEIGHT_TWO'); ?>
                    </label>
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-sm-6">
                                <input type="number" name="related_product_thumb_width_2" id="related_product_thumb_width_2" class="form-control"
                                       value="<?php echo $this->config->get('RELATED_PRODUCT_THUMB_WIDTH_2'); ?>"/>
                            </div>
                            <div class="col-sm-6">
                                <input type="number" name="related_product_thumb_height_2" id="related_product_thumb_height_2"
                                       class="form-control"
                                       value="<?php echo $this->config->get('RELATED_PRODUCT_THUMB_HEIGHT_2'); ?>"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row-fluid">
                    <label class="col-md-4 hasPopover"
                           data-content="<?php echo JText::_('COM_REDSHOP_TOOLTIP_RELATED_PRODUCT_THUMB_WIDTH_THREE'); ?>">
						<?php echo JText::_('COM_REDSHOP_RELATED_PRODUCT_THUMB_WIDTH_HEIGHT_THREE'); ?>
                    </label>
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-sm-6">
                                <input type="number" name="related_product_thumb_width_3" id="related_product_thumb_width_3" class="form-control"
                                       value="<?php echo $this->config->get('RELATED_PRODUCT_THUMB_WIDTH_3'); ?>"/>
                            </div>
                            <div class="col-sm-6">
                                <input type="number" name="related_product_thumb_height_3" id="related_product_thumb_height_3"
                                       class="form-control"
                                       value="<?php echo $this->config->get('RELATED_PRODUCT_THUMB_HEIGHT_3'); ?>"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
