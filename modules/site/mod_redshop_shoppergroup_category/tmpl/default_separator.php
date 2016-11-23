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
<?php $title = $item->anchor_title ? 'title="' . $item->anchor_title . '" ' : ''; ?>

<?php if ($item->menu_image): ?>
	<?php $item->params->get('menu_text', 1) ?
		$linktype = '<img src="' . $item->menu_image . '" alt="' . $item->title . '" /><span class="image-title">' . $item->title . '</span> ' :
		$linktype = '<img src="' . $item->menu_image . '" alt="' . $item->title . '" />'; ?>
<?php else: ?>
	<?php $linktype = $item->title; ?>
<?php endif ?>

<span class="separator"><?php echo $title ?>><?php echo $linktype; ?></span>
