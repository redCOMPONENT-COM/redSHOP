<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.model');
jimport('joomla.html.pagination');

class manufacturersModelmanufacturers extends JModel
{
	var $_id = null;
	var $_data = null;
	var $_productlimit = null;
	var $_table_prefix = null;
	var $_template = null;

	function __construct()
	{
		global $mainframe, $context;

		parent::__construct();

		$this->_table_prefix = '#__redshop_';
		$params              = & $mainframe->getParams('com_redshop');
		if ($params->get('manufacturerid') != "")
		{
			$manid = $params->get('manufacturerid');
		}
		else
		{
			$manid = (int) JRequest::getInt('mid', 0);
		}
		$this->setId($manid);

		$limit = $mainframe->getUserStateFromRequest($context . 'limit', 'limit', $params->get('maxmanufacturer'), 5);
		//$limitstart = $mainframe->getUserStateFromRequest( $context.'limitstart', 'limitstart', 0 );
		$limitstart = JRequest::getVar('limitstart', 0, '', 'int');

		// In case limit has been changed, adjust it
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
	}

	function setId($id)
	{
		$this->_id   = $id;
		$this->_data = null;
	}

	function setProductLimit($limit)
	{
		$this->_productlimit = $limit;
	}

	function getProductLimit()
	{

		return $this->_productlimit;
	}

	function _buildQuery()
	{
		$orderby = $this->_buildContentOrderBy();
		$and     = "";

		// Shopper group - choose from manufactures Start

		$rsUserhelper               = new rsUserhelper();
		$shopper_group_manufactures = $rsUserhelper->getShopperGroupManufacturers();

		if ($shopper_group_manufactures != "")
		{
			$and .= " AND mn.manufacturer_id IN (" . $shopper_group_manufactures . ") ";
		}

		// Shopper group - choose from manufactures End

		if ($this->_id)
		{
			$and .= " AND mn.manufacturer_id='" . $this->_id . "' ";
		}
		$query = "SELECT mn.* FROM " . $this->_table_prefix . "manufacturer AS mn "
			. "WHERE mn.published = 1 "
			. $and
			. $orderby;

		return $query;
	}

	function getTotal()
	{
		if (empty($this->_total))
		{
			$query        = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
		}

		return $this->_total;
	}

	function getData()
	{
		$layout = JRequest::getVar('layout');
		$query  = $this->_buildQuery();
		if ($layout == "products")
		{
			$this->_data = $this->_getList($query); //, $this->getState('limitstart'), $this->getState('limit') );
		}
		else
		{
			$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
		}

		return $this->_data;
	}

	function _buildContentOrderBy()
	{
		global $mainframe, $context;
		$layout  = JRequest::getVar('layout');
		$orderby = JRequest::getVar('order_by', DEFAULT_MANUFACTURER_ORDERING_METHOD);
		if ($layout != "products" && $orderby)
		{
			$filter_order = $orderby;
		}
		else
		{
			$filter_order = 'mn.manufacturer_id';
		}
		$orderby = " ORDER BY " . $filter_order . ' ';

		return $orderby;
	}

	function getPagination()
	{
		if (empty($this->_pagination))
		{

			$this->_pagination = new redPagination ($this->getTotal(), $this->getState('limitstart'), $this->getState('limit'));
//			$this->_pagination = new JPagination( $this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
		}

		return $this->_pagination;
	}

	function getCategoryList()
	{
		$query = "SELECT DISTINCT(c.category_id) as value, c.category_name as text "
			. "FROM " . $this->_table_prefix . "category AS c "
			. "LEFT JOIN #__redshop_product_category_xref  AS pcx ON c.category_id  = pcx.category_id "
			. "LEFT JOIN #__redshop_product  AS p ON pcx.product_id = p.product_id  "
			. "WHERE p.manufacturer_id = '" . $this->_id . "' "
			. "AND c.published = '1' "
			. "ORDER BY c.category_name ASC";
		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectlist();

		return $list;
	}

