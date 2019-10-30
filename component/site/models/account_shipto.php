<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Class RedshopModelAccount_Shipto
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 * @since       1.0
 */
class RedshopModelAccount_Shipto extends RedshopModel
{
	/**
	 * @var integer
	 */
	public $_id = null;

	/**
	 * @var mixed
	 */
	public $_data = null;

	/**
	 * @var null|string
	 */
	public $_table_prefix = null;

	/**
	 * RedshopModelAccount_Shipto constructor.
	 *
	 * @throws Exception
	 */
	public function __construct()
	{
		parent::__construct();

		$this->_table_prefix = '#__redshop_';
		$infoid              = JFactory::getApplication()->input->getInt('infoid');

		$this->setId($infoid);
	}

	/**
	 * Method for set Id
	 *
	 * @param   integer $id ID
	 *
	 * @return  void
	 */
	public function setId($id)
	{
		$this->_id   = $id;
		$this->_data = null;
	}

	/**
	 * Method for get data
	 *
	 * @return mixed|null
	 */
	public function &getData()
	{
		if (!$this->_loadData())
		{
			$this->_initData();
		}

		return $this->_data;
	}

	/**
	 * Method for init data
	 *
	 * @return  boolean
	 */
	public function _initData()
	{
		if (empty($this->_data))
		{
			$detail = new stdClass;

			$detail->users_info_id = 0;
			$detail->user_id       = 0;
			$detail->firstname     = null;
			$detail->lastname      = null;
			$detail->company_name  = null;
			$detail->address       = null;
			$detail->state_code    = null;
			$detail->country_code  = null;
			$detail->city          = null;
			$detail->zipcode       = null;
			$detail->phone         = 0;

			$this->_data = $detail;

			return true;
		}

		return true;
	}

	/**
	 * Method for load data
	 *
	 * @param   integer $userInfoId User infor Id
	 *
	 * @return  mixed
	 */
	public function _loadData($userInfoId = 0)
	{
		if ($userInfoId)
		{
			return RedshopEntityUser::getInstance((int) $userInfoId)->getItem();
		}

		if (empty($this->_data))
		{
			$this->_data = RedshopEntityUser::getInstance((int) $this->_id)->getItem();

			return $this->_data;
		}

		return true;
	}

	/**
	 * Method for delete shipping data
	 *
	 * @param   integer  $infoid  Infor data
	 *
	 * @return  boolean
	 */
	public function delete($infoid)
	{
		// Init variables.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->delete($db->qn('#__redshop_users_info'))
			->where($db->qn('users_info_id') . ' = ' . (int) $infoid);

		// Set the query and execute the delete.
		return $db->setQuery($query)->execute();
	}

	/**
	 * Method for store shipping data
	 *
	 * @param   array  $post  Infor data
	 *
	 * @return  boolean|Tableuser_detail
	 * @throws  Exception
	 */
	public function store($post)
	{
		$post['user_email'] = $post['email1'] = $post['email'];

		return RedshopHelperUser::storeRedshopUserShipping($post);
	}
}
