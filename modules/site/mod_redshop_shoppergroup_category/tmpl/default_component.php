<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_shoppergroup_category
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
?>

<!-- Note. It is important to remove spaces between elements. -->
<?php $class = $item->anchor_css ? 'class="' . $item->anchor_css . '" ' : ''; ?>
<?php $title = $item->anchor_title ? 'title="' . $item->anchor_title . '" ' : ''; ?>

<?php if ($item->menu_image): ?>
	<?php $item->params->get('menu_text', 1) ?
		$linktype = '<img src="' . $item->menu_image . '" alt="' . $item->title . '" /><span class="image-title">' . $item->title . '</span> ' :
		$linktype = '<img src="' . $item->menu_image . '" alt="' . $item->title . '" />'; ?>
<?php else: ?>
	<?php $linktype = $item->title; ?>
<?php endif ?>

<?php switch ($item->browserNav): default: ?>
	<?php case 0: ?>
		<a <?php echo $class; ?> href="<?php echo $item->flink; ?>" <?php echo $title; ?>><?php echo $linktype; ?></a>
		<?php break; ?>
	<?php case 1: ?>
		<!-- _blank -->
		<a <?php echo $class; ?> href="<?php echo $item->flink; ?>" target="_blank" <?php echo $title; ?>><?php echo $linktype; ?></a>
		<?php break; ?>
	<?php case 2: ?>
		<!-- window.open -->
		<a <?php echo $class; ?> href="<?php echo $item->flink; ?>"
     		onclick="window.open(this.href,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes');return false;" <?php echo $title; ?>><?php echo $linktype; ?></a>
		<?php break; ?>
<?php endswitch; ?>
