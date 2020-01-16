<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopModelSearch extends RedshopModel
{
	protected $id = null;
	
	protected $stockRoomId = null;
	
	protected $data = null;
	
	protected $search = '';
	
	protected $product = null;
	
	protected $template = null;
	
	protected $limit = null;
	
	protected $isCompany = -1;
	
	protected $alert = null;
	
	protected $mediaSection = '';
	
	protected $voucherId = 0;
	
	protected $user = 0;
	
	protected $addRedUser = null;
	
	protected $products = null;
	
	protected $related = 0;
	
	protected $productId = 0;
	
	protected $parent = 0;
	
	protected $navigator = 0;
	
	protected $plgCustomView = 0;
	
	public function __construct()
	{
		parent::__construct();

		if (!empty($jinput = JFactory::getApplication()->input))
		{
			$this->isCompany = $jinput->getInt('iscompany', -1);

			$this->parent = $jinput->get('parent', 0);

			$this->limit = $jinput->get('limit', '');

			$this->search = $jinput->get('input', null);

			$this->alert = $jinput->get('alert', '');

			$this->id = ((int) $jinput->get('id', 0));

			$this->stockRoomId = ((int) $jinput->get('stockroom_id', 0));

			$this->productId = ((int) $jinput->get('product_id', 0));

			$this->related = ((int) $jinput->get('related', 0));

			$this->navigator = ((int) $jinput->get('navigator', 0));

			$this->voucherId = ((int) $jinput->get('voucher_id', 0));

			$this->mediaSection = $jinput->get('media_section', '');

			$this->user = ((int) $jinput->get('user', 0));

			$this->plgCustomView = $jinput->get('plgcustomview', 0);

			$this->addRedUser = $jinput->get('addreduser', '');

			$this->products = $jinput->get('isproduct', '');
		}
	}

	public function search()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$db     = JFactory::getDbo();

		if($jInput = JFactory::getApplication()->input)
		{
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
					->from($db->qn("#__redshop_product_voucher_xref", 'cp'))
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

		$json = new stdClass;
		$json->result = '';

		return json_encode($json);
	}

	public function setId($id)
	{
		$this->id   = $id;
	}

	public function getData()
	{
		if ($this->alert == 'termsarticle')
		{
			return $this->_buildQuery();
		}

		$query       = $this->_buildQuery();

		return $this->_getList($query);
	}

	public function _buildQuery()
	{
		$result = '';

		if ($this->mediaSection)
		{
			$result = RedshopHelperSearch::buildQueryMediaSection($this->mediaSection,$this->search);
		}
		elseif ($this->alert == 'voucher')
		{
			$result = RedshopHelperSearch::buildQueryAlertVoucher($this->voucherId,$this->search);
		}
		elseif ($this->alert == 'termsarticle')
		{
			$result = RedshopHelperSearch::buildQueryAlertTermsArticle($this->search);
		}
		elseif ($this->user == 1)
		{
			$result = RedshopHelperSearch::buildQueryIsUser($this->search);
		}
		elseif ($this->plgCustomView == 1)
		{
			if ($this->isCompany == 0)
			{
				$result = RedshopHelperSearch::buildQueryIsCompanyFalse($this->search,$this->isCompany);
			}

			if ($this->isCompany == 1)
			{
				$result = RedshopHelperSearch::buildQueryIsCompanyTrue($this->search,$this->isCompany);
			}
		}
		elseif ($this->addRedUser == 1)
		{
			$result = RedshopHelperSearch::buildQueryAddRedUser($this->search);
		}
		elseif ($this->products == 1)
		{
			$result = RedshopHelperSearch::buildQueryProductTrue($this->search);
		}
		elseif ($this->related == 1)
		{
			$result = RedshopHelperSearch::buildQueryRelatedTrue($this->search,$this->productId);
		}
		elseif ($this->parent == 1)
		{
			$result = RedshopHelperSearch::buildQueryParentTrue($this->search,$this->productId);
		}
		elseif ($this->navigator == 1)
		{
			$result = RedshopHelperSearch::buildQueryNavigatorTrue($this->search);
		}
		else
		{
			$result = RedshopHelperSearch::buildQueryNavigatorFalse($this->search,$this->productId);
		}

		return $result;
	}
}
