<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$mediaConfig =& $this->lists['media'];

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_CONFIG_MEDIA_UPLOAD_MAX_FILE_SIZE'),
		'desc'  => JText::_('COM_REDSHOP_CONFIG_MEDIA_UPLOAD_MAX_FILE_SIZE_DESC'),
		'field' => '<label name="media_upload_max_filesize" id="media_upload_max_filesize"><b>' . $this->upload_max_filesize . '</b></label>'
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_CONFIG_MEDIA_POST_MAX_SIZE'),
		'desc'  => JText::_('COM_REDSHOP_CONFIG_MEDIA_POST_MAX_SIZE_DESC'),
		'field' => '<label name="media_post_max_size" id="media_post_max_size"><b>' . $this->post_max_size . '</b></label>'
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_CONFIG_MEDIA_EXTENSION'),
		'desc'  => JText::_('COM_REDSHOP_CONFIG_MEDIA_EXTENSION_DESC'),
		'field' => $mediaConfig['media_upload_extension'],
		'line'  => false,
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_CONFIG_MEDIA_MIMETYPE'),
		'desc'  => JText::_('COM_REDSHOP_CONFIG_MEDIA_MIMETYPE_DESC'),
		'field' => $mediaConfig['media_upload_mimetype'],
		'line'  => false,
	)
);
