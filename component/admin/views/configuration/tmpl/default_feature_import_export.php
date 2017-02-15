<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

?>

<div class="row">
    <div class="col-sm-6">
        <legend><?php echo JText::_('COM_REDSHOP_IMPORT_SETTINGS'); ?></legend>

        <div class="form-group">
			<span class="editlinktip hasTip"
                  title="<?php echo JText::_('COM_REDSHOP_IMPORT_SETTINGS_MIN_FILE_SIZE'); ?>::<?php echo JText::_('COM_REDSHOP_IMPORT_SETTINGS_MIN_FILE_SIZE_DESC'); ?>">
				<label
                        for="default_stockroom"><?php echo JText::_('COM_REDSHOP_IMPORT_SETTINGS_MIN_FILE_SIZE'); ?></label>
			</span>

            <div class="input-group">
                <input type="number" name="import_min_file_size" class="form-control"
                       value="<?php echo $this->config->get('IMPORT_MIN_FILE_SIZE', 1) ?>"/>
                <span class="input-group-addon">bytes</span>
            </div>
        </div>

        <div class="form-group">
			<span class="editlinktip hasTip"
                  title="<?php echo JText::_('COM_REDSHOP_IMPORT_SETTINGS_MAX_FILE_SIZE'); ?>::<?php echo JText::_('COM_REDSHOP_IMPORT_SETTINGS_MAX_FILE_SIZE_DESC'); ?>">
				<label
                        for="default_stockroom"><?php echo JText::_('COM_REDSHOP_IMPORT_SETTINGS_MAX_FILE_SIZE'); ?></label>
			</span>
            <div class="input-group">
                <input type="number" name="import_max_file_size" class="form-control"
                       value="<?php echo $this->config->get('IMPORT_MAX_FILE_SIZE', 2000000) ?>"/>
                <span class="input-group-addon">bytes</span>
            </div>
        </div>

        <div class="form-group">
			<span class="editlinktip hasTip"
                  title="<?php echo JText::_('COM_REDSHOP_IMPORT_SETTINGS_FILE_MIME'); ?>::<?php echo JText::_('COM_REDSHOP_IMPORT_SETTINGS_FILE_MIME_DESC'); ?>">
				<label
                        for="default_stockroom"><?php echo JText::_('COM_REDSHOP_IMPORT_SETTINGS_FILE_MIME'); ?></label>
			</span>

            <input type="text" name="import_file_mime" class="form-control"
                   value="<?php echo $this->config->get('IMPORT_FILE_MIME', 'text/csv,application/vnd.ms-excel') ?>"/>
        </div>

        <div class="form-group">
			<span class="editlinktip hasTip"
                  title="<?php echo JText::_('COM_REDSHOP_IMPORT_SETTINGS_FILE_EXTENSION'); ?>::<?php echo JText::_('COM_REDSHOP_IMPORT_SETTINGS_FILE_EXTENSION_DESC'); ?>">
				<label
                        for="default_stockroom"><?php echo JText::_('COM_REDSHOP_IMPORT_SETTINGS_FILE_EXTENSION'); ?></label>
			</span>

            <input type="text" name="import_file_extension" class="form-control"
                   value="<?php echo $this->config->get('IMPORT_FILE_EXTENSION', '.csv') ?>"/>
        </div>

    </div>
</div>

