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
