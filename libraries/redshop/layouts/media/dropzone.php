<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Layout variables
 * =======================
 * @var  array   $displayData   List of data.
 * @var  string  $id            ID
 * @var  string  $type          Type of section (Ex: product)
 * @var  string  $sectionId     Section ID (Ex: Product ID if $type is product)
 * @var  string  $mediaSection  Section media (Ex: product)
 * @var  array   $file          File data as array
 * @var  bool    $showMedia     Show pop-up for select image from media or not
 */
extract($displayData);
?>

<!-- Dropzone Container -->
<div action="/" class="dropzone" id="j-dropzone" enctype="multipart/form-data">
	<div class="fallback">
		<input name="file" type="file"/>
	</div>
</div>
<hr />
<div class="btn-group btn-group-justified" role="group">
	<div class="btn-group" role="group">
		<button type="button" class="btn btn-primary cropping" data-toggle="tooltip"
				title="<?php echo JText::_('COM_REDSHOP_MEDIA_BUTTON_CROP'); ?>">
			<i class="fa fa-crop"></i> <?php echo JText::_('COM_REDSHOP_MEDIA_BUTTON_CROP') ?>
		</button>
	</div>
	<div class="btn-group" role="group">
		<button type="button" class="btn btn-danger removing" data-toggle="tooltip"
				title="<?php echo JText::_('COM_REDSHOP_MEDIA_BUTTON_REMOVE'); ?>">
			<i class="fa fa-trash"></i> <?php echo JText::_('COM_REDSHOP_MEDIA_BUTTON_REMOVE') ?>
		</button>
	</div>
	<?php if ($showMedia): ?>
	<div class="btn-group" role="group">
		<button type="button" class="btn btn-success hasTooltip choosing"
				data-original-title="<?php echo JText::_('COM_REDSHOP_MEDIA_BUTTON_INSERT'); ?>">
			<i class="fa fa-picture-o"></i> <?php echo JText::_('COM_REDSHOP_MEDIA_BUTTON_INSERT'); ?>
		</button>
	</div>
	<?php endif; ?>
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

<?php if ($showMedia): ?>
	<?php
	$selectedImage = !empty($file) ? $file['name'] : '';

	echo RedshopLayoutHelper::render(
		'media.media_files',
		array(
			'id'           => $id,
			'type'         => $type,
			'sectionId'    => $sectionId,
			'mediaSection' => $mediaSection,
			'gallery'  => RedshopHelperMediaImage::getMediaFiles($selectedImage)
		)
	);
	?>
<?php endif; ?>

<script type="text/javascript">
	rsMedia.dropzone();

	// Preload file
	var file = false;
	<?php if (!empty($file)): ?>
	file = {
		name: "<?php echo $file['name'] ?>",
		size: <?php echo $file['size'] ?>,
		// status: Dropzone.QUEUED,
		accepted: true,
		url: "<?php echo $file['path'] ?>",
		blob: "<?php echo $file['blob'] ?>",
		preload: true
	};
	<?php endif; ?>
	rsMedia.dropzonePreload(rsMedia.dropzoneInstance, file);
</script>
