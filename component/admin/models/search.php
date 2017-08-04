<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopModelSearch extends RedshopModel
{
	public $_id = null;

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

		$jinput = JFactory::getApplication()->input;

		$id = $jinput->get('id', 0);

		$product_id = $jinput->get('product_id', '');

		$related = $jinput->get('related', '');

		$navigator = $jinput->get('navigator', '');

		$voucher_id = $jinput->get('voucher_id', '');

		$stockroom_id = $jinput->get('stockroom_id', '');

		$media_section = $jinput->get('media_section', '');

		$user = $jinput->get('user', '');

		$plgcustomview = $jinput->get('plgcustomview', '');

		$this->_iscompany = $jinput->getInt('iscompany', -1);

		$addreduser = $jinput->get('addreduser', '');

		$products = $jinput->get('isproduct', '');

		$search = $jinput->get('input', '');

		$parent = $jinput->get('parent', '');

		$alert = $jinput->get('alert', '');

		$limit = $jinput->get('limit', '');

		$this->_parent = $parent;

		$this->_limit = $limit;

		$this->_search = $search;

		$this->_alert = $alert;

		$this->setId((int) $id);

		$this->_stockroom_id = ((int) $stockroom_id);

		$this->_product_id = ((int) $product_id);

		$this->_related = ((int) $related);

		$this->_navigator = ((int) $navigator);

		$this->_voucher_id = ((int) $voucher_id);

		$this->_media_section = $media_section;

		$this->_user = $user;

		$this->_plgcustomview = $plgcustomview;

		$this->_addreduser = $addreduser;

		$this->_products = $products;
	}

	/**
	 * Method select needed values from search input.
	 *
	 * @return string  A result select items and count items
	 */
	public function search()
	{
		JSession::checkToken() or jexit('Invalid Token');
		$db = JFactory::getDbo();
		$app = JFactory::getApplication();
		$jInput = $app->input;
		$search = ' LIKE ' . $db->quote('%' . $jInput->getString('input', '') . '%');
		$query = $db->getQuery(true);

		if ($jInput->getCmd('media_section', '') != '')
		{
			switch ($jInput->getCmd('media_section', ''))
			{
				case 'category':
					$query->select(
						array(
							$db->qn('id'),
							$db->qn('name', 'text')
						)
					)
						->from($db->qn('#__redshop_category'))
						->where($db->qn('name') . $search);
					break;
				case 'property':
					$query->select(
						array(
							$db->qn('property_id', 'id'),
							$db->qn('property_name', 'text')
						)
					)
						->from($db->qn('#__redshop_product_attribute_property'))
						->where($db->qn('property_name') . $search);
					break;
				case 'subproperty':
					$query->select(
						array(
							$db->qn('subattribute_color_id', 'id'),
							$db->qn('subattribute_color_name', 'text')
						)
					)
						->from($db->qn('#__redshop_product_subattribute_color'))
						->where($db->qn('subattribute_color_name') . $search);
					break;
				case 'manufacturer':
					$query->select(
						array(
							$db->qn('manufacturer_id', 'id'),
							$db->qn('manufacturer_name', 'text')
						)
					)
						->from($db->qn('#__redshop_manufacturer'))
						->where($db->qn('manufacturer_name') . $search);
					break;
				case 'catalog':
					$query->select(
						array(
							$db->qn('catalog_id', 'id'),
							$db->qn('catalog_name', 'text')
						)
					)
						->from($db->qn('#__redshop_catalog'))
						->where('catalog_name' . $search);
					break;
				case 'product':
				default:
					$query->select(
						array(
							$db->qn('product_id', 'id'),
							'CONCAT(' . $db->qn('product_name') . ', " (", ' . $db->qn('product_number') . ', ")") as text'
						)
					)
						->from($db->qn('#__redshop_product'))
						->where($db->qn('product_name') . $search . ' OR ' . $db->qn('product_number') . $search);
					break;
			}
		}
		elseif ($jInput->getCmd('alert', '') == 'container')
		{
			$query->select(
				array(
					$db->qn('p.product_id', 'id'),
					'CONCAT(' . $db->qn('p.product_name') . ', " (", ' . $db->qn('p.product_number') . ', ")") as text',
					$db->qn('p.supplier_id'),
					$db->qn('p.product_volume', 'volume')
				)
			)
				->from($db->qn('#__redshop_product', 'p'))
				->leftJoin($db->qn('#__redshop_container_product_xref', 'cp') . ' ON cp.product_id = p.product_id')
				->where($db->qn('p.product_name') . $search)
				->where($db->qn('cp.container_id') . ' != ' . $jInput->getInt('container_id', 0));
		}
		elseif ($jInput->getCmd('alert', '') == 'voucher')
		{
			$subQuery = $db->getQuery(true)
				->select('COUNT(cp.product_id)')
				->from($db->qn('#__redshop_product_voucher_xref', 'cp'))
				->where('cp.product_id = p.product_id')
				->where('cp.voucher_id = ' . $jInput->getInt('voucher_id', 0));
			$query->select(
				array(
					$db->qn('p.product_id', 'id'),
					'CONCAT(' . $db->qn('p.product_name') . ', " (", ' . $db->qn('p.product_number') . ', ")") as text'
				)
			)
				->from($db->qn('#__redshop_product', 'p'))
				->where($db->qn('p.product_name') . $search)
				->where('(' . $subQuery . ') = 0');
		}
		elseif ($jInput->getCmd('alert', '') == 'stoockroom')
		{
			$query->select(
				array(
					$db->qn('p.container_id', 'id'),
					$db->qn('p.container_name', 'text')
				)
			)
				->from($db->qn('#__redshop_container', 'p'))
				->leftJoin($db->qn('#__redshop_stockroom_container_xref', 'cp') . ' ON cp.container_id = p.container_id')
				->where($db->qn('p.container_name') . $search)
				->where($db->qn('cp.stockroom_id') . ' != ' . $jInput->getInt('stockroom_id', 0));
		}
		elseif ($jInput->getCmd('alert', '') == 'termsarticle')
		{
			$query->select(
				array(
					$db->qn('a.id'),
					$db->qn('a.title', 'text')
				)
			)
				->from($db->qn('#__content', 'a'))
				->leftJoin($db->qn('#__categories', 'cc') . ' ON cc.id = a.catid')
				->where($db->qn('a.title') . $search)
				->where($db->qn('a.state') . ' = 1')
				->where($db->qn('cc.extension' . ' = ' . $db->quote('com_content')))
				->where($db->qn('cc.published') . ' = 1');
		}
		elseif ($jInput->getInt('user', 0) == 1 || $jInput->getInt('addreduser', 0) == 1)
		{
			if ($jInput->getInt('addreduser', 0) == 1)
			{
				$emailLabel = 'value_number';
			}
			else
			{
				$emailLabel = 'volume';
			}

			$query->select(
				array(
					$db->qn('u.id'),
					'CONCAT (' . $db->qn('uf.firstname') . ', ' . $db->quote(' ') . ', ' . $db->qn('uf.lastname') . ', ' . $db->quote(' (')
					. ', ' . $db->qn('u.username') . ', ' . $db->quote(')') . ') AS text',
					$db->qn('u.email', $emailLabel)
				)
			)
				->from($db->qn('#__users', 'u'))
				->leftJoin($db->qn('#__redshop_users_info', 'uf') . ' ON uf.user_id = u.id')
				->where('(' . $db->qn('u.username') . $search
					. ' OR ' . $db->qn('uf.firstname') . $search
					. ' OR ' . $db->qn('uf.lastname') . $search . ')')
				->where($db->qn('uf.address_type') . ' = ' . $db->quote('BT'));
		}
		elseif ($jInput->getInt('plgcustomview', 0) == 1)
		{
			$iscompany = $jInput->getInt('iscompany', -1);

			if ($iscompany == 0)
			{
				$query->select(
					array(
						$db->qn('u.id'),
						'CONCAT (' . $db->qn('uf.firstname') . ', ' . $db->quote(' ') . ', ' . $db->qn('uf.lastname') . ', ' . $db->quote(' (')
						. ', ' . $db->qn('u.username') . ', ' . $db->quote(')') . ') AS text',
						$db->qn('u.email', 'volume')
					)
				)
					->from($db->qn('#__users', 'u'))
					->leftJoin($db->qn('#__redshop_users_info', 'uf') . ' ON uf.user_id = u.id')
					->where('(' . $db->qn('u.username') . $search
						. ' OR ' . $db->qn('uf.firstname') . $search
						. ' OR ' . $db->qn('uf.lastname') . $search . ')')
					->where($db->qn('uf.address_type') . ' = ' . $db->quote('BT'))
					->where($db->qn('uf.is_company') . ' = 0');
			}
			elseif ($iscompany == 1)
			{
				$query->select(
					array(
						$db->qn('u.id'),
						'CONCAT (' . $db->qn('uf.company_name') . ', ' . $db->quote(' (') . ', '
						. $db->qn('u.username') . ', ' . $db->quote(')') . ') AS text',
						$db->qn('u.email', 'volume')
					)
				)
					->from($db->qn('#__redshop_users_info', 'uf'))
					->leftJoin($db->qn('#__users', 'u') . ' ON uf.user_id = u.id')
					->where('(' . $db->qn('u.username') . $search
						. ' OR ' . $db->qn('uf.company_name') . $search . ')')
					->where($db->qn('uf.address_type') . ' = ' . $db->quote('BT'))
					->where($db->qn('uf.is_company') . ' = 1');
			}
		}
		elseif ($jInput->getInt('isproduct', 0) == 1)
		{
			$query->select(
				array(
					$db->qn('product_id', 'id'),
					'CONCAT(' . $db->qn('product_name') . ', " (", ' . $db->qn('product_number') . ', ")") as text',
					$db->qn('product_number', 'value_number')
				)
			)
				->from($db->qn('#__redshop_product'))
				->where($db->qn('product_name') . $search . ' OR ' . $db->qn('product_number') . $search);
		}
		elseif ($jInput->getInt('related', 0) == 1)
		{
			$query->select(
				array(
					$db->qn('p.product_id', 'id'),
					'CONCAT(' . $db->qn('p.product_name') . ', " (", ' . $db->qn('p.product_number') . ', ")") as text',
					$db->qn('p.product_number', 'value_number')
				)
			)
				->from($db->qn('#__redshop_product', 'p'))
				->where($db->qn('p.product_id') . ' != ' . $jInput->getInt('product_id', 0))
				->where('(' . $db->qn('p.product_name') . $search
					. ' OR ' . $db->qn('p.product_number') . $search . ')');
		}
		elseif ($jInput->getInt('parent', 0) == 1)
		{
			if ($product_id = $jInput->getInt('product_id', 0))
			{
				$query->where($db->qn('p.product_id') . ' != ' . $product_id);
			}

			$query->select(
				array(
					$db->qn('p.product_id', 'id'),
					'CONCAT(' . $db->qn('p.product_name') . ', " (", ' . $db->qn('p.product_number') . ', ")") as text',
				)
			)
				->from($db->qn('#__redshop_product', 'p'))
				->where($db->qn('p.product_name') . $search)
				->where($db->qn('p.product_parent_id') . ' = 0');
		}
		elseif ($jInput->getInt('navigator', 0) == 1)
		{
			$query->select(
				array(
					$db->qn('p.product_id', 'id'),
					'CONCAT(' . $db->qn('p.product_name') . ', " (", ' . $db->qn('p.product_number') . ', ")") as text',
					$db->qn('p.product_number', 'value_number'),
					$db->qn('p.product_price', 'price')
				)
			)
				->from($db->qn('#__redshop_product', 'p'))
				->where($db->qn('p.published') . ' = 1')
				->where('(' . $db->qn('p.product_name') . $search
					. ' OR ' . $db->qn('p.product_number') . $search . ')');
		}
		else
		{
			if ($accessoryList = $jInput->getString('accessoryList', ''))
			{
				$accessoryList = explode(',', $accessoryList);
				JArrayHelper::toInteger($accessoryList);
				$query->where('p.product_id NOT IN (' . implode(',', $accessoryList) . ')');
			}

			if ($product_id = $jInput->getInt('product_id', 0))
			{
				$query->leftJoin($db->qn('#__redshop_product_accessory', 'pa') . ' ON pa.child_product_id = p.product_id AND pa.product_id = ' . $product_id)
					->where('pa.accessory_id IS NULL')
					->where($db->qn('p.product_id') . ' != ' . $product_id);
			}

			$query->select(
				array(
					$db->qn('p.product_id', 'id'),
					'CONCAT(' . $db->qn('p.product_name') . ', " (", ' . $db->qn('p.product_number') . ', ")") as text',
					$db->qn('p.product_number', 'value_number'),
					$db->qn('p.product_price', 'price')
				)
			)
				->from($db->qn('#__redshop_product', 'p'))
				->where('(' . $db->qn('p.product_name') . $search
					. ' OR ' . $db->qn('p.product_number') . $search . ')');
		}

		$json = new stdClass;
		$db->setQuery($query)->execute();
		$json->total = $db->getNumRows();

		if ($json->total != 0)
		{
			$limit = $jInput->getInt('limit', 10);
			$limitStart = ($jInput->getInt('page', 1) - 1) * $limit;
			$db->setQuery($query, $limitStart, $limit);
			$json->result = $db->loadObjectList();
		}
		else
		{
			$json->result = '';
		}

		return json_encode($json);
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
				$query = "SELECT id as id,name as value FROM " . $this->_table_prefix . "category  WHERE name like '" .
					$this->_search . "%'";
			}
			else
			{
				$query = "SELECT catalog_id  as id,catalog_name	 as value FROM " . $this->_table_prefix . "catalog  WHERE catalog_name like '" .
					$this->_search . "%' AND published = 1";
			}
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
		elseif ($this->_alert == 'termsarticle')
		{
			$query = 'SELECT a.sectionid,a.catid, a.id AS value, a.title AS text '
				. 'FROM #__content AS a '
				. 'WHERE a.state = 1 '
				. 'AND a.title LIKE "' . $this->_search . '%"';
			$this->_db->setQuery($query);
			$rows = $this->_db->loadObjectList();
			$article = array();

			for ($j = 0, $jn = count($rows); $j < $jn; $j++)
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
				$related = $this->_db->loadColumn();
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
