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

class wrapperModelwrapper extends JModel
{
	public $_productid = 0;
	public $_data = null;
	public $_total = null;
	public $_pagination = null;
	public $_table_prefix = null;
	public $_context = null;

	function __construct()
	{
		parent::__construct();
		global $mainframe;

		$this->_context = 'wrapper_id';

		$this->_table_prefix = '#__' . TABLE_PREFIX . '_';
		$limit = $mainframe->getUserStateFromRequest($this->_context . 'limit', 'limit', $mainframe->getCfg('list_limit'), 0);
		$limitstart = $mainframe->getUserStateFromRequest($this->_context . 'limitstart', 'limitstart', 0);
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);

		$product_id = JRequest::getVar('product_id');
		$this->setProductId((int) $product_id);
	}

	function setProductId($id)
	{
		$this->_productid = $id;
		$this->_data = null;
	}

	function getData()
	{
		if (empty($this->_data))
		{
			$query = $this->_buildQuery();
			$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
		}
		return $this->_data;
	}

	function getTotal()
	{
		if (empty($this->_total))
		{
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
		}
		return $this->_total;
	}

	function getPagination()
	{
		if (empty($this->_pagination))
		{
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit'));
		}
		return $this->_pagination;
	}


	function _buildQuery()
	{
		//$orderby	= $this->_buildContentOrderBy();
		$showall = JRequest::getVar('showall', '0');
		$and = '';
		if ($showall && $this->_productid != 0)
		{
			$and = 'WHERE FIND_IN_SET(' . $this->_productid . ',w.product_id) OR wrapper_use_to_all = 1 ';

			$query = "SELECT * FROM " . $this->_table_prefix . "product_category_xref "
				. "WHERE product_id = " . $this->_productid;
			$cat = $this->_getList($query);
			for ($i = 0; $i < count($cat); $i++)
			{
				$and .= " OR FIND_IN_SET(" . $cat[$i]->category_id . ",category_id) ";
			}
		}
		$query = 'SELECT distinct(w.wrapper_id), w.* FROM ' . $this->_table_prefix . 'wrapper AS w '
//				.'LEFT JOIN '.$this->_table_prefix.'product AS p ON p.product_id = w.product_id '
			. $and;
		return $query;
	}
}

?>
