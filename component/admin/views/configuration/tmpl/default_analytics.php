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
		'title' => JText::_('COM_REDSHOP_GOOGLE_ANALYTICS_TRACKER_KEY'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_GOOGLE_ANALYATICS_TRACKER_KEY'),
		'field' => '<input type="text" name="google_ana_tracker" id="google_ana_tracker"
            value="' . $this->config->get('GOOGLE_ANA_TRACKER_KEY') . '" class="form-control" />'
	)
);
