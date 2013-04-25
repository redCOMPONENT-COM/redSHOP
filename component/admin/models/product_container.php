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

class product_containerModelproduct_container extends JModel
{
	public $_data = null;

	public $_total = null;

	public $_pagination = null;

	public $_table_prefix = null;

	public $_context = null;

	public function __construct()
	{
		parent::__construct();

		$app = JFactory::getApplication();

		$this->_context = 'product_id';
		$this->_table_prefix = '#__redshop_';
		$limit = $app->getUserStateFromRequest($this->_context . 'limit', 'limit', $app->getCfg('list_limit'), 0);
		$limitstart = $app->getUserStateFromRequest($this->_context . 'limitstart', 'limitstart', 0);
		$filter_supplier = $app->getUserStateFromRequest($this->_context . 'filter_supplier', 'filter_supplier', 0);
		$filter_container = $app->getUserStateFromRequest($this->_context . 'filter_container', 'filter_container', 0);

		$this->setState('filter_supplier', $filter_supplier);
		$this->setState('filter_container', $filter_container);
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
	}

	public function getData()
	{
		if (empty($this->_data))
		{
			$query = $this->_buildQuery();

			$preorder = JRequest::getVar('preorder', '', 'request', 0);
			$newproducts = JRequest::getVar('newproducts', '', 'request', 0);
			$existingproducts = JRequest::getVar('existingproducts', '', 'request', 0);

			if (!$preorder)
			{
				$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
			}
			else
			{
				$this->_data = $this->_getList($query);
			}
		}

		return $this->_data;
	}

	public function getTotal()
	{
		if (empty($this->_total))
		{
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
		}

		return $this->_total;
	}

	public function getPagination()
	{
		if (empty($this->_pagination))
		{
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit'));
		}

		return $this->_pagination;
	}

	public function _buildQuery()
	{
		$filter_supplier = $this->getState('filter_supplier');

		$filter_container = $this->getState('filter_container');

		$where = array();

		if ($filter_supplier)
		{
			$where[] = "p.supplier_id = '" . $filter_supplier . "'";
		}

		$container = JRequest::getVar('container', '', 'request', 0);

		if ($container == 1)
		{
			if ($filter_container)
			{
				$where[] = "op.container_id = '" . $filter_container . "'";
			}

			$where[] = " op.container_id > 0 ";

		}
		else
		{
			$where[] = " op.container_id < 1 ";
		}

		$where = count($where) ? ' AND ' . implode(' AND ', $where) : ' ';

		$orderby = $this->_buildContentOrderBy();

		$query = ' SELECT p.*,s.supplier_name,op.order_id,op.order_item_id,op.product_quantity,op.container_id as ocontainer_id FROM ' .
			$this->_table_prefix . 'product as p left join ' . $this->_table_prefix . 'supplier as s on s.supplier_id = p.supplier_id , ' .
			$this->_table_prefix . 'order_item as op WHERE   op.product_id = p.product_id ' . $where . ' ' . $orderby;

		$preorder = JRequest::getVar('preorder', '', 'request', 0);
		$newproducts = JRequest::getVar('newproducts', '', 'request', 0);
		$existingproducts = JRequest::getVar('existingproducts', '', 'request', 0);

		if ($preorder == '1')
		{
			$query = ' SELECT  0 as show_qty,p.*,s.supplier_name,op.order_id,op.order_item_id,sum(product_quantity) as product_quantity,
			op.container_id as ocontainer_id FROM ' . $this->_table_prefix . 'product as p left join '
				. $this->_table_prefix . 'supplier as s on s.supplier_id = p.supplier_id , ' . $this->_table_prefix .
				'order_item as op WHERE   op.product_id = p.product_id ' . $where . ' group by product_id ' . $orderby;
		}

		if ($newproducts == '1')
		{
			$query = ' SELECT *,1 as product_quantity,0 as show_qty   FROM ' . $this->_table_prefix . 'product ';
		}

		if ($existingproducts == '1')
		{
			$container_id = JRequest::getVar('container_id', '', 'request', 0);

			$query = "SELECT 1 as show_qty,product_volume,cp.product_id,cp.quantity as product_quantity ,p.product_name,p.product_volume,
			cp.container_id FROM " . $this->_table_prefix . "product as p , " . $this->_table_prefix . "container_product_xref as cp  WHERE
			cp.container_id=$container_id and cp.product_id=p.product_id ";
		}

		return $query;
	}

