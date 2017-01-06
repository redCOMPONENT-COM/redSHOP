<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$editor = JFactory::getEditor();
?>

<div class="row">
    <div class="col-sm-8">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo JText::_('COM_REDSHOP_CATEGORY_INFORMATION'); ?></h3>
            </div>
            <div class="box-body">
                <div class="form-group">
                    <label for="category_name">
						<?php echo JText::_('COM_REDSHOP_CATEGORY_NAME'); ?>
						<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_CATEGORY_NAME'), JText::_('COM_REDSHOP_CATEGORY_NAME'), 'tooltip.png', '', '', false); ?>
                    </label>
                    <input class="text_area" type="text" name="category_name" id="category_name" size="32" maxlength="250"
                           value="<?php echo $this->detail->category_name; ?>"/>
                </div>

                <div class="form-group">
                    <label for="category_parent_id">
						<?php echo JText::_('COM_REDSHOP_CATEGORY_PARENT'); ?>
						<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_CATEGORY_PARENT'), JText::_('COM_REDSHOP_CATEGORY_PARENT'), 'tooltip.png', '', '', false); ?>
                    </label>
					<?php echo $this->lists['categories']; ?>
                </div>

                <div class="form-group">
                    <label>
						<?php echo JText::_('COM_REDSHOP_PUBLISHED'); ?>
                    </label>
					<?php echo $this->lists['published']; ?>
                </div>

                <div class="form-group">
                    <label>
						<?php echo JText::_('COM_REDSHOP_SHOW_PRODUCT_PER_PAGE'); ?>
						<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_SHOW_PRODUCT_PER_PAGE'), JText::_('COM_REDSHOP_CATEGORY_NAME'), 'tooltip.png', '', '', false); ?>
                    </label>
                    <input class="text_area" type="text" name="products_per_page" id="products_per_page" size="32"
                           maxlength="250"
                           value="<?php echo $this->detail->products_per_page; ?>"/>
                </div>

                <div class="form-group">
                    <label>
						<?php echo JText::_('COM_REDSHOP_CATEGORY_TEMPLATE'); ?>
						<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_CATEGORY_TEMPLATE'), JText::_('COM_REDSHOP_CATEGORY_TEMPLATE'), 'tooltip.png', '', '', false); ?>
                    </label>
					<?php echo $this->lists['category_template']; ?>
                </div>

                <div class="form-group">
                    <label>
						<?php echo JText::_('COM_REDSHOP_CATEGORY_MORE_TEMPLATE'); ?>
						<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_CATEGORY_MORE_TEMPLATE'), JText::_('COM_REDSHOP_CATEGORY_MORE_TEMPLATE'), 'tooltip.png', '', '', false); ?>
                    </label>
					<?php echo $this->lists['category_more_template']; ?>
                </div>

                <div class="form-group">
                    <label>
						<?php echo JText::_('COM_REDSHOP_PRODUCT_COMPARE_TEMPLATE_FOR_CATEGORY'); ?>
						<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_COMPARE_TEMPLATE_FOR_CATEGORY_LABEL'), JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_COMPARE_TEMPLATE_FOR_CATEGORY'), 'tooltip.png', '', '', false); ?>
                    </label>
					<?php echo $this->lists['compare_template_id']; ?>
                </div>

                <div class="form-group">
                    <label>
						<?php echo JText::_('COM_REDSHOP_SHORT_DESCRIPTION'); ?>
                    </label>
					<?php echo $editor->display("category_short_description", $this->detail->category_short_description, '$widthPx', '$heightPx', '100', '20', false); ?>
                </div>

                <div class="form-group">
                    <label>
						<?php echo JText::_('COM_REDSHOP_DESCRIPTION'); ?>
                    </label>
					<?php echo $editor->display("category_description", $this->detail->category_description, '$widthPx', '$heightPx', '100', '20', false); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo JText::_('COM_REDSHOP_CATEGORY_IMAGES'); ?></h3>
            </div>
            <div class="box-body">
				<?php
				$section_id    = $this->detail->category_id;
				$media_section = 'category';
				echo RedshopHelperMediaImage::render(
					'category_full_image',
					'category',
					$section_id,
					$media_section,
					$this->detail->category_full_image,
					false
				);
				?>
            </div>
        </div>

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo JText::_('COM_REDSHOP_CATEGORY_BACK_IMAGE'); ?></h3>
            </div>
            <div class="box-body">
				<?php
				$section_id    = $this->detail->category_id;
				$media_section = 'category';
				echo RedshopHelperMediaImage::render(
					'category_back_full_image',
					'category',
					$section_id,
					$media_section,
					$this->detail->category_back_full_image,
					false
				);
				?>
            </div>
        </div>
    </div>
</div>

