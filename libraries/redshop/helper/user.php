<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Redshop\Economic\Economic as RedshopEconomic;

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
				$shippingAddress = RedshopHelperOrder::getShippingAddress($userId);

				if (count($shippingAddress) > 0 && Redshop::getConfig()->get('CALCULATE_VAT_ON') == 'ST')
				{
					$redshopUserInforId = $shippingAddress[0]->users_info_id;
					$userInformation    = self::getUserInformation($userId, 'ST', $redshopUserInforId);
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
	 * @param   integer  $userId  User identifier
	 *
	 * @return  integer            User group
	 */
	public static function getShopperGroup($userId = 0)
	{
		$shopperGroupData = self::getShopperGroupData($userId);

		if (!is_null($shopperGroupData))
		{
			return $shopperGroupData->shopper_group_id;
		}

		return Redshop::getConfig()->get('SHOPPER_GROUP_DEFAULT_UNREGISTERED');
	}

	/**
	 * Get Shopper Group Data
	 *
	 * @param   int  $userId  User id
	 *
	 * @return  mixed
	 */
	public static function getShopperGroupData($userId = 0)
	{
		$userId = !$userId ? JFactory::getUser()->id : $userId;

		// If user is guest. Try to get redshop user id.
		if (!$userId)
		{
			$auth = JFactory::getSession()->get('auth');

			if (is_array($auth) && array_key_exists('users_info_id', $auth))
			{
				$userId -= $auth['users_info_id'];
			}
		}

		// In case user doesn't not entered any information yet. Get from default config.
		if (!$userId)
		{
			$shopperGroupId = Redshop::getConfig()->get('SHOPPER_GROUP_DEFAULT_UNREGISTERED');

			return self::getShopperGroupDataById($shopperGroupId);
		}

		// In case user is not guest.
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
		}

		return self::$userShopperGroupData[$userId];
	}

	/**
	 * Get Shopper Group Data using shopper group id
	 *
	 * @param   int  $id  Shopper Group Id
	 *
	 * @return  null|object  Shopper group object data. Null if not found.
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopEntityShopper_Group instead.
	 */
	public static function getShopperGroupDataById($id)
	{
		return RedshopEntityShopper_Group::getInstance($id)->getItem();
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
	 * @return  integer
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
	 * @return  integer
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

			if ($userData['rs_user_info_id'] && Redshop::getConfig()->get('CALCULATE_VAT_ON') == 'ST')
			{
				$userInformation = self::getUserInformation($userId, '', $userData['rs_user_info_id'], false);
			}

			if (!empty((array) $userInformation))
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
				$db = JFactory::getDbo();

				$query = $db->getQuery(true)
					->select($db->qn('country_code'))
					->select($db->qn('state_code'))
					->from($db->qn('#__redshop_users_info', 'u'))
					->leftJoin(
						$db->qn('#__redshop_shopper_group', 'sh') . ' ON ' . $db->qn('sh.shopper_group_id') . ' = ' . $db->qn('u.shopper_group_id')
					)
					->where($db->qn('u.users_info_id') . ' = ' . $userInforId)
					->order($db->qn('u.users_info_id'));

				$userInformation = $db->setQuery($query)->loadObject();
			}
		}

		return $userInformation;
	}

	/**
	 * Method for store redshop user.
	 *
	 * @param   array   $data   User data.
	 * @param   integer $userId ID of user
	 * @param   integer $admin  Is admin user.
	 *
	 * @return  boolean|Tableuser_detail      RedshopTableUser if success. False otherwise.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function storeRedshopUser($data, $userId = 0, $admin = 0)
	{
		$app = JFactory::getApplication();

		$data['user_email']   = $data['email'] = $data['email1'];
		$data['name']         = $name = $data['firstname'];
		$data['address_type'] = 'BT';

		/** @var Tableuser_detail $row */
		$row   = JTable::getInstance('user_detail', 'Table');
		$isNew = true;

		if (isset($data['users_info_id']) && $data['users_info_id'] != 0)
		{
			$row->load($data['users_info_id']);

			$data["old_tax_exempt_approved"] = $row->tax_exempt_approved;

			$userId = $row->user_id;
			$isNew  = false;
		}
		else
		{
			$data['password'] = $app->input->post->get('password1', '', 'RAW');

			$isAdmin = $app->isAdmin();

			/*
			 * Set user shopper group in case:
			 *      1. User register at frontend.
			 *      2. User created in backend by super user but forget to set shopper group.
			 */
			if (!$isAdmin || ($isAdmin && empty($data['shopper_group_id'])))
			{
				$data['shopper_group_id'] = ($data['is_company'] == 1) ? (int) Redshop::getConfig()->get('SHOPPER_GROUP_DEFAULT_COMPANY', 2)
					: (int) Redshop::getConfig()->get('SHOPPER_GROUP_DEFAULT_PRIVATE', 1);
			}
		}

		if ($userId > 0)
		{
			$joomlaUser       = new JUser($userId);
			$data['username'] = $joomlaUser->username;
			$data['name']     = $joomlaUser->name;
			$data['email']    = $joomlaUser->email;
		}

		if (Redshop::getConfig()->get('SHOW_TERMS_AND_CONDITIONS') == 1 && isset($data['termscondition']) && $data['termscondition'] == 1)
		{
			$data['accept_terms_conditions'] = 1;
		}

		$row->user_id = $data['user_id'] = $userId;

		JPluginHelper::importPlugin('redshop_shipping');
		JPluginHelper::importPlugin('redshop_user');
		RedshopHelperUtility::getDispatcher()->trigger('onBeforeCreateRedshopUser', array(&$data, $isNew));

		if (!$row->bind($data))
		{
			JFactory::getApplication()->enqueueMessage($row->getError(), 'error');

			return false;
		}

		if (Redshop::getConfig()->get('USE_TAX_EXEMPT'))
		{
			if (!$admin && $row->is_company == 1)
			{
				$row->requesting_tax_exempt = $data['tax_exempt'];

				if ($row->requesting_tax_exempt == 1)
				{
					RedshopHelperMail::sendRequestTaxExemptMail($row, $data['username']);
				}
			}

			// Sending tax exempted mails (tax_exempt_approval_mail)
			if (!$isNew && $admin && isset($data["tax_exempt_approved"]) && $data["old_tax_exempt_approved"] != $data["tax_exempt_approved"])
			{
				$mailTemplate = $data["tax_exempt_approved"] == 1 ? 'tax_exempt_approval_mail' : 'tax_exempt_disapproval_mail';
				RedshopHelperMail::sendTaxExemptMail($mailTemplate, $data, $row->user_email);
			}
		}

		if (!$row->store())
		{
			JFactory::getApplication()->enqueueMessage($row->getError(), 'error');

			return false;
		}

		// Update user info id
		if (Redshop::getConfig()->get('ECONOMIC_INTEGRATION'))
		{
			if ($isNew)
			{
				$maxDebtor = RedshopEconomic::getMaxDebtorInEconomic();
				$maxDebtor = is_array($maxDebtor) ? $maxDebtor[0] : $maxDebtor;

				if ($row->users_info_id <= $maxDebtor)
				{
					$db = JFactory::getDbo();

					$query = $db->getQuery(true)
						->select('MAX(' . $db->qn('users_info_id') . ')')
						->from($db->qn('#__redshop_users_info'));

					$currentMax = $db->setQuery($query)->loadResult();
					$nextId     = $currentMax + 1;

					$query->clear()
						->update($db->qn('#__redshop_users_info'))
						->set($db->qn('users_info_id') . ' = ' . $nextId)
						->where($db->qn('users_info_id') . ' = ' . (int) $row->users_info_id);
					$db->setQuery($query)->execute();

					$alterQuery = 'ALTER TABLE ' . $db->qn('#__redshop_users_info') . ' AUTO_INCREMENT = ' . ($nextId + 1);
					$db->setQuery($alterQuery)->execute();

					$row->users_info_id = $nextId;
				}
			}

			RedshopEconomic::createUserInEconomic($row);

			if ($row->is_company && trim($row->ean_number) != '' && JError::isError(JError::getError()))
			{
				$msg = JText::_('PLEASE_ENTER_EAN_NUMBER');
				JError::raiseWarning('', $msg);

				return false;
			}
		}

		$session = JFactory::getSession();
		$auth    = $session->get('auth', array());

		$auth['users_info_id'] = $row->users_info_id;

		$session->set('auth', $auth);

		// For non-registered customers
		if (!$row->user_id)
		{
			$row->user_id = (0 - $row->users_info_id);
			$row->store();

			$jUserTable = JFactory::getUser();

			$jUserTable->set('username', $row->user_email);
			$jUserTable->set('email', $row->user_email);
			$jUserTable->set('usertype', 'Registered');
			$jUserTable->set('registerDate', JFactory::getDate()->toSql());

			$data['user_id']  = $row->user_id;
			$data['username'] = $row->user_email;
			$data['email']    = $row->user_email;
		}

		if (isset($data['newsletter_signup']) && $data['newsletter_signup'] == 1)
		{
			RedshopHelperNewsletter::subscribe($row->user_id, $data, 0, $isNew);
		}

		$useBillingAsShipping = !isset($data['billisship']) ? false : true;

		// Info: field_section 6 :User information
		RedshopHelperExtrafields::extraFieldSave($data, \Redshop\Extrafields\Helper::SECTION_USER_INFORMATIONS, $row->users_info_id);

		$extraFieldSection = !$row->is_company ?
			\Redshop\Extrafields\Helper::SECTION_PRIVATE_BILLING_ADDRESS : \Redshop\Extrafields\Helper::SECTION_COMPANY_BILLING_ADDRESS;

		// Store user billing data.
		RedshopHelperExtrafields::extraFieldSave($data, $extraFieldSection, $row->users_info_id);

		if (!$useBillingAsShipping)
		{
			RedshopHelperUser::storeRedshopUserShipping($data);
		}

		$registerMethod = Redshop::getConfig()->get('REGISTER_METHOD');

		if ($registerMethod != 1 && $isNew && $admin == 0)
		{
			if ($registerMethod != 2
				|| ($registerMethod == 2 && isset($data['createaccount']) && $data['createaccount'] == 1))
			{
				RedshopHelperMail::sendRegistrationMail($data);
			}
		}

		JPluginHelper::importPlugin('user');
		RedshopHelperUtility::getDispatcher()->trigger('onAfterCreateRedshopUser', array($data, $isNew));

		return $row;
	}

	/**
	 * Method for store user shipping data
	 *
	 * @param   array  $data  Available data.
	 *
	 * @return  boolean|Tableuser_detail  Table user if success. False otherwise.
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public static function storeRedshopUserShipping($data = array())
	{
		JPluginHelper::importPlugin('redshop_user');
		JPluginHelper::importPlugin('redshop_shipping');
		RedshopHelperUtility::getDispatcher()->trigger('onBeforeStoreRedshopUserShipping', array(&$data));

		/** @var Tableuser_detail $userTable */
		$userTable = JTable::getInstance('user_detail', 'Table');

		if (!$userTable->bind($data))
		{
			JFactory::getApplication()->enqueueMessage($userTable->getError(), 'error');

			return false;
		}

		$userTable->user_id               = $data['user_id'];
		$userTable->address_type          = 'ST';
		$userTable->country_code          = $data['country_code_ST'];
		$userTable->state_code            = (isset($data['state_code_ST'])) ? $data['state_code_ST'] : "";
		$userTable->firstname             = $data['firstname_ST'];
		$userTable->lastname              = $data['lastname_ST'];
		$userTable->address               = $data['address_ST'];
		$userTable->city                  = $data['city_ST'];
		$userTable->zipcode               = $data['zipcode_ST'];
		$userTable->phone                 = $data['phone_ST'];
		$userTable->user_email            = $data['user_email'];
		$userTable->tax_exempt            = $data['tax_exempt'];
		$userTable->requesting_tax_exempt = $data['requesting_tax_exempt'];
		$userTable->shopper_group_id      = $data['shopper_group_id'];
		$userTable->tax_exempt_approved   = $data['tax_exempt_approved'];
		$userTable->is_company            = $data['is_company'];

		if ($data['is_company'] == 1)
		{
			$userTable->company_name = $data['company_name'];
			$userTable->vat_number   = $data['vat_number'];
		}

		if (!$userTable->store())
		{
			JFactory::getApplication()->enqueueMessage($userTable->getError(), 'error');

			return false;
		}

		if ($data['is_company'] == 0)
		{
			// Info: field_section 14 :Customer shipping Address
			RedshopHelperExtrafields::extraFieldSave($data, \Redshop\Extrafields\Helper::SECTION_PRIVATE_SHIPPING_ADDRESS, $userTable->users_info_id);
		}
		else
		{
			// Info: field_section 15 :Company shipping Address
			RedshopHelperExtrafields::extraFieldSave($data, \Redshop\Extrafields\Helper::SECTION_COMPANY_SHIPPING_ADDRESS, $userTable->users_info_id);
		}

		return $userTable;
	}
}
