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
	href="index.php?option=com_redshop&view=media&section_id=<?php echo $sectionId ?>&showbuttons=1&media_section=<?php echo $mediaSection ?>&section_name=<?php echo $sectionName; ?>&tmpl=component"
	rel="{handler: '<?php echo $handler; ?>', size: {x: <?php echo $width ?>, y: <?php echo $height ?>}}" title=""> <img
		src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH; ?>media16.png" align="absmiddle"
		alt="media"> (<?php  echo $count;?>)</a>
