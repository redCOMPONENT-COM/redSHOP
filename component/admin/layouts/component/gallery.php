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
<!-- Cropper Modal -->
<div id="galleryModal" class="modal fade in" tabindex="-1" role="dialog" data-backdrop="static">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"> <i class="fa fa-picture-o"></i> Gallery</h4>
			</div>
			<div class="modal-body">
				<ul class="nav nav-tabs" role="tablist">
					<li role="presentation"><a href="#upload-media" aria-controls="upload-media" role="tab" data-toggle="tab">Upload Files</a></li>
					<li role="presentation" class="active"><a href="#upload-lib" aria-controls="upload-lib" role="tab" data-toggle="tab">Library Media</a></li>
				</ul>

				<!-- Tab panes -->
				<div class="tab-content">
					<div role="tabpanel" class="tab-pane" id="upload-media">...</div>

					<div role="tabpanel" class="tab-pane active" id="upload-lib">
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
								<div class="col-md-2">
									<div class="thumbnail img-obj">
										<img src="<?php echo $thumb['url'] ?>" alt="<?php echo $thumb['name'] ?>"
										data-id="<?php echo $thumb['id'] ?>"
										data-size="<?php echo $thumb['size'] ?>"
										data-dimension="<?php echo $thumb['dimension'] ?>"
										data-media="<?php echo $thumb['media'] ?>"
										data-attached="<?php echo $thumb['attached'] ?>"	>
									</div>
								</div>
								<?php } ?>
								<?php } ?>
							</div>
						</div>

						<div class="col-md-3 preview-pane">
							<div class="pv-wrapper hidden">
								<div class="pv-title">ATTACHMENT DETAILS</div>
								<div class="pv-img thumbnail"></div>
								<div class="pv-info">
									<ul>
										<li class="pv-name"></li>
										<li class="pv-size"></li>
										<li class="pv-dimension"></li>
										<li class="pv-url"></li>
										<li class="pv-remove"><a href="" class="btn btn-small btn-danger" data-toggle="modal" data-target="#alertGModal"><i class="fa fa-times"></i> Delete Permanently</a></li>
									</ul>
								</div>
							</div>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
			<div class="modal-footer btn-toolbar text-center">
				<button type="button" class="btn btn-small btn-success pull-right btn-insert" disabled="true"><i class="fa fa-anchor"></i> Insert to <?php echo $type ?></button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Cropper Modal -->

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
				<button type="button" class="btn btn-small btn-danger float-none" style="margin-right: 10px;" >Yes</button>
				<button type="button" class="btn btn-small float-none" data-dismiss="modal">No</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Alert Modal -->
<script>
	rsMedia.customizeModal();
	rsMedia.galleryEvents();
</script>
