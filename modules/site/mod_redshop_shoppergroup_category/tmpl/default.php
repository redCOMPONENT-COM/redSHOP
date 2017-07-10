<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_shoppergroup_category
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

// Note. It is important to remove spaces between elements.
?>

<ul class="menu<?php echo $classSuffix; ?>"
	<?php $tag = ''; ?>
	<?php if ($params->get('tag_id') != null): ?>
		<?php $tag = $params->get('tag_id') . ''; ?>
		id="<?php echo $tag ?>"
	<?php endif ?>
>
	<?php foreach ($list as $i => &$item): ?>
		<?php $class = 'item-' . $item->id; ?>

		<?php if ($item->id == $activeId): ?>
			<?php $class .= ' current'; ?>
		<?php endif ?>

		<?php if ($item->type == 'alias' && in_array($item->params->get('aliasoptions'), $path) || in_array($item->id, $path)): ?>
			<?php $class .= ' active'; ?>
		<?php endif ?>

		<?php if ($item->deeper): ?>
			<?php $class .= ' deeper'; ?>
		<?php endif ?>

		<?php if ($item->parent): ?>
			<?php $class .= ' parent'; ?>
		<?php endif ?>

		<?php if (!empty($class)): ?>
			<?php $class = ' class="' . trim($class) . '"'; ?>
		<?php endif ?>

		<li<?php echo $class ?>>

		<!-- Render the menu item. -->
		<?php switch ($item->type): case 'separator': ?>
			<?php case 'url': ?>
			<?php case 'component': ?>
				<?php require JModuleHelper::getLayoutPath('mod_redshop_shoppergroup_category', 'default_' . $item->type); ?>
				<?php break; ?>
			<?php default: ?>
				<?php require JModuleHelper::getLayoutPath('mod_redshop_shoppergroup_category', 'default_url'); ?>
				<?php break; ?>
		<?php endswitch ?>

		<!-- The next item is deeper. -->
		<?php if ($item->deeper): ?>
			<ul>
		<!-- The next item is shallower. -->
		<?php elseif ($item->shallower): ?>
			</li>
			<?php echo str_repeat('</ul></li>', $item->level_diff); ?>

		<!-- The next item is on the same level.-->
		<?php else: ?>
			</li>
		<?php endif ?>

	<?php endforeach;?>
</ul>
