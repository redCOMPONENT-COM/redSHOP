<?php
/**
 * @package     Redshop\Product\Utilities
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Redshop\Product\Utilities;


use Joomla\String\StringHelper;

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
	public static function renameToUniqueValue($fieldName, $fieldValue, $style = 'default', $tableName = '')
	{
		$table = \JTable::getInstance('Product_Detail', 'Table');

		while ($table->load(array($fieldName => $fieldValue)))
		{
			$fieldValue = StringHelper::increment($fieldValue, $style);
		}

		return $fieldValue;
	}
}