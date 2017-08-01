<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
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
 * @var  array   $file          Files data as array
 * @var  array   $gallery       List of data
 */
extract($displayData);

?>
<!-- Cropper Modal -->
<div id="galleryModal" class="modal fade in" tabindex="-1" role="dialog" data-backdrop="static">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"> <i class="fa fa-picture-o"></i> <?php echo JText::_('COM_REDSHOP_MEDIA_ADDITIONAL_MEDIA_FILES') ?></h4>
			</div>
			<div class="modal-body">
				<ul class="nav nav-tabs" id="g-tab" role="tablist">
					<li role="presentation"><a href="#upload-media" aria-controls="upload-media" role="tab" data-toggle="tab">Upload Files</a></li>
					<li role="presentation" class="active"><a href="#upload-lib" aria-controls="upload-lib" role="tab" data-toggle="tab">Library Media</a></li>
				</ul>

				<!-- Tab panes -->
				<div class="tab-content">
					<div role="tabpanel" class="tab-pane fade" id="upload-media">
						<div class="col-md-12 dropzone-pane">
							<div class="row">
								<div action="/" class="dropzone" id="g-dropzone" enctype="multipart/form-data">
									<div class="dz-cons">&#11015;</div>
									<div class="dz-cons-addon"><i class="fa fa-folder-o"></i></div>
								</div>
							</div>
						</div>
					</div>

					<div role="tabpanel" class="tab-pane fade in active" id="upload-lib">
						<div class="col-md-9 thumbnail-pane">
							<div class="row">
								<div class="col-md-4">
									<select name="type_filter" id="type-filter" class="form-control">
										<option value="all">All Media</option>
										<option value="<?php echo $type ?>">All <?php echo $type ?></option>
										<option value="attached">Attached this <?php echo $type ?></option>
									</select>
								</div>
								<div class="col-md-4 pull-right">
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-search"></i></span>
										<input type="text" class="form-control" placeholder="Search..." aria-describedby="addon-search">
									</div>
								</div>
							</div>
							<div class="row list-pane">
								<?php if(!empty($gallery)) { ?>
									<?php foreach($gallery as $thumb) { ?>
										<div class="col-md-3">
											<div class="thumbnail img-obj">
												<?php if($thumb['mime'] == 'image') { ?>
													<img src="<?php echo $thumb['url'] ?>" alt="<?php echo $thumb['name'] ?>" class="img-type"
													     data-id="<?php echo $thumb['id'] ?>"
													     data-size="<?php echo $thumb['size'] ?>"
													     data-dimension="<?php echo $thumb['dimension'] ?>"
													     data-media="<?php echo $thumb['media'] ?>"
													     data-attached="<?php echo $thumb['attached'] ?>">
												<?php } else { ?>
													<span class="img-type img-icon img-file"
													      src="<?php echo $thumb['url'] ?>" alt="<?php echo $thumb['name'] ?>"
													      data-id="<?php echo $thumb['id'] ?>"
													      data-size="<?php echo $thumb['size'] ?>"
													      data-dimension="<?php echo $thumb['dimension'] ?>"
													      data-media="<?php echo $thumb['media'] ?>"
													      data-attached="<?php echo $thumb['attached'] ?>">
											<?php if (!empty($thumb['mime'])) { ?>
												<i class="fa fa-file-<?php echo $thumb['mime'] ?>-o"></i>
											<?php } else { ?>
												<i class="fa fa-file-o"></i>
											<?php } ?>
										</span>
												<?php } ?>
												<span class="img-status"><i class="fa fa-eye<?php echo $thumb['status'] ?>"></i></span>
												<span class="img-mime" data-mime="<?php echo $thumb['mime'] ?>">
											<?php if (!empty($thumb['mime'])) { ?>
												<i class="fa fa-file-<?php echo $thumb['mime'] ?>-o"></i>
											<?php } else { ?>
												<i class="fa fa-file-o"></i>
											<?php } ?>
										</span>
												<span class="img-name"><?php echo $thumb['name'] ?></span>
											</div>
										</div>
									<?php } ?>
								<?php } ?>
							</div>
						</div>

						<div class="col-md-3 preview-pane">
							<div class="pv-wrapper hidden">
								<div class="pv-title">ATTACHMENT DETAILS</div>
								<div class="pv-img thumbnail">
									<div class="pv-overlay">
										<a href="#" class="pv-zoom" data-lightbox="roadtrip"><i class="fa fa-search-plus"></i></a>
										<a href="#" class="pv-link" target="_blank"><i class="fa fa-external-link"></i></a>
									</div>
								</div>
								<div class="pv-info">
									<ul>
										<li class="pv-name"></li>
										<li class="pv-size"></li>
										<li class="pv-dimension"></li>
										<li class="pv-url"></li>
										<li class="pv-remove btn-toolbar">
											<a href="#" class="btn btn-small btn-danger btn-del-g" data-id="">
												<i class="fa fa-times"></i> Delete Permanently</a>
										</li>
									</ul>
								</div>
							</div>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
			<div class="modal-footer btn-toolbar text-center">
				<button type="button" class="btn btn-small btn-success pull-right btn-insert" disabled="true">
					<i class="fa fa-anchor"></i> Insert to <?php echo $type ?>
				</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Cropper Modal -->

<!-- Dropzone Template -->
<div id="g-dropzone-tpl" style="display: none">
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

<!-- Gallery Item Template -->
<div id="g-item-tpl" style="display: none">
	<div class="col-md-3">
		<div class="thumbnail img-obj">
			<img src="" alt="" class="img-type" data-id="" data-size="" data-dimension="" data-media="" data-attached="false">
			<span class="img-type img-icon img-file" src="" alt="" data-id="" data-size="" data-dimension="" data-media="" data-attached="">
				<i class="fa fa-file-o"></i>
			</span>
			<span class="img-status"><i class="fa fa-eye"></i></span>
			<span class="img-mime" data-mime=""><i class="fa fa-file-o"></i></span>
			<span class="img-name"></span>
		</div>
	</div>
</div>
<!-- Gallery Item Template -->

<!-- Alert Modal -->
<div id="alertGModal" class="modal fade in" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><i class="fa fa-warning text-yellow"></i> Warning</h4>
			</div>
			<div class="modal-body">
				<div class="alert-text text-center">
					<p>You are about to delete permanently this item.</p>
					<p>Are you sure to continue?</p>
				</div>
			</div>
			<div class="modal-footer btn-toolbar text-center">
				<button type="button" class="btn btn-small btn-danger float-none btn-confirm-del-g" data-url="" data-id="">Yes</button>
				<button type="button" class="btn btn-small float-none" data-dismiss="modal">No</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Alert Modal -->
<script>
    rsMedia.galleryDropzone();
    rsMedia.galleryEvents();
</script>
