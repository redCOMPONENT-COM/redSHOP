<?php
/**
 * @package     Redshop\Repositories
 * @subpackage  Table
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
class Table
{
	/**
	 * @param   string $table Table
	 *
	 * @return  mixed
	 *
	 * @since   2.1.0
	 */
	public static function getFields($table)
	{
		$db = \JFactory::getDbo();

		return $db->setQuery('SHOW COLUMNS FROM ' . $db->quoteName($table))->loadObjectList();
	}
}
