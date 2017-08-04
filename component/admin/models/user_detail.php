<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;



class RedshopModelUser_detail extends RedshopModel
{
	public $_id = null;

	public $_uid = null;

	public $_data = null;

	public $_table_prefix = null;

	public $_pagination = null;

	public $_copydata = null;

	public $_context = null;

	public function __construct()
	{
		$app = JFactory::getApplication();
		parent::__construct();

		$this->_table_prefix = '#__redshop_';
		$this->_context = 'order_id';

		$array      = $app->input->get('cid', 0, 'array');
		$this->_uid = $app->input->get('user_id', 0);

		$limit = $app->getUserStateFromRequest($this->_context . 'limit', 'limit', $app->getCfg('list_limit'), 0);
		$limitstart = $app->getUserStateFromRequest($this->_context . 'limitstart', 'limitstart', 0);

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
		$this->setId((int) $array[0]);
	}

	public function setId($id)
	{
		$this->_id = $id;
		$this->_data = null;
	}

	public function &getData()
	{
		if ($this->_loadData())
		{
		}
		else
		{
			$this->_initData();
		}

		return $this->_data;
	}

	public function _loadData()
	{
		if (empty($this->_data))
		{
			$this->_uid = 0;
			$query = 'SELECT * FROM ' . $this->_table_prefix . 'users_info AS uf '
				. 'LEFT JOIN #__users as u on u.id = uf.user_id '
				. 'WHERE users_info_id="' . $this->_id . '" ';
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();

			if (isset($this->_data->user_id))
			{
				$this->_uid = $this->_data->user_id;
			}

			if (count($this->_data) > 0 && !$this->_data->email)
			{
				$this->_data->email = $this->_data->user_email;
			}
			return (boolean) $this->_data;
		}
		return true;
	}

	public function _initData()
	{
		$data = JFactory::getApplication()->getUserState('com_redshop.user_detail.data');

		if (!empty($data))
		{
			$this->_data = (object) $data;

			return (boolean) $this->_data;
		}
		elseif (empty($this->_data))
		{
			$detail = new stdClass;

			$detail->users_info_id         = 0;
			$detail->user_id               = 0;
			$detail->id                    = 0;
			$detail->gid                   = null;
			$detail->name                  = null;
			$detail->username              = null;
			$detail->email                 = null;
			$detail->password              = null;
			$detail->usertype              = null;
			$detail->block                 = null;
			$detail->sendEmail             = null;
			$detail->registerDate          = null;
			$detail->lastvisitDate         = null;
			$detail->activation            = null;
			$detail->is_company            = null;
			$detail->firstname             = null;
			$detail->lastname              = null;
			$detail->contact_info          = null;
			$detail->address_type          = null;
			$detail->company_name          = null;
			$detail->vat_number            = null;
			$detail->tax_exempt            = 0;
			$detail->country_code          = null;
			$detail->state_code            = null;
			$detail->shopper_group_id      = null;
			$detail->published             = 1;
			$detail->address               = null;
			$detail->city                  = null;
			$detail->zipcode               = null;
			$detail->phone                 = null;
			$detail->requesting_tax_exempt = 0;
			$detail->tax_exempt_approved   = 0;
			$detail->approved              = 1;
			$detail->ean_number            = null;
			$detail->state_code_ST         = null;

			$jinput   = JFactory::getApplication()->input;
			$info_id  = $jinput->get('info_id', 0);
			$shipping = $jinput->get('shipping', 0);

			if ($shipping)
			{
				$query = 'SELECT * FROM ' . $this->_table_prefix . 'users_info AS uf '
					. 'LEFT JOIN #__users as u on u.id = uf.user_id '
					. 'WHERE users_info_id="' . $info_id . '" ';
				$this->_db->setQuery($query);
				$bill_data = $this->_db->loadObject();

				$detail->id = $detail->user_id = $this->_uid = $bill_data->user_id;
				$detail->email = $bill_data->user_email;
				$detail->is_company = $bill_data->is_company;
				$detail->company_name = $bill_data->company_name;
				$detail->vat_number = $bill_data->vat_number;
				$detail->tax_exempt = $bill_data->tax_exempt;
				$detail->shopper_group_id = $bill_data->shopper_group_id;
				$detail->requesting_tax_exempt = $bill_data->requesting_tax_exempt;
				$detail->tax_exempt_approved = $bill_data->tax_exempt_approved;
				$detail->ean_number = $bill_data->ean_number;
			}

			$this->_data = $detail;

			return (boolean) $this->_data;
		}

		return true;
	}

	public function storeUser($post)
	{
		$post['createaccount'] = (isset($post['username']) && $post['username'] != "") ? 1 : 0;
		$post['user_email'] = $post['email1'] = $post['email'];

		$post['billisship'] = 1;

		if ($post['createaccount'])
		{
			$joomlauser = RedshopHelperJoomla::createJoomlaUser($post);
		}
		else
		{
			$joomlauser = RedshopHelperJoomla::updateJoomlaUser($post);
		}

		if (!$joomlauser)
		{
			return false;
		}

		$reduser = RedshopHelperUser::storeRedshopUser($post, $joomlauser->id, 1);

		return $reduser;
	}

