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

class searchModelsearch extends JModel
{
	public $_id = null;

	public $_container_id = null;

	public $_stockroom_id = null;

	public $_data = null;

	public $_search = null;

	public $_product = null;

	public $_table_prefix = null;

	public $_template = null;

	public $_limit = null;

	public $_iscompany = null;

	public function __construct()
	{
		parent::__construct();

		$this->_table_prefix = '#__redshop_';

		$id = JRequest::getVar('id', 0);

		$product_id = JRequest::getVar('product_id', '');

		$related = JRequest::getVar('related', '');

		$navigator = JRequest::getVar('navigator', '');

		$container_id = JRequest::getVar('container_id', '');

		$voucher_id = JRequest::getVar('voucher_id', '');

		$stockroom_id = JRequest::getVar('stockroom_id', '');

		$media_section = JRequest::getVar('media_section', '');

		$user = JRequest::getVar('user', '');

		$plgcustomview = JRequest::getVar('plgcustomview', '');

		$this->_iscompany = JRequest::getInt('iscompany', -1);

		$addreduser = JRequest::getVar('addreduser', '');

		$products = JRequest::getVar('isproduct', '');

		$search = JRequest::getVar('input', '');

		$parent = JRequest::getVar('parent', '');

		$alert = JRequest::getVar('alert', '');

		$limit = JRequest::getVar('limit', '');

		$this->_parent = $parent;

		$this->_limit = $limit;

		$this->_search = $search;

		$this->_alert = $alert;

		$this->setId((int) $id);

		$this->_stockroom_id = ((int) $stockroom_id);

		$this->_product_id = ((int) $product_id);

		$this->_related = ((int) $related);

		$this->_navigator = ((int) $navigator);

		$this->_container_id = ((int) $container_id);

		$this->_voucher_id = ((int) $voucher_id);

		$this->_media_section = $media_section;

		$this->_user = $user;

		$this->_plgcustomview = $plgcustomview;

		$this->_addreduser = $addreduser;

		$this->_products = $products;
	}

	public function setId($id)
	{
		$this->_id = $id;
		$this->_data = null;
	}

	public function getData()
	{
		if ($this->_alert == 'termsarticle')
		{
			$this->_data = $this->_buildQuery();

			return $this->_data;
		}

		$query = $this->_buildQuery();
		$this->_data = $this->_getList($query);

		return $this->_data;
	}

