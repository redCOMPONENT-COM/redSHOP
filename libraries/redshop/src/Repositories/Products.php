<?php
/**
 * @package     Redshop\Repositories
 * @subpackage  Products
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

namespace Redshop\Repositories;

/**
 * @package     Redshop\Repositories
 *
 * @since       2.1.0
 */
class Products
{
	/**
	 * @var   string
	 * @since 2.1.0
	 */
	protected static $table = '#__redshop_product';

	/**
	 * @param   array $conditions Conditions
	 *
	 * @return  boolean
	 * @since   2.1.0
	 */
	public static function delete($conditions)
	{
		$db    = \JFactory::getDbo();
		$query = $db->getQuery(true);

		foreach ($conditions as $key => $value)
		{
			$query->where($db->quoteName($key) . ' = ' . (int) $value);
		}

		return $db->setQuery($query->delete(self::$table))->execute();
	}
}
