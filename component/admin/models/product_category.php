<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.model');
require_once JPATH_COMPONENT . '/helpers/extra_field.php';

class product_categoryModelproduct_category extends JModel
{
	public function __construct()
	{
		parent::__construct();

		$this->_table_prefix = '#__redshop_';
	}

	public function getProductlist()
	{
		$pid = JRequest::getVar('cid', array(), 'post', 'array');
		$pids = implode(",", $pid);
		$query = 'SELECT product_id,product_name FROM ' . $this->_table_prefix . 'product  WHERE product_id IN(' . $pids . ')';
		$this->_db->setQuery($query);

		return $this->_db->loadObjectlist();
	}

	public function saveProduct_Category()
	{
		$pid = JRequest::getVar('cid', array(), 'post', 'array');
		$cat_id = JRequest::getVar('category_id');

		for ($i = 0; $i < count($pid); $i++)
		{
			for ($j = 0; $j < count($cat_id); $j++)
			{
				if (count($this->getIdfromXref($pid[$i], $cat_id[$j])) <= 0)
				{
					$query = "INSERT INTO " . $this->_table_prefix . "product_category_xref "
						. "(`category_id`,`product_id`) VALUES ('" . $cat_id[$j] . "','" . $pid[$i] . "')";
					$this->_db->setQuery($query);

					if (!$this->_db->Query())
					{
						return false;
					}
				}
			}
		}

		return true;
	}

	public function removeProduct_Category()
	{
		$pid = JRequest::getVar('cid', array(), 'post', 'array');
		$cat_id = JRequest::getVar('category_id', array(), 'post', 'array');
		$cat_ids = implode(",", $cat_id);

		for ($i = 0; $i < count($pid); $i++)
		{
			$query = "DELETE FROM " . $this->_table_prefix . "product_category_xref "
				. " WHERE product_id=" . $pid[$i] . " AND category_id IN (" . $cat_ids . ")";
			$this->_db->setQuery($query);

			if (!$this->_db->Query())
			{
				return false;
			}
		}

		return true;
	}

	public function getIdfromXref($pid, $cid)
	{
		$query = 'SELECT product_id FROM ' . $this->_table_prefix . 'product_category_xref '
			. ' WHERE product_id ="' . $pid . '" AND category_id="' . $cid . '"';
		$this->_db->setQuery($query);

		return $this->_db->loadObjectlist();
	}
}
