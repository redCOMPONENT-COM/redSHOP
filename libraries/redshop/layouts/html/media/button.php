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

$url = JRoute::_(JURI::base() . 'index.php?option=com_redshop&view=media&section_id=' . $sectionId . '&showbuttons=1&media_section=' . $mediaSection . '&section_name=' . $sectionName . '&tmpl=component');

JFactory::getDocument()->addScriptDeclaration('
(function($){
	jQuery(document).ready(function(){
		jQuery("#btnModal_' . $sectionId . '").on("click", function(){
			html = \'<iframe src="' . $url . '" frameborder="0" width="' . $width . '" height="' . $height . '"></iframe>\';

			$("#mediaButton_' . $sectionId . '").html(html);
			$("#mediaButton_' . $sectionId . '").modal("show");
		});
	});
})(jQuery);
');
?>
<a style="cursor: pointer" id="btnModal_<?php echo $sectionId ?>"><img
	src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH; ?>media16.png" align="absmiddle"
	alt="media"> (<?php  echo $count;?>)</a>

<div class="<?php echo $class ?> fade shadow" 
	id="mediaButton_<?php echo $sectionId?>"
	style="overflow-y: hidden; width: <?php echo $width ?>px; padding: 5px;">
</div>


