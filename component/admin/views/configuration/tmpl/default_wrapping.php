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
    <div class="form-group row-fluid">
        <label class="col-md-4 hasPopover" data-content="<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_WRAPPER_THUMB_WIDTH_LBL'); ?>">
			<?php echo JText::_('COM_REDSHOP_DEFAULT_WRAPPER_THUMB_WIDTH_HEIGHT'); ?>
        </label>
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-6">
                    <input type="number" name="default_wrapper_thumb_width" class="form-control"
                           value="<?php echo $this->config->get('DEFAULT_WRAPPER_THUMB_WIDTH') ?>"/>
                </div>
                <div class="col-md-6">
                    <input type="number" name="default_wrapper_thumb_height" class="form-control"
                           value="<?php echo $this->config->get('DEFAULT_WRAPPER_THUMB_HEIGHT') ?>"/>
                </div>
            </div>
        </div>
    </div>
<?php
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_AUTO_SCROLL_FOR_WRAPPER_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_AUTO_SCROLL_FOR_WRAPPER_LBL'),
		'field' => $this->lists['auto_scroll_wrapper']
	)
);
