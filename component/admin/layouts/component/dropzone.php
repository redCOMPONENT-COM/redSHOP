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
<style type="text/css">
	.dropzone{ border: 1px dashed #ccc; display: flex; align-items: center; }
	.dropzone .dz-message{margin: 0 auto;}
	.dropzone .dz-preview.dz-image-preview{padding: 0; margin: 0; width: 100%; overflow: hidden;}
	.dropzone .dz-preview.dz-image-preview .dz-details{opacity: 1; padding: 0; max-width: initial; min-height: auto; position: relative;}

	.modal-content .modal-body{max-height: initial;}
</style>

<!-- Dropzone Container -->
<div action="/" class="dropzone" id="j-dropzone" enctype="multipart/form-data">
	<div class="fallback">
		<input name="file" type="file"/>
	</div>
</div>
<!-- End Dropzone Container -->

<!-- Dropzone Template -->
<div id="j-dropzone-tpl" style="display: none">
	<div class="dz-preview dz-file-preview">
		<div class="dz-details">
			<!-- <div class="dz-filename"><span data-dz-name></span></div> -->
			<!-- <div class="dz-size" data-dz-size></div> -->
			<img data-dz-thumbnail />
		</div>
		<!-- <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div> -->
		<!-- <div class="dz-success-mark"><span>✔</span></div> -->
		<!-- <div class="dz-error-mark"><span>✘</span></div> -->
		<!-- <div class="dz-error-message"><span data-dz-errormessage></span></div> -->
	</div>
</div>
<!-- End Dropzone Template -->

<!-- Cropper Modal -->
<div id="cropModal" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Modal title</h4>
			</div>
			<div class="modal-body">
				<div class="image-container"></div>
			</div>
			<div class="modal-footer btn-toolbar">
				<button type="button" class="btn btn-small" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-small btn-success crop-upload">Crop Image</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Cropper Modal -->

<script>
	//$(function(){

		// transform cropper dataURI output to a Blob which Dropzone accepts
		function dataURItoBlob(dataURI) {
		    var byteString = atob(dataURI.split(',')[1]);
		    var ab = new ArrayBuffer(byteString.length);
		    var ia = new Uint8Array(ab);
		    for (var i = 0; i < byteString.length; i++) {
		        ia[i] = byteString.charCodeAt(i);
		    }
		    return new Blob([ab], { type: 'image/jpeg' });
		}

		Dropzone.autoDiscover = false;

		var dropzoneFromHtml = $("#j-dropzone-form").html();
		$('body').append(dropzoneFromHtml);
		if ($('#j-dropzone').length) {
			var jDropzone = new Dropzone(
				"#j-dropzone",
				{
					autoProcessQueue: false,
					maxFiles: 1,
					thumbnailWidth: null,
					thumbnailHeight: null,
					previewTemplate: $("#j-dropzone-tpl").html(),
					// initialize
					/*init: function() {
						this.on('addedfile', function(file) {
							if (this.files.length > 1) {
								this.removeFile(this.files[0]);
							}
						})
					}*/
				}
			);

			jDropzone.on("maxfilesexceeded", function(file) {
				this.removeAllFiles();
				this.addFile(file);
			});

			jDropzone.on('addedfile',  function(file) {
				if (this.files.length > 1) {
					this.removeFile(this.files[0]);
				}
			});

			jDropzone.on('thumbnail',  function(file) {
				// ignore files which were already cropped and re-rendered
				// to prevent infinite loop
				if (file.cropped) {
					return;
				}
				if (file.width < 100) {
					// validate width to prevent too small files to be uploaded
					// .. add some error message here
					return;
				}
				// cache filename to re-assign it to cropped file
				var cachedFilename = file.name;
				// remove not cropped file from dropzone (we will replace it later)
				jDropzone.removeFile(file);

				// dynamically create modals to allow multiple files processing
				// var $cropperModal = $($.parseHTML(modalTemplate));
				var $cropperModal = $("#cropModal");
				// 'Crop and Upload' button in a modal
				var $uploadCrop = $cropperModal.find('.crop-upload');

				var $img = $('<img />');
				// initialize FileReader which reads uploaded file
				var reader = new FileReader();
				reader.onloadend = function () {
					// add uploaded and read image to modal
					$cropperModal.find('.image-container').html($img);
					$img.attr('src', reader.result);

					// initialize cropper for uploaded image
					$img.cropper({
						// aspectRatio: 16 / 9,
						dragMode: 'move',
						autoCropArea: 1,
						movable: false,
						cropBoxResizable: true,
						minContainerWidth: "100%",
						viewMode: 1,
						zoomable: false
					});
				};
				// read uploaded file (triggers code above)
				reader.readAsDataURL(file);

				$cropperModal.modal('show');

				// listener for 'Crop and Upload' button in modal
				$uploadCrop.on('click', function() {
					// get cropped image data
					var blob = $img.cropper('getCroppedCanvas').toDataURL();
					// transform it to Blob object
					var newFile = dataURItoBlob(blob);
					// set 'cropped to true' (so that we don't get to that listener again)
					newFile.cropped = true;
					// assign original filename
					newFile.name = cachedFilename;

					// add cropped file to dropzone
					jDropzone.addFile(newFile);
					// upload cropped file with dropzone
					/*jDropzone.processQueue();*/
					$cropperModal.modal('hide');
				});
			});
		}
	//});
</script>
