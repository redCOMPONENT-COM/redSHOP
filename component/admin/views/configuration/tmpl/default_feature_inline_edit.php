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
		'title' => JText::_('COM_REDSHOP_FEATURE_INLINE_EDIT_LBL'),
		'desc'  => JText::_('COM_REDSHOP_FEATURE_INLINE_EDIT_DESC'),
		'field' => $this->lists['inline_editing']
	)
);
