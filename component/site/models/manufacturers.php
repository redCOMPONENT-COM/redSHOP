<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('joomla.html.pagination');

/**
 * Class manufacturersModelmanufacturers
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 * @since       1.0
 */
class RedshopModelManufacturers extends RedshopModel
{
	public $_id = null;

	public $_data = null;

	public $_productlimit = null;

	public $_table_prefix = null;

	public $_template = null;

	public $filter_fields_products = null;

	public $filter_fields_manufacturer = null;

	public function __construct()
	{
		global $context;

		$app = JFactory::getApplication();

		// @ToDo In fearure, when class Manufacturers extends RedshopModelList, replace filter_fields in constructor
		$this->filter_fields_products = array(
			'p.product_name ASC', 'product_name ASC',
			'p.product_price ASC', 'product_price ASC',
			'p.product_price DESC', 'product_price DESC',
			'p.product_number ASC', 'product_number ASC',
			'p.product_id DESC', 'product_id DESC',
			'pc.ordering ASC', 'ordering ASC'
		);
		$this->filter_fields_manufacturer = array(
			'mn.manufacturer_name ASC', 'manufacturer_name ASC',
			'mn.manufacturer_id DESC', 'manufacturer_id DESC',
			'mn.ordering ASC', 'ordering ASC'
		);

		parent::__construct();

		$this->_table_prefix = '#__redshop_';
		$params              = $app->getParams('com_redshop');

		if ($params->get('manufacturerid') != "")
		{
			$manid = $params->get('manufacturerid');
		}
		else
		{
			$manid = (int) JRequest::getInt('mid', 0);
		}

		$this->setId($manid);

		$limit = $app->getUserStateFromRequest($context . 'limit', 'limit', $params->get('maxmanufacturer'), 5);

		$limitstart = JRequest::getVar('limitstart', 0, '', 'int');

		// In case limit has been changed, adjust it
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
	}

	public function setId($id)
	{
		$this->_id   = $id;
		$this->_data = null;
	}

	public function setProductLimit($limit)
	{
		$this->_productlimit = $limit;
	}

	public function getProductLimit()
	{
		return $this->_productlimit;
	}

	public function _buildQuery()
	{
		$orderby = $this->_buildContentOrderBy();
		$and     = "";

		// Shopper group - choose from manufactures Start
		$rsUserhelper               = new rsUserhelper;
		$shopper_group_manufactures = $rsUserhelper->getShopperGroupManufacturers();

		if ($shopper_group_manufactures != "")
		{
			$shopper_group_manufactures = explode(',', $shopper_group_manufactures);
			JArrayHelper::toInteger($shopper_group_manufactures);
			$shopper_group_manufactures = implode(',', $shopper_group_manufactures);
			$and .= " AND mn.manufacturer_id IN (" . $shopper_group_manufactures . ") ";
		}

		// Shopper group - choose from manufactures End
		if ($this->_id)
		{
			$and .= " AND mn.manufacturer_id = " . (int) $this->_id . " ";
		}

		$query = "SELECT mn.* FROM " . $this->_table_prefix . "manufacturer AS mn "
			. "WHERE mn.published = 1 "
			. $and
			. $orderby;

		return $query;
	}

	public function getTotal()
	{
		if (empty($this->_total))
		{
			$query        = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
		}

		return $this->_total;
	}

	public function getData()
	{
		$layout = JRequest::getVar('layout');
		$query  = $this->_buildQuery();

		if ($layout == "products")
		{
			$this->_data = $this->_getList($query);
		}
		else
		{
			$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
		}

		return $this->_data;
	}

	public function _buildContentOrderBy()
	{
		$db  = JFactory::getDbo();
		$app = JFactory::getApplication();
		$layout  = $app->input->getCmd('layout', '');
		$order_by = urldecode($app->input->getCmd('order_by', DEFAULT_MANUFACTURER_ORDERING_METHOD));

		if (($layout == "products" && in_array($order_by, $this->filter_fields_products))
			|| ($layout != "products" && in_array($order_by, $this->filter_fields_manufacturer)))
		{
			$filter_order = $order_by;
		}
		else
		{
			$filter_order = 'mn.manufacturer_id';
		}

		$orderby = " ORDER BY " . $db->escape($filter_order) . ' ';

		return $orderby;
	}

	public function getPagination()
	{
		if (empty($this->_pagination))
		{
			$this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit'));
		}

		return $this->_pagination;
	}

