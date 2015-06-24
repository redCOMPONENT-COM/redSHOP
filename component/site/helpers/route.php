<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_contact
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Contact Component Route Helper
 *
 * @static
 * @package     Joomla.Site
 * @subpackage  com_contact
 * @since       1.5
 */
abstract class RedshopHelperRoute
{
	protected static $format;

	protected static $lookup;

	protected static $type;

	/**
	 * Get the URL route for a product from a product ID, product category ID and language
	 *
	 * @param   integer  $id              The id of the product
	 * @param   integer  $catid           The id of the product's category
	 * @param   integer  $manufacturerId  The id of the product's manufacturer
	 *
	 * @return  string  The link to the product
	 *
	 * @since   1.5
	 */
	public static function getProductRoute($id, $catid, $manufacturerId)
	{
		$needles = array(
			'product'  => array((int) $id)
		);

		// Create the link
		$link = 'index.php?option=com_redshop&view=product&pid=' . $id;

		if ((int) $catid > 0)
		{
			$link .= '&cid=' . $catid;
		}

		// Find the menu item for the search
		$app = JFactory::getApplication();
		$menu  = $app->getMenu();
		$items = $menu->getItems('link', 'index.php?option=com_redshop&view=category&layout=detail&cid=' . $catid . '&manufacturer_id=' . $manufacturerId);

		if (isset($items[0]))
		{
			$link .= '&Itemid=' . $items[0]->id;
		}

		return $link;
	}

	/**
	 * Find an item ID.
	 *
	 * @param   array  $needles  An array of language codes.
	 *
	 * @return  mixed  The ID found or null otherwise.
	 *
	 * @since   1.5
	 */
	protected static function _findItem($needles = null)
	{
		$app = JFactory::getApplication();
		$menus = $app->getMenu('site');

		// Prepare the reverse lookup array.
		if (static::$lookup === null)
		{
			static::$lookup = array();
			$component = JComponentHelper::getComponent('com_redshop');
			$items = $menus->getItems('component_id', $component->id);

			foreach ($items as $item)
			{
				if (isset($item->query) && isset($item->query['view']))
				{
					$view = $item->query['view'];

					if (!isset(static::$lookup[$view]))
					{
						static::$lookup[$view] = array();
					}

					// Some trickery to get the right link for products
					if (isset(static::$type) && static::$type == 'product')
					{
						if (isset($item->query['pid']))
						{
							static::$lookup[$view][$item->query['pid']] = $item->id;
						}
						elseif (isset($item->query['cid']))
						{
							static::$lookup[$view][$item->query['cid']] = $item->id;
						}
					}
				}
			}
		}

		if ($needles)
		{
			foreach ($needles as $view => $ids)
			{
				if (isset(static::$lookup[$view]))
				{
					foreach ($ids as $id)
					{
						if (isset(static::$lookup[$view][(int) $id]))
						{
							return static::$lookup[$view][(int) $id];
						}
					}
				}
			}
		}
		else
		{
			$active = $menus->getActive();

			if ($active && $active->component == 'com_redshop')
			{
				return $active->id;
			}
		}

		return null;
	}
}
