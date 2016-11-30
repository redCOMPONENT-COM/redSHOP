<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
?>
<style type="text/css">
	.m-s-cls{width: 100%; max-width: 100%; height: 300px; background: #69584C; color: #fff; font-family: monospace; padding: 5px;}
	.m-s-cls .flicker{animation: blinker 1s linear infinite;}
	@keyframes blinker {
		50% { opacity: 0; }
	}
</style>
<h3>Sanitise Media Files</h3>
<div style="margin-bottom: 20px;">
	<a href="<?php echo JRoute::_('/administrator/index.php?option=com_redshop&view=media') ?>">< Back to Media Management</a>
</div>
<div>
	<button class="btn btn-primary btn-start-sanitise">Start Rename</button>
</div>
<div style="margin-top: 20px;">
	<div class="m-s-cls">><span class="flicker">_</span> <span class="ct"></span></div>
</div>
<script>
	$('.btn-start-sanitise').on('click', function(e){
		e.preventDefault();
		$('.flicker').hide();
		$('.m-s-cls .ct').html('Begin analyzing medias<span class="flicker">...</span>');

		$.ajax({
			url: '/administrator/index.php?option=com_redshop&view=media&task=ajaxProcessRenameMedia'
		}).done(function () {
			$('.m-s-cls .ct').append('<br> DONE!');
		});
	})
</script>
