<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHtml::_('behavior.modal', '.joom-box');

$url = JUri::root();
$link_path = "/components/com_redshop/assets/images/";

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_RETURN_TO_CATEGORY_PREFIX'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_RETURN_TO_CATEGORY_PREFIX'),
		'field' => '<input type="text" name="return_to_category_prefix" id="return_to_category_prefix" class="form-control"'
			. ' value="' . $this->config->get('DAFULT_RETURN_TO_CATEGORY_PREFIX') . '"/>'
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_DEFAULT_NP_LINK_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_NP_LINK'),
		'field' => $this->lists['next_previous_link']
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_DAFULT_PREVIOUS_PREFIX_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_DAFULT_PREVIOUS'),
		'field' => '<input type="text" name="default_previous_prefix" id="default_previous_prefix" class="form-control"
                   value="' . $this->config->get('DAFULT_PREVIOUS_LINK_PREFIX') . '"/>'
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_DAFULT_NEXT_SUFFIX_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_DAFULT_NEXT_SUFFIX'),
		'field' => '<input type="text" name="default_next_suffix" id="default_next_suffix" class="form-control"
                   value="' . $this->config->get('DAFULT_NEXT_LINK_SUFFIX') . '"/>'
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_CUSTOM_PREVIOUS_LINK'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_CUSTOM_PREVIOUS_LINK'),
		'field' => '<input type="text" name="custom_previous_link" id="custom_previous_link" class="form-control"
                   value="' . $this->config->get('CUSTOM_PREVIOUS_LINK_FIND') . '"/>'
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_CUSTOM_NEXT_LINK'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_CUSTOM_NEXT_LINK'),
		'field' => '<input type="text" name="custom_next_link" id="custom_next_link" class="form-control"
                   value="' . $this->config->get('CUSTOM_NEXT_LINK_FIND') . '"/>'
	)
);
?>
<div class="form-group row-fluid">
    <label class="col-md-4 hasPopover" data-content="<?php echo JText::_('COM_REDSHOP_TOOLTIP_IMAGE_PREVIOUS_LINK'); ?>">
		<?php echo JText::_('COM_REDSHOP_IMAGE_PREVIOUS_LINK'); ?>
    </label>
    <div class="col-md-8">
		<?php $imagePreviousLinkFind = $this->config->get('IMAGE_PREVIOUS_LINK_FIND'); ?>
        <div>
            <div>
                <input class="text_area" type="file" name="imgpre" id="imgpre" size="40"/>
                <input type="hidden" name="image_previous_link" id="image_previous_link"
                       value="<?php echo $imagePreviousLinkFind; ?>"/>
                <a href="#123"
                   onclick="delimg('<?php echo $imagePreviousLinkFind ?>','prvlinkdiv','<?php echo $link_path ?>');"><?php echo JText::_('COM_REDSHOP_REMOVE_FILE'); ?></a>
            </div>
			<?php if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . $imagePreviousLinkFind)): ?>
                <div id="prvlinkdiv">
                    <a class="joom-box" href="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . $imagePreviousLinkFind; ?>"
                       title="<?php echo $imagePreviousLinkFind; ?>" rel="{handler: 'image', size: {}}">
                        <img alt="<?php echo $imagePreviousLinkFind; ?>" class="thumbnail"
                                src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . $imagePreviousLinkFind; ?>"/>
                    </a>
                </div>
			<?php endif; ?>
        </div>
    </div>
</div>
<div class="form-group row-fluid">
    <label class="col-md-4 hasPopover" data-content="<?php echo JText::_('COM_REDSHOP_TOOLTIP_IMAGE_NEXT_LINK'); ?>">
		<?php echo JText::_('COM_REDSHOP_IMAGE_NEXT_LINK'); ?>
    </label>
    <div class="col-md-8">
		<?php $imageNextLinkFind = $this->config->get('IMAGE_NEXT_LINK_FIND'); ?>
        <div>
            <div>
                <input class="text_area" type="file" name="imgnext" id="imgnext" size="40"/>
                <input type="hidden" name="image_next_link" id="image_next_link"
                       value="<?php echo $imageNextLinkFind; ?>"/>
                <a href="#123"
                   onclick="delimg('<?php echo $imageNextLinkFind ?>','nxtlinkdiv','<?php echo $link_path ?>');"><?php echo JText::_('COM_REDSHOP_REMOVE_FILE'); ?></a>
            </div>
			<?php if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . $imageNextLinkFind)): ?>
                <div id="nxtlinkdiv">
                    <a class="joom-box" href="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . $imageNextLinkFind; ?>"
                       title="<?php echo $imageNextLinkFind; ?>" rel="{handler: 'image', size: {}}">
                        <img alt="<?php echo $imageNextLinkFind; ?>" class="thumbnail"
                                src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . $imageNextLinkFind; ?>"/>
                    </a>
                </div>
			<?php endif; ?>
        </div>
    </div>
</div>