	function getManufacturerProducts($template_data = '')
	{
		$limit          = $this->getProductLimit();
		$limitstart     = JRequest::getVar('limitstart', 0, '', 'int');
		$query          = $this->_buildProductQuery($template_data);
		$this->products = $this->_getList($query, $limitstart, $limit);

		return $this->products;
	}

	function _buildProductQuery($template_data = '')
	{
		$filter_by = JRequest::getVar('filter_by', 0);
		$and       = '';

		// Shopper group - choose from manufactures Start

		$rsUserhelper               = new rsUserhelper();
		$shopper_group_manufactures = $rsUserhelper->getShopperGroupManufacturers();

		if ($shopper_group_manufactures != "")
		{
			$and .= " AND p.manufacturer_id IN (" . $shopper_group_manufactures . ") ";
		}

		// Shopper group - choose from manufactures End

		if ($filter_by != '0')
		{
			$and .= " AND c.category_id = " . $filter_by;
		}
		$orderby = $this->_buildProductOrderBy($template_data);

		$query = "SELECT DISTINCT(p.product_id),p.*, c.category_name, c.category_id FROM " . $this->_table_prefix . "product AS p "
			. "LEFT JOIN " . $this->_table_prefix . "product_category_xref AS pc ON p.product_id=pc.product_id "
			. "LEFT JOIN " . $this->_table_prefix . "category AS c ON pc.category_id=c.category_id "
			. "WHERE p.published=1 "
			. "AND p.manufacturer_id='" . $this->_id . "' "
			. "AND p.expired=0 "
			. "AND p.product_parent_id=0 "
			. $and
			. " GROUP BY p.product_id "
			. $orderby;

		return $query;
	}

	function getmanufacturercategory($mid, $tblobj)
	{
		$and              = "";
		$order_functions  = new order_functions();
		$plg_manufacturer = $order_functions->getparameters('plg_manucaturer_excluding_category');
		if (count($plg_manufacturer) > 0 && $plg_manufacturer[0]->enabled && $tblobj->excluding_category_list != '')
		{
			$and = "AND c.category_id NOT IN (" . $tblobj->excluding_category_list . ") ";
		}
		$query = "SELECT DISTINCT(c.category_id), c.category_name,c.category_short_description,c.category_description "
			. "FROM " . $this->_table_prefix . "product AS p "
			. "LEFT JOIN " . $this->_table_prefix . "product_category_xref AS pc ON p.product_id=pc.product_id "
			. "LEFT JOIN " . $this->_table_prefix . "category AS c ON pc.category_id=c.category_id "
			. "WHERE p.published=1 "
			. "AND p.manufacturer_id='" . $mid . "' "
			. "AND p.expired=0 "
			. "AND p.product_parent_id=0 "
			. $and//			 	."GROUP BY p.product_id "
		;
		$this->_db->setQuery($query);

		return $this->_db->loadObjectlist();
	}

	function getProductTotal()
	{
		$query = $this->_buildProductQuery();
		$total = $this->_getListCount($query);

		return $total;
	}

	function getProductPagination()
	{
		$limit             = $this->getProductLimit();
		$limitstart        = JRequest::getVar('limitstart', 0, '', 'int');
		$productpagination = new redPagination($this->getProductTotal(), $limitstart, $limit);

		return $productpagination;
	}

	function _buildProductOrderBy($template_data = '')
	{
		$layout  = JRequest::getVar('layout');
		$orderby = JRequest::getVar('order_by', DEFAULT_MANUFACTURER_PRODUCT_ORDERING_METHOD);
		if ($layout == "products" && $orderby)
		{
			$filter_order = $orderby;
		}
		else
		{
			$filter_order = 'pc.ordering';
		}
		//
		if (strstr($template_data, '{category_name}'))
		{
			$filter_order = "c.ordering,c.category_id, " . $filter_order;
		}
		$orderby = " ORDER BY " . $filter_order . ' ';

		return $orderby;
	}
}
