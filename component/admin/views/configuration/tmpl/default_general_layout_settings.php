<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_CONFIG_LOAD_REDSHOP_STYLE_LBL'),
		'desc'  => JText::_('COM_REDSHOP_CONFIG_LOAD_REDSHOP_STYLE_DESC'),
		'field' => $this->lists['load_redshop_style']
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_DEFAULT_ALLOWED_EXTENSION_TYPE_LBL'),
		'desc'  => JText::_('COM_REDSHOP_DEFAULT_ALLOWED_EXTENSION_TYPE_TOOLTIP'),
		'field' => '<textarea name="media_allowed_mime_type" id="media_allowed_mime_type" cols="5" rows="5" class="form-control">'
			. $this->config->get('MEDIA_ALLOWED_MIME_TYPE') . '</textarea>'
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_DEFAULT_IMAGE_QUALITY_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_IMAGE_QUALITY_LBL'),
		'field' => '<input type="number" name="image_quality_output" id="image_quality_output" class="form-control"'
			. 'value="' . $this->config->get('IMAGE_QUALITY_OUTPUT', 70) . '"/>'
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_CONFIG_IMAGE_MAX_WIDTH_LBL'),
		'desc'  => JText::_('COM_REDSHOP_CONFIG_IMAGE_MAX_WIDTH_DESC'),
		'field' => '<input type="number" name="image_max_width" id="image_max_width" class="form-control"'
			. 'value="' . $this->config->get('IMAGE_MAX_WIDTH', 2048) . '"/>'
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_CONFIG_IMAGE_MAX_HEIGHT_LBL'),
		'desc'  => JText::_('COM_REDSHOP_CONFIG_IMAGE_MAX_HEIGHT_DESC'),
		'field' => '<input type="number" name="image_max_height" id="image_max_height" class="form-control"'
			. 'value="' . $this->config->get('IMAGE_MAX_HEIGHT', 2048) . '"/>'
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_CONFIG_IMAGE_PROCESSING_METHOD_LBL'),
		'desc'  => JText::_('COM_REDSHOP_CONFIG_IMAGE_PROCESSING_METHOD_DESC'),
		'field' => $this->lists ['use_image_size_swapping'],
		'line'  => false
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_CONFIG_IMAGE_MAX_FILE_SIZE_UPLOAD'),
		'desc'  => JText::_('COM_REDSHOP_CONFIG_IMAGE_MAX_FILE_SIZE_UPLOAD_DESC'),
		'field' => '<input type="number" name="max_file_size_upload" id="max_file_size_upload" class="form-control"'
			. 'value="' . $this->config->get('MAX_FILE_SIZE_UPLOAD', 2048) . '"/>'
	)
);
