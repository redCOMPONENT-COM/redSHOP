<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title"
		    id="import_process_title"><?php echo JText::_('COM_REDSHOP_IMPORT_STEP_3') ?></h4>
	</div>
	<div id="import_process_panel">
		<div class="panel-body">
			<div class="row">
				<div class="col-md-6">
					<button class="btn btn-primary btn-large" id="import_btn_start" type="button">
						<?php echo JText::_('COM_REDSHOP_IMPORT_SELECT_FILE') ?>&nbsp;&nbsp;<i
							class="fa fa-upload"></i>
					</button>
					<input id="fileupload" type="file" name="csv_file" class="hidden"
					       data-url="index.php?option=com_redshop&task=import.uploadFile"/>
					<p></p>
					<div class="progress" id="import_upload_progress_wrapper" style="display: none;">
						<div id="import_upload_progress" class="progress-bar" role="progressbar"
						     aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"
						     style="width: 0%;">0%
						</div>
					</div>
					<hr/>
					<h3><?php echo JText::_('COM_REDSHOP_IMPORT_DATA_IMPORT') ?>: <span
							id="import_count"></span></h3>
					<div class="progress" style="display: none;">
						<div id="import_process_bar" class="progress-bar progress-bar-success"
						     role="progressbar"
						     aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"
						     style="width: 0%;">
							0%
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<fieldset id="import_process_msg">
							<legend><?php echo JText::_('COM_REDSHOP_IMPORT_LOG') ?></legend>
							<div id="import_process_msg_body"
							     style="max-height: 300px; overflow-x: hidden;"></div>
						</fieldset>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>