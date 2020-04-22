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
		'title' => JText::_('COM_REDSHOP_RATING_DONE_MSG'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_RATING_DONE_MSG'),
		'field' => '<input type="text" name="rating_msg" id="rating_msg" size="50"'
			. ' class="form-control" value="' . $this->config->get('RATING_MSG') . '" />'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_FAVOURED_REVIEWS_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_FAVOURED_REVIEWS_LBL'),
		'field' => '<input type="number" name="favoured_reviews" id="favoured_reviews"'
			. ' class="form-control" value="' . $this->config->get('FAVOURED_REVIEWS') . '" />'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_RATING_THUMB_IMAGE_WIDTH_LBL'),
		'desc'  => JText::_('COM_REDSHOP_RATING_THUMB_IMAGE_WIDTH_DESC'),
		'field' => '<input type="number" name="rating_thumb_image_width" id="rating_thumb_image_width"'
			. ' class="form-control" value="' . $this->config->get('RATING_THUMB_IMAGE_WIDTH') . '" />'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_RATING_THUMB_IMAGE_HEIGHT_LBL'),
		'desc'  => JText::_('COM_REDSHOP_RATING_THUMB_IMAGE_HEIGHT_DESC'),
		'field' => '<input type="number" name="rating_thumb_image_height" id="rating_thumb_image_height"'
			. ' class="form-control" value="' . $this->config->get('RATING_THUMB_IMAGE_HEIGHT') . '" />'
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_RATING_REVIEW_LOGIN_REQUIRED_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_RATING_REVIEW_LOGIN_REQUIRED_LBL'),
		'line'  => false,
		'field' => $this->lists['rating_review_login_required']
	)
);
