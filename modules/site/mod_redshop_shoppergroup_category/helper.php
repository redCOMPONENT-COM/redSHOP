<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_shoppergroup_category
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * @package        Joomla.Site
 * @subpackage     mod_menu
 * @since          1.5
 */
class redmodMenuHelper
{
	/**
	 * Get a list of the menu items.
	 *
	 * @param    JRegistry $params    The module options.
	 *
	 * @return    array
	 * @since    1.5
	 */
	static function getList(&$params)
	{
		$user   = JFactory::getUser();
		$levels = $user->getAuthorisedViewLevels();
		asort($levels);
		$key   = 'menu_items' . $params . implode(',', $levels);
		$cache = JFactory::getCache('mod_menu', '');
		if (!($items = $cache->get($key)))
		{
			// Initialise variables.
			$list = array();
			$db   = JFactory::getDbo();
			$user = JFactory::getUser();
			$app  = JFactory::getApplication();
			$menu = $app->getMenu();

			// If no active menu, use default
			$active = ($menu->getActive()) ? $menu->getActive() : $menu->getDefault();

			$path     = $active->tree;
			$start    = (int) $params->get('startLevel');
			$end      = (int) $params->get('endLevel');
			$showAll  = $params->get('showAllChildren');
			$maxdepth = $params->get('maxdepth');
			$items    = $menu->getItems('menutype', $params->get('menutype'));


			$lastitem = 0;

			if ($items)
			{
				foreach ($items as $i => $item)
				{
					$link       = parse_url($item->link);
					$view       = '';
					$categoryid = 0;
					if (isset($link['query']))
					{
						parse_str($link['query'], $output);
						if (isset($output['view']))
							$view = $output['view'];
						if (isset($output['cid']))
							$categoryid = $output['cid'];
					}

					if ($view == 'category' && $categoryid > 0)
					{
						$shoppercat = redmodMenuHelper::getShopperGroupCategory($categoryid);
						if ($shoppercat <= 0)
						{
							unset($items[$i]);
							continue;
						}

						if (($start && $start > $item->level)
							|| ($end && $item->level > $end)
							|| (!$showAll && $item->level > 1 && !in_array($item->parent_id, $path))
							|| ($maxdepth && $item->level > $maxdepth)
							|| ($start > 1 && !in_array($item->tree[$start - 2], $path))
						)
						{
							unset($items[$i]);
							continue;
						}

						$item->deeper     = false;
						$item->shallower  = false;
						$item->level_diff = 0;

						if (isset($items[$lastitem]))
						{
							$items[$lastitem]->deeper     = ($item->level > $items[$lastitem]->level);
							$items[$lastitem]->shallower  = ($item->level < $items[$lastitem]->level);
							$items[$lastitem]->level_diff = ($items[$lastitem]->level - $item->level);
						}

						$item->parent = (boolean) $menu->getItems('parent_id', (int) $item->id, true);

						$lastitem     = $i;
						$item->active = false;
						$item->flink  = $item->link;

						switch ($item->type)
						{
							case 'separator':
								// No further action needed.
								continue;

							case 'url':
								if ((strpos($item->link, 'index.php?') === 0) && (strpos($item->link, 'Itemid=') === false))
								{
									// If this is an internal Joomla link, ensure the Itemid is set.
									$item->flink = $item->link . '&Itemid=' . $item->id;
								}
								break;

							case 'alias':
								// If this is an alias use the item id stored in the parameters to make the link.
								$item->flink = 'index.php?Itemid=' . $item->params->get('aliasoptions');
								break;

							default:
								$router = JSite::getRouter();
								if ($router->getMode() == JROUTER_MODE_SEF)
								{
									$item->flink = 'index.php?Itemid=' . $item->id;
								}
								else
								{
									$item->flink .= '&Itemid=' . $item->id;
								}
								break;
						}

						if (strcasecmp(substr($item->flink, 0, 4), 'http') && (strpos($item->flink, 'index.php?') !== false))
						{
							$item->flink = JRoute::_($item->flink, true, $item->params->get('secure'));
						}
						else
						{
							$item->flink = JRoute::_($item->flink);
						}

						$item->title        = htmlspecialchars($item->title);
						$item->anchor_css   = htmlspecialchars($item->params->get('menu-anchor_css', ''));
						$item->anchor_title = htmlspecialchars($item->params->get('menu-anchor_title', ''));
						$item->menu_image   = $item->params->get('menu_image', '') ? htmlspecialchars($item->params->get('menu_image', '')) : '';
					}
					else
					{
						if (($start && $start > $item->level)
							|| ($end && $item->level > $end)
							|| (!$showAll && $item->level > 1 && !in_array($item->parent_id, $path))
							|| ($maxdepth && $item->level > $maxdepth)
							|| ($start > 1 && !in_array($item->tree[$start - 2], $path))
						)
						{
							unset($items[$i]);
							continue;
						}

						$item->deeper     = false;
						$item->shallower  = false;
						$item->level_diff = 0;

						if (isset($items[$lastitem]))
						{
							$items[$lastitem]->deeper     = ($item->level > $items[$lastitem]->level);
							$items[$lastitem]->shallower  = ($item->level < $items[$lastitem]->level);
							$items[$lastitem]->level_diff = ($items[$lastitem]->level - $item->level);
						}

						$item->parent = (boolean) $menu->getItems('parent_id', (int) $item->id, true);

						$lastitem     = $i;
						$item->active = false;
						$item->flink  = $item->link;


						switch ($item->type)
						{
							case 'separator':
								// No further action needed.
								continue;

							case 'url':
								if ((strpos($item->link, 'index.php?') === 0) && (strpos($item->link, 'Itemid=') === false))
								{
									// If this is an internal Joomla link, ensure the Itemid is set.
									$item->flink = $item->link . '&Itemid=' . $item->id;
								}
								break;

							case 'alias':
								// If this is an alias use the item id stored in the parameters to make the link.
								$item->flink = 'index.php?Itemid=' . $item->params->get('aliasoptions');
								break;

							default:
								$router = JSite::getRouter();
								if ($router->getMode() == JROUTER_MODE_SEF)
								{
									$item->flink = 'index.php?Itemid=' . $item->id;
								}
								else
								{
									$item->flink .= '&Itemid=' . $item->id;
								}
								break;
						}

						if (strcasecmp(substr($item->flink, 0, 4), 'http') && (strpos($item->flink, 'index.php?') !== false))
						{
							$item->flink = JRoute::_($item->flink, true, $item->params->get('secure'));
						}
						else
						{
							$item->flink = JRoute::_($item->flink);
						}

						$item->title        = htmlspecialchars($item->title);
						$item->anchor_css   = htmlspecialchars($item->params->get('menu-anchor_css', ''));
						$item->anchor_title = htmlspecialchars($item->params->get('menu-anchor_title', ''));
						$item->menu_image   = $item->params->get('menu_image', '') ? htmlspecialchars($item->params->get('menu_image', '')) : '';
					}
				}

				if (isset($items[$lastitem]))
				{
					$items[$lastitem]->deeper     = (($start ? $start : 1) > $items[$lastitem]->level);
					$items[$lastitem]->shallower  = (($start ? $start : 1) < $items[$lastitem]->level);
					$items[$lastitem]->level_diff = ($items[$lastitem]->level - ($start ? $start : 1));
				}
			}

			$cache->store($items, $key);
		}

		return $items;
	}

	function getShopperGroupCategory($cid = 0)
	{

		$db   = JFactory::getDbo();
		$user = JFactory::getUser();

		if ($user->id > 0)
			$query = "SELECT count(*) as total,sg.* FROM `#__redshop_shopper_group` as sg LEFT JOIN #__redshop_users_info as uf ON sg.`shopper_group_id` = uf.shopper_group_id WHERE uf.user_id = " . $user->id . " AND FIND_IN_SET(" . $cid . ",sg.shopper_group_categories) GROUP BY sg.shopper_group_id ";
		else
			$query = "SELECT count(*) as total,sg.* FROM `#__redshop_shopper_group` as sg WHERE  sg.`shopper_group_id` = " . SHOPPER_GROUP_DEFAULT_UNREGISTERED . " AND FIND_IN_SET(" . $cid . ",sg.shopper_group_categories) GROUP BY sg.shopper_group_id";

		$db->setQuery($query);
		$shoppercatdata = $db->loadObject();
		$total          = 0;
		if (count($shoppercatdata) > 0)
			$total = $shoppercatdata->total;

		return $total;
	}
}

