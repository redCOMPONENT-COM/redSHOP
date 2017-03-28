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
 */
extract($displayData);
?>

<div class="rs-media-gallery-modal modal fade in" tabindex="-1" role="dialog" data-backdrop="static">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h3 class="modal-title"> <i class="fa fa-picture-o"></i> <?php echo JText::_('COM_REDSHOP_MEDIA_SELECT_FROM_MEDIA') ?></h3>
			</div>
			<div class="modal-body">
				<div class="col-md-8 thumbnail-pane">
					<div class="row list-pane">
						<?php if (!empty($gallery)): ?>
							<?php foreach($gallery as $thumb): ?>
								<?php if ($thumb['mime'] != 'image'): ?>
									<?php continue; ?>
								<?php endif; ?>
								<div class="col-md-3">
									<div class="thumbnail img-obj">
										<img src="<?php echo $thumb['url'] ?>" alt="<?php echo $thumb['name'] ?>" class="img-type"
											 data-id="<?php echo $thumb['id'] ?>"
											 data-size="<?php echo $thumb['size'] ?>"
											 data-dimension="<?php echo $thumb['dimension'] ?>"
											 data-media="<?php echo $thumb['media'] ?>"
											 data-attached="<?php echo $thumb['attached'] ?>">
										<span class="img-name"><?php echo $thumb['name'] ?></span>
									</div>
								</div>
							<?php endforeach; ?>
						<?php endif; ?>
					</div>
				</div>
				<div class="col-md-4 preview-pane">
				<div class="pv-wrapper hidden">
					<div class="pv-title"><h4><?php echo JText::_('COM_REDSHOP_MEDIA_ATTACHMENT_DETAIL') ?></h4></div>
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
							<li class="pv-remove">
								<a href="#" class="btn btn-danger btn-del-g" data-id="">
									<i class="fa fa-times"></i> <?php echo JText::_('JTOOLBAR_DELETE') ?></a>
							</li>
						</ul>
					</div>
				</div>
			</div>
			</div>
			<div class="modal-footer btn-toolbar text-center">
				<button type="button" class="btn btn-small btn-success pull-right rs-media-gallery-insert-btn" disabled="true">
					<i class="fa fa-anchor"></i> Insert to <?php echo $type ?>
				</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Cropper Modal -->

<!-- Gallery Item Template -->
<div class="rs-media-gallery-preview hidden">
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
<div class="rs-media-gallery-delete-modal modal fade in" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><i class="fa fa-warning text-yellow"></i> Warning</h4>
			</div>
			<div class="modal-body">
				<div class="alert-text text-center">
					<p><?php echo JText::_('COM_REDSHOP_MEDIA_ASK_DELETE_PERMANENTLY_FILE') ?></p>
				</div>
			</div>
			<div class="modal-footer btn-toolbar text-center">
				<button type="button" class="btn btn-small btn-success float-none btn-confirm-del-g" data-url="" data-id="">
					<?php echo JText::_('JYES') ?>
				</button>
				<button type="button" class="btn btn-small btn-danger float-none" data-dismiss="modal"><?php echo JText::_('JNO') ?></button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Alert Modal -->
