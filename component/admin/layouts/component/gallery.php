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
	.modal-content{border-radius: 6px; box-sizing: border-box; width: 100%;}
	.modal-content .modal-body{max-height: initial; margin: 20px; padding: 0; width: auto;overflow: initial;}

	.btn-toolbar .btn-primary{background-color: #286090; color: #fff;}
	.btn-toolbar .btn-danger{background-color: #d9534f; color: #fff;}

	.text-center{text-align: center;}

	.btn-toolbar .float-none{float: none;}

	.list-pane{padding: 15px 0;}
	.tab-content > .tab-pane{position: relative;}
	#galleryModal .modal-dialog{position: absolute; top: 0; bottom: 0; left: 30px; right: 30px; width: 1024px;}
	#galleryModal .modal-dialog .modal-content{position: absolute; top: 0; bottom: 0; left: 0; right: 0;}
	#galleryModal .modal-dialog .modal-body{max-height: initial; margin: 0; padding: 0; overflow: auto !important; position: absolute; bottom: 64px; top: 40px; width: 100%;}
	#galleryModal .modal-dialog .modal-body .nav-tabs{position: absolute; height: 37px; left: 0; right: 25%; top: 0; margin: 0 !important; width: 100%;}
	#galleryModal .modal-dialog .modal-body .tab-content{position: absolute; left: 0; top: 37px; bottom: 0; overflow: auto; height: auto; }
	#galleryModal .modal-dialog .modal-body .tab-pane{position: relative; height: 100%; overflow: hidden; }
	#galleryModal .modal-dialog .modal-body .thumbnail-pane{position: relative; height: 100%; overflow: auto; padding: 15px;}
	#galleryModal .modal-dialog .modal-body .preview-pane{position: absolute; background: #f3f3f3; top: 0; border-left: 1px solid #ddd; right: 0; overflow: auto; bottom: 0; box-shadow: inset 1px 0 0 #fff;}
	#galleryModal .modal-dialog .modal-footer{position: absolute; bottom: 0; left: 0; right: 0; margin-bottom: 0;}
	.modal-backdrop, .modal-backdrop.fade.in{opacity: 0.4}

	#alertGModal .modal-sm{width: 360px;}

	.img-obj{cursor: pointer; width: 88px; height: 88px; overflow: hidden;}
	.img-obj:hover{box-shadow: 1px 1px 3px #ddd;}
	.img-obj.selected{box-shadow: 0px 0px 5px #0073aa; border-color: #0073aa;}

	.pv-wrapper{padding: 15px;}
	.pv-title{margin-bottom: 5px;}
	.pv-img{margin-bottom: 5px;}
	.pv-info ul{list-style: none; color: #666; font-size: 12px; margin: 0; padding: 0;}
	.pv-info ul li{margin-bottom: 5px;}
	.pv-name{font-weight: bold;}
</style>
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
									<select name="" id="" class="form-control">
										<option value="">All <?php echo $type ?></option>
										<option value="">Attached this <?php echo $type ?></option>
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
										<img src="<?php echo $thumb['url'] ?>" alt="<?php echo $thumb['name'] ?>" data-id="<?php echo $thumb['id'] ?>" data-size="<?php echo $thumb['size'] ?>" data-dimension="<?php echo $thumb['dimension'] ?>">
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
										<li class="pv-crop"><a href="" class="btn btn-small btn-success"><i class="fa fa-crop"></i> Crop Image</a></li>
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
	$(".modal").on("show.bs.modal", function(e){
		$(document).find(".modal-backdrop").remove();
	});

	$(".img-obj").on('click', function(e){
		e.preventDefault();
		if ($(this).hasClass('selected')) {
			$(this).removeClass('selected');
		} else{
			$('.img-obj').removeClass('selected');
			$(this).addClass('selected');
			showInfoThumbnail(this);
		}
		resetInfoThumbnail();
		toggleInsert();
	});

	function showInfoThumbnail(elem)
	{
		var info = {
			url: $(elem).find('img').attr('src'),
			name: $(elem).find('img').attr('alt'),
			size: $(elem).find('img').data('size'),
			dimension: $(elem).find('img').data('dimension')
		};

		var $img = $(elem).find('img').clone();

		var $pane = $(".preview-pane");
		$pane.find('.pv-img').html($img);
		$pane.find('.pv-name').text(info.name);
		$pane.find('.pv-size').text(info.size);
		$pane.find('.pv-dimension').text(info.dimension);
		$pane.find('.pv-url').html('<input type="text" value="'+info.url+'" class="form-control" readonly="true">');

		$pane.find('.pv-wrapper').removeClass('hidden');
	}

	function resetInfoThumbnail()
	{
		var $pane = $(".preview-pane");
		if ($(".img-obj.selected").length <= 0) {
			$pane.find('.pv-wrapper').addClass('hidden');
		}
	}

	function toggleInsert()
	{
		if ($(".img-obj.selected").length > 0) {
			$(".btn-insert").removeAttr('disabled');
		} else {
			$(".btn-insert").attr('disabled', 'true');
		}

	}
</script>
