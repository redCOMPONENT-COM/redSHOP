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

<ul class="menu<?php echo $class_sfx; ?>"<?php
	$tag = '';
	if ($params->get('tag_id') != null)
	{
		$tag = $params->get('tag_id') . '';
		echo ' id="' . $tag . '"';
	}
	?>>
	<?php
	foreach ($list as $i => &$item) :
		$class = 'item-' . $item->id;
		if ($item->id == $active_id)
		{
			$class .= ' current';
		}

		if ($item->type == 'alias' &&
			in_array($item->params->get('aliasoptions'), $path)
			|| in_array($item->id, $path)
		)
		{
			$class .= ' active';
		}

		if ($item->deeper)
		{
			$class .= ' deeper';
		}

		if ($item->parent)
		{
			$class .= ' parent';
		}

		if (!empty($class))
		{
			$class = ' class="' . trim($class) . '"';
		}

		echo '<li' . $class . '>';

		// Render the menu item.
		switch ($item->type) :
			case 'separator':
			case 'url':
			case 'component':
				require JModuleHelper::getLayoutPath('mod_redshop_shoppergroup_category', 'default_' . $item->type);
				break;

			default:
				require JModuleHelper::getLayoutPath('mod_redshop_shoppergroup_category', 'default_url');
				break;
		endswitch;

		// The next item is deeper.
		if ($item->deeper)
		{
			echo '<ul>';
		}
		// The next item is shallower.
		elseif ($item->shallower)
		{
			echo '</li>';
			echo str_repeat('</ul></li>', $item->level_diff);
		}
		// The next item is on the same level.
		else
		{
			echo '</li>';
		}
	endforeach;
	?></ul>
