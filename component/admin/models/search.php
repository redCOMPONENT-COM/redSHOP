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
			$db     = JFactory::getDbo();
			$params = array(
				'mediaSection'  => $input->getString('media_section', ''),
				'alert'         => $input->getString('alert', ''),
				'user'          => $input->getInt('user', 0),
				'isCompany'     => $input->getInt('iscompany', -1),
				'plgCustomView' => $input->getInt('plgcustomview', 0),
				'addRedUser'    => $input->getInt('addreduser', 0),
				'related'       => $input->getInt('related', 0),
				'productId'     => $input->getInt('product_id', 0),
				'parent'        => $input->getInt('parent', 0),
				'navigator'     => 0,
				'input'         => $input->getString('input', ''),
				'voucherId'     => $input->getInt('voucher_id', 0),
				'containerId'   => $input->getInt('container_id', 0),
				'stockroomId'   => $input->getInt('stockroom_id', 0),
				'isProduct'     => $input->getInt('isproduct', 0),
				'accessoryList' => $input->getString('accessoryList', '')
			);

			$resultQuery = RedshopHelperSearch::getSearchQuery($params);

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
		return RedshopHelperSearch::getBuildQuery($this->mediaSection, $this->alert, $this->user, $this->plgCustomView, $this->isCompany = -1,
											$this->addRedUser, $this->products, $this->related, $this->productId, $this->parent,
											$this->navigator, $this->search, $this->voucherId );
	}
}
