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
		'title' => JText::_('COM_REDSHOP_CONFIG_MEDIA_ADMIN_VIDEO_WIDTH'),
		'desc'  => JText::_('COM_REDSHOP_CONFIG_MEDIA_ADMIN_VIDEO_WIDTH_DESC'),
		'field' => '<input type="text" name="media_preview_video_width" id="shop_name" value="' . $this->config->get('media_preview_video_width') . '" class="form-control"/>'
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_CONFIG_MEDIA_ADMIN_VIDEO_HEIGHT'),
		'desc'  => JText::_('COM_REDSHOP_CONFIG_MEDIA_ADMIN_VIDEO_HEIGHT_DESC'),
		'field' => '<input type="text" name="media_preview_video_height" id="shop_name" value="' . $this->config->get('media_preview_video_height') . '" class="form-control"/>'
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_CONFIG_MEDIA_ADMIN_VIDEO_AUTOPLAY'),
		'desc'  => JText::_('COM_REDSHOP_CONFIG_MEDIA_ADMIN_VIDEO_AUTOPLAY_DESC'),
		'field' => $mediaConfig['media_video_autoplay']
	)
);
