<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/mail.php';
require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/extra_field.php';
require_once JPATH_SITE . '/components/com_redshop/helpers/cart.php';
require_once JPATH_SITE . '/components/com_redshop/helpers/helper.php';

class rsUserhelper
{
	public $_table_prefix = null;

	public $_session = null;

	public $_userId = null;

	public $_shopperGroupData = null;

	public $_db = null;

	public $_shopper_group_id = null;

	public $_shopper_group_data = null;

	public function __construct()
	{
		$this->_table_prefix = '#__' . TABLE_PREFIX . '_';
		$this->_session      = JFactory::getSession();
		$this->_db           = JFactory::getDBO();
	}

	/**
	 * Replace Conditional tag from Redshop tax
	 *
	 * @param   integer  $user_id  User identifier
	 *
	 * @return  integer            User group
	 */
	public function getShopperGroup($user_id = 0)
	{
		// Get redCRM Contact person session array
		$isredcrmuser = $this->_session->get('isredcrmuser', false);

		if ($isredcrmuser)
		{
			$this->_db->setQuery("SELECT user_id FROM  " . $this->_table_prefix . "users_info WHERE users_info_id IN (SELECT users_info_id FROM #__redcrm_contact_persons WHERE cp_user_id = " . $user_id . ") and address_type='BT'");
			$user_id = $this->_db->loadResult();
		}

		$shopperGroupId = SHOPPER_GROUP_DEFAULT_UNREGISTERED;

		if ($user_id)
		{
			$shopperGroupData = $this->getShoppergroupData($user_id);

			if (count($shopperGroupData) > 0)
			{
				$shopperGroupId = $shopperGroupData->shopper_group_id;
			}
		}

		$this->_userId = $user_id;

		return $shopperGroupId;
	}

	/**
	 * Get User groups
	 *
	 * @param   integer  $user_id  User identifier
	 *
	 * @return  array              Array of user groups
	 */
	public function getUserGroupList($user_id = 0)
	{
		$query = 'SELECT group_id FROM ' . $this->_table_prefix . 'users_info AS uf '
			. 'LEFT JOIN #__user_usergroup_map as u on u.user_id = uf.user_id '
			. 'WHERE users_info_id="' . $user_id . '" ';
		$this->_db->setQuery($query);
		$usergroups = $this->_db->loadResultArray();

		return $usergroups;
	}

	public function updateUserTermsCondition($users_info_id = 0, $isSet = 0)
	{
		$and = '';

		if ($users_info_id != 0)
		{
			$and .= "AND users_info_id = '" . $users_info_id . "' ";
		}

		$query = "UPDATE " . $this->_table_prefix . "users_info "
			. "SET accept_terms_conditions='" . $isSet . "' "
			. "WHERE 1=1 "
			. $and;
		$this->_db->setQuery($query);
		$this->_db->Query();
	}

	public function getShoppergroupData($user_id = 0)
	{
		$list = array();
		$user = JFactory::getUser();

		if ($user_id == 0)
		{
			$user_id = $user->id;
		}

		if ($user_id != 0)
		{
			if (!$this->_shopperGroupData && $this->_userId != $user_id)
			{
				$query = "SELECT sg.* FROM " . $this->_table_prefix . "shopper_group AS sg "
					. "LEFT JOIN " . $this->_table_prefix . "users_info AS ui ON ui.shopper_group_id=sg.shopper_group_id "
					. "WHERE ui.user_id = '" . $user_id . "' and ui.address_type='BT' ORDER BY shopper_group_id DESC  LIMIT 0,1";
				$this->_db->setQuery($query);
				$list = $this->_shopperGroupData = $this->_db->loadObject();
			}
			else
			{
				$list = $this->_shopperGroupData;
			}

			$this->_userId = $user_id;
		}

		return $list;
	}

	public function getShopperGroupList($shopper_group_id = 0)
	{
		$and = '';

		if ($shopper_group_id != 0)
		{
			$and .= 'AND shopper_group_id="' . $shopper_group_id . '" ';
		}

		$query = 'SELECT sh.*, shopper_group_id AS value, shopper_group_name AS text FROM ' . $this->_table_prefix . 'shopper_group AS sh '
			. 'WHERE published=1 '
			. $and;
		$this->_db->setQuery($query);

		$list                    = $this->_shopper_group_data = $this->_db->loadObjectList();
		$this->_shopper_group_id = $shopper_group_id;

		return $list;
	}

	public function createUserSession($user_id)
	{
		$userArr = $this->_session->get('rs_user');

		if (empty($userArr))
		{
			$userArr              = array();
			$userArr['rs_userid'] = $user_id;
		}

		if (array_key_exists('rs_userid', $userArr))
		{
			if ($user_id != $userArr['rs_userid'])
			{
				$userArr['rs_userid'] = $user_id;
			}
		}
		else
		{
			$userArr['rs_userid'] = $user_id;
		}

		if ($user_id)
		{
			$userArr['rs_is_user_login'] = 1;
		}
		else
		{
			unset($userArr);
			$userArr                     = array();
			$userArr['rs_is_user_login'] = 0;
		}

		$userArr['rs_user_shopperGroup'] = $this->getShopperGroup($user_id);
		$this->_session->set('rs_user', $userArr);

		return $userArr;
	}

	public function validate_user($username, $id = 0)
	{
		$query = "SELECT username FROM #__users "
			. "WHERE username='" . $username . "' "
			. "AND id!='" . $id . "' ";
		$this->_db->setQuery($query);
		$users = $this->_db->loadObjectList();

		return count($users);
	}

	public function validate_email($email, $id = 0)
	{
		$query = "SELECT email FROM #__users "
			. "WHERE email = '" . $email . "' "
			. "AND id!='" . $id . "' ";
		$this->_db->setQuery($query);
		$emails = $this->_db->loadObjectList();

		return count($emails);
	}

