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
 * @since       2.0.0.2.1
 */

class RedshopTableCountry extends RedshopTable
{
	/**
	 * The table name without the prefix. Ex: cursos_courses
	 *
	 * @var  string
	 */

	protected $_tableName = 'redshop_country';

	/**
	 * Function display template
	 * 
	 * @return  boolean
	 * 
	 * @since   1.x
	 */

	public function check()
	{
		if (!parent::check())
		{
			return false;
		}

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->qn(['id', 'country_3_code']))
			->from($db->qn('#__redshop_country'))
			->where($db->qn('country_3_code') . ' = ' . $db->q($this->country_3_code) . ' AND ' . $db->qn('id') . ' != ' . $db->q($this->id));

		$db->setQuery($query);

		$xid = intval($db->loadResult());

		if ($xid)
		{
			$this->error = JText::_('COM_REDSHOP_COUNTRY_CODE_3_ALREADY_EXISTS');
			JError::raiseWarning('', $this->error);

			return false;
		}
		else
		{
			$query = $db->getQuery(true);
			$query->select($db->qn(['id', 'country_3_code', 'country_2_code']))
				->from($db->qn('#__redshop_country'))
				->where($db->qn('country_2_code') . ' = ' . $db->q($this->country_2_code) . ' AND ' . $db->qn('id') . ' != ' . $db->q($this->id));

			$db->setQuery($query);
			$xid = intval($db->loadResult());

			if ($xid)
			{
				$this->error = JText::_('COM_REDSHOP_COUNTRY_CODE_2_ALREADY_EXISTS');
				JError::raiseWarning('', $this->error);

				return false;
			}
		}

		return true;
	}
}
