<?php
/**
 * @package     Redshop.Library
 * @subpackage  Base
 *
 * @copyright   Copyright (C) 2012 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.model');

/**
 * Redshop Model
 *
 * @package     Redshop.library
 * @subpackage  Model
 * @since       1.4
 */
class RedshopModel extends JModelLegacy
{
	/**
	 * Method to rename value to unique in current table.
	 *
	 * @param   string  $fieldName   Field name
	 * @param   string  $fieldValue  Field value
	 * @param   string  $style       The the style (default|dash)
	 * @param   string  $tableName   Use table with name in value
	 *
	 * @return  string  Unique field value
	 *
	 * @since   1.5
	 */
	protected function renameToUniqueValue($fieldName, $fieldValue, $style = 'default', $tableName = '')
	{
		$table = $this->getTable($tableName);

		while ($table->load(array($fieldName => $fieldValue)))
		{
			$fieldValue = JString::increment($fieldValue, $style);
		}

		return $fieldValue;
	}
}
