<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHtml::_('redshopjquery.ui');
JHtml::script('com_redshop/jquery.iframe-transport.js', false, true);
JHtml::script('com_redshop/jquery.fileupload.js', false, true);
JHtml::script('com_redshop/require.js', false, true);
?>
<?php if (empty($this->imports)): ?>
	<div class="alert alert-warning">
		<span class="close" data-dismiss="alert">×</span>
		<h4 class="alert-heading">
			<i class="fa fa-exclamation-triangle"></i> <?php echo JText::_('WARNING') ?>
		</h4>
		<div>
			<p><?php echo JText::_('COM_REDSHOP_IMPORT_WARNING_MISSING_PLUGIN') ?></p>
		</div>
	</div>
<?php else: ?>
	<script type="text/javascript">;
		// Setup requirejs
		requirejs.config({
			// By default we load under ../media/com_redshop/js
			baseUrl: '../media/com_redshop/js',
			deps: [
				// General library
				'lib/log',
				// Administrator import
				'admin/import'
			]
		});

		var allowFileType = ["<?php echo implode('","', $this->allowFileTypes) ?>"];
		var allowFileExt = ["<?php echo implode('","', $this->allowFileExtensions) ?>"];
		var allowMaxFileSize = <?php echo $this->allowMaxFileSize ?>;
		var allowMinFileSize = <?php echo $this->allowMinFileSize ?>;
	</script>

	<form action="index.php?option=com_redshop&view=import" method="post" name="adminForm" id="adminForm">
		<div class="row">
			<div class="col-md-6">
				<!-- Step 1. Choose plugin -->
				<?php echo $this->loadTemplate('plugins'); ?>
				<!-- Step 1. End -->
			</div>
			<div class="col-md-6">
				<!-- Step 2. Config -->
				<?php echo $this->loadTemplate('configs'); ?>
				<!-- Step 2. End -->
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<!-- Step 3. Process -->
				<?php echo $this->loadTemplate('process'); ?>
				<!-- Step 3. End -->
			</div>
		</div>

		<!-- Hidden field -->
		<?php echo JHtml::_('form.token') ?>
	</form>
<?php endif; ?>
