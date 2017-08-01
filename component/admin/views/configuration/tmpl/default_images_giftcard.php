<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_WATERMARK_GIFTCARD_IMAGE_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_WATERMARK_GIFTCARD_IMAGE_LBL'),
		'field' => $this->lists['watermark_giftcart_image']
	)
);
?>
<div class="form-group row-fluid">
    <label class="col-md-4 hasPopover" data-content="<?php echo JText::_('COM_REDSHOP_TOOLTIP_GIFTCARD_THUMB_WIDTH_LBL'); ?>">
		<?php echo JText::_('COM_REDSHOP_GIFTCARD_THUMB_WIDTH_HEIGHT'); ?>
    </label>
    <div class="col-md-8">
        <div class="row">
            <div class="col-sm-6">
                <input type="number" name="giftcard_thumb_width" id="giftcard_thumb_width" class="form-control"
                       value="<?php echo $this->config->get('GIFTCARD_THUMB_WIDTH'); ?>"/>
            </div>
            <div class="col-sm-6">
                <input type="number" name="giftcard_thumb_height" id="giftcard_thumb_height" class="form-control"
                       value="<?php echo $this->config->get('GIFTCARD_THUMB_HEIGHT'); ?>"/>
            </div>
        </div>
    </div>
</div>
<?php
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_WATERMARK_GIFTCARD_THUMB_IMAGE_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_WATERMARK_GIFTCARD_THUMB_IMAGE_LBL'),
		'line'  => false,
		'field' => $this->lists['watermark_giftcart_thumb_image']
	)
);
?>
<div class="form-group row-fluid">
    <label class="col-md-4 hasPopover" data-content="<?php echo JText::_('COM_REDSHOP_TOOLTIP_GIFTCARD_LIST_THUMB_WIDTH_LBL'); ?>">
		<?php echo JText::_('COM_REDSHOP_GIFTCARD_LIST_THUMB_WIDTH_HEIGHT'); ?>
    </label>
    <div class="col-md-8">
        <div class="row">
            <div class="col-sm-6">
                <input type="number" name="giftcard_list_thumb_width" id="giftcard_list_thumb_width" class="form-control"
                       value="<?php echo $this->config->get('GIFTCARD_LIST_THUMB_WIDTH'); ?>"/>
            </div>
            <div class="col-sm-6">
                <input type="number" name="giftcard_list_thumb_height" id="giftcard_list_thumb_height" class="form-control"
                       value="<?php echo $this->config->get('GIFTCARD_LIST_THUMB_HEIGHT'); ?>"/>
            </div>
        </div>
    </div>
</div>
