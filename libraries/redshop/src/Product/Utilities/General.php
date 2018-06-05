<?php
/**
 * @package     Redshop\Product\Utilities
 * @subpackage  General
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Product\Utilities;

use Joomla\String\StringHelper;

/**
 * Class General
 * @package Redshop\Product\Utilities
 *
 * @since   2.1.0
 */
class General
{
	/**
	 * Method to rename value to unique in current table.
	 *
	 * @param   string $fieldName  Field name
	 * @param   string $fieldValue Field value
	 * @param   string $style      The the style (default|dash)
	 * @param   string $tableName  Use table with name in value
	 *
	 * @return  string  Unique field value
	 *
	 * @since   1.5
	 */
	public static function renameToUniqueValue($fieldName, $fieldValue, $style = 'default', $tableName = 'Product_Detail')
	{
		$table = \JTable::getInstance($tableName, 'Table');

		while ($table->load(array($fieldName => $fieldValue)))
		{
			$fieldValue = StringHelper::increment($fieldValue, $style);
		}

		return $fieldValue;
	}
}
