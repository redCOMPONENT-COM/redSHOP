<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
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

		$this->_iscompany = $jinput->getInt('iscompany', -1);

		$this->_parent = $jinput->get('parent', '');

		$this->_limit = $jinput->get('limit', '');

		$this->_search = $jinput->get('input', '');

		$this->_alert = $jinput->get('alert', '');

		$this->setId((int) $jinput->get('id', 0));

		$this->_stockroom_id = ((int) $jinput->get('stockroom_id', 0));

		$this->_product_id = ((int) $jinput->get('product_id', 0));

		$this->_related = ((int) $jinput->get('related',0));

		$this->_navigator = ((int) $jinput->get('navigator', 0));

		$this->_voucher_id = ((int) $jinput->get('voucher_id', 0));

		$this->_media_section = $jinput->get('media_section', '');

		$this->_user = $jinput->get('user', '');

		$this->_plgcustomview = $jinput->get('plgcustomview', '');

		$this->_addreduser = $jinput->get('addreduser', '');

		$this->_products = $jinput->get('isproduct', '');
	}

	/**
	 * Method select needed values from search input.
	 *
	 * @return string  A result select items and count items
	 */
	public function search()
	{
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
		$db     = JFactory::getDbo();
		$app    = JFactory::getApplication();
		$jInput = $app->input;
		$search = ' LIKE ' . $db->quote('%' . $jInput->getString('input', '') . '%');
		$query  = $db->getQuery(true);

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
							$db->qn('id', 'id'),
							$db->qn('name', 'text')
						)
					)
						->from($db->qn('#__redshop_manufacturer'))
						->where($db->qn('name') . $search);
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
					. ', ' . $db->qn('u.username') . ', ' . $db->quote(')') . ',' . $db->quote(' - ') . ',' . $db->qn('uf.phone') . ') AS text',
					$db->qn('u.email', $emailLabel),
					$db->qn('uf.phone', 'phone')
				)
			)
				->from($db->qn('#__users', 'u'))
				->leftJoin($db->qn('#__redshop_users_info', 'uf') . ' ON uf.user_id = u.id')
				->where('(' . $db->qn('u.username') . $search
					. ' OR ' . $db->qn('uf.firstname') . $search
					. ' OR ' . $db->qn('uf.lastname') . $search
					. ' OR ' . $db->qn('uf.phone') . $search . ')')
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
						. ' OR ' . $db->qn('uf.lastname') . $search . ')'
					)
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
						. ' OR ' . $db->qn('uf.company_name') . $search . ')'
					)
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
					. ' OR ' . $db->qn('p.product_number') . $search . ')'
				);
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
					. ' OR ' . $db->qn('p.product_number') . $search . ')'
				);
		}
		else
		{
			if ($accessoryList = $jInput->getString('accessoryList', ''))
			{
				$accessoryList = explode(',', $accessoryList);
				$accessoryList = Joomla\Utilities\ArrayHelper::toInteger($accessoryList);
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
					. ' OR ' . $db->qn('p.product_number') . $search . ')'
				);
		}

		$json = new stdClass;
		$db->setQuery($query)->execute();
		$json->total = $db->getNumRows();

		if ($json->total != 0)
		{
			$limit      = $jInput->getInt('limit', 10);
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
		$this->_id   = $id;
	}

	public function getData()
	{
		if ($this->_alert == 'termsarticle')
		{
			return $this->_buildQuery();
		}

		$query       = $this->_buildQuery();

		return $this->_getList($query);
	}

	public function _buildQuery()
	{
		$db    = $this->getDbo();
		
		if ($this->_media_section)
		{
			if ($this->_media_section == 'product')
			{
				$query = $db->getQuery(true)
					->select($db->qn('product_id','id'))
					->select($db->qn('product_name','value'))
					->from($db->qn('#__redshop_product', 'p'))
					->where($db->qn('p.product_name') . ' LIKE ' . $db->q('%' . $this->_search . '%'));
			}
			elseif ($this->_media_section == 'category')
			{
				$query = $db->getQuery(true)
					->select($db->qn('id','id'))
					->select($db->qn('name','value'))
					->from($db->qn('#__redshop_category', 'cat'))
					->where($db->qn('cat.name') . ' LIKE ' . $db->q('%' . $this->_search . '%'));
			}
			else
			{
				$query = $db->getQuery(true)
					->select($db->qn('catalog_id','id'))
					->select($db->qn('catalog_name','value'))
					->from($db->qn('#__redshop_catalog', 'log'))
					->where('published = 1')
					->where($db->qn('log.catalog_name') . ' LIKE ' . $db->q('%' . $this->_search . '%'));
			}
		}
		elseif ($this->_alert == 'voucher')
		{
			$query = $db->getQuery(true)
				->select($db->qn('cp.product_id','value'))
				->select($db->qn('p.product_name','text'))
				->from($db->qn('#__redshop_product', 'p'))
				->leftjoin(
					$db->qn('#__redshop_product_voucher_xref', 'cp')
					. ' ON ' . $db->qn('cp.product_id') . ' = ' . $db->qn('p.product_id')
				)
				->where($db->qn('cp.voucher_id') . ' = ' . $db->q( (int) $this->_voucher_id) );

			$this->_db->setQuery($query);
			$this->_productdata = $this->_db->loadObjectList();

			$query = $db->getQuery(true)
				->select('DISTINCT p.product_id AS id')
				->select($db->qn('p.product_name', 'value'))
				->from($db->qn('#__redshop_product','p'))
				->leftjoin(
					$db->qn('#__redshop_product_voucher_xref', 'cp')
					. ' ON ' . $db->qn('cp.product_id') . ' = ' . $db->qn('p.product_id')
				);

			if (count($this->_productdata) > 0)
			{
				foreach ($this->_productdata as $rc)
				{
					$pid[] = $rc->value;
				}

				$query->where($db->qn('p.product_id') . ' NOT IN (' . implode("," , $pid ) . ')')
					->where($db->qn('p.product_name') . ' LIKE ' . $db->q('%' . $this->_search . '%'));
			}
			else
			{
				$query->where($db->qn('p.product_name') . ' LIKE ' . $db->q('%' . $this->_search . '%'));
			}
		}
		elseif ($this->_alert == 'termsarticle')
		{
			$query = $db->getQuery(true)
				->select($db->qn('a.sectionid'))
				->select($db->qn('a.catid'))
				->select($db->qn('a.id','value'))
				->select($db->qn('a.title','text'))
				->from($db->qn('#__content', 'a'))
				->where('a.state = 1')
				->where($db->qn('a.title') . ' LIKE ' . $db->q('%' . $this->_search . '%'));

			$this->_db->setQuery($query);
			$rows    = $this->_db->loadObjectList();
			$article = array();

			for ($j = 0, $jn = count($rows); $j < $jn; $j++)
			{
				if ($rows[$j]->sectionid != 0 && $rows[$j]->catid != 0)
				{
					$query = $db->getQuery(true)
						->select($db->qn('a.id','value'))
						->select($db->qn('a.title','text'))
						->from($db->qn('#__content', 'a'))
						->leftjoin(
							$db->qn('#__categories', 'cc')
							. ' ON ' . $db->qn('cc.id') . ' = ' . $db->qn('a.catid')
						)
						->leftjoin(
							$db->qn('#__sections', 's')
							. ' ON ' . $db->qn('s.id') . ' = ' . $db->qn('cc.section')
						)
						->leftjoin(
							$db->qn('#__groups', 'g')
							. ' ON ' . $db->qn('a.access') . ' = ' . $db->qn('g.id')
						)
						->where('a.state = 1')
						->where('cc.published = 1')
						->where('s.published = 1')
						->where($db->qn('s.scope' . ' = ' . $db->quote('content')))
						->where($db->qn('a.title') . ' LIKE ' . $db->q('%' . $this->_search . '%'));

					$this->_db->setQuery($query);
					$r = $this->_db->loadObjectList();
					$i = 0;

					foreach ($r as $value)
					{
						$article[$i]->value = $value->text;
						$article[$i]->id    = $value->value;
						$i++;
					}
				}
				else
				{
					$article[$j]->value = $rows[$j]->text;
					$article[$j]->id    = $rows[$j]->value;
				}
			}

			return $article;
		}
		elseif ($this->_user == 1)
		{
			$query = $db->getQuery(true)
						->select($db->qn('u.id', 'id'))
						->select(
							'CONCAT(' . $db->qn('uf.firstname') . ','
							. $db->quote(' ') . ','
							. $db->qn('uf.lastname') . ','
							. $db->quote(' ( ') . ','
							. $db->qn('u.username') . ','
							. $db->quote(')') . ') AS ' . $db->qn('value')
						)
						->select($db->qn('u.email', 'volume'))
						->from($db->qn('#__redshop_users_info', 'uf'))
						->leftjoin(
							$db->qn('#__users', 'u')
							. ' ON ' . $db->qn('uf.user_id') . ' = ' . $db->qn('u.id')
						)
						->orwhere($db->qn('u.username') . ' LIKE ' . $db->q('%' . $this->_search . '%'))
						->orwhere($db->qn('uf.firstname') . ' LIKE ' . $db->q('%' . $this->_search . '%'))
						->orwhere($db->qn('uf.lastname') . ' LIKE ' . $db->q('%' . $this->_search . '%'))
						->where($db->qn('uf.address_type') . ' LIKE ' . $db->quote('BT'));
		}
		elseif ($this->_plgcustomview == 1)
		{
			if ($this->_iscompany == 0)
			{
				$query = $db->getQuery(true)
					->select($db->qn('u.id', 'id'))
					->select(
						'CONCAT(' . $db->qn('uf.firstname') . ','
						. $db->quote(' ') . ','
						. $db->qn('uf.lastname') . ','
						. $db->quote(' ( ') . ','
						. $db->qn('u.username') . ','
						. $db->quote(')') . ') AS ' . $db->qn('value')
					)
					->select($db->qn('u.email', 'volume'))
					->from($db->qn('#__redshop_users_info', 'uf'))
					->leftjoin(
						$db->qn('#__users', 'u')
						. ' ON ' . $db->qn('uf.user_id') . ' = ' . $db->qn('u.id')
					)
					->orwhere($db->qn('u.username') . ' LIKE ' . $db->q('%' . $this->_search . '%'))
					->orwhere($db->qn('uf.firstname') . ' LIKE ' . $db->q('%' . $this->_search . '%'))
					->orwhere($db->qn('uf.lastname') . ' LIKE ' . $db->q('%' . $this->_search . '%'))
					->where($db->qn('uf.address_type') . ' LIKE ' . $db->q('BT'))
					->where($db->qn('uf.is_company') . ' = ' . $db->q( (int) $this->_iscompany));
			}

			if ($this->_iscompany == 1)
			{
				$query = $db->getQuery(true)
					->select($db->qn('u.id', 'id'))
					->select(
						'CONCAT(' . $db->qn('uf.firstname') . ','
						. $db->quote(' ') . ','
						. $db->qn('uf.lastname') . ','
						. $db->quote(' ( ') . ','
						. $db->qn('u.username') . ','
						. $db->quote(')') . ') AS ' . $db->qn('value')
					)
					->select($db->qn('u.email', 'volume'))
					->from($db->qn('#__redshop_users_info', 'uf'))
					->leftjoin(
						$db->qn('#__users', 'u')
						. ' ON ' . $db->qn('uf.user_id') . ' = ' . $db->qn('u.id')
					)
					->orwhere($db->qn('u.username') . ' LIKE ' . $db->q('%' . $this->_search . '%'))
					->orwhere($db->qn('uf.company_name') . ' LIKE ' . $db->q('%' . $this->_search . '%'))
					->where($db->qn('uf.address_type') . ' LIKE ' . $db->quote('BT'))
					->where($db->qn('uf.is_company') . ' = ' . $db->quote($this->_iscompany));
			}
		}
		elseif ($this->_addreduser == 1)
		{
			$query = $db->getQuery(true)
				->select($db->qn('uf.user_id', 'id'))
				->select(
					'CONCAT(' . $db->qn('uf.firstname') . ','
					. $db->quote(' ') . ','
					. $db->qn('uf.lastname') . ','
					. $db->quote(' IF( ') . ','
					. $db->qn('u.username')  . ' != ' . $db->q('') . ','
					. 'CONCAT(' . $db->qn('u.username') . ' ) ,'
					. $db->quote(')') . ') AS ' . $db->qn('value')
				)
				->select($db->qn('u.user_email', 'value_number'))
				->from($db->qn('#__redshop_users_info', 'uf'))
				->leftjoin(
					$db->qn('#__users', 'u')
					. ' ON ' . $db->qn('uf.user_id') . ' = ' . $db->qn('u.id')
				)
				->orwhere($db->qn('u.username') . ' LIKE ' . $db->q('%' . $this->_search . '%'))
				->orwhere($db->qn('uf.firstname') . ' LIKE ' . $db->q('%' . $this->_search . '%'))
				->orwhere($db->qn('uf.lastname') . ' LIKE ' . $db->q('%' . $this->_search . '%'))
				->where($db->qn('uf.address_type') . ' LIKE ' . $db->quote('BT'));
		}
		elseif ($this->_products == 1)
		{
			$query = $db->getQuery(true)
				->select($db->qn('p.product_id', 'id'))
				->select($db->qn('p.product_name', 'value'))
				->select($db->qn('p.product_number', 'value_number'))
				->from($db->qn('#__redshop_product','p'))
				->where($db->qn('p.product_name') . ' LIKE ' . $db->q('%' . $this->_search . '%'));
		}
		elseif ($this->_related == 1)
		{
			$query = $db->getQuery(true)
				->select($db->qn('p.product_id', 'id'))
				->select($db->qn('p.product_name', 'value'))
				->select($db->qn('p.product_number', 'value_number'))
				->from($db->qn('#__redshop_product','p'))
				->where($db->qn('p.product_name') . ' LIKE ' . $db->q('%' . $this->_search . '%'))
				->orwhere($db->qn('p.product_number') . ' LIKE ' . $db->q('%' . $this->_search . '%'));
				
			if ($this->_product_id != 0)
			{
				$query = $db->getQuery(true)
					->select($db->qn('related_id'))
					->from($db->qn('#__redshop_product_related'))
					->where($db->qn('product_id') . ' = ' . $db->q((int) $this->_product_id));

				$this->_db->setQuery($query);

				$related                  = $this->_db->loadColumn();
				$related[count($related)] = $this->_product_id;
				
				$query->where($db->qn('p.product_id') . ' NOT IN (' . implode("," , $related ) . ')');
				
			}
			
			$query->setLimit(50,0);
		}
		elseif ($this->_parent == 1)
		{
			$query = $db->getQuery(true)
				->select($db->qn('p.product_id', 'id'))
				->select($db->qn('p.product_name', 'value'))
				->from($db->qn('#__redshop_product','p'))
				->where($db->qn('p.product_name') . ' LIKE ' . $db->q('%' . $this->_search . '%'));

			if ($this->_product_id != 0)
			{
				$query->where($db->qn('p.product_id') . ' NOT IN (' . $this->_product_id . ')');
			}

			$query->setLimit(50,0);
		}
		elseif ($this->_navigator == 1)
		{
			$query = $db->getQuery(true)
				->select($db->qn('p.product_id', 'id'))
				->select($db->qn('p.product_name', 'value'))
				->select($db->qn('p.product_number', 'value_number'))
				->select($db->qn('p.product_price', 'price'))
				->from($db->qn('#__redshop_product','p'))
				->where('p.published = 1')
				->where($db->qn('p.product_name') . ' LIKE ' . $db->q('%' . $this->_search . '%'))
				->orwhere($db->qn('p.product_number') . ' LIKE ' . $db->q('%' . $this->_search . '%'));
		}
		else
		{
			$query = $db->getQuery(true)
				->select('DISTINCT p.product_id AS id')
				->select($db->qn('p.product_name', 'value'))
				->select($db->qn('p.product_number', 'value_number'))
				->select($db->qn('p.product_price', 'price'))
				->from($db->qn('#__redshop_product','p'))
				->leftjoin(
					$db->qn('#__redshop_product_accessory', 'cp')
					. ' ON ' . $db->qn('cp.product_id') . ' = ' . $db->qn('p.product_id')
				);

			if ($this->_product_id != 0)
			{
				$subQuery = $db->getQuery(true)
					->select($db->qn('child_product_id'))
					->from($db->qn('#__redshop_product_accessory'))
					->where($db->qn('product_id') . ' = ' . $db->q( $this->_product_id ));

				$query->where($db->qn('p.product_id') . ' NOT IN (' . $subQuery . ')')
					->where($db->qn('p.product_id') . ' != ' . $db->q( (int) $this->_product_id ))
					->where($db->qn('p.product_name') . ' LIKE ' . $db->q('%' . $this->_search . '%'))
					->where($db->qn('p.product_number') . ' LIKE ' . $db->q('%' . $this->_search . '%'));
			}
			else
			{
				$query->where($db->qn('p.product_name') . ' LIKE ' . $db->q('%' . $this->_search . '%'))
					->where($db->qn('p.product_number') . ' LIKE ' . $db->q('%' . $this->_search . '%'));
			}
		}

		return $query;
	}
}