	public function _buildContentOrderBy()
	{
		$app = JFactory::getApplication();

		$filter_order = $app->getUserStateFromRequest($this->_context . 'filter_order', 'filter_order', 'product_id');
		$filter_order_Dir = $app->getUserStateFromRequest($this->_context . 'filter_order_Dir', 'filter_order_Dir', '');

		$orderby = ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir;

		return $orderby;
	}

	public function listedincats($pid)
	{
		$query = 'SELECT c.category_name FROM ' . $this->_table_prefix . 'product_category_xref as ref, ' .
			$this->_table_prefix . 'category as c WHERE product_id =' . $pid . ' AND ref.category_id=c.category_id ORDER BY c.category_name';
		$this->_db->setQuery($query);

		return $this->_db->loadObjectlist();
	}

	public function product_template($template_id, $product_id, $section)
	{
		require_once JPATH_COMPONENT . '/helpers/extra_field.php';
		$query = 'SELECT template_desc FROM ' . $this->_table_prefix . 'template  WHERE template_id =' . $template_id;

		if ($section == 1)
		{
			$query .= ' and template_section="product" ';
		}

		else
		{
			$query .= ' and template_section="category" ';
		}

		$this->_db->setQuery($query);
		$template_desc = $this->_db->loadObject();
		$template = $template_desc->template_desc;
		$tmp1 = explode("{", $template);
		$str = '';

		for ($h = 0; $h < count($tmp1); $h++)
		{
			$word = explode("}", $tmp1[$h]);

			if ($h != 0)
			{
				$str .= "'" . $word[0] . "'";
			}

			if ($h != 0 && $h != count($tmp1) - 1)
			{
				$str .= ",";
			}
		}

		$field = new extra_field;
		$list_field = $field->list_all_field($section, $product_id, $str); /// field_section 6 :Userinformations

		return $list_field;
	}

	public function getmanufacturername($mid)
	{
		$query = 'SELECT manufacturer_name FROM ' . $this->_table_prefix . 'manufacturer  WHERE manufacturer_id=' . $mid;
		$this->_db->setQuery($query);

		return $this->_db->loadResult();
	}

	public function getmanufacturelist($name = 'manufacturelist', $selected = '', $attributes = ' class="inputbox" size="1" ')
	{
		$db = JFactory::getDBO();

		$query = "SELECT manufacturer_id AS value, manufacturer_name AS text"
			. "\n FROM " . $this->_table_prefix . "manufacturer  where published = '1'";

		$db->setQuery($query);
		$types[] = JHTML::_('select.option', '0', '- ' . JText::_('COM_REDSHOP_SELECT_MANUFACTURER') . ' -');
		$types = array_merge($types, $db->loadObjectList());
		$mylist['manufacturelist'] = JHTML::_('select.genericlist', $types, $name, $attributes, 'value', 'text', $selected);

		return $mylist['manufacturelist'];
	}

	public function getsupplierlist($name = 'supplierlist', $selected = '', $attributes = ' class="inputbox" size="1" ')
	{
		$db = JFactory::getDBO();

		// Get list of Groups for dropdown filter
		$query = "SELECT supplier_id AS value, supplier_name AS text"
			. "\n FROM " . $this->_table_prefix . "supplier  where published = '1'";

		$db->setQuery($query);
		$types[] = JHTML::_('select.option', '0', '- ' . JText::_('COM_REDSHOP_SELECT_SUPPLIER') . ' -');
		$types = array_merge($types, $db->loadObjectList());
		$mylist['supplierlist'] = JHTML::_('select.genericlist', $types, $name, $attributes, 'value', 'text', $selected);

		return $mylist['supplierlist'];
	}

	public function getcontainerlist($name = 'containerlist', $selected = '', $attributes = ' class="inputbox" size="1" ')
	{
		$db = JFactory::getDBO();

		// Get list of Groups for dropdown filter
		$query = "SELECT container_id AS value, container_name AS text"
			. "\n FROM " . $this->_table_prefix . "container  where published = '1'";

		$db->setQuery($query);
		$types[] = JHTML::_('select.option', '0', '- ' . JText::_('COM_REDSHOP_SELECT_CONTAINER') . ' -');
		$types = array_merge($types, $db->loadObjectList());
		$mylist['containerlist'] = JHTML::_('select.genericlist', $types, $name, $attributes, 'value', 'text', $selected);

		return $mylist['containerlist'];
	}

	public function getcontainerproducts()
	{
		$query = $this->_buildQuery();
		$this->_db->setQuery($query);
		$this->_data = $this->_db->loadObjectlist();

		return $this->_data;
	}
}
