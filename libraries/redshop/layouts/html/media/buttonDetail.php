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
<a class="<?php echo $class ?>"
	href="index.php?tmpl=component&option=com_redshop&amp;view=media_detail&amp;cid[]=<?php echo $mediaId; ?>&amp;section_id=<?php echo $sectionId; ?>&amp;showbuttons=1&amp;media_section=<?php echo $mediaSection ?>&amp;section_name=<?php echo $sectionName; ?>"
	rel="{handler: '<?php echo $handler ?>', size: {x: <?php echo $width ?>, y: <?php echo $height ?>}}" title=""><img
		src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH; ?>media16.png" align="absmiddle"
		alt="media"></a>