	public function store($post)
	{
		$shipping = isset($post["shipping"]) ? true : false;
		$post['createaccount'] = (isset($post['username']) && $post['username'] != "") ? 1 : 0;
		$post['user_email'] = $post['email1'] = $post['email'];

		if ($shipping)
		{
			$post['country_code_ST'] = $post['country_code'];
			$post['state_code_ST'] = $post['state_code'];
			$post['firstname_ST'] = $post['firstname'];
			$post['lastname_ST'] = $post['lastname'];
			$post['address_ST'] = $post['address'];
			$post['city_ST'] = $post['city'];
			$post['zipcode_ST'] = $post['zipcode'];
			$post['phone_ST'] = $post['phone'];

			$reduser = RedshopHelperUser::storeRedshopUserShipping($post);
		}
		else
		{
			$post['billisship'] = 1;
			$joomlauser = RedshopHelperJoomla::updateJoomlaUser($post);

			if (!$joomlauser)
			{
				return false;
			}
			$reduser = RedshopHelperUser::storeRedshopUser($post, $joomlauser->id, 1);
		}

		return $reduser;
	}

	/**
	 * Delete redSHOP and Joomla! users
	 *
	 * @param   array  $cid                Array of user ids
	 * @param   bool   $deleteJoomlaUsers  Delete Joomla! users
	 *
	 * @return bool
	 *
	 * @since version
	 */
	public function delete($cid = array(), $deleteJoomlaUsers = false)
	{
		if (count($cid))
		{
			$db = JFactory::getDbo();
			$cids = implode(',', $cid);

			$queryDefault = $db->getQuery(true)
					->delete($db->qn('#__redshop_users_info'))
					->where($db->qn('users_info_id') . ' IN (' . $cids . ' )');

			if ($deleteJoomlaUsers)
			{
				$queryAllUserIds = $db->getQuery(true)
							->select($db->qn('id'))
							->from($db->qn('#__users'));
				$allUserIds = $db->setQuery($queryAllUserIds)->loadColumn();

				$queryCustom = $db->getQuery(true)
						->select($db->qn('user_id'))
						->from($db->qn('#__redshop_users_info'))
						->where($db->qn('users_info_id') . ' IN (' . $cids . ' )')
						->where($db->qn('user_id') . ' IN (' . implode(',', $allUserIds) . ' )')
						->group($db->qn('user_id'));

				$joomlaUserIds = $db->setQuery($queryCustom)->loadColumn();

				foreach ($joomlaUserIds as $joomlaUserId)
				{
					$joomlaUser = JFactory::getUser($joomlaUserId);

					// Skip this user whom in Super Administrator group.
					if ($joomlaUser->authorise('core.admin'))
					{
						continue;
					}

					$user = JFactory::getUser($joomlaUserId);

					if ($user->guest)
					{
						continue;
					}

					if (!$user->delete())
					{
						$this->setError($user->getError());

						return false;
					}
				}
			}

			$db->setQuery($queryDefault);

			if (!$db->execute())
			{
				$this->setError($db->getErrorMsg());

				return false;
			}
		}

		return true;
	}

	public function publish($cid = array(), $publish = 1)
	{
		if (count($cid))
		{
			$cids = implode(',', $cid);

			$query = 'UPDATE ' . $this->_table_prefix . 'users_info '
				. 'SET approved=' . intval($publish) . ' '
				. 'WHERE user_id IN ( ' . $cids . ' ) ';
			$this->_db->setQuery($query);

			if (!$this->_db->execute())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}

		return true;
	}

	public function validate_user($user, $uid)
	{
		$query = "SELECT username FROM #__users WHERE username='" . $user . "' AND id !=" . $uid;
		$this->_db->setQuery($query);
		$users = $this->_db->loadObjectList();

		return count($users);
	}

	public function validate_email($email, $uid)
	{
		$query = "SELECT email FROM #__users WHERE email = '" . $email . "' AND id !=" . $uid;
		$this->_db->setQuery($query);
		$emails = $this->_db->loadObjectList();

		return count($emails);
	}

	public function userOrders()
	{
		$query = $this->_buildUserorderQuery();
		$list = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));

		return $list;
	}

	public function _buildUserorderQuery()
	{
		$query = "SELECT * FROM `" . $this->_table_prefix . "orders` "
			. "WHERE `user_id`='" . $this->_uid . "' "
			. "ORDER BY order_id DESC ";

		return $query;
	}

	public function getTotal()
	{
		if ($this->_id)
		{
			$query = $this->_buildUserorderQuery();
			$this->_total = $this->_getListCount($query);

			return $this->_total;
		}
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
}