	public function updateJoomlaUser($data)
	{
		$app = JFactory::getApplication();

		if (!$app->isAdmin())
		{
			if (REGISTER_METHOD == 1 || $data['user_id'] < 0)
			{
				$reduser->id = $data['user_id'];

				return $reduser;
				die();
			}
		}

		if ($app->isAdmin() && $data['user_id'] < 0 && isset($data['users_info_id']))
		{
			$reduser->id = $data['user_id'];

			return $reduser;
			die;
		}

		$app = JFactory::getApplication();
		$db  = JFactory::getDBO();
		$me  = JFactory::getUser();
		$acl = JFactory::getACL();

		$data['name'] = $name = $data['firstname'];

		if (trim($data['username']) == "") // && $registeruser==1)
		{
			JError::raiseWarning('', JText::_('EMPTY_USERNAME'));

			return false;
		}

		$countusername = $this->validate_user($data['username'], $data['user_id']);

		if ($countusername > 0)
		{
			JError::raiseWarning('', JText::_('USERNAME_ALREADY_EXISTS'));

			return false;
		}

		if (trim($data['email']) == "")
		{
			JError::raiseWarning('', JText::_('EMPTY_EMAIL'));

			return false;
		}

		$countemail = $this->validate_email($data['email'], $data['user_id']);

		if ($countemail > 0)
		{
			JError::raiseWarning('', JText::_('EMAIL_ALREADY_EXISTS'));

			return false;
		}

		// Get required system objects
		$user = new JUser($data['user_id']);


		if (!$user->bind($data))
		{
			JError::raiseError(500, $user->getError());

			return false;
		}

		// Initialise variables;
		$pk = $user->get('id');

		if ($user->get('block') && $pk == $me->id && !$me->block)
		{
			$this->setError(JText::_('YOU_CANNOT_BLOCK_YOURSELF!'));

			return false;
		}

		// Make sure that we are not removing ourself from Super Admin group
		$iAmSuperAdmin = $me->authorise('core.admin');

		if ($iAmSuperAdmin && $me->get('id') == $pk)
		{
			// Check that at least one of our new groups is Super Admin
			$stillSuperAdmin = false;
			$myNewGroups     = $user->groups;

			foreach ($myNewGroups as $group)
			{
				$stillSuperAdmin = ($stillSuperAdmin) ? ($stillSuperAdmin) : JAccess::checkGroup($group, 'core.admin');
			}

			if (!$stillSuperAdmin)
			{
				$this->setError(JText::_('COM_USERS_USERS_ERROR_CANNOT_DEMOTE_SELF'));

				return false;
			}
		}

		// If there was an error with registration, set the message and display form
		if (!$user->save())
		{
			JError::raiseWarning('', JText::_($user->getError()));

			return false;
		}

		return $user;
	}