	public function _buildQuery()
	{
		if ($this->_media_section)
		{
			if ($this->_media_section == 'product')
			{
				$query = "SELECT product_id as id,product_name as value FROM " . $this->_table_prefix . "product  WHERE product_name like '%" .
					$this->_search . "%'";
			}
			elseif ($this->_media_section == 'category')
			{
				$query = "SELECT category_id as id,category_name as value FROM " . $this->_table_prefix . "category  WHERE category_name like '" .
					$this->_search . "%'";

			}
			else
			{
				$query = "SELECT catalog_id  as id,catalog_name	 as value FROM " . $this->_table_prefix . "catalog  WHERE catalog_name like '" .
					$this->_search . "%' AND published = 1";
			}
		}

		elseif ($this->_alert == 'container')
		{
			$query = "SELECT cp.product_id as value,p.product_name as text FROM " . $this->_table_prefix . "product as p , " .
				$this->_table_prefix . "container_product_xref as cp  WHERE cp.container_id=" . $this->_container_id . "
				and cp.product_id=p.product_id  ";
			$this->_db->setQuery($query);
			$this->_productdata = $this->_db->loadObjectList();

			if (count($this->_productdata) > 0)
			{
				foreach ($this->_productdata as $rc)
				{
					$pid[] = $rc->value;
				}
			}

			if ($this->_productdata)
			{
				$pid = @implode(",", $pid);
				$where = " and p.product_id not in (" . $pid . ") and p.product_name like '%" . $this->_search . "%'";
			}
			else
			{
				$where = " and p.product_name like '%" . $this->_search . "%'";
			}

			$query = "SELECT distinct concat(p.product_id,'`',p.supplier_id) as id,p.product_name as value,p.product_volume as volume  FROM " .
				$this->_table_prefix . "product as p left join   " . $this->_table_prefix . "container_product_xref as cp on
				cp.product_id=p.product_id WHERE 1=1  " . $where;

		}
		elseif ($this->_alert == 'voucher')
		{
			$query = "SELECT cp.product_id as value,p.product_name as text FROM " . $this->_table_prefix . "product as p , "
				. $this->_table_prefix . "product_voucher_xref as cp  WHERE cp.voucher_id=" . $this->_voucher_id
				. " and cp.product_id=p.product_id ";
			$this->_db->setQuery($query);
			$this->_productdata = $this->_db->loadObjectList();

			if (count($this->_productdata) > 0)
			{
				foreach ($this->_productdata as $rc)
				{
					$pid[] = $rc->value;
				}
			}

			if ($this->_productdata)
			{
				$pid = @implode(",", $pid);
				$where = " and p.product_id not in (" . $pid . ") and p.product_name like '%" . $this->_search . "%'";
			}
			else
			{
				$where = " and p.product_name like '%" . $this->_search . "%'";
			}

			$query = "SELECT distinct p.product_id as id,p.product_name as value FROM " . $this->_table_prefix . "product as p left join   "
				. $this->_table_prefix . "product_voucher_xref as cp on cp.product_id=p.product_id WHERE 1=1 " . $where;

		}
		elseif ($this->_alert == 'stoockroom')
		{
			$q = "SELECT cp.container_id as value,p.container_name as text FROM " . $this->_table_prefix . "container as p , "
				. $this->_table_prefix . "stockroom_container_xref as cp  WHERE cp.stockroom_id=" . $this->_stockroom_id
				. " and cp.container_id=p.container_id ";
			$this->_db->setQuery($q);
			$this->_productdata = $this->_db->loadObjectList();

			if (count($this->_productdata) > 0)
			{
				$result_stock = $this->_productdata;
			}

			else
			{
				$result_stock = array();
			}

			if (count($result_stock) > 0)
			{
				foreach ($result_stock as $rc)
				{
					$pid[] = $rc->value;
				}
			}

			if ($result_stock)
			{
				$pid = @implode(",", $pid);
				$where = " and p.container_id not in ($pid)";
			}
			else
			{
				$where = '';
			}

			$where .= " and p.container_id NOT IN ( SELECT container_id FROM " . $this->_table_prefix . "stockroom_container_xref )
			and p.container_name like '" . $this->_search . "%'";

			$query = "SELECT p.container_id as id,p.container_name as value FROM " . $this->_table_prefix . "container as p left join   " .
				$this->_table_prefix . "stockroom_container_xref as cp on cp.container_id=p.container_id WHERE 1=1 " . $where;

		}
		elseif ($this->_alert == 'termsarticle')
		{
			$query = 'SELECT a.sectionid,a.catid, a.id AS value, a.title AS text '
				. 'FROM #__content AS a '
				. 'WHERE a.state = 1 '
				. 'AND a.title LIKE "' . $this->_search . '%"';
			$this->_db->setQuery($query);
			$rows = $this->_db->loadObjectList();
			$article = array();

			for ($j = 0; $j < count($rows); $j++)
			{
				if ($rows[$j]->sectionid != 0 && $rows[$j]->catid != 0)
				{
					$query = 'SELECT a.id AS value, a.title AS text '
						. 'FROM #__content AS a '
						. 'LEFT JOIN #__categories AS cc ON cc.id = a.catid '
						. 'LEFT JOIN #__sections AS s ON s.id = cc.section AND s.scope = "content" '
						. 'LEFT JOIN #__groups AS g ON a.access = g.id '
						. 'WHERE (cc.published = 1 AND s.published = 1) '
						. 'AND a.state = 1 '
						. 'AND a.title LIKE "' . $this->_search . '%"';
					$this->_db->setQuery($query);
					$r = $this->_db->loadObjectList();
					$i = 0;

					foreach ($r as $value)
					{
						$article[$i]->value = $value->text;
						$article[$i]->id = $value->value;
						$i++;
					}
				}
				else
				{
					$article[$j]->value = $rows[$j]->text;
					$article[$j]->id = $rows[$j]->value;
				}
			}

			return $article;
		}
		elseif ($this->_user == 1)
		{
			$query = "SELECT u.id as id,concat(uf.firstname,' ', uf.lastname,' (', u.username,')') as value , u.email as volume ";
			$query .= " FROM " . $this->_table_prefix . "users_info as uf , #__users as u ";
			$query .= " WHERE (uf.user_id=u.id) and (u.username like '" . $this->_search . "%' or  uf.firstname like '" .
				$this->_search . "%' or  uf.lastname like '" . $this->_search . "%') and (uf.address_type like 'BT') ";

		}
		elseif ($this->_plgcustomview == 1)
		{
			if ($this->_iscompany == 0)
			{
				$query = "SELECT u.id as id,concat(uf.firstname,' ', uf.lastname,' (', u.username,')') as value , u.email as volume ";
				$query .= " FROM " . $this->_table_prefix . "users_info as uf , #__users as u ";
				$query .= " WHERE (uf.user_id=u.id) and (u.username like '" . $this->_search . "%' or  uf.firstname like '" .
					$this->_search . "%' or  uf.lastname like '" . $this->_search . "%') and (uf.address_type like 'BT') ";
				$query .= " AND uf.is_company = " . $this->_iscompany . "";
			}

			if ($this->_iscompany == 1)
			{
				$query = "SELECT u.id as id,concat(uf.company_name,' (', u.username,')') as value , u.email as volume ";
				$query .= " FROM " . $this->_table_prefix . "users_info as uf , #__users as u ";
				$query .= " WHERE (uf.user_id=u.id) and (u.username like '" . $this->_search . "%' or  uf.company_name like '" .
					$this->_search . "%') and (uf.address_type like 'BT') ";
				$query .= " AND uf.is_company = " . $this->_iscompany . "";
			}
		}
		elseif ($this->_addreduser == 1)
		{
			$query = "SELECT uf.user_id AS id, CONCAT(uf.firstname,' ', uf.lastname, IF(u.username!='', CONCAT( ' (',u.username,')'), '' ))
			AS value, uf.user_email AS value_number "
				. "FROM " . $this->_table_prefix . "users_info AS uf "
				. "LEFT JOIN #__users AS u ON uf.user_id=u.id "
				. "WHERE (u.username LIKE '" . $this->_search . "%' "
				. "OR uf.firstname LIKE '" . $this->_search . "%' "
				. "OR uf.lastname LIKE '" . $this->_search . "%') "
				. "AND (uf.address_type LIKE 'BT')";

		}
		elseif ($this->_products == 1)
		{
			$query = "SELECT product_id as id,product_name as value, product_number as value_number FROM " .
				$this->_table_prefix . "product  WHERE product_name like '%" . $this->_search . "%'";
		}
		elseif ($this->_related == 1)
		{
			$and = "";

			if ($this->_product_id != 0)
			{
				$query = "SELECT related_id "
					. "FROM " . $this->_table_prefix . "product_related "
					. "WHERE product_id='" . $this->_product_id . "' ";
				$this->_db->setQuery($query);
				$related = $this->_db->loadResultArray();
				$related[count($related)] = $this->_product_id;
				$relatedid = implode(", ", $related);

				$and = "AND p.product_id NOT IN (" . $relatedid . ") ";
			}

			$query = "SELECT p.product_id AS id,p.product_name AS value,p.product_number as value_number "
				. "FROM " . $this->_table_prefix . "product as p "
				. "WHERE (p.product_name LIKE '" . $this->_search . "%' or p.product_number LIKE '" . $this->_search . "%') "
				. $and
				. " LIMIT 0,50 ";
		}
		elseif ($this->_parent == 1)
		{
			$and = "";

			if ($this->_product_id != 0)
			{
				$and = "AND p.product_id NOT IN (" . $this->_product_id . ") ";
			}

			$query = "SELECT p.product_id AS id,p.product_name AS value "
				. "FROM " . $this->_table_prefix . "product as p "
				. "WHERE p.product_name LIKE '" . $this->_search . "%' "
				. $and
				. " LIMIT 0,50 ";
		}

		elseif ($this->_navigator == 1)
		{
			$where = " and (p.product_name like '%" . $this->_search . "%' or p.product_number LIKE '" . $this->_search . "%')";
			$query = "SELECT distinct p.product_id as id,p.product_name as value ,p.product_number as value_number ,product_price as price FROM " .
				$this->_table_prefix . "product as p WHERE 1=1 and p.published = 1 " . $where;
		}
		else
		{
			if ($this->_product_id != 0)
			{
				$where = " and p.product_id not in (select child_product_id from " . $this->_table_prefix . "product_accessory where product_id=" .
					$this->_product_id . ") and p.product_id!=" . $this->_product_id . " and (p.product_name like '%" .
					$this->_search . "%' or p.product_number LIKE '" . $this->_search . "%')";
			}
			else
			{
				$where = " and (p.product_name like '%" . $this->_search . "%' or p.product_number LIKE '" . $this->_search . "%')";
			}

			$query = "SELECT distinct p.product_id as id,p.product_name as value ,p.product_number as value_number ,product_price as price FROM " .
				$this->_table_prefix . "product as p left join   " . $this->_table_prefix . "product_accessory as cp on cp.product_id=p.product_id
				WHERE 1=1 " . $where;

		}

		return $query;
	}
}
