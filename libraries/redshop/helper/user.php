<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Class Redshop Helper Stock Room
 *
 * @since  1.5
 */
class RedshopHelperUser
{
	/**
	 * Shopper Group information
	 *
	 * @var  array
	 */
	protected static $userShopperGroupData = array();

	/**
	 * Shopper Group information
	 *
	 * @var  array
	 */
	protected static $shopperGroupData = array();

	/**
	 * Users Info
	 *
	 * @var  array
	 */
	protected static $redshopUserInfo = array();

	/**
	 * Total Sales of user
	 *
	 * @var  array
	 */
	protected static $totalSales = array();

	/**
	 * Get redshop user information
	 *
	 * @param   int    $userId         Id joomla user
	 * @param   string $addressType    Type user address BT (Billing Type) or ST (Shipping Type)
	 * @param   int    $userInfoId     Id redshop user
	 * @param   bool   $useAddressType Select user info relate with address type
	 * @param   bool   $force          Force to get user information from DB instead of cache
	 *
	 * @return  object  Redshop user information
	 */
	public static function getUserInformation($userId = 0, $addressType = 'BT', $userInfoId = 0, $useAddressType = true, $force = false)
	{
		if (0 == $userId && 0 == $userInfoId)
		{
			$userId     = JFactory::getUser()->id;
			$auth       = JFactory::getSession()->get('auth');
			$userInfoId = $auth['users_info_id'];
		}

		// If both is not set return, as we also have silent user creating where joomla user id is not set
		if (!$userId && !$userInfoId)
		{
			return (new stdClass);
		}

		if (!$useAddressType)
		{
			$addressType = 'NA';
		}
		elseif ($addressType == '')
		{
			$addressType = 'BT';
		}

		$key = $userId . '.' . $addressType . '.' . $userInfoId;

		if (!array_key_exists($key, self::$redshopUserInfo) || $force)
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select(array('sh.*', 'u.*'))
				->from($db->qn('#__redshop_users_info', 'u'))
				->leftJoin($db->qn('#__redshop_shopper_group', 'sh') . ' ON sh.shopper_group_id = u.shopper_group_id');

			// Not necessary that all user is registered with joomla id. We have silent user creation too.
			if ($userId)
			{
				$query->where('u.user_id = ' . (int) $userId);
			}

			if ($useAddressType)
			{
				$query->where('u.address_type = ' . $db->quote($addressType));
			}

			if ($userInfoId)
			{
				$query->where('u.users_info_id = ' . (int) $userInfoId);
			}

			self::$redshopUserInfo[$key] = $db->setQuery($query)->loadObject();
		}

