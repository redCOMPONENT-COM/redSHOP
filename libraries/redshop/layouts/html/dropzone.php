<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

extract($displayData);
?>

<!-- Dropzone Container -->
<div action="/" class="dropzone" id="j-dropzone" enctype="multipart/form-data">
	<div class="fallback">
		<input name="file" type="file"/>
	</div>
</div>
<div class="btn-toolbar">
	<button type="button" class="btn btn-small btn-primary cropping" data-toggle="tooltip"
	title="<?php echo JText::_('COM_REDSHOP_MEDIA_BUTTON_CROP'); ?>">
		<span class="fa fa-crop"></span>
	</button>
	<!-- button -->
	<button type="button" class="btn btn-small btn-danger removing" data-toggle="tooltip"
	title="<?php echo JText::_('COM_REDSHOP_MEDIA_BUTTON_REMOVE'); ?>">
		<span class="fa fa-trash"></span>
	</button>
	<!-- button -->
	<button type="button" class="btn btn-small btn-success pull-right hasTooltip choosing"
	data-original-title="<?php echo JText::_('COM_REDSHOP_MEDIA_BUTTON_INSERT'); ?>">
		<span class="fa fa-picture-o"></span>
	</button>
</div>
<input type="hidden" name="<?php echo $mediaSection ?>_image" id="<?php echo $mediaSection ?>_image" class="img-select">
<!-- End Dropzone Container -->

<!-- Dropzone Template -->
<div id="j-dropzone-tpl" style="display: none">
	<div class="dz-preview dz-file-preview">
		<div class="dz-details">
			<!-- <div class="dz-filename"><span data-dz-name></span></div> -->
			<!-- <div class="dz-size" data-dz-size></div> -->
			<img data-dz-thumbnail />
		</div>
		<div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>
		<!-- <div class="dz-success-mark"><span>✔</span></div> -->
		<!-- <div class="dz-error-mark"><span>✘</span></div> -->
		<!-- <div class="dz-error-message"><span data-dz-errormessage></span></div> -->
	</div>
</div>
<!-- End Dropzone Template -->

<!-- Cropper Modal -->
<div id="cropModal" class="modal fade in" tabindex="-1" role="dialog" data-backdrop="static">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><?php echo JText::_('COM_REDSHOP_MEDIA_MODAL_CROPPER_TITLE'); ?></h4>
			</div>
			<div class="modal-body">
				<div class="image-container"></div>
			</div>
			<div class="modal-footer btn-toolbar text-center">
				<button type="button" class="btn btn-small float-none" data-dismiss="modal">
					<?php echo JText::_('COM_REDSHOP_MEDIA_MODAL_BTN_CANCEL'); ?>
				</button>
				<button type="button" class="btn btn-small btn-success float-none crop-upload">
					<?php echo JText::_('COM_REDSHOP_MEDIA_MODAL_BTN_CROP'); ?>
				</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Cropper Modal -->

<!-- Alert Modal -->
<div id="alertModal" class="modal fade in" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><i class="fa fa-warning text-yellow"></i> <?php echo JText::_('COM_REDSHOP_MEDIA_MODAL_ALERT_TITLE'); ?></h4>
			</div>
			<div class="modal-body">
				<div class="alert-text text-center"></div>
			</div>
			<div class="modal-footer btn-toolbar text-center">
				<button type="button" class="btn btn-small float-none" data-dismiss="modal"><?php echo JText::_('COM_REDSHOP_MEDIA_MODAL_BTN_CLOSE'); ?></button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Alert Modal -->
<script>
	rsMedia.dropzone();

	// preload file
	var file = false;
	<?php if (!empty($file)) { ?>
	file = {
		name: "<?php echo $file['name'] ?>",
		size: <?php echo $file['size'] ?>,
		// status: Dropzone.QUEUED,
		accepted: true,
		url: "<?php echo $file['path'] ?>",
		blob: "<?php echo $file['blob'] ?>",
		preload: true
	};
	<?php } ?>
	rsMedia.dropzonePreload(rsMedia.dropzoneInstance, file);
</script>
