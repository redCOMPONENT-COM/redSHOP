<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('joomla.html.pagination');
use Joomla\Utilities\ArrayHelper;

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
		$app = JFactory::getApplication();

		$this->context = 'com_redshop.' . $app->input->getCmd('view') . '.' . $app->input->getCmd('layout', 'default');

		// @ToDo In fearure, when class Manufacturers extends RedshopModelList, replace filter_fields in constructor
		$this->filter_fields_products = array(
			'p.product_name ASC', 'product_name ASC',
			'p.product_name DESC', 'product_name DESC',
			'p.product_price ASC', 'product_price ASC',
			'p.product_price DESC', 'product_price DESC',
			'p.product_number ASC', 'product_number ASC',
			'p.product_number DESC', 'product_number DESC',
			'p.product_id DESC', 'product_id DESC',
			'pc.ordering ASC', 'ordering ASC',
			'pc.ordering DESC', 'ordering DESC'
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
			$manid = (int) $app->input->getInt('mid', 0);
		}

		$this->setId($manid);

		$limit = $app->getUserStateFromRequest($this->context . 'limit', 'limit', $params->get('maxmanufacturer'), 5);

		$limitstart = $app->input->getInt('limitstart', 0);

		// In case limit has been changed, adjust it
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$this->setState($this->context . 'limit', $limit);
		$this->setState($this->context . 'limitstart', $limitstart);
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
		$rsUserhelper               = rsUserHelper::getInstance();
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
		$layout = JFactory::getApplication()->input->getCmd('layout');
		$query  = $this->_buildQuery();

		if ($layout == "products")
		{
			$this->_data = $this->_getList($query);
		}
		else
		{
			$this->_data = $this->_getList($query, $this->getState($this->context . 'limitstart'), $this->getState($this->context . 'limit'));
		}

		return $this->_data;
	}

	public function _buildContentOrderBy()
	{
		$db  = JFactory::getDbo();
		$app = JFactory::getApplication();
		$layout  = $app->input->getCmd('layout', '');
		$params = $app->getParams('com_redshop');

		if ($app->input->getString('order_by', '') != null)
		{
			$order_by = urldecode($app->input->getString('order_by', ''));
			$app->setUserState('com_redshop.manufacturers.default.order_state', $order_by);
		}
		elseif ($app->getUserState('com_redshop.manufacturers.default.order_state') != null)
		{
			$order_by = $app->getUserState('com_redshop.manufacturers.default.order_state');
		}
		else
		{
			$order_by = $params->get('order_by', Redshop::getConfig()->get('DEFAULT_MANUFACTURER_ORDERING_METHOD'));
		}

		if ($layout == 'products')
		{
			$filter_order = 'mn.manufacturer_id';
		}
		else
		{
			if (in_array($order_by, $this->filter_fields_manufacturer))
			{
				$filter_order = $order_by;
			}

			// User can get not allowed order_by, when url contain Itemid from another view, so it need check here
			elseif (in_array($params->get('order_by', Redshop::getConfig()->get('DEFAULT_MANUFACTURER_ORDERING_METHOD')), $this->filter_fields_manufacturer))
			{
				$filter_order = $params->get('order_by', Redshop::getConfig()->get('DEFAULT_MANUFACTURER_ORDERING_METHOD'));
			}
			else
			{
				$filter_order = Redshop::getConfig()->get('DEFAULT_MANUFACTURER_ORDERING_METHOD');
			}
		}

		$orderby = " ORDER BY " . $db->escape($filter_order) . ' ';

		return $orderby;
	}

	public function getPagination()
	{
		if (empty($this->_pagination))
		{
			$this->_pagination = new JPagination($this->getTotal(), $this->getState($this->context . 'limitstart'), $this->getState($this->context . 'limit'));
		}

		return $this->_pagination;
	}

	public function getCategoryList()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('c.id', 'value'))
			->select($db->qn('c.name', 'text'))
			->from($db->qn('#__redshop_category', 'c'))
			->leftjoin($db->qn('#__redshop_product_category_xref', 'pcx') . ' ON ' . $db->qn('c.id') . ' = ' . $db->qn('pcx.category_id'))
			->leftjoin($db->qn('#__redshop_product', 'p') . ' ON ' . $db->qn('p.product_id') . ' = ' . $db->qn('pcx.product_id'))
			->where($db->qn('p.manufacturer_id') . ' = ' . $db->q((int) $this->_id))
			->where($db->qn('c.published') . ' = 1')
			->order($db->qn('c.name') . ' ASC');

		return $db->setQuery($query)->loadObjectlist();
	}

	public function getManufacturerProducts($template_data = '')
	{
		$limit          = $this->getProductLimit();
		$limitstart     = JFactory::getApplication()->input->getInt('limitstart', 0);
		$query          = $this->_buildProductQuery($template_data);
		$this->products = $this->_getList($query, $limitstart, $limit);

		return $this->products;
	}

	public function _buildProductQuery($template_data = '')
	{
		$filterBy = JFactory::getApplication()->input->get('filter_by', 0);
		$orderBy  = $this->_buildProductOrderBy($template_data);

		// Shopper group - choose from manufactures Start
		$rsUserhelper               = rsUserHelper::getInstance();
		$shopperGroupManufactures = $rsUserhelper->getShopperGroupManufacturers();

		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('p.*')
			->select($db->qn('c.id'))
			->select($db->qn('c.name'))
			->from($db->qn('#__redshop_product', 'p'))
			->leftjoin($db->qn('#__redshop_product_category_xref', 'pcx') . ' ON ' . $db->qn('p.product_id') . ' = ' . $db->qn('pcx.product_id'))
			->leftjoin($db->qn('#__redshop_category', 'c') . ' ON ' . $db->qn('c.id') . ' = ' . $db->qn('pcx.category_id'))
			->where($db->qn('p.manufacturer_id') . ' = ' . $db->q((int) $this->_id))
			->where($db->qn('p.published') . ' = 1')
			->where($db->qn('p.expired') . ' = 0')
			->where($db->qn('p.product_parent_id') . ' = 0')
			->order($orderBy)
			->group($db->qn('p.product_id'));

		if (!empty($shopperGroupManufactures))
		{
			$shopperGroupManufactures = explode(',', $shopperGroupManufactures);
			$shopperGroupManufactures = ArrayHelper::toInteger($shopperGroupManufactures);
			$shopperGroupManufactures = implode(',', $shopperGroupManufactures);
			$query->where($db->qn('p.manufacturer_id') . ' IN (' . $shopperGroupManufactures . ')');
		}

		if ($filterBy != '0')
		{
			$query->where($db->qn('c.id') . ' = ' . $db->q((int) $filterBy));
		}

		return $query;
	}

	public function getmanufacturercategory($mid, $tblobj)
	{
		$order_functions  = order_functions::getInstance();
		$plg_manufacturer = $order_functions->getparameters('plg_manucaturer_excluding_category');
		$db = $this->_db;

		$query = $db->getQuery(true)
			->select('DISTINCT(c.id)')
			->select($db->qn('c.name'))
			->select($db->qn('c.short_description'))
			->select($db->qn('c.description'))
			->select($db->qn('c.category_thumb_image'))
			->select($db->qn('c.category_full_image'))
			->from($db->qn('#__redshop_product') . ' AS p')
			->leftJoin($db->qn('#__redshop_product_category_xref') . ' AS pc' . ' ON ' . $db->qn('p.product_id') . ' = ' . $db->qn('pc.product_id'))
			->leftJoin($db->qn('#__redshop_category') . ' AS c' . ' ON ' . $db->qn('pc.category_id') . ' = ' . $db->qn('c.id'))
			->where($db->qn('p.published') . ' = 1')
			->where($db->qn('p.manufacturer_id') . ' = ' . $db->q((int) $mid))
			->where($db->qn('p.expired') . ' = 0')
			->where($db->qn('p.product_parent_id') . ' = 0');

		if (count($plg_manufacturer) > 0 && $plg_manufacturer[0]->enabled && $tblobj->excluding_category_list != '')
		{
			$excluding_category_list = explode(',', $tblobj->excluding_category_list);
			JArrayHelper::toInteger($excluding_category_list);
			$excluding_category_list = implode(',', $excluding_category_list);
			$query->where($db->qn('c.id') . ' NOT IN (' . $excluding_category_list . ')');
		}

		return $db->setQuery($query)->loadObjectlist();
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
		$limitstart        = JFactory::getApplication()->input->getInt('limitstart', 0);
		$productpagination = new JPagination($this->getProductTotal(), $limitstart, $limit);

		return $productpagination;
	}

	public function _buildProductOrderBy($template_data = '')
	{
		$orderByObj  = RedshopHelperUtility::prepareOrderBy(
			urldecode(
				JFactory::getApplication()->input->getString('order_by', Redshop::getConfig()->get('DEFAULT_MANUFACTURER_PRODUCT_ORDERING_METHOD'))
			)
		);
		$orderBy     = $orderByObj->ordering . ' ' . $orderByObj->direction;
		$filterOrder = 'pc.ordering';

		if (in_array($orderBy, $this->filter_fields_products))
		{
			$filterOrder = $orderBy;
		}

		if (strstr($template_data, '{category_name}'))
		{
			$filterOrder = "c.ordering, c.id, " . $filterOrder;
		}

		return JFactory::getDbo()->escape($filterOrder) . ' ';
	}
}
