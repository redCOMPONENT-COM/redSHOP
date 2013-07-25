<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
jimport('joomla.application.component.model');

class Tableproduct_category_xref extends JTable
{
	public $product_id = 0;

	public $category_id = 0;

	public $ordering = null;


	public function __construct(& $db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'product_category_xref', 'product_id', $db);
	}

	public function reorder($where = '', $column = 'ordering')
	{
		$k = $this->_tbl_key;
		$query = "SELECT {$this->_tbl_key}, {$column} FROM {$this->_table_prefix}product_category_xref WHERE {$column}>0";
		$query .= ($where ? " AND " . $where : "");
		$query .= " ORDER BY {$column}";

		$this->_db->setQuery($query);

		if (!($orders = $this->_db->loadObjectList()))
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		for ($i = 0, $n = count($orders); $i < $n; $i++)
		{
			if ($orders[$i]->$column >= 0 && $orders[$i]->$column != $i + 1)
			{
				$orders[$i]->$column = $i + 1;
				$query = "UPDATE {$this->_table_prefix}product_category_xref SET {$column}=" . (int) $orders[$i]->$column;
				$query .= ' WHERE ' . $k . ' = ' . $this->_db->Quote($orders[$i]->$k);
				$this->_db->setQuery($query);
				$this->_db->query();
			}
		}

		return true;
	}

	public function move($dirn, $where = '', $column = 'ordering')
	{
		$k = $this->_tbl_key;

		$sql = "SELECT $this->_tbl_key, {$column} FROM $this->_tbl";

		if ($dirn < 0)
		{
			$sql .= ' WHERE ' . $column . ' < ' . (int) $this->$column;
			$sql .= ($where ? ' AND ' . $where : '');
			$sql .= ' ORDER BY ' . $column . ' DESC';
		}
		elseif ($dirn > 0)
		{
			$sql .= ' WHERE ' . $column . ' > ' . (int) $this->$column;
			$sql .= ($where ? ' AND ' . $where : '');
			$sql .= ' ORDER BY ' . $column;
		}
		else
		{
			$sql .= ' WHERE ' . $column . ' = ' . (int) $this->$column;
			$sql .= ($where ? ' AND ' . $where : '');
			$sql .= ' ORDER BY ' . $column;
		}

		$this->_db->setQuery($sql, 0, 1);

		$row = null;
		$row = $this->_db->loadObject();

		if (isset($row))
		{
			$query = 'UPDATE ' . $this->_tbl . ' SET ' . $column . ' = ' . (int) $row->$column . ' WHERE ' . $this->_tbl_key . ' = ' . $this->_db->Quote($this->$k);
			$this->_db->setQuery($query);

			if (!$this->_db->query())
			{
				$err = $this->_db->getErrorMsg();
				JError::raiseError(500, $err);
			}

			$query = 'UPDATE ' . $this->_tbl . ' SET ' . $column . ' = ' . (int) $this->$column . ' WHERE ' . $this->_tbl_key . ' = ' . $this->_db->Quote($row->$k);
			$this->_db->setQuery($query);

			if (!$this->_db->query())
			{
				$err = $this->_db->getErrorMsg();
				JError::raiseError(500, $err);
			}

			$this->$column = $row->$column;
		}
		else
		{
			$query = 'UPDATE ' . $this->_tbl . ' SET ' . $column . ' = ' . (int) $this->$column . ' WHERE ' . $this->_tbl_key . ' = ' . $this->_db->Quote($this->$k);
			$this->_db->setQuery($query);

			if (!$this->_db->query())
			{
				$err = $this->_db->getErrorMsg();
				JError::raiseError(500, $err);
			}
		}

		return true;
	}
}
