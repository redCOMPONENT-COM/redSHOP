<?php
/**
 * @package     Aesir.iframe.php
 *
 * @copyright   Copyright (C) 2016 - 2022 Aesir. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 * @since       __DEPLOY_VERSION__
 */

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\Utilities\ArrayHelper;

extract($displayData);

if (empty($modalButton))
{
	$modalButton = '.RedshopModalButton';
}

if (empty($modalFrame))
{
	$modalFrame = 'RedshopModalFrame';
}

if (empty($params))
{
	$params = [
		'height' => '400px',
		'width'  => '800px',
	];
}

$iframeAttributes = [];

if (isset($params['height']))
{
	$iframeAttributes['height'] = $params['height'];
}

if (isset($params['width']))
{
	$iframeAttributes['width'] = $params['width'];
}

?>
<script>
	(function($){
		$(document).ready(function () {
			$('<?php echo $modalButton ?>').on('click', function () {
				var modal = $('#<?php echo $modalFrame ?>');
				var frame = modal.find('iframe');
				if (frame.length) {
					frame.remove();
				}
				modal.find('.modal-body').append('<iframe <?php echo ArrayHelper::toString($iframeAttributes); ?> src="'+$(this).data('url')+'" />')
				modal.modal('show');
			});
		});
	})(jQuery);
</script>
<?php

echo HTMLHelper::_(
	'bootstrap.renderModal', $modalFrame, $params
);
