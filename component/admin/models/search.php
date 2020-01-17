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

		if (!empty($input = JFactory::getApplication()->input))
		{
			$this->isCompany = $input->getInt('iscompany', -1);

			$this->parent = $input->get('parent', 0);

			$this->limit = $input->get('limit', '');

			$this->search = $input->get('input', null);

			$this->alert = $input->get('alert', '');

			$this->id = ((int) $input->get('id', 0));

			$this->stockRoomId = ((int) $input->get('stockroom_id', 0));

			$this->productId = ((int) $input->get('product_id', 0));

			$this->related = ((int) $input->get('related', 0));

			$this->navigator = ((int) $input->get('navigator', 0));

			$this->voucherId = ((int) $input->get('voucher_id', 0));

			$this->mediaSection = $input->get('media_section', '');

			$this->user = ((int) $input->get('user', 0));

			$this->plgCustomView = $input->get('plgcustomview', 0);

			$this->addRedUser = $input->get('addreduser', '');

			$this->products = $input->get('isproduct', '');
		}
	}

	public function search()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		if ($input = JFactory::getApplication()->input)
		{
			$db    = JFactory::getDbo();
			$resultQuery = $db->getQuery(true);

			if (!empty($input->getString('media_section', '')))
			{
				$resultQuery = RedshopHelperSearch::buildQuerySwichCaseMediaSection($input->getString('media_section', ''), $input->getString('input', ''));
			}
			elseif ($input->getString('alert', '') == 'container')
			{
				$resultQuery = RedshopHelperSearch::buildQueryAlertContainer($input->getString('input', ''), $input->getInt('container_id', 0));
			}
			elseif ($input->getString('alert', '') == 'voucher')
			{
				$resultQuery = RedshopHelperSearch::buildQueryAlertVoucherSearch($input->getInt('voucher_id', 0), $input->getString('input', ''));
			}
			elseif ($input->getString('alert', '') == 'stoockroom')
			{
				$resultQuery = RedshopHelperSearch::buildQueryAlertStoockroomSearch($input->getString('input', ''), $input->getInt('stockroom_id', 0));
			}
			elseif ($input->getString('alert', '') == 'termsarticle')
			{
				$resultQuery = RedshopHelperSearch::buildQueryAlertTermsArticleSearch($input->getString('input', ''));
			}
			elseif ($input->getInt('user', 0) == 1 || $input->getInt('addreduser', 0) == 1)
			{
				if ($input->getInt('addreduser', 0) == 1)
				{
					$emailLabel = 'value_number';
				}
				else
				{
					$emailLabel = 'volume';
				}
				
				$resultQuery = RedshopHelperSearch::buildQueryAddRedUserSearch($input->getString('input', ''), $emailLabel);
			}
			elseif ($input->getInt('plgcustomview', 0) == 1)
			{
				$iscompany = $input->getInt('iscompany', -1);
				
				if ($iscompany == 0)
				{
					$resultQuery = RedshopHelperSearch::buildQueryIsCompanyFalseSearch($input->getString('input', ''));
				}
				elseif ($iscompany == 1)
				{
					$resultQuery = RedshopHelperSearch::buildQueryIsCompanyTrueSearch($input->getString('input', ''));
				}
			}
			elseif ($input->getInt('isproduct', 0) == 1)
			{
				$resultQuery = RedshopHelperSearch::buildQueryIsProductTrueSearch($input->getString('input', ''));
			}
			elseif ($input->getInt('related', 0) == 1)
			{
				$resultQuery = RedshopHelperSearch::buildQueryIsRelatedTrueSearch($input->getString('input', ''), $input->getInt('product_id', 0));
			}
			elseif ($input->getInt('parent', 0) == 1)
			{
				if ($product_id = $input->getInt('product_id', 0))
				{
					$resultQuery->where($db->qn('p.product_id') . ' != ' . $product_id);
				}

				$resultQuery = RedshopHelperSearch::buildQueryIsParentTrueSearch($input->getString('input', ''));
			}
			elseif ($input->getInt('navigator', 0) == 1)
			{
				$resultQuery = RedshopHelperSearch::buildQueryIsParentTrueSearch($input->getString('input', ''));
			}
			else
			{
				if ($accessoryList = $input->getString('accessoryList', ''))
				{
					$accessoryList = explode(',', $accessoryList);
					$accessoryList = Joomla\Utilities\ArrayHelper::toInteger($accessoryList);
					$resultQuery->where('p.product_id NOT IN (' . implode(',', $accessoryList) . ')');
				}

				if ($product_id = $input->getInt('product_id', 0))
				{
					$resultQuery->leftJoin($db->qn('#__redshop_product_accessory', 'pa') . ' ON pa.child_product_id = p.product_id AND pa.product_id = ' . $product_id)
						->where('pa.accessory_id IS NULL')
						->where($db->qn('p.product_id') . ' != ' . $product_id);
				}

				$resultQuery->select(
					array(
						$db->qn('p.product_id', 'id'),
						'CONCAT(' . $db->qn('p.product_name') . ', " (", ' . $db->qn('p.product_number') . ', ")") as text',
						$db->qn('p.product_number', 'value_number'),
						$db->qn('p.product_price', 'price')
					)
				)
					->from($db->qn('#__redshop_product', 'p'))
					->where('('
						. $db->qn('p.product_name') . ' LIKE ' . $db->q('%' . $input->getString('input', '') . '%') . ' OR '
						. $db->qn('p.product_number') . ' LIKE ' . $db->q('%' . $input->getString('input', '') . '%')
						. ')');

				$json = new stdClass;
				$db->setQuery($resultQuery)->execute();
				$json->total = $db->getNumRows();

				if ($json->total != 0)
				{
					$limit      = $input->getInt('limit', 10);
					$limitStart = ($input->getInt('page', 1) - 1) * $limit;
					$db->setQuery($resultQuery, $limitStart, $limit);
					$json->result = $db->loadObjectList();
				}
				else
				{
					$json->result = '';
				}

				return json_encode($json);
			}

			$json         = new stdClass;
			$json->result = '';

			return json_encode($json);
		}
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

		$resultQueryuery       = $this->_buildQuery();

		return $this->_getList($resultQueryuery);
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
