<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class rsUserHelper
{
	public $_session = null;

	public $_userId = null;

	public $_db = null;

	protected static $shopperGroupData = array();

	protected static $userInfo = array();

	protected static $instance = null;

	/**
	 * Returns the rsUserHelper object, only creating it
	 * if it doesn't already exist.
	 *
	 * @return  rsUserHelper  The rsUserHelper object
	 *
	 * @since   1.6
	 */
	public static function getInstance()
	{
		if (self::$instance === null)
		{
			self::$instance = new static;
		}

		return self::$instance;
	}

	public function __construct()
	{
		$this->_session      = JFactory::getSession();
		$this->_db           = JFactory::getDbo();
	}

	/**
	 * Get RedSHOP User Info
	 *
	 * @param   int     $joomlaUserId  Joomla user id
	 * @param   string  $addressType   Type user address BT (Billing Type) or ST (Shipping Type)
	 *
	 * @deprecated  1.5  Use RedshopHelperUser::getUserInformation instead
	 *
	 * @return mixed
	 */
	public function getRedSHOPUserInfo($joomlaUserId = 0, $addressType = 'BT')
	{
		return RedshopHelperUser::getUserInformation($joomlaUserId, $addressType);
	}

	/**
	 * Replace Conditional tag from Redshop tax
	 *
	 * @param   integer  $userId  User identifier
	 *
	 * @deprecated  1.5  Use RedshopHelperUser::getShopperGroup instead
	 *
	 * @return  integer            User group
	 */
	public function getShopperGroup($userId = 0)
	{
		return RedshopHelperUser::getShopperGroup($userId);
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
		$query = 'SELECT group_id FROM #__redshop_users_info AS uf '
			. 'LEFT JOIN #__user_usergroup_map as u on u.user_id = uf.user_id '
			. 'WHERE users_info_id = ' . (int) $user_id;
		$this->_db->setQuery($query);
		$usergroups = $this->_db->loadColumn();

		return $usergroups;
	}

	public function updateUserTermsCondition($users_info_id = 0, $isSet = 0)
	{
		// One id is mandatory ALWAYS
		if ($users_info_id != 0)
		{
			$query = "UPDATE #__redshop_users_info"
				. " SET accept_terms_conditions = " . (int) $isSet
				. " WHERE users_info_id = " . (int) $users_info_id;
			$this->_db->setQuery($query);
			$this->_db->execute();
		}
	}

	/**
	 * Get Shopper Group Data
	 *
	 * @param   int  $userId  User id
	 *
	 * @deprecated  1.5  Use RedshopHelperUser::getShopperGroupData instead
	 *
	 * @return mixed
	 */
	public function getShoppergroupData($userId = 0)
	{
		return RedshopHelperUser::getShopperGroupData($userId);
	}

	/**
	 * Get Shopper Group List
	 *
	 * @param   int  $shopperGroupId  Shopper Group Id
	 *
	 * @return mixed
	 */
	public function getShopperGroupList($shopperGroupId = 0)
	{
		if (!array_key_exists($shopperGroupId, self::$shopperGroupData))
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select(array('sh.*', $db->qn('sh.shopper_group_id', 'value'), $db->qn('sh.shopper_group_name', 'text')))
				->from($db->qn('#__redshop_shopper_group', 'sh'))
				->where('sh.published = 1');

			if ($shopperGroupId)
			{
				$query->where('sh.shopper_group_id = ' . (int) $shopperGroupId);
			}

			$db->setQuery($query);

			self::$shopperGroupData[$shopperGroupId] = $db->loadObjectList();
		}

		return self::$shopperGroupData[$shopperGroupId];
	}

	/**
	 * Create redshop user session
	 *
	 * @param   int  $user_id  Joomla user id
	 *
	 * @deprecated  1.5  Use RedshopHelperUser::createUserSession instead
	 *
	 * @return  array|mixed
	 */
	public function createUserSession($user_id = 0)
	{
		return RedshopHelperUser::createUserSession($user_id);
	}

	/**
	 * This function is used to check if the 'username' already exist in the database with any other ID
	 *
	 * @param   string  $username
	 * @param   int     $id
	 *
	 * @deprecated  1.5  Use RedshopHelperUser::validateUser instead
	 *
	 * @return  int|void
	 */
	public function validate_user($username, $id = 0)
	{
		return RedshopHelperUser::validateUser($username, $id);
	}

	/**
	 * This function is used to check if the 'email' already exist in the database with any other ID
	 *
	 * @param   string  $username
	 * @param   int     $id
	 *
	 * @deprecated  1.5  Use RedshopHelperUser::validateEmail instead
	 *
	 * @return  int|void
	 */
	public function validate_email($email, $id = 0)
	{
		return RedshopHelperUser::validateEmail($email, $id);
	}

	public function updateJoomlaUser($data)
	{
		$app = JFactory::getApplication();

		if (!$app->isAdmin())
		{
			if (Redshop::getConfig()->get('REGISTER_METHOD') == 1 || $data['user_id'] < 0)
			{
				$reduser = new stdClass;
				$reduser->id = $data['user_id'];

				return $reduser;
			}
		}

		if ($app->isAdmin() && $data['user_id'] < 0 && isset($data['users_info_id']))
		{
			$reduser = new stdClass;
			$reduser->id = $data['user_id'];

			return $reduser;
		}

		$app = JFactory::getApplication();
		$db  = JFactory::getDbo();
		$me  = JFactory::getUser();
		$acl = JFactory::getACL();

		$data['name'] = $name = $data['firstname'];

		if (trim($data['username']) == "")
		{
			JError::raiseWarning('', JText::_('COM_REDSHOP_EMPTY_USERNAME'));

			return false;
		}

		$countusername = $this->validate_user($data['username'], $data['user_id']);

		if ($countusername > 0)
		{
			JError::raiseWarning('', JText::_('COM_REDSHOP_USERNAME_ALREADY_EXISTS'));

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
			JError::raiseWarning('', JText::_('COM_REDSHOP_EMAIL_ALREADY_EXISTS'));

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
		$createaccount = (isset($data['createaccount']) && $data['createaccount'] == 1) ? 1 : 0;

		// Registration is without account creation REGISTER_METHOD = 1
		// Or Optional account creation
		if (Redshop::getConfig()->get('REGISTER_METHOD') == 1 || (Redshop::getConfig()->get('REGISTER_METHOD') == 2 && $createaccount == 0))
		{
			$user = new stdClass;
			$user->id = 0;

			return $user;
		}

		$data['password']  = JRequest::getVar('password1', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$data['password2'] = JRequest::getVar('password2', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$data['email']     = $data['email1'];
		$data['name']      = $name = $data['firstname'];

		$app = JFactory::getApplication();

		// Prevent front-end user to change user group in the form and then being able to register on any Joomla! user group.
		if ($app->isSite())
		{
			$params = JComponentHelper::getParams('com_users');
			$data['groups'] = array($params->get('new_usertype', 2));
		}

		// Do a password safety check
		if (Redshop::getConfig()->get('REGISTER_METHOD') == 3)
		{
			// Silent registration
			$better_token      = substr(uniqid(md5(rand()), true), 0, 10);
			$data['username']  = $data['email'];
			$data['password']  = $better_token;
			$data['password2'] = $better_token;
			JRequest::setVar('password1', $better_token);
		}



		if (trim($data['email']) == "")
		{
			JError::raiseWarning('', JText::_('COM_REDSHOP_EMPTY_EMAIL'));

			return false;
		}

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

		if (!$user->bind($data))
		{
			JError::raiseError(500, $user->getError());

			return false;
		}

		$date = JFactory::getDate();
		$user->set('id', 0);
		$user->set('registerDate', $date->toSql());

		// If user activation is turned on, we need to set the activation information
		$useractivation = $usersConfig->get('useractivation');

		if ($useractivation == '1')
		{
			if (version_compare(JVERSION, '3.0', '<'))
			{
				$hash = JApplication::getHash(JUserHelper::genRandomPassword());
			}
			else
			{
				$hash = JApplicationHelper::getHash(JUserHelper::genRandomPassword());
			}

			$user->set('activation', $hash);
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

		// Perform the login action
		if (!JFactory::getUser()->id)
		{
			$app->login($credentials);
		}

		return $user;
	}

	public function checkCaptcha($data, $displayWarning = true)
	{
		$default = JFactory::getConfig()->get('captcha');

		if (JFactory::getApplication()->isSite())
		{
			$default = JFactory::getApplication()->getParams()->get('captcha', JFactory::getConfig()->get('captcha'));
		}

		if (!empty($default))
		{
			$captcha = JCaptcha::getInstance($default, array('namespace' => 'redshop'));

			if ($captcha != null && !$captcha->checkAnswer($data))
			{
				if ($displayWarning)
				{
					JFactory::getApplication()->enqueueMessage(JText::_('COM_REDSHOP_INVALID_SECURITY'), 'error');
				}

				return false;
			}
		}

		return true;
	}

	public function storeRedshopUser($data, $user_id = 0, $admin = 0)
	{
		$redshopMail = redshopMail::getInstance();
		$extra_field = extra_field::getInstance();
		$helper      = redhelper::getInstance();

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
				if ($is_admin && isset($data['shopper_group_id']) && $data['shopper_group_id'] != 0)
				{
					$data['shopper_group_id'] = $data['shopper_group_id'];
				}
				else
				{
					$data['shopper_group_id'] = (Redshop::getConfig()->get('SHOPPER_GROUP_DEFAULT_COMPANY') != 0) ? Redshop::getConfig()->get('SHOPPER_GROUP_DEFAULT_COMPANY') : 2;
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
					$data['shopper_group_id'] = (Redshop::getConfig()->get('SHOPPER_GROUP_DEFAULT_PRIVATE') != 0) ? Redshop::getConfig()->get('SHOPPER_GROUP_DEFAULT_PRIVATE') : 1;
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
		if (Redshop::getConfig()->get('SHOW_TERMS_AND_CONDITIONS') == 1 && isset($data['termscondition']) && $data['termscondition'] == 1)
		{
			$data['accept_terms_conditions'] = 1;
		}

		$row->user_id = $data['user_id'] = $user_id;

		if (!$row->bind($data))
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}
		if (Redshop::getConfig()->get('USE_TAX_EXEMPT'))
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
		if (Redshop::getConfig()->get('ECONOMIC_INTEGRATION'))
		{
			$economic         = economic::getInstance();
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
						$sql    = "UPDATE #__redshop_users_info "
							. "SET users_info_id = " . (int) $nextId . " "
							. "WHERE users_info_id = " . (int) $row->users_info_id;
						$this->_db->setQuery($sql);
						$this->_db->execute();
						$row->users_info_id = $nextId;
					}
				}
			}

			$debtorHandle = $economic->createUserInEconomic($row);

			if ($row->is_company && trim($row->ean_number) != '' && JError::isError(JError::getError()))
			{
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
			$u->set('registerDate', $date->toSql());
			$data['user_id']  = $row->user_id;
			$data['username'] = $row->user_email;
			$data['email']    = $row->user_email;
		}

		if (isset($data['newsletter_signup']) && $data['newsletter_signup'] == 1)
		{
			$this->newsletterSubscribe($row->user_id, $data, 0, $isNew);
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

		if (Redshop::getConfig()->get('REGISTER_METHOD') != 1 && $isNew && $admin == 0)
		{
			if (Redshop::getConfig()->get('REGISTER_METHOD') == 2)
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

		JPluginHelper::importPlugin('user');
		RedshopHelperUtility::getDispatcher()->trigger('onAfterCreateRedshopUser', array($data, $isNew));

		return $row;
	}

	public function storeRedshopUserShipping($data)
	{
		$extra_field = extra_field::getInstance();

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
			. "LEFT JOIN #__redshop_users_info AS ru ON ru.user_id = u.id "
			. "WHERE ru.user_id IS NULL ";
		$this->_db->setQuery($query);
		$jusers = $this->_db->loadObjectList();

		for ($i = 0, $in = count($jusers); $i < $in; $i++)
		{
			$name = explode(" ", $jusers[$i]->name);

			$post               = array();
			$post['user_id']    = $jusers[$i]->id;
			$post['email']      = $jusers[$i]->email;
			$post['email1']     = $jusers[$i]->email;
			$post['firstname']  = $name[0];
			$post['lastname']   = (isset($name[1]) && $name[1]) ? $name[1] : '';
			$post['is_company'] = (Redshop::getConfig()->get('DEFAULT_CUSTOMER_REGISTER_TYPE') == 2) ? 1 : 0;
			$post['password1']  = '';
			$post['billisship'] = 1;
			$reduser            = $this->storeRedshopUser($post, $jusers[$i]->id, 1);
		}

		return count($jusers);
	}

	/**
	 * Method for add an subscriber for Newsletter
	 *
	 * @param   int    $userId    ID of user.
	 * @param   array  $data      Data of subscriber
	 * @param   int    $sendMail  True for send mail.
	 * @param   null   $isNew     Capability for old method.
	 *
	 * @return  boolean
	 *
	 * @deprecated  2.0.3  Use RedshopHelperNewsletter::subscribe() instead
	 */
	public function newsletterSubscribe($userId = 0, $data = array(), $sendMail = 0, $isNew = null)
	{
		return RedshopHelperNewsletter::subscribe($userId, $data, boolval($sendMail), $isNew);
	}

	public function newsletterUnsubscribe($email = "")
	{
		$db   = JFactory::getDbo();
		$user = JFactory::getUser();
		$and  = "";

		if (Redshop::getConfig()->get('DEFAULT_NEWSLETTER') != "")
		{
			$and .= "AND newsletter_id='" . Redshop::getConfig()->get('DEFAULT_NEWSLETTER') . "' ";
		}

		if ($user->id)
		{
			$and .= "AND `user_id` = " . (int) $user->id . " ";
			$email = $user->email;
		}

		if ($and != "")
		{
			$query = "DELETE FROM #__redshop_newsletter_subscription "
				. "WHERE email = " . $db->quote($email) . " "
				. $and;
			$this->_db->setQuery($query);
			$this->_db->execute();
			$redshopMail = redshopMail::getInstance();
			$redshopMail->sendNewsletterCancellationMail($email);
		}

		return true;
	}

	public function getBillingTable($post = array(), $is_company = 0, $lists, $show_shipping = 0, $show_newsletter = 0, $create_account = 1)
	{
		$redTemplate = Redtemplate::getInstance();

		$billingisshipping = "";

		if (isset($post['billisship']) && $post['billisship'] == 1)
		{
			$billingisshipping = "checked='checked'";
		}
		elseif (Redshop::getConfig()->get('OPTIONAL_SHIPPING_ADDRESS'))
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
			$template_desc = '<table class="admintable" border="0" cellspacing="0" cellpadding="0"><tbody><tr valign="top"><td>{private_billing_template:private_billing_template}{company_billing_template:company_billing_template}</td><td>{account_creation_start}<table class="admintable" border="0"><tbody><tr><td width="100" align="right">{username_lbl}</td><td>{username}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{password_lbl}</td><td>{password}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{confirm_password_lbl}</td><td>{confirm_password}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{newsletter_signup_chk}</td><td colspan="2">{newsletter_signup_lbl}</td></tr></tbody></table>{account_creation_end}</td></tr><tr><td colspan="2" align="right"><span class="required">*</span>{required_lbl}</td></tr><tr class="trshipping_add"><td class="tdshipping_add" colspan="2">{shipping_same_as_billing_lbl} {shipping_same_as_billing}</td></tr></tbody></table>';
		}

		$private_template = $redTemplate->getTemplate("private_billing_template");

		if (count($private_template) <= 0)
		{
			$private_template[0]->template_name = 'private_billing_template';
			$private_template[0]->template_id   = 0;
		}

		for ($i = 0, $in = count($private_template); $i < $in; $i++)
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

		for ($i = 0, $in = count($company_template); $i < $in; $i++)
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

		if ($show_shipping && Redshop::getConfig()->get('SHIPPING_METHOD_ENABLE'))
		{
			$template_desc = str_replace("{shipping_same_as_billing_lbl}", JText::_('COM_REDSHOP_SHIPPING_SAME_AS_BILLING'), $template_desc);
			$template_desc = str_replace("{shipping_same_as_billing}", '<input type="checkbox" id="billisship" name="billisship" value="1" onclick="billingIsShipping(this);" ' . $billingisshipping . ' />', $template_desc);
		}
		else
		{
			$template_desc = str_replace("{shipping_same_as_billing_lbl}", '', $template_desc);
			$template_desc = str_replace("{shipping_same_as_billing}", '', $template_desc);
		}

		if (strstr($template_desc, "{account_creation_start}") && strstr($template_desc, "{account_creation_end}"))
		{
			$template_pd_sdata = explode('{account_creation_start}', $template_desc);
			$template_pd_edata = explode('{account_creation_end}', $template_pd_sdata [1]);
			$template_middle   = "";
			$checkbox_style  = '';

			if (Redshop::getConfig()->get('REGISTER_METHOD') != 1 && Redshop::getConfig()->get('REGISTER_METHOD') != 3)
			{
				$template_middle = $template_pd_edata[0];

				if (Redshop::getConfig()->get('REGISTER_METHOD') == 2)
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

				if ($show_newsletter && Redshop::getConfig()->get('NEWSLETTER_ENABLE'))
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
		$countryarray          = RedshopHelperWorld::getCountryList($post);
		$post['country_code']  = $countryarray['country_code'];
		$lists['country_code'] = $countryarray['country_dropdown'];
		$statearray            = RedshopHelperWorld::getStateList($post);
		$lists['state_code']   = $statearray['state_dropdown'];
		$countrystyle          = (count($countryarray['countrylist']) == 1 && count($statearray['statelist']) == 0) ? 'display:none;' : '';
		$statestyle            = ($statearray['is_states'] <= 0) ? 'display:none;' : '';

		$read_only = "";

		$template_desc = str_replace("{email_lbl}", JText::_('COM_REDSHOP_EMAIL'), $template_desc);
		$template_desc = str_replace("{email}", '<input class="inputbox required" type="text" title="' . JTEXT::_('COM_REDSHOP_PROVIDE_CORRECT_EMAIL_ADDRESS') . '" name="email1" id="email1" size="32" maxlength="250" value="' . (isset($post["email1"]) ? $post["email1"] : '') . '" />', $template_desc);

		if (strstr($template_desc, "{retype_email_start}") && strstr($template_desc, "{retype_email_end}"))
		{
			$template_pd_sdata = explode('{retype_email_start}', $template_desc);
			$template_pd_edata = explode('{retype_email_end}', $template_pd_sdata [1]);
			$template_middle   = "";

			if (Redshop::getConfig()->get('SHOW_EMAIL_VERIFICATION'))
			{
				$template_middle = $template_pd_edata[0];
				$template_middle = str_replace("{retype_email_lbl}", JText::_('COM_REDSHOP_RETYPE_CUSTOMER_EMAIL'), $template_middle);
				$template_middle = str_replace("{retype_email}", '<input class="inputbox required" type="text" id="email2" name="email2" size="32" maxlength="250" value="" />', $template_middle);
			}

			$template_desc = $template_pd_sdata[0] . $template_middle . $template_pd_edata[1];
		}

		$template_desc = str_replace("{company_name_lbl}", JText::_('COM_REDSHOP_COMPANY_NAME'), $template_desc);
		$template_desc = str_replace("{company_name}", '<input class="inputbox required" type="text" name="company_name" id="company_name" size="32" maxlength="250" value="' . (isset($post["company_name"]) ? $post["company_name"] : '') . '" />', $template_desc);
		$template_desc = str_replace("{firstname_lbl}", JText::_('COM_REDSHOP_FIRSTNAME'), $template_desc);
		$template_desc = str_replace("{firstname}", '<input class="inputbox required" type="text" name="firstname" id="firstname" size="32" maxlength="250" value="' . (isset($post["firstname"]) ? $post["firstname"] : '') . '" />', $template_desc);
		$template_desc = str_replace("{lastname_lbl}", JText::_('COM_REDSHOP_LASTNAME'), $template_desc);
		$template_desc = str_replace("{lastname}", '<input class="inputbox required" type="text" name="lastname" id="lastname" size="32" maxlength="250" value="' . (isset($post["lastname"]) ? $post["lastname"] : '') . '" />', $template_desc);
		$template_desc = str_replace("{address_lbl}", JText::_('COM_REDSHOP_ADDRESS'), $template_desc);
		$template_desc = str_replace("{address}", '<input class="inputbox required" type="text" name="address" id="address" size="32" maxlength="250" value="' . (isset($post["address"]) ? $post["address"] : '') . '" />', $template_desc);
		$template_desc = str_replace("{zipcode_lbl}", JText::_('COM_REDSHOP_ZIP'), $template_desc);
		$template_desc = str_replace("{zipcode}", '<input class="inputbox required"  type="text" name="zipcode" id="zipcode" size="32" maxlength="10" value="' . (isset($post["zipcode"]) ? $post["zipcode"] : '') . '" onblur="return autoFillCity(this.value,\'BT\');" />', $template_desc);
		$template_desc = str_replace("{city_lbl}", JText::_('COM_REDSHOP_CITY'), $template_desc);
		$template_desc = str_replace("{city}", '<input class="inputbox required" type="text" name="city" ' . $read_only . ' id="city" value="' . (isset($post["city"]) ? $post["city"] : '') . '" size="32" maxlength="250" />', $template_desc);

		// Allow phone number to be optional using template tags.
		$phoneIsRequired = ((boolean) strstr($template_desc, '{phone_optional}')) ? '' : 'required';
		$template_desc = str_replace("{phone_optional}",'', $template_desc);
		$template_desc = str_replace(
			"{phone}",
			'<input class="inputbox ' . $phoneIsRequired . '" type="text" name="phone" id="phone" size="32" maxlength="250" value="' . (isset($post["phone"]) ? $post["phone"] : '') . '" onblur="return searchByPhone(this.value,\'BT\');" />',
			$template_desc
		);
		$template_desc = str_replace("{phone_lbl}", JText::_('COM_REDSHOP_PHONE'), $template_desc);

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
			$extra_field_user = (Redshop::getConfig()->get('ALLOW_CUSTOMER_REGISTER_TYPE') != 2 && $lists['extra_field_user'] != "") ? $lists['extra_field_user'] : "";
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

			if (Redshop::getConfig()->get('USE_TAX_EXEMPT') == 1)
			{
				$template_middle = $template_pd_edata[0];
				$classreq        = (Redshop::getConfig()->get('REQUIRED_VAT_NUMBER') == 1) ? "required" : "";
				$template_middle = str_replace("{vat_number_lbl}", JText::_('COM_REDSHOP_BUSINESS_NUMBER'), $template_middle);
				$template_middle = str_replace("{vat_number}", '<input type="text" class="inputbox ' . $classreq . '" name="vat_number" id="vat_number" size="32" maxlength="250" value="' . @$post ["vat_number"] . '" />', $template_middle);
			}

			$template_desc = $template_pd_sdata[0] . $template_middle . $template_pd_edata[1];
		}

		if (Redshop::getConfig()->get('USE_TAX_EXEMPT') == 1 && Redshop::getConfig()->get('SHOW_TAX_EXEMPT_INFRONT'))
		{
			$allowCompany  = '';
			$taxExempt = '';

			if (isset($post['is_company']) && 1 != (int) $post['is_company'])
			{
				$allowCompany = 'style="display:none;"';
			}

			if (isset($post["tax_exempt"]))
			{
				$taxExempt = $post["tax_exempt"];
			}

			$tax_exempt    = JHTML::_('select.booleanlist', 'tax_exempt', 'class="inputbox" ', $taxExempt, JText::_('COM_REDSHOP_COMPANY_IS_VAT_EXEMPTED'), JText::_('COM_REDSHOP_COMPANY_IS_NOT_VAT_EXEMPTED'));
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
			$extra_field_company = (Redshop::getConfig()->get('ALLOW_CUSTOMER_REGISTER_TYPE') != 1 && $lists['extra_field_company'] != "") ? $lists['extra_field_company'] : "";
			$template_desc       = str_replace("{company_extrafield}", $extra_field_company, $template_desc);
		}

		return $template_desc;
	}

	public function getShippingTable($post = array(), $is_company = 0, $lists)
	{
		$redTemplate       = Redtemplate::getInstance();
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
		$countryarray             = RedshopHelperWorld::getCountryList($post, 'country_code_ST', 'ST', 'inputbox billingRequired valid', 'state_code_ST');
		$post['country_code_ST']  = $countryarray['country_code_ST'];
		$lists['country_code_ST'] = $countryarray['country_dropdown'];

		$statearray               = RedshopHelperWorld::getStateList($post, 'state_code_ST', 'ST');
		$lists['state_code_ST']   = $statearray['state_dropdown'];

		$countrystyle = (count($countryarray['countrylist']) == 1 && count($statearray['statelist']) == 0) ? 'display:none;' : '';
		$statestyle               = ($statearray['is_states'] <= 0) ? 'display:none;' : '';

		$template_desc = str_replace("{firstname_st_lbl}", JText::_('COM_REDSHOP_FIRSTNAME'), $template_desc);
		$value = (!empty($post["firstname_ST"])) ? $post["firstname_ST"] : '';
		$template_desc = str_replace("{firstname_st}", '<input class="inputbox billingRequired valid" type="text" name="firstname_ST" id="firstname_ST" size="32" maxlength="250" value="' . $value . '" data-msg="' . JText::_('COM_REDSHOP_THIS_FIELD_IS_REQUIRED') . '"/>', $template_desc);
		$template_desc = str_replace("{lastname_st_lbl}", JText::_('COM_REDSHOP_LASTNAME'), $template_desc);
		$value = (!empty($post["lastname_ST"])) ? $post["lastname_ST"] : '';
		$template_desc = str_replace("{lastname_st}", '<input class="inputbox billingRequired valid" type="text" name="lastname_ST" id="lastname_ST" size="32" maxlength="250" value="' . $value . '" data-msg="' . JText::_('COM_REDSHOP_THIS_FIELD_IS_REQUIRED') . '"/>', $template_desc);
		$template_desc = str_replace("{address_st_lbl}", JText::_('COM_REDSHOP_ADDRESS'), $template_desc);
		$value = (!empty($post["address_ST"])) ? $post["address_ST"] : '';
		$template_desc = str_replace("{address_st}", '<input class="inputbox billingRequired valid" type="text" name="address_ST" id="address_ST" size="32" maxlength="250" value="' . $value . '" data-msg="' . JText::_('COM_REDSHOP_THIS_FIELD_IS_REQUIRED') . '"/>', $template_desc);
		$template_desc = str_replace("{zipcode_st_lbl}", JText::_('COM_REDSHOP_ZIP'), $template_desc);
		$value = (!empty($post["zipcode_ST"])) ? $post["zipcode_ST"] : '';
		$template_desc = str_replace("{zipcode_st}", '<input class="inputbox billingRequired valid zipcode" type="text" name="zipcode_ST" id="zipcode_ST" size="32" maxlength="10" value="' . $value . '" onblur="return autoFillCity(this.value,\'ST\');" data-msg="' . JText::_('COM_REDSHOP_YOUR_MUST_PROVIDE_A_ZIP') . '" />', $template_desc);
		$template_desc = str_replace("{city_st_lbl}", JText::_('COM_REDSHOP_CITY'), $template_desc);
		$value = (!empty($post["city_ST"])) ? $post["city_ST"] : '';
		$template_desc = str_replace("{city_st}", '<input class="inputbox billingRequired valid" type="text" name="city_ST" ' . $read_only . ' id="city_ST" value="' . $value . '" size="32" maxlength="250" data-msg="' . JText::_('COM_REDSHOP_THIS_FIELD_IS_REQUIRED') . '"/>', $template_desc);
		$template_desc = str_replace("{phone_st_lbl}", JText::_('COM_REDSHOP_PHONE'), $template_desc);
		$value = (!empty($post["phone_ST"])) ? $post["phone_ST"] : '';
		$template_desc = str_replace("{phone_st}", '<input class="inputbox billingRequired valid phone" type="text" name="phone_ST" id="phone_ST" size="32" maxlength="250" value="' . $value . '" onblur="return searchByPhone(this.value,\'ST\');" data-msg="' . JText::_('COM_REDSHOP_YOUR_MUST_PROVIDE_A_VALID_PHONE') . '"/>', $template_desc);

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

			$extra_field_company = (Redshop::getConfig()->get('ALLOW_CUSTOMER_REGISTER_TYPE') != 1 && $lists['shipping_company_field'] != "") ? $lists['shipping_company_field'] : "";
			$extra_field_user    = (Redshop::getConfig()->get('ALLOW_CUSTOMER_REGISTER_TYPE') != 2 && $lists['shipping_customer_field'] != "") ? $lists['shipping_customer_field'] : "";

			$template_middle_company = str_replace("{extra_field_st}", $extra_field_company, $template_middle);
			$template_middle_user    = str_replace("{extra_field_st}", $extra_field_user, $template_middle);

			$template_middle_company = '<div id="exCompanyFieldST" ' . $allowCompany . '>' . $template_middle_company . '</div>';
			$template_middle_user    = '<div id="exCustomerFieldST" ' . $allowCustomer . '>' . $template_middle_user . '</div>';

			$template_desc = $template_pd_sdata[0] . $template_middle_company . $template_middle_user . $template_pd_edata[1];
		}

		return $template_desc;
	}

	/**
	 * Get captcha html table
	 *
	 * @return  string  HTML output to render captch.
	 *
	 * @deprecated 1.5 This function will be removed in 1.6 version. Please use RedshopLayoutHelper::render('registration.captcha') instead.
	 */
	public function getCaptchaTable()
	{
		return RedshopLayoutHelper::render('registration.captcha');
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

	/**
	 * Display an error message
	 *
	 * @param   string  $error  Error message
	 *
	 * @return  void
	 */
	public function setError($error)
	{
		JFactory::getApplication()->enqueueMessage($error, 'error');
	}
}

