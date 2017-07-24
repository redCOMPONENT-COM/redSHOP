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
<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
			<?php echo JText::_('COM_REDSHOP_IMPORT_STEP_2') ?>
		</h4>
	</div>
	<div class="panel-body">
		<div id="import_config">
			<fieldset class="form-horizontal">
				<p>
					<?php echo JText::_('COM_REDSHOP_IMPORT_SETTINGS_MIN_FILE_SIZE') ?>:&nbsp;
					<span class="text-primary"><?php echo \Redshop\String\Helper::getFilesize($this->allowMinFileSize) ?></span>
				</p>
				<p>
					<?php echo JText::_('COM_REDSHOP_IMPORT_SETTINGS_MAX_FILE_SIZE') ?>:&nbsp;
					<span class="text-primary"><?php echo \Redshop\String\Helper::getFilesize($this->allowMaxFileSize) ?></span>
				</p>
				<p>
					<?php echo JText::_('COM_REDSHOP_IMPORT_SETTINGS_FILE_EXTENSION') ?>:&nbsp;
					<span class="text-primary"><?php echo implode(', ', $this->allowFileExtensions) ?></span>
				</p>
				<p class="help-block"><?php echo JText::_('COM_REDSHOP_IMPORT_SETTINGS_HELP') ?></p>
				<hr/>
				<div class="form-group">
					<label class="col-md-2 control-label">
						<?php echo JText::_('COM_REDSHOP_IMPORT_CONFIG_SEPARATOR') ?>
					</label>
					<div class="col-md-10">
						<input type="text" value="," class="form-control" maxlength="1" name="separator"/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label"><?php echo JText::_('COM_REDSHOP_IMPORT_ENCODING') ?></label>
					<div class="col-md-10">
						<?php echo JHtml::_(
							'select.genericlist',
							$this->encodings,
							'encoding',
							'class="form-control"',
							'value',
							'text',
							'UTF-8'
						); ?>
					</div>
				</div>
				<div id="import_config_body"></div>
			</fieldset>
		</div>
	</div>
</div>
