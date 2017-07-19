<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
			<?php echo JText::_('COM_REDSHOP_IMPORT_STEP_1') ?>
		</h4>
	</div>
	<div class="panel-body" id="import_plugins">
		<?php foreach ($this->imports as $import): ?>
			<label>
				<input type="radio" value="<?php echo $import->name ?>"
				       name="plugin_name"/> <?php echo JText::_('PLG_REDSHOP_IMPORT_' . strtoupper($import->name) . '_TITLE') ?>
			</label>
		<?php endforeach; ?>
	</div>
</div>