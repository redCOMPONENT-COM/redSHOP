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
		'title' => JText::_('COM_REDSHOP_IMPORT_SETTINGS_MIN_FILE_SIZE'),
		'desc'  => JText::_('COM_REDSHOP_IMPORT_SETTINGS_MIN_FILE_SIZE_DESC'),
		'field' => '<div class="input-group"><input type="number" name="import_min_file_size" class="form-control"
            value="' . $this->config->get('IMPORT_MIN_FILE_SIZE', 1) . '"/><span class="input-group-addon">bytes</span></div>'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_IMPORT_SETTINGS_MAX_FILE_SIZE'),
		'desc'  => JText::_('COM_REDSHOP_IMPORT_SETTINGS_MAX_FILE_SIZE_DESC'),
		'field' => '<div class="input-group"><input type="number" name="import_max_file_size" class="form-control"
            value="' . $this->config->get('IMPORT_MAX_FILE_SIZE', 2000000) . '"/><span class="input-group-addon">bytes</span></div>'
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_IMPORT_SETTINGS_FILE_EXTENSION'),
		'desc'  => JText::_('COM_REDSHOP_IMPORT_SETTINGS_FILE_EXTENSION_DESC'),
		'field' => '<input type="text" name="import_file_extension" class="form-control"
                   value="' . $this->config->get('IMPORT_FILE_EXTENSION', '.csv') . '"/>',
		'line'  => false
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_IMPORT_SETTINGS_MAX_LINE'),
		'desc'  => JText::_('COM_REDSHOP_IMPORT_SETTINGS_MAX_LINE_DESC'),
		'field' => '<input type="text" name="import_max_line" class="form-control"
                   value="' . $this->config->get('IMPORT_MAX_LINE', 1) . '"/>',
		'line'  => false
	)
);