	public function createJoomlaUser($data, $createuser = 0)
	{
		$app = JFactory::getApplication();

		$createaccount = (isset($data['createaccount']) && $data['createaccount'] == 1) ? 1 : 0;

		$data['password']  = JRequest::getVar('password1', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$data['password2'] = JRequest::getVar('password2', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$data['email']     = $data['email1'];
		$data['name']      = $name = $data['firstname'];

		// Do a password safety check
		if (REGISTER_METHOD == 3)
		{
			// Silent registration
			$better_token      = substr(uniqid(md5(rand()), true), 0, 10);
			$data['username']  = $data['email'];
			$data['password']  = $better_token;
			$data['password2'] = $better_token;
			JRequest::setVar('password1', $better_token);
		}

		$registeruser = 1;

		if (REGISTER_METHOD == 1 || (REGISTER_METHOD == 2 && $createaccount == 0))
		{
			$registeruser = 0;
		}

		if (trim($data['email']) == "")
		{
			JError::raiseWarning('', JText::_('COM_REDSHOP_EMPTY_EMAIL'));

			return false;
		}

		if ($registeruser == 1)
		{
			if (trim($data['username']) == "")
			{
				JError::raiseWarning('', JText::_('COM_REDSHOP_EMPTY_USERNAME'));

				return false;
			}

			$countusername = $this->validate_user($data['username']);

			if ($countusername > 0)
			{
				JError::raiseWarning('', JText::_('COM_REDSHOP_USERNAME_ALREADY_EXISTS'));

				return false;
			}

			$countemail = $this->validate_email($data['email']);

			if ($countemail > 0)
			{
				JError::raiseWarning('', JText::_('COM_REDSHOP_EMAIL_ALREADY_EXISTS'));

				return false;
			}
			if (trim($data['password']) == "")
			{
				JError::raiseWarning('', JText::_('COM_REDSHOP_EMPTY_PASSWORD'));

				return false;
			}
			if (strlen($data['password']) || strlen($data['password2']))
			{
				if ($data['password'] != $data['password2'])
				{
					JError::raiseWarning('', JText::_('COM_REDSHOP_PASSWORDS_DO_NOT_MATCH'));

					return false;
				}
			}

			// Get required system objects
			$user = clone(JFactory::getUser());

			// If user registration is not allowed, show 403 not authorized.
			$usersConfig = JComponentHelper::getParams('com_users');

			$usersConfig->set('allowUserRegistration', 1);

			if ($usersConfig->get('allowUserRegistration') == '0')
			{
				JError::raiseError(403, JText::_('ACCESS_FORBIDDEN'));

				return false;
			}

			if (!$user->bind($data))
			{
				JError::raiseError(500, $user->getError());

				return false;
			}

			$date = JFactory::getDate();
			$user->set('id', 0);
			$user->set('registerDate', $date->toMySQL());

			// If user activation is turned on, we need to set the activation information
			$useractivation = $usersConfig->get('useractivation');

			if ($useractivation == '1')
			{
				$user->set('activation', JUtility::getHash(JUserHelper::genRandomPassword()));
				$user->set('block', '0');
			}

			$user->set('name', $name);
			$user->name = $name;

			// If there was an error with registration, set the message and display form
			if (!$user->save())
			{
				JError::raiseWarning('', JText::_($user->getError()));

				return false;
			}

			$credentials             = array();
			$credentials['username'] = $data['username'];
			$credentials['password'] = $data['password2'];

			//preform the login action
			$app->login($credentials);

			return $user;
		}

		return true;
	}

	public function checkCaptcha($data)
	{
		if (SHOW_CAPTCHA)
		{
			$security_code = $_COOKIE['security_code'];

			// Unset copy
			setcookie('security_code', '');

			if (empty($security_code) || $security_code != $data['security_code'])
			{
				JError::raiseWarning(21, JText::_('COM_REDSHOP_INVALID_SECURITY'));

				return false;
			}
		}

		return true;
	}

	public function storeRedshopUser($data, $user_id = 0, $admin = 0)
	{
		$redshopMail = new redshopMail;
		$extra_field = new extra_field;
		$helper      = new redhelper;

		$data['user_email']   = $data['email'] = $data['email1'];
		$data['name']         = $name = $data['firstname'];
		$data['address_type'] = 'BT';

		$row = JTable::getInstance('user_detail', 'Table');

		if (isset($data['users_info_id']) && $data['users_info_id'] != 0)
		{
			$isNew = false;
			$row->load($data['users_info_id']);
			$data["old_tax_exempt_approved"] = $row->tax_exempt_approved;
			$user_id                         = $row->user_id;
		}
		else
		{
			$isNew            = true;
			$data['password'] = JRequest::getVar('password1', '', 'post', 'string', JREQUEST_ALLOWRAW);
			$app              = JFactory::getApplication();
			$is_admin         = $app->isAdmin();

			if ($data['is_company'] == 1)
			{
				if ($is_admin && $data['shopper_group_id'] != 0)
				{
					$data['shopper_group_id'] = $data['shopper_group_id'];
				}
				else
				{
					$data['shopper_group_id'] = (SHOPPER_GROUP_DEFAULT_COMPANY != 0) ? SHOPPER_GROUP_DEFAULT_COMPANY : 2;
				}
			}
			else
			{
				if ($is_admin && isset($data['shopper_group_id']) && $data['shopper_group_id'] != 0)
				{
					$data['shopper_group_id'] = $data['shopper_group_id'];
				}
				else
				{
					$data['shopper_group_id'] = (SHOPPER_GROUP_DEFAULT_PRIVATE != 0) ? SHOPPER_GROUP_DEFAULT_PRIVATE : 1;
				}
			}
		}
		if ($user_id > 0)
		{
			$joomlauser       = new JUser($user_id);
			$data['username'] = $joomlauser->username;
			$data['name']     = $joomlauser->name;
			$data['email']    = $joomlauser->email;
		}
		if (SHOW_TERMS_AND_CONDITIONS == 1 && isset($data['termscondition']) && $data['termscondition'] == 1)
		{
			$data['accept_terms_conditions'] = 1;
		}

		$row->user_id = $data['user_id'] = $user_id;

		if (!$row->bind($data))
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}
		if (USE_TAX_EXEMPT)
		{
			if (!$admin && $row->is_company == 1)
			{
				$row->requesting_tax_exempt = $data['tax_exempt'];

				if ($row->requesting_tax_exempt == 1)
				{
					$redshopMail->sendRequestTaxExemptMail($row, $data['username']);
				}
			}

			// Sending tax exempted mails (tax_exempt_approval_mail)
			if (!$isNew && $admin && isset($data["tax_exempt_approved"]) && $data["old_tax_exempt_approved"] != $data["tax_exempt_approved"])
			{
				if ($data["tax_exempt_approved"] == 1)
				{
					$redshopMail->sendTaxExemptMail("tax_exempt_approval_mail", $data, $row->user_email);
				}
				else
				{
					$redshopMail->sendTaxExemptMail("tax_exempt_disapproval_mail", $data, $row->user_email);
				}
			}
		}


		if (!$row->store())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		// Update user info id
		if (ECONOMIC_INTEGRATION)
		{
			$economic         = new economic;
			$original_info_id = $row->users_info_id;

			if ($isNew)
			{
				$maxDebtor = $economic->getMaxDebtorInEconomic();

				if (count($maxDebtor) > 0)
				{
					$maxDebtor = $maxDebtor[0];

					if ($row->users_info_id <= $maxDebtor)
					{
						$nextId = $maxDebtor + 1;
						$sql    = "UPDATE " . $this->_table_prefix . "users_info "
							. "SET users_info_id = '" . $nextId . "' "
							. "WHERE users_info_id='" . $row->users_info_id . "' ";
						$this->_db->setQuery($sql);
						$this->_db->Query();
						$row->users_info_id = $nextId;
					}
				}
			}

			$debtorHandle = $economic->createUserInEconomic($row);

			if ($row->is_company && trim($row->ean_number) != '' && JError::isError(JError::getError()))
			{
				if ($isNew)
				{
					$sql = "DELETE FROM " . $this->_table_prefix . "users_info "
						. "WHERE users_info_id='" . $original_info_id . "' ";
					$this->_db->setQuery($sql);
					$this->_db->Query();

					if ($row->user_id)
					{
						$user = JUser::getInstance((int) $row->user_id);

						// Delete user
						$user->delete();

						if (!$admin)
						{
							$user = $this->_session->get('user');
							unset($user);
							$this->_session->set('user', $user);
						}
					}
				}
				$msg = JText::_('PLEASE_ENTER_EAN_NUMBER');
				JError::raiseWarning('', $msg);

				return false;
			}
		}

		$auth['users_info_id'] = $row->users_info_id;
		$this->_session->set('auth', $auth);

		// For non-registered customers
		if (!$row->user_id)
		{
			$row->user_id = (0 - $row->users_info_id);
			$row->store();
			$u = JFactory::getUser();

			$u->set('username', $row->user_email);
			$u->set('email', $row->user_email);
			$u->set('usertype', 'Registered');
			$date = JFactory::getDate();
			$u->set('registerDate', $date->toMySQL());
			$data['user_id']  = $row->user_id;
			$data['username'] = $row->user_email;
			$data['email']    = $row->user_email;
		}

		if (isset($data['newsletter_signup']) && $data['newsletter_signup'] == 1)
		{
			$this->newsletterSubscribe($row->user_id, $data);
		}

		$billisship = 1;

		if (!isset($data['billisship']))
		{
			$billisship = 0;
		}

		// Info: field_section 6 :Userinformations
		$list_field = $extra_field->extra_field_save($data, 6, $row->users_info_id);

		if ($row->is_company == 0)
		{
			// Info: field_section 7 :Userinformations
			$list_field = $extra_field->extra_field_save($data, 7, $row->users_info_id);
		}
		else
		{
			// Info: field_section 8 :Userinformations
			$list_field = $extra_field->extra_field_save($data, 8, $row->users_info_id);
		}

		if ($billisship != 1)
		{
			$rowShip = $this->storeRedshopUserShipping($data);
		}

		if (REGISTER_METHOD != 1 && $isNew && $admin == 0)
		{
			if (REGISTER_METHOD == 2)
			{
				if (isset($data['createaccount']) && $data['createaccount'] == 1)
				{
					$redshopMail->sendRegistrationMail($data);
				}
			}
			else
			{
				$redshopMail->sendRegistrationMail($data);
			}
		}

		if ($isNew)
		{
			JPluginHelper::importPlugin('highrise');
			$dispatcher = JDispatcher::getInstance();
			$hResponses = $dispatcher->trigger('oncreateHighriseUser', array());
		}

		/**
		 * redCRM includes
		 */
		if ($helper->isredCRM())
		{
			$this->setoreredCRMDebtor($row);
		}

		return $row;
	}

	public function storeRedshopUserShipping($data)
	{
		$extra_field = new extra_field;

		$rowShip = JTable::getInstance('user_detail', 'Table');

		if (!$rowShip->bind($data))
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		$rowShip->user_id               = $data['user_id'];
		$rowShip->address_type          = 'ST';
		$rowShip->country_code          = $data['country_code_ST'];
		$rowShip->state_code            = (isset($data['state_code_ST'])) ? $data['state_code_ST'] : "";
		$rowShip->firstname             = $data['firstname_ST'];
		$rowShip->lastname              = $data['lastname_ST'];
		$rowShip->address               = $data['address_ST'];
		$rowShip->city                  = $data['city_ST'];
		$rowShip->zipcode               = $data['zipcode_ST'];
		$rowShip->phone                 = $data['phone_ST'];
		$rowShip->user_email            = $data['user_email'];
		$rowShip->tax_exempt            = $data['tax_exempt'];
		$rowShip->requesting_tax_exempt = $data['requesting_tax_exempt'];
		$rowShip->shopper_group_id      = $data['shopper_group_id'];
		$rowShip->tax_exempt_approved   = $data['tax_exempt_approved'];
		$rowShip->is_company            = $data['is_company'];

		if ($data['is_company'] == 1)
		{
			$rowShip->company_name = $data['company_name'];
			$rowShip->vat_number   = $data['vat_number'];
		}

		if (!$rowShip->store())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		if ($data['is_company'] == 0)
		{
			// Info: field_section 14 :Customer shipping Address
			$list_field = $extra_field->extra_field_save($data, 14, $rowShip->users_info_id);
		}
		else
		{
			// Info: field_section 15 :Company shipping Address
			$list_field = $extra_field->extra_field_save($data, 15, $rowShip->users_info_id);
		}

		return $rowShip;
	}

	public function userSynchronization()
	{
		$query = "SELECT u.* FROM #__users AS u "
			. "LEFT JOIN " . $this->_table_prefix . "users_info AS ru ON ru.user_id = u.id "
			. "WHERE ru.user_id IS NULL ";
		$this->_db->setQuery($query);
		$jusers = $this->_db->loadObjectList();

		for ($i = 0; $i < count($jusers); $i++)
		{
			$name = explode(" ", $jusers[$i]->name);

			$post               = array();
			$post['user_id']    = $jusers[$i]->id;
			$post['email']      = $post['email1'] = $jusers[$i]->email;
			$post['firstname']  = $name[0];
			$post['lastname']   = (isset($name[1]) && $name[1]) ? $name[1] : '';
			$post['is_company'] = (DEFAULT_CUSTOMER_REGISTER_TYPE == 2) ? 1 : 0;
			$post['password1']  = '';
			$post['billisship'] = 1;
			$reduser            = $this->storeRedshopUser($post, $jusers[$i]->id, 1);
		}

		return count($jusers);
	}

	public function newsletterSubscribe($user_id = 0, $data = array(), $sendmail = 0)
	{
		$newsletter = 1;
		$user       = JFactory::getUser();

		if ($user_id == 0)
		{
			$user_id = $user->id;
		}
		if (DEFAULT_NEWSLETTER > 0)
		{
			$newsletter = DEFAULT_NEWSLETTER;
		}
		if (count($data) <= 0)
		{
			$data['user_id']  = $user->id;
			$data['username'] = $user->username;
			$data['email']    = $user->email;
			$data['name']     = $user->name . " (" . $user->username . ")";
		}
		else
		{
			$data['user_id'] = $user_id;
			$data['name']    = $data['name'];
			$data['email']   = $data['email1'];

			if (isset($data['username']))
			{
				$data['name'] = $data['username'];
			}

			if ($user->id && $user->email == $data['email'])
			{
				$data['name'] = $user->name . " (" . $user->username . ")";
			}
		}

		$data['date']          = time();
		$data['newsletter_id'] = $newsletter;
		$data['published']     = 1;

		if (NEWSLETTER_CONFIRMATION && $sendmail)
		{
			$data['published'] = 0;
		}

		JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_redshop/tables');
		$row = JTable::getInstance('newslettersubscr_detail', 'Table');

		if (!$row->bind($data))
		{
			$this->setError($this->_db->getErrorMsg());
		}

		if (!$row->store())
		{
			$this->setError($this->_db->getErrorMsg());
		}

		if (NEWSLETTER_CONFIRMATION && $sendmail)
		{
			$redshopMail = new redshopMail;
			$redshopMail->sendNewsletterConfirmationMail($row->subscription_id);
		}

		return true;
	}

	public function newsletterUnsubscribe($email = "")
	{
		$user = JFactory::getUser();
		$and  = "";

		if (DEFAULT_NEWSLETTER != "")
		{
			$and .= "AND newsletter_id='" . DEFAULT_NEWSLETTER . "' ";
		}

		if ($user->id)
		{
			$and .= "AND `user_id`='" . $user->id . "' ";
			$email = $user->email;
		}

		if ($and != "")
		{
			$query = "DELETE FROM " . $this->_table_prefix . "newsletter_subscription "
				. "WHERE email='" . $email . "' "
				. $and;
			$this->_db->setQuery($query);
			$this->_db->query();
			$redshopMail = new redshopMail;
			$redshopMail->sendNewsletterCancellationMail($email);
		}

		return true;
	}

	public function getBillingTable($post = array(), $is_company = 0, $lists, $show_shipping = 0, $show_newsletter = 0, $create_account = 1)
	{
		$redTemplate = new Redtemplate;

		$billingisshipping = "";

		if (count($post) > 0)
		{
			if (isset($post['billisship']) && $post['billisship'] == 1)
			{
				$billingisshipping = "checked='checked'";
			}
		}
		elseif (OPTIONAL_SHIPPING_ADDRESS)
		{
			$billingisshipping = "checked='checked'";
		}

		$billing_template = $redTemplate->getTemplate("billing_template");

		if (count($billing_template) > 0 && $billing_template[0]->template_desc != "" && strstr($billing_template[0]->template_desc, "private_billing_template:") && strstr($billing_template[0]->template_desc, "company_billing_template:"))
		{
			$template_desc = $billing_template[0]->template_desc;
		}
		else
		{
			$template_desc = '<table class="admintable" border="0" cellspacing="0" cellpadding="0"><tbody><tr valign="top"><td>{private_billing_template:private_billing_template}{company_billing_template:company_billing_template}</td><td>{account_creation_start}<table class="admintable" border="0"><tbody><tr><td width="100" align="right">{username_lbl}</td><td>{username}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{password_lbl}</td><td>{password}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{confirm_password_lbl}</td><td>{confirm_password}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{newsletter_signup_chk}</td><td colspan="2">{newsletter_signup_lbl}</td></tr></tbody></table>{account_creation_end}</td></tr><tr><td colspan="2" align="right"><span class="required">*</span>{required_lbl}</td></tr><tr class="trshipping_add"><td class="tdshipping_add" colspan="2">{sipping_same_as_billing_lbl} {sipping_same_as_billing}</td></tr></tbody></table>';
		}

		$private_template = $redTemplate->getTemplate("private_billing_template");

		if (count($private_template) <= 0)
		{
			$private_template[0]->template_name = 'private_billing_template';
			$private_template[0]->template_id   = 0;
		}

		for ($i = 0; $i < count($private_template); $i++)
		{
			if (strstr($template_desc, "{private_billing_template:" . $private_template[$i]->template_name . "}"))
			{
				if ($private_template[$i]->template_desc != "")
				{
					$private_template_desc = $private_template[$i]->template_desc;
				}
				else
				{
					$private_template_desc = '<table class="admintable" style="height: 221px;" border="0" width="183"><tbody><tr><td width="100" align="right">{email_lbl}:</td><td>{email}</td><td><span class="required">*</span></td></tr><!-- {retype_email_start} --><tr><td width="100" align="right">{retype_email_lbl}</td><td>{retype_email}</td><td><span class="required">*</span></td></tr><!-- {retype_email_end} --><tr><td width="100" align="right">{firstname_lbl}</td><td>{firstname}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{lastname_lbl}</td><td>{lastname}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{address_lbl}</td><td>{address}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{zipcode_lbl}</td><td>{zipcode}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{city_lbl}</td><td>{city}</td><td><span class="required">*</span></td></tr><tr id="{country_txtid}" style="{country_style}"><td width="100" align="right">{country_lbl}</td><td>{country}</td><td><span class="required">*</span></td></tr><tr id="{state_txtid}" style="{state_style}"><td width="100" align="right">{state_lbl}</td><td>{state}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{phone_lbl}</td><td>{phone}</td><td><span class="required">*</span></td></tr><tr><td colspan="3">{private_extrafield}</td></tr></tbody></table>';
				}

				$private_template_desc = ($is_company == 1) ? '' : $this->replacePrivateCustomer($private_template_desc, $post, $lists);
				$template_desc         = str_replace("{private_billing_template:" . $private_template[$i]->template_name . "}", "<div id='tblprivate_customer'>" . $private_template_desc . "</div><div id='divPrivateTemplateId' style='display:none;'>" . $private_template[$i]->template_id . "</div>", $template_desc);
				break;
			}
		}

		$company_template = $redTemplate->getTemplate("company_billing_template");

		if (count($company_template) <= 0)
		{
			$company_template[0]->template_name = 'company_billing_template';
			$company_template[0]->template_id   = 0;
		}

		for ($i = 0; $i < count($company_template); $i++)
		{
			if (strstr($template_desc, "{company_billing_template:" . $company_template[$i]->template_name . "}"))
			{
				if ($company_template[$i]->template_desc != "")
				{
					$company_template_desc = $company_template[$i]->template_desc;
				}
				else
				{
					$company_template_desc = '<table class="admintable" style="height: 221px;" border="0" width="183"><tbody><tr><td width="100" align="right">{email_lbl}:</td><td>{email}</td><td><span class="required">*</span></td></tr><!-- {retype_email_start} --><tr><td width="100" align="right">{retype_email_lbl}</td><td>{retype_email}</td><td><span class="required">*</span></td></tr><!-- {retype_email_end} --><tr><td width="100" align="right">{company_name_lbl}</td><td>{company_name}</td><td><span class="required">*</span></td></tr><!-- {vat_number_start} --><tr><td width="100" align="right">{vat_number_lbl}</td><td>{vat_number}</td><td><span class="required">*</span></td></tr><!-- {vat_number_end} --><tr><td width="100" align="right">{firstname_lbl}</td><td>{firstname}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{lastname_lbl}</td><td>{lastname}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{address_lbl}</td><td>{address}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{zipcode_lbl}</td><td>{zipcode}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{city_lbl}</td><td>{city}</td><td><span class="required">*</span></td></tr><tr id="{country_txtid}" style="{country_style}"><td width="100" align="right">{country_lbl}</td><td>{country}</td><td><span class="required">*</span></td></tr><tr id="{state_txtid}" style="{state_style}"><td width="100" align="right">{state_lbl}</td><td>{state}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{phone_lbl}</td><td>{phone}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{ean_number_lbl}</td><td>{ean_number}</td><td></td></tr><tr><td width="100" align="right">{tax_exempt_lbl}</td><td>{tax_exempt}</td></tr><tr><td colspan="3">{company_extrafield}</td></tr></tbody></table>';
				}

				$company_template_desc = ($is_company == 1) ? $this->replaceCompanyCustomer($company_template_desc, $post, $lists) : '';
				$template_desc         = str_replace("{company_billing_template:" . $company_template[$i]->template_name . "}", "<div id='tblcompany_customer'>" . $company_template_desc . "</div><div id='divCompanyTemplateId' style='display:none;'>" . $company_template[$i]->template_id . "</div>", $template_desc);
				break;
			}
		}

		$template_desc = str_replace("{required_lbl}", JText::_('COM_REDSHOP_REQUIRED'), $template_desc);

		if ($show_shipping && SHIPPING_METHOD_ENABLE)
		{
			$template_desc = str_replace("{sipping_same_as_billing_lbl}", JText::_('COM_REDSHOP_SHIPPING_SAME_AS_BILLING'), $template_desc);
			$template_desc = str_replace("{sipping_same_as_billing}", '<input type="checkbox" id="billisship" name="billisship" value="1" onclick="billingIsShipping(this);" ' . $billingisshipping . ' />', $template_desc);
		}
		else
		{
			$template_desc = str_replace("{sipping_same_as_billing_lbl}", '', $template_desc);
			$template_desc = str_replace("{sipping_same_as_billing}", '', $template_desc);
		}

		if (strstr($template_desc, "{account_creation_start}") && strstr($template_desc, "{account_creation_end}"))
		{
			$template_pd_sdata = explode('{account_creation_start}', $template_desc);
			$template_pd_edata = explode('{account_creation_end}', $template_pd_sdata [1]);
			$template_middle   = "";

			if (REGISTER_METHOD != 1 && REGISTER_METHOD != 3)
			{
				$checkbox_style  = '';
				$template_middle = $template_pd_edata[0];

				if (REGISTER_METHOD == 2)
				{
					if ($create_account == 1)
					{
						$checkbox_style = 'style="display:block"';

					}
					else
					{
						$checkbox_style = 'style="display:none"';
					}
				}
				else
				{
					$checkbox_style = 'style="display:block"';
				}

				$template_middle = str_replace("{username_lbl}", JText::_('COM_REDSHOP_USERNAME_REGISTER'), $template_middle);
				$template_middle = str_replace("{username}", '<input class="inputbox required" type="text" name="username" id="username" size="32" maxlength="250" value="' . @$post ["username"] . '" />', $template_middle);
				$template_middle = str_replace("{password_lbl}", JText::_('COM_REDSHOP_PASSWORD_REGISTER'), $template_middle);
				$template_middle = str_replace("{password}", '<input class="inputbox required" type="password" name="password1" id="password1" size="32" maxlength="250" value="" />', $template_middle);
				$template_middle = str_replace("{confirm_password_lbl}", JText::_('COM_REDSHOP_CONFIRM_PASSWORD'), $template_middle);
				$template_middle = str_replace("{confirm_password}", '<input class="inputbox required" type="password" name="password2" id="password2" size="32" maxlength="250" value="" />', $template_middle);

				$newsletter_signup_lbl = "";
				$newsletter_signup_chk = "";

				if ($show_newsletter && NEWSLETTER_ENABLE)
				{
					$newsletter_signup_lbl = JText::_('COM_REDSHOP_SIGN_UP_FOR_NEWSLETTER');
					$newsletter_signup_chk = '<input type="checkbox" name="newsletter_signup" id="newsletter_signup" value="1">';
				}

				$template_middle = str_replace("{newsletter_signup_lbl}", $newsletter_signup_lbl, $template_middle);
				$template_middle = str_replace("{newsletter_signup_chk}", $newsletter_signup_chk, $template_middle);
			}

			$template_desc = $template_pd_sdata[0] . '<div id="tdUsernamePassword" ' . $checkbox_style . '>' . $template_middle . '</div>' . $template_pd_edata[1];
		}

		$template_desc = $template_desc . "<div id='tmpRegistrationDiv' style='display: none;'></div>";

		return $template_desc;
	}

	public function replaceBillingCommonFields($template_desc, $post = array(), $lists)
	{
		$Redconfiguration = new Redconfiguration;

		$ajax                  = (isset($lists['isAjax']) && $lists['isAjax'] == 1) ? 1 : 0;
		$countryarray          = $Redconfiguration->getCountryList(@$post, "country_code", "BT");
		$post['country_code']  = $countryarray['country_code'];
		$lists['country_code'] = $countryarray['country_dropdown'];
		$statearray            = $Redconfiguration->getStateList(@$post, 'state_code', 'country_code', 'BT', $ajax);
		$lists['state_code']   = $statearray['state_dropdown'];
		$countrystyle          = (count($countryarray['countrylist']) == 1 && count($statearray['statelist']) == 0) ? 'display:none;' : '';
		$statestyle            = ($statearray['is_states'] <= 0) ? 'display:none;' : '';

		$read_only = "";

		$template_desc = str_replace("{email_lbl}", JText::_('COM_REDSHOP_EMAIL'), $template_desc);
		$template_desc = str_replace("{email}", '<input class="inputbox required" type="text" title="' . JTEXT::_('COM_REDSHOP_PROVIDE_CORRECT_EMAIL_ADDRESS') . '" name="email1" id="email1" size="32" maxlength="250" value="' . @$post ["email1"] . '" />', $template_desc);

		if (strstr($template_desc, "{retype_email_start}") && strstr($template_desc, "{retype_email_end}"))
		{
			$template_pd_sdata = explode('{retype_email_start}', $template_desc);
			$template_pd_edata = explode('{retype_email_end}', $template_pd_sdata [1]);
			$template_middle   = "";

			if (SHOW_EMAIL_VERIFICATION)
			{
				$template_middle = $template_pd_edata[0];
				$template_middle = str_replace("{retype_email_lbl}", JText::_('COM_REDSHOP_RETYPE_CUSTOMER_EMAIL'), $template_middle);
				$template_middle = str_replace("{retype_email}", '<input class="inputbox required" type="text" id="email2" name="email2" size="32" maxlength="250" value="" />', $template_middle);
			}

			$template_desc = $template_pd_sdata[0] . $template_middle . $template_pd_edata[1];
		}

		$template_desc = str_replace("{company_name_lbl}", '<div>' . JText::_('COM_REDSHOP_COMPANY_NAME') . '</div>', $template_desc);
		$template_desc = str_replace("{company_name}", '<div><input class="inputbox required" type="text" name="company_name" id="company_name" size="32" maxlength="250" value="' . @$post ["company_name"] . '" /></div>', $template_desc);
		$template_desc = str_replace("{firstname_lbl}", JText::_('COM_REDSHOP_FIRSTNAME'), $template_desc);
		$template_desc = str_replace("{firstname}", '<input class="inputbox required" type="text" name="firstname" id="firstname" size="32" maxlength="250" value="' . @$post ["firstname"] . '" />', $template_desc);
		$template_desc = str_replace("{lastname_lbl}", JText::_('COM_REDSHOP_LASTNAME'), $template_desc);
		$template_desc = str_replace("{lastname}", '<input class="inputbox required" type="text" name="lastname" id="lastname" size="32" maxlength="250" value="' . @$post ["lastname"] . '" />', $template_desc);
		$template_desc = str_replace("{address_lbl}", JText::_('COM_REDSHOP_ADDRESS'), $template_desc);
		$template_desc = str_replace("{address}", '<input class="inputbox required" type="text" name="address" id="address" size="32" maxlength="250" value="' . @$post ["address"] . '" />', $template_desc);
		$template_desc = str_replace("{zipcode_lbl}", JText::_('COM_REDSHOP_ZIP'), $template_desc);
		$template_desc = str_replace("{zipcode}", '<input class="inputbox required"  type="text" name="zipcode" id="zipcode" size="32" maxlength="10" value="' . @$post['zipcode'] . '" onblur="return autoFillCity(this.value,\'BT\');" />', $template_desc);
		$template_desc = str_replace("{city_lbl}", JText::_('COM_REDSHOP_CITY'), $template_desc);
		$template_desc = str_replace("{city}", '<input class="inputbox required" type="text" name="city" ' . $read_only . ' id="city" value="' . @$post['city'] . '" size="32" maxlength="250" />', $template_desc);
		$template_desc = str_replace("{phone_lbl}", JText::_('COM_REDSHOP_PHONE'), $template_desc);
		$template_desc = str_replace("{phone}", '<input class="inputbox required" type="text" name="phone" id="phone" size="32" maxlength="250" value="' . @$post ["phone"] . '" onblur="return searchByPhone(this.value,\'BT\');" />', $template_desc);

		$template_desc = str_replace("{country_txtid}", "div_country_txt", $template_desc);
		$template_desc = str_replace("{country_style}", $countrystyle, $template_desc);
		$template_desc = str_replace("{state_txtid}", "div_state_txt", $template_desc);
		$template_desc = str_replace("{state_style}", $statestyle, $template_desc);

		$template_desc = str_replace("{country_lbl}", JText::_('COM_REDSHOP_COUNTRY'), $template_desc);
		$template_desc = str_replace("{country}", $lists['country_code'], $template_desc);
		$template_desc = str_replace("{state_lbl}", JText::_('COM_REDSHOP_STATE'), $template_desc);
		$template_desc = str_replace("{state}", $lists ['state_code'], $template_desc);

		return $template_desc;
	}

	public function replacePrivateCustomer($template_desc, $post = array(), $lists)
	{
		$template_desc = $this->replaceBillingCommonFields($template_desc, $post, $lists);

		if (strstr($template_desc, "{private_extrafield}"))
		{
			$extra_field_user = (ALLOW_CUSTOMER_REGISTER_TYPE != 2 && $lists['extra_field_user'] != "") ? $lists['extra_field_user'] : "";
			$template_desc    = str_replace("{private_extrafield}", $extra_field_user, $template_desc);
		}

		return $template_desc;
	}

	public function replaceCompanyCustomer($template_desc, $post = array(), $lists)
	{
		$template_desc = $this->replaceBillingCommonFields($template_desc, $post, $lists);

		$template_desc = str_replace("{company_name_lbl}", JText::_('COM_REDSHOP_COMPANY_NAME'), $template_desc);
		$template_desc = str_replace("{company_name}", '<input class="inputbox required" type="text" name="company_name" id="company_name" size="32" maxlength="250" value="' . @$post ["company_name"] . '" />', $template_desc);
		$template_desc = str_replace("{ean_number_lbl}", JText::_('COM_REDSHOP_EAN_NUMBER'), $template_desc);
		$template_desc = str_replace("{ean_number}", '<input class="inputbox" type="text" name="ean_number" id="ean_number" size="32" maxlength="250" value="' . @$post ["ean_number"] . '" />', $template_desc);

		if (strstr($template_desc, "{vat_number_start}") && strstr($template_desc, "{vat_number_end}"))
		{
			$template_pd_sdata = explode('{vat_number_start}', $template_desc);
			$template_pd_edata = explode('{vat_number_end}', $template_pd_sdata [1]);
			$template_middle   = "";

			if (USE_TAX_EXEMPT == 1)
			{
				$template_middle = $template_pd_edata[0];
				$classreq        = (REQUIRED_VAT_NUMBER == 1) ? "required" : "";
				$template_middle = str_replace("{vat_number_lbl}", JText::_('COM_REDSHOP_BUSINESS_NUMBER'), $template_middle);

				if (JPluginHelper::isEnabled('redshop_veis_registration', 'rs_veis_registration'))
				{
					$template_middle = str_replace("{vat_number}", '<input type="text" class="inputbox ' . $classreq . '" name="vat_number" id="vat_number" size="32" maxlength="250" value="' . @$post ["vat_number"] . '" onblur="return replaceveisval();"/><input type="text" name="veis_wait_input" value="" id="veis_wait_input" style="width:1px;height:1px;border:none;background:none;" class="inputbox required">', $template_middle);
				}
				else
				{
					$template_middle = str_replace("{vat_number}", '<input type="text" class="inputbox ' . $classreq . '" name="vat_number" id="vat_number" size="32" maxlength="250" value="' . @$post ["vat_number"] . '" />', $template_middle);
				}
			}

			$template_desc = $template_pd_sdata[0] . $template_middle . $template_pd_edata[1];
		}

		if (USE_TAX_EXEMPT == 1 && SHOW_TAX_EXEMPT_INFRONT)
		{
			$tax_exempt    = JHTML::_('select.booleanlist', 'tax_exempt', 'class="inputbox" ', @$post["tax_exempt"], JText::_('COM_REDSHOP_COMPANY_IS_VAT_EXEMPTED'), JText::_('COM_REDSHOP_COMPANY_IS_NOT_VAT_EXEMPTED'));
			$template_desc = str_replace("{tax_exempt_lbl}", '<div id="lblTaxExempt" ' . $allowCompany . '>' . JText::_('COM_REDSHOP_TAX_EXEMPT') . '</div>', $template_desc);
			$template_desc = str_replace("{tax_exempt}", '<div id="trTaxExempt" ' . $allowCompany . '>' . $tax_exempt . '</div>', $template_desc);
		}
		else
		{
			$template_desc = str_replace("{tax_exempt_lbl}", '', $template_desc);
			$template_desc = str_replace("{tax_exempt}", '', $template_desc);
		}

		if (strstr($template_desc, "{company_extrafield}"))
		{
			$extra_field_company = (ALLOW_CUSTOMER_REGISTER_TYPE != 1 && $lists['extra_field_company'] != "") ? $lists['extra_field_company'] : "";
			$template_desc       = str_replace("{company_extrafield}", $extra_field_company, $template_desc);
		}

		return $template_desc;
	}

	public function getShippingTable($post = array(), $is_company = 0, $lists)
	{
		$Redconfiguration  = new Redconfiguration;
		$redTemplate       = new Redtemplate;
		$shipping_template = $redTemplate->getTemplate("shipping_template");

		if (count($shipping_template) > 0 && $shipping_template[0]->template_desc != "")
		{
			$template_desc = $shipping_template[0]->template_desc;
		}
		else
		{
			$template_desc = '<table class="admintable" border="0"><tbody><tr><td width="100" align="right">{firstname_st_lbl}</td><td>{firstname_st}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{lastname_st_lbl}</td><td>{lastname_st}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{address_st_lbl}</td><td>{address_st}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{zipcode_st_lbl}</td><td>{zipcode_st}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{city_st_lbl}</td><td>{city_st}</td><td><span class="required">*</span></td></tr><tr id="{country_st_txtid}" style="{country_st_style}"><td width="100" align="right">{country_st_lbl}</td><td>{country_st}</td><td><span class="required">*</span></td></tr><tr id="{state_st_txtid}" style="{state_st_style}"><td width="100" align="right">{state_st_lbl}</td><td>{state_st}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{phone_st_lbl}</td><td>{phone_st}</td><td><span class="required">*</span></td></tr><tr><td colspan="3">{extra_field_st_start} <table border="0"><tbody><tr><td>{extra_field_st}</td></tr></tbody></table>{extra_field_st_end}</td></tr></tbody></table>';
		}

		if (!isset($post ["phone_ST"]) || $post ["phone_ST"] == 0)
		{
			$post ["phone_ST"] = '';
		}

		$allowCustomer = '';
		$allowCompany  = '';

		if ($is_company == 1)
		{
			$allowCustomer = 'style="display:none;"';
		}
		else
		{
			$allowCompany = 'style="display:none;"';
		}

		$read_only                = "";
		$countryarray             = $Redconfiguration->getCountryList(@$post, "country_code_ST", "ST", "inputbox billingRequired valid");
		$post['country_code_ST']  = $countryarray['country_code_ST'];
		$lists['country_code_ST'] = $countryarray['country_dropdown'];
		$statearray               = $Redconfiguration->getStateList(@$post, "state_code_ST", "country_code_ST", "ST", 0, "");
		$lists['state_code_ST']   = $statearray['state_dropdown'];
		$countrystyle             = (count($countryarray['countrylist']) == 1 && count($statearray['statelist']) == 0) ? 'display:none;' : '';
		$statestyle               = ($statearray['is_states'] <= 0) ? 'display:none;' : '';

		$template_desc = str_replace("{firstname_st_lbl}", JText::_('COM_REDSHOP_FIRSTNAME'), $template_desc);
		$template_desc = str_replace("{firstname_st}", '<input class="inputbox billingRequired valid" type="text" name="firstname_ST" id="firstname_ST" size="32" maxlength="250" value="' . @$post ["firstname_ST"] . '" />', $template_desc);
		$template_desc = str_replace("{lastname_st_lbl}", JText::_('COM_REDSHOP_LASTNAME'), $template_desc);
		$template_desc = str_replace("{lastname_st}", '<input class="inputbox billingRequired valid" type="text" name="lastname_ST" id="lastname_ST" size="32" maxlength="250" value="' . @$post ["lastname_ST"] . '" />', $template_desc);
		$template_desc = str_replace("{address_st_lbl}", JText::_('COM_REDSHOP_ADDRESS'), $template_desc);
		$template_desc = str_replace("{address_st}", '<input class="inputbox billingRequired valid" type="text" name="address_ST" id="address_ST" size="32" maxlength="250" value="' . @$post ["address_ST"] . '" />', $template_desc);
		$template_desc = str_replace("{zipcode_st_lbl}", JText::_('COM_REDSHOP_ZIP'), $template_desc);
		$template_desc = str_replace("{zipcode_st}", '<input class="inputbox billingRequired valid zipcode" type="text" name="zipcode_ST" id="zipcode_ST" size="32" maxlength="10" value="' . @$post['zipcode_ST'] . '" onblur="return autoFillCity(this.value,\'ST\');"  />', $template_desc);
		$template_desc = str_replace("{city_st_lbl}", JText::_('COM_REDSHOP_CITY'), $template_desc);
		$template_desc = str_replace("{city_st}", '<input class="inputbox billingRequired valid" type="text" name="city_ST" ' . $read_only . ' id="city_ST" value="' . @$post['city_ST'] . '" size="32" maxlength="250" />', $template_desc);
		$template_desc = str_replace("{phone_st_lbl}", JText::_('COM_REDSHOP_PHONE'), $template_desc);
		$template_desc = str_replace("{phone_st}", '<input class="inputbox billingRequired valid phone" type="text" name="phone_ST" id="phone_ST" size="32" maxlength="250" value="' . @$post ["phone_ST"] . '" onblur="return searchByPhone(this.value,\'ST\');" />', $template_desc);

		$template_desc = str_replace("{country_st_txtid}", "div_country_st_txt", $template_desc);
		$template_desc = str_replace("{country_st_style}", $countrystyle, $template_desc);
		$template_desc = str_replace("{state_st_txtid}", "div_state_st_txt", $template_desc);
		$template_desc = str_replace("{state_st_style}", $statestyle, $template_desc);
		$template_desc = str_replace("{country_st_lbl}", JText::_('COM_REDSHOP_COUNTRY'), $template_desc);
		$template_desc = str_replace("{country_st}", $lists['country_code_ST'], $template_desc);
		$template_desc = str_replace("{state_st_lbl}", JText::_('COM_REDSHOP_STATE'), $template_desc);
		$template_desc = str_replace("{state_st}", $lists ['state_code_ST'], $template_desc);

		if (strstr($template_desc, "{extra_field_st_start}") && strstr($template_desc, "{extra_field_st_end}"))
		{
			$template_pd_sdata = explode('{extra_field_st_start}', $template_desc);
			$template_pd_edata = explode('{extra_field_st_end}', $template_pd_sdata [1]);
			$template_middle   = $template_pd_edata[0];

			$extra_field_company = (ALLOW_CUSTOMER_REGISTER_TYPE != 1 && $lists['shipping_company_field'] != "") ? $lists['shipping_company_field'] : "";
			$extra_field_user    = (ALLOW_CUSTOMER_REGISTER_TYPE != 2 && $lists['shipping_customer_field'] != "") ? $lists['shipping_customer_field'] : "";

			$template_middle_company = str_replace("{extra_field_st}", $extra_field_company, $template_middle);
			$template_middle_user    = str_replace("{extra_field_st}", $extra_field_user, $template_middle);

			$template_middle_company = '<div id="exCompanyFieldST" ' . $allowCompany . '>' . $template_middle_company . '</div>';
			$template_middle_user    = '<div id="exCustomerFieldST" ' . $allowCustomer . '>' . $template_middle_user . '</div>';

			$template_desc = $template_pd_sdata[0] . $template_middle_company . $template_middle_user . $template_pd_edata[1];
		}

		return $template_desc;
	}

	public function getCaptchaTable()
	{
		$html = '';

		if (SHOW_CAPTCHA)
		{

			$html .= '<table cellspacing="0" cellpadding="0" border="0" width="100%">';
			$html .= '<tr><td>&nbsp;</td>
						<td align="left"><img src="' . JURI::base(true) . '/index.php?tmpl=component&option=com_redshop&view=registration&task=captcha&captcha=security_code&width=100&height=40&characters=5" /></td></tr>';
			$html .= '<tr><td width="100" align="right"><label for="security_code">' . JText::_('COM_REDSHOP_SECURITY_CODE') . '</label></td>
						<td><input class="inputbox" id="security_code" name="security_code" type="text" /></td></tr>';
			$html .= '</table>';
		}

		return $html;
	}

	public function getAskQuestionCaptcha()
	{
		$html = '';
		$html .= '<table cellspacing="0" cellpadding="0" border="0" width="100%">';
		$html .= '<tr><td>&nbsp;</td>
						<td align="left"><img src="' . JURI::base(true) . '/index.php?tmpl=component&option=com_redshop&view=registration&task=captcha&captcha=security_code&width=100&height=40&characters=5" /></td></tr>';
		$html .= '<tr><td width="100" align="right"><label for="security_code">' . JText::_('COM_REDSHOP_SECURITY_CODE') . '</label></td>
						<td><input class="inputbox" id="security_code" name="security_code" type="text" /></td></tr>';
		$html .= '</table>';

		return $html;
	}

	/**
	 * Function to store redCRM user
	 *
	 * @param   object  $row  Row to store
	 *
	 * @return  object
	 */
	public function setoreredCRMDebtor($row)
	{
		$this->_db->setQuery("SELECT debitor_id FROM #__redcrm_debitors WHERE users_info_id = '" . $row->users_info_id . "'");
		$row->debitor_id = $this->_db->loadResult();

		if ($row->debitor_id > 0)
		{
			return;
		}

		if (DEBITOR_NUMBER_AUTO_GENERATE == 1 && $row->users_info_id <= 0)
		{
			JModel::addIncludePath(REDCRM_ADMIN . '/models');

			$crmmodel = JModel::getInstance('debitor', 'redCRMModel');

			$maxdebtor_id = $crmmodel->getMaxdebtor();

			if ($maxdebtor_id < DEBITOR_START_NUMBER)
			{
				$row->customer_number = DEBITOR_START_NUMBER;
			}
			else
			{
				$row->customer_number = $maxdebtor_id + 1;
			}
		}
		else
		{
			$row->customer_number = $row->users_info_id;
		}

		// Set redshop user detail table path
		JTable::addIncludePath(REDCRM_ADMIN . '/tables');
		$debtor = JTable::getInstance('debitors', 'Table');
		$debtor->bind($row);
		$debtor->store();

		return $debtor;
	}

	public function getShopperGroupManufacturers()
	{
		$user                       = JFactory::getUser();
		$user_id                    = $user->id;
		$shopperGroupId             = $this->getShopperGroup($user_id);
		$shopperGroupdata           = $this->getShopperGroupList($shopperGroupId);
		$shopper_group_manufactures = $shopperGroupdata[0]->shopper_group_manufactures;

		return $shopper_group_manufactures;
	}
}

