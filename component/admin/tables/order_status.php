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
 * Order Status table
 *
 * @package     RedSHOP.Backend
 * @subpackage  Table
 * @since       __DEPLOY_VERSION__
 */

class RedshopTableOrder_Status extends RedshopTable
{
	/**
	 * The table name without the prefix. Ex: cursos_courses
	 *
	 * @var  string
	 */
	protected $_tableName = 'redshop_order_status';

	/**
	 * The table key column. Usually: id
	 *
	 * @var  string
	 */
	protected $_tableKey = 'order_status_id';

	/**
	 * Field name to publish/unpublish/trash table registers. Ex: state
	 *
	 * @var  string
	 */
	protected $_tableFieldState = 'published';

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

		// Check status code.
		$db = $this->getDbo();
		$query = $db->getQuery(true)
			->select('COUNT(' . $db->qn('order_status_code') . ') AS ' . $db->qn('count'))
			->from($db->qn('#__' . $this->_tableName))
			->where($db->qn('order_status_code') . ' = ' . $db->quote($this->order_status_code));

		if ($this->order_status_id)
		{
			$query->where($db->qn('order_status_id') . ' != ' . $this->order_status_id);
		}

		if ($db->setQuery($query)->loadResult())
		{
			$this->setError(JText::_('COM_REDSHOP_ORDER_STATUS_ERROR_STATUS_EXIST'));

			return false;
		}


		return true;
	}
}
