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
 * @since       2.0.0.6
 */
class RedshopTableCurrency extends RedshopTable
{
	/**
	 * The table name without the prefix. Ex: cursos_courses
	 *
	 * @var  string
	 */

	protected $_tableName = 'redshop_currency';

	/**
	 * Function check data
	 * 
	 * @return  boolean
	 * 
	 * @since   1.x
	 */

	public function check()
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true);
		$query->select($db->qn(['id', 'currency_code']))
			->from($db->qn('#__redshop_currency'))
			->where(
					$db->qn('currency_code') . ' = ' . $db->q($this->currency_code)
					. ' AND ' . $db->qn('id') . ' != ' . $db->q($this->id)
				);

		$db->setQuery($query);

		$xid = intval($db->loadResult());

		if ($xid)
		{
			$this->_error = JText::_('COM_REDSHOP_CURRENCY_CODE_ALREADY_EXISTS');
			JError::raiseWarning('', $this->_error);

			return false;
		}

		return true;
	}
}
