<?php
/**
 * @package     RedShop
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Redshop\Plugin\Import;

JLoader::import('redshop.library');

/**
 * Plugin redSHOP Import User
 *
 * @since  1.0
 */
class PlgRedshop_ImportUser extends Import\AbstractBase
{
	/**
	 * @var string
	 *
	 * @since  1.0
	 */
	protected $primaryKey = 'users_info_id';

	/**
	 * @var string
	 *
	 * @since  1.0
	 */
	protected $nameKey = 'email';

	/**
	 * Event run when user load config for export this data.
	 *
	 * @return  string
	 *
	 * @since  1.0.0
	 */
	public function onAjaxUser_Config()
	{
		RedshopHelperAjax::validateAjaxRequest();

		// Ajax response
		$this->config();
	}

	/**
	 * Event run when run importing.
	 *
	 * @return  mixed
	 *
	 * @since  1.0.0
	 */
	public function onAjaxUser_Import()
	{
		RedshopHelperAjax::validateAjaxRequest();

		return $this->import();
	}

	/**
	 * Method for get table object.
	 *
	 * @return  \JTable
	 *
	 * @since   1.0.0
	 */
	public function getTable()
	{
		JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_redshop/tables');

		return JTable::getInstance('User_detail', 'Table');
	}

	/**
	 * Process import data.
	 *
	 * @param   \JTable  $table  Header array
	 * @param   array    $data   Data array
	 *
	 * @return  boolean
	 *
	 * @since   1.0.0
	 */
	public function processImport($table, $data)
	{
		$db = $this->db;
		$shopperGroups = $this->getShopperGroupInfo();

		if (array_key_exists(trim($data['shopper_group_name']), $shopperGroups->name))
		{
			$shopperGroupId = $shopperGroups->name[trim($data['shopper_group_name'])]
				->shopper_group_id;
		}
		// Create new shopper group
		else
		{
			$shopper = JTable::getInstance('Shopper_group_detail', 'Table');
			$shopper->load();
			$shopper->shopper_group_name          = trim($data['shopper_group_name']);
			$shopper->shopper_group_customer_type = 1;
			$shopper->shopper_group_portal        = 0;
			$shopper->store();

			// Get inserted shopper group id
			$shopperGroupId = $shopper->shopper_group_id;
		}

		$userInfo = $this->getUsersInfoByEmail();
		$csvRSUserId = 0;

		if (isset($data['users_info_id']))
		{
			$csvRSUserId = (int) trim($data['users_info_id']);
		}

		// Setting default for new users
		$jUserId    = 0;
		$newRedUser = true;
		$redUserId  = $csvRSUserId;

		// Using email to map users as unique
		if (isset($userInfo[trim($data['email'])]))
		{
			$usersInfo = $userInfo[trim($data['email'])];

			// Joomla User
			$jUserId = $usersInfo->id;

			$redUserId = (int) $usersInfo->users_info_id;

			// Redshop User
			// @todo: review this condition 0 != $csvRSUserId && $csvRSUserId == $usersInfo->users_info_id
			if ($redUserId)
			{
				$newRedUser = false;
			}
		}

		// Update/Create Joomla User
		$user = JUser::getInstance($jUserId);

		$jUserInfo = array(
			'username'     => trim($data['username']),
			'name'         => trim($data['name']),
			'email'        => trim($data['email']),
			'groups'       => explode(',', trim($data['usertype'])),
			'registerDate' => JFactory::getDate()->toSql()
		);

		if (isset($data['block']))
		{
			$jUserInfo['block'] = (int) $data['block'];
		}

		if (isset($data['sendEmail']))
		{
			$jUserInfo['sendEmail'] = (int) $data['sendEmail'];
		}

		if (isset($data['password']) && '' != trim($data['password']))
		{
			$jUserInfo['password'] = trim($data['password']);
			$jUserInfo['password2'] = trim($data['password']);
		}

		// Bind the data.
		if (!$user->bind($jUserInfo))
		{
			$this->setError($user->getError());

			return false;
		}

		// Save user information
		if ($user->save())
		{
			// Assign user id from table
			$jUserId = $user->id;
		}

		if (!empty($data['email']))
		{
			$data['user_email'] = $data['email'];
		}

		$data['user_id'] = $jUserId;
		$data['address_type'] = 'BT';
		$data['shopper_group_id'] = $shopperGroupId;
		$isNew = false;

		if (array_key_exists($this->primaryKey, $data) && $data[$this->primaryKey])
		{
			$isNew = $table->load($data[$this->primaryKey]);
		}

		if (!$table->bind($data))
		{
			return false;
		}

		if ((!$isNew && !$db->insertObject('#__redshop_users_info', $table, $this->primaryKey)) || !$table->store())
		{
			return false;
		}

		return true;
	}

	/**
	 * Get all users information
	 *
	 * @return  array  User email id as a key of an array
	 *
	 * @since  1.0.0
	 */
	private function getUsersInfoByEmail()
	{
		$db    = $this->db;
		$query = $db->getQuery(true);

		// Create the base select statement.
		$query->select('*')
			->from($db->qn('#__users', 'u'))
			->leftjoin(
				$db->qn('#__redshop_users_info', 'ui')
				. ' ON ' . $db->qn('u.email') . ' = ' . $db->qn('ui.user_email')
				. ' AND ' . $db->qn('ui.address_type') . '=' . $db->q('BT')
			);

		return $db->setQuery($query)->loadObjectList('email');
	}

	/**
	 * Get Shopper Group Id from input
	 *
	 * @return  object  Shopper Group object
	 *
	 * @since  1.0.0
	 */
	public function getShopperGroupInfo()
	{
		// Initialiase variables.
		$db    = $this->db;
		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__redshop_shopper_group'));

		// Set the query and load the result.
		$db->setQuery($query);

		try
		{
			$shopperGroups        = new stdClass;
			$shopperGroups->index = $db->loadObjectList();
			$shopperGroups->name  = $db->loadObjectList('shopper_group_name');
		}
		catch (RuntimeException $e)
		{
			throw new RuntimeException($e->getMessage(), $e->getCode());
		}

		return $shopperGroups;
	}
}
