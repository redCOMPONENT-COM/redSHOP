<?php
/**
 * @package     Redshop.Library
 * @subpackage  Base
 *
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Redshopb list Model
 *
 * @package     Redshopb
 * @subpackage  List
 * @since       1.0
 */
class RedshopModelList extends JModelList
{
	/**
	 * Gets an array of objects from the results of database query.
	 *
	 * @param   string   $query       The query.
	 * @param   integer  $limitstart  Offset.
	 * @param   integer  $limit       The number of records.
	 *
	 * @return  array  An array of results.
	 *
	 * @since   12.2
	 * @throws  RuntimeException
	 */
	protected function _getList($query, $limitstart = 0, $limit = 0)
	{
		// Disable limit for CSV export
		if ($this->getState('streamOutput', '') == 'csv')
		{
			$this->_db->setQuery($query);
		}
		else
		{
			$this->_db->setQuery($query, $limitstart, $limit);
		}

		$result = $this->_db->loadObjectList();

		return $result;
	}
}