		return self::$redshopUserInfo[$key];
	}

	/**
	 * Create redshop user session
	 *
	 * @param   int $userId Joomla user id
	 *
	 * @return  array|mixed
	 */
	public static function createUserSession($userId = 0)
	{
		$session = JFactory::getSession();
		$userArr = $session->get('rs_user');
		$order_functions = order_functions::getInstance();

		if (!$userId)
		{
			$userId = JFactory::getUser()->id;
		}

		if (empty($userArr))
		{
			$userArr = array();
		}

		$userArr['rs_userid'] = $userId;

		if ($userId)
		{
			$userArr['rs_is_user_login'] = 1;

			if (!isset($userArr['rs_user_info_id']))
			{
				$userInformation = self::getUserInformation($userId);
				$shippingAddress = $order_functions->getShippingAddress($userId);

				if (count($shippingAddress) > 0 && Redshop::getConfig()->get('CALCULATE_VAT_ON') == 'ST')
				{
					$users_info_id = $shippingAddress[0]->users_info_id;
					$userInformation = self::getUserInformation($userId, 'ST', $users_info_id);
				}

				$userArr['rs_user_info_id'] = isset($userInformation->users_info_id) ? $userInformation->users_info_id : 0;
			}
		}
		else
		{
			$userArr['rs_is_user_login'] = 0;
		}

		$userArr['rs_user_shopperGroup'] = self::getShopperGroup($userId);
		$session->set('rs_user', $userArr);

		return $userArr;
	}

	/**
	 * Replace Conditional tag from Redshop tax
	 *
	 * @param   integer $userId User identifier
	 *
	 * @return  integer            User group
	 */
	public static function getShopperGroup($userId = 0)
	{
		if (0 == $userId)
		{
			$auth = JFactory::getSession()->get('auth');

			if (is_array($auth) && array_key_exists('users_info_id', $auth))
			{
				$userId -= $auth['users_info_id'];
			}
		}

		$shopperGroupId = Redshop::getConfig()->get('SHOPPER_GROUP_DEFAULT_UNREGISTERED');

		if ($userId)
		{
			$shopperGroupData = self::getShopperGroupData($userId);

			if (count($shopperGroupData) > 0)
			{
				$shopperGroupId = $shopperGroupData->shopper_group_id;
			}
		}

		return $shopperGroupId;
	}

	/**
	 * Get Shopper Group Data
	 *
	 * @param   int $userId User id
	 *
	 * @return mixed
	 */
	public static function getShopperGroupData($userId = 0)
	{
		if ($userId == 0)
		{
			$user   = JFactory::getUser();
			$userId = $user->id;
		}

		if ($userId != 0)
		{
			if (!array_key_exists($userId, self::$userShopperGroupData))
			{
				$db    = JFactory::getDbo();
				$query = $db->getQuery(true)
					->select('sg.*')
					->from($db->qn('#__redshop_shopper_group', 'sg'))
					->leftJoin($db->qn('#__redshop_users_info', 'ui') . ' ON ui.shopper_group_id = sg.shopper_group_id')
					->where('ui.user_id = ' . (int) $userId)
					->where('ui.address_type = ' . $db->q('BT'));
				$db->setQuery($query);
				self::$userShopperGroupData[$userId] = $db->loadObject();

				if (!self::$userShopperGroupData[$userId])
				{
					self::$userShopperGroupData[$userId] = array();
				}
			}

			return self::$userShopperGroupData[$userId];
		}

		return array();
	}

	/**
	 * Get Shopper Group Data using shopper group id
	 *
	 * @param   int $id Shopper Group Id
	 *
	 * @return mixed
	 */
	public static function getShopperGroupDataById($id)
	{
		if (!array_key_exists($id, self::$shopperGroupData))
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select('sg.*')
				->from($db->qn('#__redshop_shopper_group', 'sg'))
				->where('sg.shopper_group_id = ' . (int) $id);
			$db->setQuery($query);
			self::$shopperGroupData[$id] = $db->loadObject();
		}

		return self::$shopperGroupData[$id];
	}

	/**
	 * Total sale of customer
	 *
	 * @param   integer $userInfoId The user info id
	 *
	 * @return  float                 Total Number of sale for user.
	 */
	public static function totalSales($userInfoId)
	{
		if (array_key_exists($userInfoId, self::$totalSales))
		{
			return self::$totalSales[$userInfoId];
		}

		// Initialiase variables.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('SUM(order_total)')
			->from($db->qn('#__redshop_orders'))
			->where($db->qn('user_info_id') . ' = ' . (int) $userInfoId);

		// Set the query and load the result.
		$total = $db->setQuery($query)->loadResult();

		// Check for a database error.
		if ($db->getErrorNum())
		{
			JError::raiseWarning(500, $db->getErrorMsg());

			return null;
		}

		if (!$total)
		{
			$total = 0;
		}

		self::$totalSales[$userInfoId] = $total;

		return $total;
	}

	/**
	 * This function is used to check if the 'username' already exist in the database with any other ID
	 *
	 * @param   string $username User name
	 * @param   int    $id       User Id
	 *
	 * @return  int
	 *
	 * @since   2.0.0.6
	 */
	public static function validateUser($username, $id = 0)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('COUNT(id)')
			->from($db->qn('#__users'))
			->where($db->qn('username') . ' = ' . $db->q($username))
			->where($db->qn('id') . ' != ' . (int) $id);

		return $db->setQuery($query)->loadResult();
	}

	/**
	 * This function is used to check if the 'email' already exist in the database with any other ID
	 *
	 * @param   string $email User mail
	 * @param   int    $id    User Id
	 *
	 * @return  int
	 *
	 * @since   2.0.0.6
	 */
	public static function validateEmail($email, $id = 0)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('COUNT(id)')
			->from($db->qn('#__users'))
			->where($db->qn('email') . ' = ' . $db->q($email))
			->where($db->qn('id') . ' != ' . (int) $id);

		return $db->setQuery($query)->loadResult();
	}

	/**
	 * Get User groups
	 *
	 * @param   integer $userId User identifier
	 *
	 * @return  array             Array of user groups
	 *
	 * @since   2.0.6
	 */
	public static function getUserGroups($userId = 0)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('u.group_id'))
			->from($db->qn('#__redshop_users_info', 'uf'))
			->leftJoin($db->qn('#__user_usergroup_map', 'u') . ' ON ' . $db->qn('u.user_id') . ' = ' . $db->qn('uf.user_id'))
			->where($db->qn('uf.users_info_id') . ' = ' . (int) $userId);

		return $db->setQuery($query)->loadColumn();
	}

	/**
	 * Method for update term & conditions of user.
	 *
	 * @param   int $userInfoId RedSHOP User ID
	 * @param   int $isSet      Is set?
	 *
	 * @return  void
	 *
	 * @since  2.0.6
	 */
	public static function updateUserTermsCondition($userInfoId = 0, $isSet = 0)
	{
		$userInfoId = (int) $userInfoId;

		if (!$userInfoId)
		{
			return;
		}

		// One id is mandatory ALWAYS
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->update($db->qn('#__redshop_users_info'))
			->set($db->qn('accept_terms_conditions') . ' = ' . (int) $isSet)
			->where($db->qn('users_info_id') . ' = ' . (int) $userInfoId);

		$db->setQuery($query)->execute();
	}

	/**
	 * Get VAT User information
	 *
	 * @param   integer $userId User ID
	 *
	 * @return  object
	 *
	 * @since   2.0.6
	 */
	public static function getVatUserInformation($userId = 0)
	{
		// Let's create a common user session first.
		self::createUserSession();

		$user = JFactory::getUser();

		if ($userId == 0)
		{
			$userId = $user->id;
		}

		$session = JFactory::getSession();

		if ($userId)
		{
			$userData        = $session->get('rs_user');
			$userInformation = self::getUserInformation($userId);

			if (count($userInformation) > 0)
			{
				$userData['rs_user_info_id'] = isset($userInformation->users_info_id) ? $userInformation->users_info_id : 0;
				$session->set('rs_user', $userData);

				if (!$userInformation->country_code)
				{
					$userInformation->country_code = Redshop::getConfig()->get('DEFAULT_VAT_COUNTRY');
				}

				if (!$userInformation->state_code)
				{
					$userInformation->state_code = Redshop::getConfig()->get('DEFAULT_VAT_STATE');
				}

				/*
				 *  VAT_BASED_ON = 0 // webshop mode
				 *  VAT_BASED_ON = 1 // Customer mode
				 *  VAT_BASED_ON = 2 // EU mode
				 */
				if (Redshop::getConfig()->get('VAT_BASED_ON') != 2 && Redshop::getConfig()->get('VAT_BASED_ON') != 1)
				{
					$userInformation->country_code = Redshop::getConfig()->get('DEFAULT_VAT_COUNTRY');
					$userInformation->state_code   = Redshop::getConfig()->get('DEFAULT_VAT_STATE');
				}
			}
			else
			{
				$userInformation               = new stdClass;
				$userInformation->country_code = Redshop::getConfig()->get('DEFAULT_VAT_COUNTRY');
				$userInformation->state_code   = Redshop::getConfig()->get('DEFAULT_VAT_STATE');
			}
		}
		else
		{
			$auth        = $session->get('auth');
			$userInforId = $auth['users_info_id'];

			$userInformation               = new stdClass;
			$userInformation->country_code = Redshop::getConfig()->get('DEFAULT_VAT_COUNTRY');
			$userInformation->state_code   = Redshop::getConfig()->get('DEFAULT_VAT_STATE');

			if ($userInforId && (Redshop::getConfig()->get('REGISTER_METHOD') == 1 || Redshop::getConfig()->get('REGISTER_METHOD') == 2)
				&& (Redshop::getConfig()->get('VAT_BASED_ON') == 2 || Redshop::getConfig()->get('VAT_BASED_ON') == 1))
			{
				$db              = JFactory::getDbo();
				$query           = $db->getQuery(true)
					->select($db->qn('country_code'))
					->select($db->qn('state_code'))
					->from($db->qn('#__redshop_users_info', 'u'))
					->leftJoin($db->qn('#__redshop_shopper_group', 'sh') . ' ON ' . $db->qn('sh.shopper_group_id') . ' = ' . $db->qn('u.shopper_group_id'))
					->where($db->qn('u.users_info_id') . ' = ' . $userInforId)
					->order($db->qn('u.users_info_id'));
				$userInformation = $db->setQuery($query)->loadObject();
			}
		}

		return $userInformation;
	}
}
