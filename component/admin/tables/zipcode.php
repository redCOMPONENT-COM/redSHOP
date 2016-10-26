<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Table Country
 *
 * @package     RedSHOP.Backend
 * @subpackage  Table
 * @since       2.0.0.8
 */
class RedshopTableZipcode extends RedshopTable
{
	/**
	 * The table name without the prefix. Ex: cursos_courses
	 *
	 * @var  string
	 */

	protected $_tableName = 'redshop_zipcode';

	protected $zipcode;

	protected $zipcodeto;

	protected $state_code;

	protected $country_name;

	protected $country_code;

	/**
	 * Method to store a node in the database table.
	 *
	 * @param   boolean  $updateNulls  True to update null values as well.
	 *
	 * @return  boolean  True on success.
	 */
	public function store($updateNulls = false)
	{
		// Before store
		if (!$this->beforeStore($updateNulls))
		{
			return false;
		}

		if ($this->zipcodeto && ($this->zipcode > $this->zipcodeto))
		{
			return false;
		}

		$db = JFactory::getDbo();

		if (!$this->zipcodeto)
		{
			$this->zipcodeto = $this->zipcode;
		}

		$value = [];
		$columns = $db->qn(['country_code', 'state_code', 'city_name', 'zipcode']);

		for ($i = $this->zipcode; $i <= $this->zipcodeto; $i++)
		{
			$value = $db->q([$this->country_code, $this->state_code, $this->city_name, $i]);
			$query = $db->getQuery(true);

			$query->insert($db->qn('#__redshop_zipcode'))
				->columns($columns)
				->values(implode(',', $value));

			$db->setQuery($query);
			$db->execute();
		}

		// After store
		if (!$this->afterStore($updateNulls))
		{
			return false;
		}

		return true;
	}

	/**
	 * Function display template
	 *
	 * @return  boolean
	 *
	 * @since   2.0.0.8
	 */
	public function check()
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true);
		$query->select(
			$db->qn(
				[
					'id',
					'country_code',
					'state_code',
					'city_name',
					'zipcode',
					'zipcodeto'
				]
			)
		)->from($db->qn('#__redshop_zipcode'))
		->where(
				$db->qn('zipcode') . ' = ' . $db->q($this->zipcode)
				. ' AND' . $db->qn('id') . ' != ' . $db->q($this->id)
				. ' AND' . $db->qn('country_code') . ' = ' . $db->q($this->country_code)
			);

		$db->setQuery($query);

		$xid = intval($db->loadResult());

		if ($xid)
		{
			$this->_error = JText::_('COM_REDSHOP_ZIPCODE_ALREADY_EXISTS') . ": " . $this->zipcode;
			JError::raiseWarning('', $this->_error);

			return false;
		}

		return true;
	}
}
