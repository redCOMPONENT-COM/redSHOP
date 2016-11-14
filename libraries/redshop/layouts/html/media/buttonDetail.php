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
$url = JRoute::_(JURI::base() . 'index.php?tmpl=component&option=com_redshop&amp;view=media_detail&amp;cid[]=' . $mediaId . '&amp;section_id=' . $sectionId . '&amp;showbuttons=1&amp;media_section=' . $mediaSection . '&amp;section_name=' . $sectionName);

JFactory::getDocument()->addScriptDeclaration('
(function($){
	jQuery(document).ready(function(){
		jQuery("#btnWrapperDetail_' . $sectionId . '").on("click", function(){
			html = \'<iframe src="' . $url . '" frameborder="0" width="' . $width . '" height="' . $height . '"></iframe>\';

			$("#wrapperModalDetail_' . $sectionId . '").html(html);
			$("#wrapperModalDetail_' . $sectionId . '").modal("show");
		});
	});
})(jQuery);');
?>
<a style="cursor: pointer" id="btnWrapperDetail_<?php echo $sectionId ?>"><img
	src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH; ?>media16.png" align="absmiddle"
	alt="media"></a>
<div class="<?php echo $class ?> fade shadow" 
	id="wrapperModalDetail_<?php echo $sectionId?>"
	style="overflow-y: hidden; width: <?php echo $width ?>px; padding: 5px;">
</div>

