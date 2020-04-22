<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Table Tax group
 *
 * @package     RedSHOP.Backend
 * @subpackage  Table
 * @since       2.0.4
 */
class RedshopTableTax_Group extends RedshopTable
{
	/**
	 * The table name without the prefix. Ex: cursos_courses
	 *
	 * @var  string
	 */
	protected $_tableName = 'redshop_tax_group';

	/**
	 * Delete one or more registers
	 *
	 * @param   string/array  $pk  Array of ids or ids comma separated
	 *
	 * @return  boolean            Deleted successful?
	 */
	protected function doDelete($pk = null)
	{
		// Check tax rates
		if (RedshopEntityTax_Group::getInstance($this->id)->getTaxRates()->count() > 0)
		{
			$this->setError(JText::sprintf('COM_REDSHOP_TAX_GROUP_ERROR_DELETE_HAVE_TAX_RATE', $this->name));

			return false;
		}

		return parent::doDelete($pk);
	}

	/**
	 * Checks that the object is valid and able to be stored.
	 *
	 * This method checks that the parent_id is non-zero and exists in the database.
	 * Note that the root node (parent_id = 0) cannot be manipulated with this class.
	 *
	 * @return  boolean  True if all checks pass.
	 */
	protected function doCheck()
	{
		if (empty($this->name))
		{
			return false;
		}

		return parent::doCheck();
	}
}
