<?php
/**
 * @package     Redshop.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('JPATH_BASE') or die;

extract($displayData);
$url = JRoute::_(JURI::base() . 'index.php?option=com_redshop&showall=1&view=wrapper&product_id=' . $productId . '&tmpl=component');

JFactory::getDocument()->addScriptDeclaration('
(function($){
	jQuery(document).ready(function(){
		jQuery("#btnWrapper_' . $productId . '").on("click", function(){
			html = \'<iframe src="' . $url . '" frameborder="0" width="' . $width . '" height="' . $height . '"></iframe>\';

			$("#wrapperModal_' . $productId . '").html(html);
			$("#wrapperModal_' . $productId . '").modal("show");
		});
	});
})(jQuery);
');
?>
<a style="cursor: pointer" id="btnWrapper_<?php echo $productId ?>"><img
	src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH; ?>media16.png" align="absmiddle"
	alt="media"> (<?php  echo $count;?>)</a>

<div class="<?php echo $class ?> fade shadow" 
	id="wrapperModal_<?php echo $productId?>"
	style="overflow-y: hidden; width: <?php echo $width ?>px; padding: 5px;">
</div>

