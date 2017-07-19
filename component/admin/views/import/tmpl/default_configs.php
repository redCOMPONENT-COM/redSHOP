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
					<span class="text-primary"><?php echo number_format($allowMinFileSize) ?>
						bytes</span>
				</p>
				<p>
					<?php echo JText::_('COM_REDSHOP_IMPORT_SETTINGS_MAX_FILE_SIZE') ?>:&nbsp;
					<span class="text-primary"><?php echo number_format($allowMaxFileSize) ?>
						bytes</span>
				</p>
				<p>
					<?php echo JText::_('COM_REDSHOP_IMPORT_SETTINGS_FILE_MIME') ?>:&nbsp;
					<span class="text-primary"><?php echo implode(', ', $allowFileTypes) ?></span>
				</p>
				<p>
					<?php echo JText::_('COM_REDSHOP_IMPORT_SETTINGS_FILE_EXTENSION') ?>:&nbsp;
					<span class="text-primary"><?php echo implode(', ', $allowFileExtensions) ?></span>
				</p>
				<p class="help-block"><?php echo JText::_('COM_REDSHOP_IMPORT_SETTINGS_HELP') ?></p>
				<hr/>
				<div class="form-group">
					<label class="col-md-2 control-label">
						<?php echo JText::_('COM_REDSHOP_IMPORT_CONFIG_SEPARATOR') ?>
					</label>
					<div class="col-md-10">
						<input type="text" value="," class="form-control" maxlength="1"
						       name="separator"/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label"><?php echo JText::_('COM_REDSHOP_IMPORT_ENCODING') ?></label>
					<div class="col-md-10">
						<?php echo JHtml::_('select.genericlist', $encodings, 'encoding', 'class="form-control"', 'value', 'text', 'UTF-8'); ?>
					</div>
				</div>
				<div id="import_config_body"></div>
			</fieldset>
		</div>
	</div>
</div>