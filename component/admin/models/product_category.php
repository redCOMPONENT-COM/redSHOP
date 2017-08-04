<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopModelProduct_category extends RedshopModel
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

		if ($products = $this->_db->loadObjectlist('product_id'))
		{
			$products = $this->getProductCategories($products);
		}

		return $products;
	}

	/**
	 * Get Product Categories
	 *
	 * @param   array  $products  Data products
	 *
	 * @return  mixed
	 */
	public function getProductCategories($products)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('c.name, pcx.product_id')
			->from($db->qn('#__redshop_category', 'c'))
			->leftJoin($db->qn('#__redshop_product_category_xref', 'pcx') . ' ON pcx.category_id = c.id')
			->where('pcx.product_id IN (' . implode(',', array_keys($products)) . ')');

		if ($categories = $db->setQuery($query)->loadObjectList())
		{
			foreach ($categories as $category)
			{
				if (!isset($products[$category->product_id]->categories))
				{
					$products[$category->product_id]->categories = array();
				}

				$products[$category->product_id]->categories[] = $category->name;
			}
		}

		return $products;
	}

	public function saveProduct_Category()
	{
		$pid = JRequest::getVar('cid', array(), 'post', 'array');
		$cat_id = JRequest::getVar('category_id');

		for ($i = 0, $in = count($pid); $i < $in; $i++)
		{
			for ($j = 0, $jn = count($cat_id); $j < $jn; $j++)
			{
				if (count($this->getIdfromXref($pid[$i], $cat_id[$j])) <= 0)
				{
					$query = "INSERT INTO " . $this->_table_prefix . "product_category_xref "
						. "(`category_id`,`product_id`) VALUES ('" . $cat_id[$j] . "','" . $pid[$i] . "')";
					$this->_db->setQuery($query);

					if (!$this->_db->execute())
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

		for ($i = 0, $in = count($pid); $i < $in; $i++)
		{
			$query = "DELETE FROM " . $this->_table_prefix . "product_category_xref "
				. " WHERE product_id=" . $pid[$i] . " AND category_id IN (" . $cat_ids . ")";
			$this->_db->setQuery($query);

			if (!$this->_db->execute())
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
