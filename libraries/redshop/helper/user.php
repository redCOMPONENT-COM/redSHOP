<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
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
	 * Users Info
	 *
	 * @var  array
	 */
	protected static $redshopUserInfo = array();

	/**
	 * Get redshop user information
	 *
	 * @param   int     $userId          Id joomla user
	 * @param   string  $addressType     Type user address BT (Billing Type) or ST (Shipping Type)
	 * @param   int     $userInfoId      Id redshop user
	 * @param   bool    $useAddressType  Select user info relate with address type
	 * @param   bool    $force           Force to get user infromation DB instead of cache
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
			return array();
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
			$db = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select(array('sh.*', 'u.*'))
				->from($db->qn('#__redshop_users_info', 'u'))
				->leftJoin($db->qn('#__redshop_shopper_group', 'sh') . ' ON sh.shopper_group_id = u.shopper_group_id');

			// Not necessory that all user is registed with joomla id. We have silent user creation too.
			if ($userId)
			{
				$query->where('u.user_id = ' . (int) $userId);
			}

			if ($useAddressType)
			{
				$query->where('u.address_type = ' . $db->q($addressType));
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
	 * @param   int  $userId  Joomla user id
	 *
	 * @return  array|mixed
	 */
	public static function createUserSession($userId = 0)
	{
		$session = JFactory::getSession();
		$userArr = $session->get('rs_user');

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
	 * @param   integer  $userId  User identifier
	 *
	 * @return  integer            User group
	 */
	public static function getShopperGroup($userId = 0)
	{
		$session = JFactory::getSession();

		if (0 == $userId)
		{
			$auth = JFactory::getSession()->get('auth');

			if (is_array($auth) && array_key_exists('users_info_id', $auth))
			{
				$userId -= $auth['users_info_id'];
			}
		}

		// Get redCRM Contact person session array
		if ($session->get('isredcrmuser', false))
		{
			$db = JFactory::getDbo();
			$subQuery = $db->getQuery(true)
				->select('users_info_id')
				->from($db->qn('#__redcrm_contact_persons'))
				->where('cp_user_id = ' . (int) $userId);
			$query = $db->getQuery(true)
				->select('user_id')
				->from($db->qn('#__redshop_users_info'))
				->where('users_info_id IN (' . $subQuery . ')')
				->where('address_type = ' . $db->q('BT'));
			$userId = $db->setQuery($query)->loadResult();
		}

		$shopperGroupId = SHOPPER_GROUP_DEFAULT_UNREGISTERED;

		if ($userId)
		{
			$shopperGroupData = self::getShoppergroupData($userId);

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
	 * @param   int  $userId  User id
	 *
	 * @return mixed
	 */
	public static function getShopperGroupData($userId = 0)
	{
		if ($userId == 0)
		{
			$user = JFactory::getUser();
			$userId = $user->id;
		}

		if ($userId != 0)
		{
			if (!array_key_exists($userId, self::$userShopperGroupData))
			{
				$db = JFactory::getDbo();
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
}
