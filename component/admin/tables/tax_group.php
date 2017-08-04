<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
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
}
