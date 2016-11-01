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
?>
<a class="<?php echo $class ?>"
	href="index.php?option=com_redshop&showall=1&view=wrapper&product_id=<?php echo $productId; ?>&tmpl=component"
	rel="{handler: '<?php echo $handler ?>', size: {x: <?php echo $width ?>, y: <?php echo $height ?>}}">
	<img src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH; ?>wrapper16.png" align="absmiddle"
	alt="<?php echo JText::_('COM_REDSHOP_WRAPPER'); ?>"> <?php echo "(" . $count . ")";?></a>
