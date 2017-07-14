<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Helper;

defined('_JEXEC') or die;

/**
 * Shopper group helper
 *
 * @since  __DEPLOY_VERSION__
 */
class ShopperGroup
{
	/**
	 * @var array
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected static $list = array();

	/**
	 * Method for get list shopper group for select
	 *
	 * @param   int  $shopperGroupId  ID of shopper group
	 *
	 * @return  array
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function generateList($shopperGroupId = 0)
	{
		if (!array_key_exists($shopperGroupId, self::$list))
		{
			$db    = \JFactory::getDbo();
			$query = $db->getQuery(true)
				->select(array('sh.*', $db->qn('sh.shopper_group_id', 'value'), $db->qn('sh.shopper_group_name', 'text')))
				->from($db->qn('#__redshop_shopper_group', 'sh'))
				->where('sh.published = 1');

			if ($shopperGroupId)
			{
				$query->where('sh.shopper_group_id = ' . (int) $shopperGroupId);
			}

			$db->setQuery($query);

			self::$list[$shopperGroupId] = $db->loadObjectList();
		}

		return self::$list[$shopperGroupId];
	}
}