	public function getCategoryList()
	{
		$query = "SELECT DISTINCT(c.category_id) as value, c.category_name as text "
			. "FROM " . $this->_table_prefix . "category AS c "
			. "LEFT JOIN #__redshop_product_category_xref  AS pcx ON c.category_id  = pcx.category_id "
			. "LEFT JOIN #__redshop_product  AS p ON pcx.product_id = p.product_id  "
			. "WHERE p.manufacturer_id = " . (int) $this->_id . " "
			. "AND c.published = 1 "
			. "ORDER BY c.category_name ASC";
		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectlist();

		return $list;
	}

	public function getManufacturerProducts($template_data = '')
	{
		$limit          = $this->getProductLimit();
		$limitstart     = JRequest::getVar('limitstart', 0, '', 'int');
		$query          = $this->_buildProductQuery($template_data);
		$this->products = $this->_getList($query, $limitstart, $limit);

		return $this->products;
	}

	public function _buildProductQuery($template_data = '')
	{
		$filter_by = JRequest::getVar('filter_by', 0);
		$and       = '';

		// Shopper group - choose from manufactures Start
		$rsUserhelper               = new rsUserhelper;
		$shopper_group_manufactures = $rsUserhelper->getShopperGroupManufacturers();

		if ($shopper_group_manufactures != "")
		{
			$shopper_group_manufactures = explode(',', $shopper_group_manufactures);
			JArrayHelper::toInteger($shopper_group_manufactures);
			$shopper_group_manufactures = implode(',', $shopper_group_manufactures);
			$and .= " AND p.manufacturer_id IN (" . $shopper_group_manufactures . ") ";
		}

		// Shopper group - choose from manufactures End
		if ($filter_by != '0')
		{
			$and .= " AND c.category_id = " . (int) $filter_by;
		}

		$orderby = $this->_buildProductOrderBy($template_data);

		$query = "SELECT DISTINCT(p.product_id),p.*, c.category_name, c.category_id FROM " . $this->_table_prefix . "product AS p "
			. "LEFT JOIN " . $this->_table_prefix . "product_category_xref AS pc ON p.product_id=pc.product_id "
			. "LEFT JOIN " . $this->_table_prefix . "category AS c ON pc.category_id=c.category_id "
			. "WHERE p.published = 1 "
			. "AND p.manufacturer_id = " . (int) $this->_id . " "
			. "AND p.expired = 0 "
			. "AND p.product_parent_id = 0 "
			. $and
			. " GROUP BY p.product_id "
			. $orderby;

		return $query;
	}

	public function getmanufacturercategory($mid, $tblobj)
	{
		$and              = "";
		$order_functions  = new order_functions;
		$plg_manufacturer = $order_functions->getparameters('plg_manucaturer_excluding_category');

		if (count($plg_manufacturer) > 0 && $plg_manufacturer[0]->enabled && $tblobj->excluding_category_list != '')
		{
			$excluding_category_list = explode(',', $tblobj->excluding_category_list);
			JArrayHelper::toInteger($excluding_category_list);
			$excluding_category_list = implode(',', $excluding_category_list);
			$and = "AND c.category_id NOT IN (" . $excluding_category_list . ") ";
		}

		$query = "SELECT DISTINCT(c.category_id), c.category_name,c.category_short_description,c.category_description "
			. "FROM " . $this->_table_prefix . "product AS p "
			. "LEFT JOIN " . $this->_table_prefix . "product_category_xref AS pc ON p.product_id=pc.product_id "
			. "LEFT JOIN " . $this->_table_prefix . "category AS c ON pc.category_id=c.category_id "
			. "WHERE p.published = 1 "
			. "AND p.manufacturer_id = " . (int) $mid . " "
			. "AND p.expired = 0 "
			. "AND p.product_parent_id = 0 "
			. $and;

		$this->_db->setQuery($query);

		return $this->_db->loadObjectlist();
	}

	public function getProductTotal()
	{
		$query = $this->_buildProductQuery();
		$total = $this->_getListCount($query);

		return $total;
	}

	public function getProductPagination()
	{
		$limit             = $this->getProductLimit();
		$limitstart        = JRequest::getVar('limitstart', 0, '', 'int');
		$productpagination = new JPagination($this->getProductTotal(), $limitstart, $limit);

		return $productpagination;
	}

	public function _buildProductOrderBy($template_data = '')
	{
		$db  = JFactory::getDbo();
		$app = JFactory::getApplication();
		$order_by = urldecode($app->input->getCmd('order_by', DEFAULT_MANUFACTURER_PRODUCT_ORDERING_METHOD));

		if (in_array($order_by, $this->filter_fields_products))
		{
			$filter_order = $order_by;
		}
		else
		{
			$filter_order = 'pc.ordering';
		}

		if (strstr($template_data, '{category_name}'))
		{
			$filter_order = "c.ordering,c.category_id, " . $filter_order;
		}

		$orderby = " ORDER BY " . $db->escape($filter_order) . ' ';

		return $orderby;
	}
}
