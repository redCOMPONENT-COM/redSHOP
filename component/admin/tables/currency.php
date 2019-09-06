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
 * Table Currency
 *
 * @package     RedSHOP.Backend
 * @subpackage  Table
 * @since       2.0.11
 */
class RedshopTableCurrency extends RedshopTable
{
	/**
	 * @var  string
	 */
	protected $_tableName = 'redshop_currency';

	/**
	 * @var  integer
	 */
	public $id;

	/**
	 * @var  string
	 */
	public $name;

	/**
	 * @var  string
	 */
	public $code;

	/**
	 * @var  integer
	 */
	public $created_by;

	/**
	 * @var  string
	 */
	public $created_date = '0000-00-00 00:00:00';

	/**
	 * @var  integer
	 */
	public $checked_out;

	/**
	 * @var  string
	 */
	public $checked_out_time = '0000-00-00 00:00:00';

	/**
	 * @var  integer
	 */
	public $modified_by;

	/**
	 * @var  string
	 */
	public $modified_date = '0000-00-00 00:00:00';

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
		if (!parent::doCheck())
		{
			return false;
		}

		if (empty($this->name))
		{
			return false;
		}

		if (empty($this->code))
		{
			return false;
		}

		$db = $this->getDbo();

		// Check duplicate.
		$code = $this->get('code');

		$codeQuery = $db->getQuery(true)
			->select($db->qn('code'))
			->from($db->qn('#__' . $this->_tableName));

		if ($this->hasPrimaryKey())
		{
			$codeQuery->where($db->qn('id') . ' <> ' . $this->id);
		}

		$query = $db->getQuery(true)
			->select('COUNT(*)')
			->from('(' . $codeQuery . ') AS ' . $db->qn('data'))
			->where($db->qn('data.code') . ' = ' . $db->q($code));

		if ($db->setQuery($query)->loadResult())
		{
			/** @scrutinizer ignore-deprecated */ $this->setError(JText::_('COM_REDSHOP_CURRENCY_CODE_ALREADY_EXISTS'));

			return false;
		}

		return true;
	}

	/**
	 * Delete one or more registers
	 *
	 * @param   mixed  $pk  Array of ids or ids comma separated
	 *
	 * @return  boolean  Deleted successfully?
	 */
	protected function doDelete($pk = null)
	{
		if (Redshop::getConfig()->get('CURRENCY_CODE') == $this->code)
		{
			/** @scrutinizer ignore-deprecated */ $this->setError(JText::_('COM_REDSHOP_CURRENCY_ERROR_DELETE_CURRENCY_SET_IN_CONFIG'));

			return false;
		}

		return parent::doDelete();
	}
}
