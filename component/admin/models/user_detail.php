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

require_once JPATH_SITE . '/administrator/components/com_redshop/helpers/mail.php';
require_once JPATH_SITE . '/components/com_redshop/helpers/extra_field.php';
require_once JPATH_SITE . '/components/com_redshop/helpers/user.php';

class user_detailModeluser_detail extends JModel
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

		$array = JRequest::getVar('cid', 0, '', 'array');
		$this->_uid = JRequest::getVar('user_id', 0);

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
		if (empty($this->_data))
		{
			$detail = new stdClass;

			$detail->users_info_id = 0;
			$detail->user_id = 0;
			$detail->id = 0;
			$detail->gid = null;
			$detail->name = null;
			$detail->username = null;
			$detail->email = null;
			$detail->password = null;
			$detail->usertype = null;
			$detail->block = null;
			$detail->sendEmail = null;
			$detail->registerDate = null;
			$detail->lastvisitDate = null;
			$detail->activation = null;
			$detail->is_company = null;
			$detail->firstname = null;
			$detail->lastname = null;
			$detail->contact_info = null;
			$detail->address_type = null;
			$detail->company_name = null;
			$detail->vat_number = null;
			$detail->tax_exempt = 0;
			$detail->country_code = null;
			$detail->state_code = null;
			$detail->shopper_group_id = null;
			$detail->published = 1;
			$detail->address = null;
			$detail->city = null;
			$detail->zipcode = null;
			$detail->phone = null;
			$detail->requesting_tax_exempt = 0;
			$detail->tax_exempt_approved = 0;
			$detail->approved = 1;
			$detail->ean_number = null;

			$info_id = JRequest::getVar('info_id', 0);
			$shipping = JRequest::getVar('shipping', 0);

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

	/*
	 * joomla user table entry
	 *
	 * @ specially developed !!! do not delete
	 * @ it canbe use in redSHOP with diffrent purpose
	 * @ author: gunjan
	 */
	public function storeUser_bk($post)
	{

		$app = JFactory::getApplication();
		$redshopMail = new redshopMail;

		// Start data into user table
		// Initialize some variables
		$db = JFactory::getDBO();
		$me = JFactory::getUser();
		$acl = JFactory::getACL();

		// Create a new JUser object
		$user = new JUser($post['id']);
		$original_gid = $user->get('gid');

		$post['name'] = (isset($post['name'])) ? $post['name'] : $post['username'];

		// Changed for shipping code moved out of condition
		if (!$user->bind($post))
		{
			$app->enqueueMessage(JText::_('COM_REDSHOP_CANNOT_SAVE_THE_USER_INFORMATION'), 'message');
			$app->enqueueMessage($user->getError(), 'error');

			return false;
		}

		$objectID = $acl->get_object_id('users', $user->get('id'), 'ARO');
		$groups = $acl->get_object_groups($objectID, 'ARO');
		$this_group = strtolower($acl->get_group_name($groups[0], 'ARO'));

		if ($user->get('id') == $me->get('id') && $user->get('block') == 1)
		{
			$msg = JText::_('COM_REDSHOP_YOU_CANNOT_BLOCK_YOURSELF');
			$app->enqueueMessage($msg, 'message');

			return false;
		}
		elseif (($this_group == 'super administrator') && $user->get('block') == 1)
		{
			$msg = JText::_('COM_REDSHOP_YOU_CANNOT_BLOCK_A_SUPER_ADMINISTRATOR');
			$app->enqueueMessage($msg, 'message');

			return false;
		}
		elseif (($this_group == 'administrator') && ($me->get('gid') == 24) && $user->get('block') == 1)
		{
			$msg = JText::_('COM_REDSHOP_WARNBLOCK');
			$app->enqueueMessage($msg, 'message');

			return false;
		}
		elseif (($this_group == 'super administrator') && ($me->get('gid') != 25))
		{
			$msg = JText::_('COM_REDSHOP_YOU_CANNOT_EDIT_A_SUPER_ADMINISTRATOR_ACCOUNT');
			$app->enqueueMessage($msg, 'message');

			return false;
		}

		// Are we dealing with a new user which we need to create?
		$isNew = ($user->get('id') < 1);

		if (!$isNew)
		{
			// If group has been changed and where original group was a Super Admin
			if ($user->get('gid') != $original_gid && $original_gid == 25)
			{
				// Count number of active super admins
				$query = 'SELECT COUNT( id )'
					. ' FROM #__users'
					. ' WHERE gid = 25'
					. ' AND block = 0';
				$db->setQuery($query);
				$count = $db->loadResult();

				if ($count <= 1)
				{
					// Disallow change if only one Super Admin exists
					$this->setRedirect('index.php?option=' . $option . '&view=user', JText::_('COM_REDSHOP_WARN_ONLY_SUPER'));

					return false;
				}
			}
		}

		/*
	 	 * Lets save the JUser object
	 	 */
		if (!$user->save())
		{
			$app->enqueueMessage(JText::_('COM_REDSHOP_CANNOT_SAVE_THE_USER_INFORMATION'), 'message');
			$app->enqueueMessage($user->getError(), 'error');

			return false;
		}

		/*
	 	 * Time for the email magic so get ready to sprinkle the magic dust...
	 	 */
		if ($isNew)
		{
			$redshopMail->sendRegistrationMail($post);
		}

		// If updating self, load the new user object into the session
		if ($user->get('id') == $me->get('id'))
		{
			// Get an ACL object
			$acl = JFactory::getACL();

			// Get the user group from the ACL
			$grp = $acl->getAroGroup($user->get('id'));

			// Mark the user as logged in
			$user->set('guest', 0);
			$user->set('aid', 1);

			// Fudge Authors, Editors, Publishers and Super Administrators into the special access group
			if ($acl->is_group_child_of($grp->name, 'Registered')
				|| $acl->is_group_child_of($grp->name, 'Public Backend')
			)
			{
				$user->set('aid', 2);
			}

			// Set the usertype based on the ACL group name
			$user->set('usertype', $grp->name);

			$session = JFactory::getSession();
			$session->set('user', $user);
		}

		// End data into user table
		return $user;
	}

	public function storeUser($post)
	{

		$userhelper = new rsUserhelper;

		$shipping = isset($post["shipping"]) ? true : false;
		$post['createaccount'] = (isset($post['username']) && $post['username'] != "") ? 1 : 0;
		$post['user_email'] = $post['email1'] = $post['email'];


		$post['billisship'] = 1;

		if ($post['createaccount'])
		{
			$joomlauser = $userhelper->createJoomlaUser($post);
		}
		else
		{
			$joomlauser = $userhelper->updateJoomlaUser($post);
		}

		if (!$joomlauser)
		{
			return false;
		}

		$reduser = $userhelper->storeRedshopUser($post, $joomlauser->id, 1);

		return $reduser;
	}

	public function store($post)
	{
		$userhelper = new rsUserhelper;

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

			$reduser = $userhelper->storeRedshopUserShipping($post);
		}
		else
		{
			$post['billisship'] = 1;
			$joomlauser = $userhelper->updateJoomlaUser($post);

			if (!$joomlauser)
			{
				return false;
			}
			$reduser = $userhelper->storeRedshopUser($post, $joomlauser->id, 1);
		}

		return $reduser;
	}

	public function delete($cid = array())
	{
		if (count($cid))
		{
			$cids = implode(',', $cid);
			$query = 'DELETE FROM ' . $this->_table_prefix . 'users_info WHERE users_info_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);

			if (!$this->_db->query())
			{
				$this->setError($this->_db->getErrorMsg());

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

			if (!$this->_db->query())
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
