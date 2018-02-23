<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHtml::_('behavior.modal', 'a.joom-box');

$editor          = JFactory::getEditor();
$plgManufacturer = RedshopHelperOrder::getParameters('plg_manucaturer_excluding_category');

?>

<div class="row">
    <div class="col-sm-8">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo JText::_('COM_REDSHOP_DETAILS'); ?></h3>
            </div>
            <div class="box-body">
                <div class="form-group">
                    <label for="template">
						<?php echo JText::_('COM_REDSHOP_TEMPLATE'); ?>:
                    </label>
					<?php echo $this->lists['template']; ?>
                </div>

				<?php if (count($plgManufacturer) > 0 && $plgManufacturer[0]->enabled): ?>
                    <div class="form-group">
                        <label>
							<?php echo JText::_('COM_REDSHOP_EXCLUDING_CATEGORY_LIST'); ?>:
                        </label>
						<?php echo $this->lists['excluding_category_list']; ?>
                    </div>
				<?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo JText::_('COM_REDSHOP_IMAGE'); ?></h3>
            </div>
            <div class="box-body">
				<?php $media = RedshopEntityManufacturer::getInstance($this->detail->manufacturer_id)->getMedia(); ?>
                <div class="form-group">
                    <label><?php echo JText::_('COM_REDSHOP_MEDIA_ALTERNATE_TEXT') ?></label>
                    <input type="text" class="form-control" name="media_alternate_text"
                           value="<?php echo $media->get('media_alternate_text') ?>"
                           placeholder="<?php echo JText::_('COM_REDSHOP_TOOLTIP_MEDIA_ALTERNATE_TEXT') ?>"/>
                </div>
				<?php echo RedshopHelperMediaImage::render(
					'manufacturer_image',
					'manufacturer',
					$this->detail->manufacturer_id,
					'manufacturer',
					$media->get('media_name'),
					false,
					true,
					(int) $media->get('media_id')
				); ?>
            </div>
        </div>
    </div>
</div>
