<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Redshop\Helper\Utility;

/**
 * redSHOP User Helper class
 *
 * @since  1.0.0
 */
class RsUserHelper
{
	public $_session = null;

	public $_userId = null;

	public $db = null;

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

	/**
	 * rsUserHelper constructor.
	 */
	public function __construct()
	{
		$this->_session = JFactory::getSession();
		$this->db       = JFactory::getDbo();
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
	 * @param   integer  $userId  User identifier
	 *
	 * @return  array             Array of user groups
	 *
	 * @deprecated  2.0.6
	 */
	public function getUserGroupList($userId = 0)
	{
		return RedshopHelperUser::getUserGroups($userId);
	}

	/**
	 * Method for update term & conditions of user.
	 *
	 * @param   int  $userInfoId  RedSHOP User ID
	 * @param   int  $isSet       Is set?
	 *
	 * @return  void
	 *
	 * @deprecated  2.0.6
	 */
	public function updateUserTermsCondition($userInfoId = 0, $isSet = 0)
	{
		RedshopHelperUser::updateUserTermsCondition($userInfoId, $isSet);
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
	public function getShopperGroupData($userId = 0)
	{
		return RedshopHelperUser::getShopperGroupData($userId);
	}

	/**
	 * Get Shopper Group List
	 *
	 * @param   int  $shopperGroupId  Shopper Group Id
	 *
	 * @return  array
	 *
	 * @deprecated   __DEPLOY_VERSION__
	 */
	public function getShopperGroupList($shopperGroupId = 0)
	{
		return Redshop\Helper\ShopperGroup::generateList($shopperGroupId);
	}

	/**
	 * Create redshop user session
	 *
	 * @param   int  $userId  Joomla user id
	 *
	 * @deprecated  1.5  Use RedshopHelperUser::createUserSession instead
	 *
	 * @return  array|mixed
	 */
	public function createUserSession($userId = 0)
	{
		return RedshopHelperUser::createUserSession($userId);
	}

	/**
	 * This function is used to check if the 'username' already exist in the database with any other ID
	 *
	 * @param   string   $username  Username for validate
	 * @param   integer  $id        ID of user
	 *
	 * @deprecated  1.5  Use RedshopHelperUser::validateUser instead
	 *
	 * @return  integer
	 */
	public function validate_user($username, $id = 0)
	{
		return RedshopHelperUser::validateUser($username, $id);
	}

	/**
	 * This function is used to check if the 'email' already exist in the database with any other ID
	 *
	 * @param   string   $email  Email for validate
	 * @param   integer  $id     ID of user
	 *
	 * @deprecated  1.5  Use RedshopHelperUser::validateEmail instead
	 *
	 * @return  integer
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
				$reduser     = new stdClass;
				$reduser->id = $data['user_id'];

				return $reduser;
			}
		}

		if ($app->isAdmin() && $data['user_id'] < 0 && isset($data['users_info_id']))
		{
			$reduser     = new stdClass;
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

	/**
	 * Method for create Joomla user.
	 *
	 * @param   array  $data        User data.
	 * @param   int    $createUser  Create user
	 *
	 * @return  boolean|JUser       JUser if success. False otherwise.
	 *
	 * @deprecated    __DEPLOY_VERSION__
	 */
	public function createJoomlaUser($data, $createUser = 0)
	{
		return RedshopHelperJoomla::createJoomlaUser($data, (boolean) $createUser);
	}

	/**
	 * This function is for check captcha code
	 *
	 * @param   string   $data            The answer
	 * @param   boolean  $displayWarning  Display warning or not.
	 *
	 * @deprecated  __DEPLOY_VERSION__
	 *
	 * @return  boolean
	 */
	public function checkCaptcha($data, $displayWarning = true)
	{
		return Utility::checkCaptcha($data, $displayWarning);
	}

	/**
	 * Method for store redshop user.
	 *
	 * @param   array    $data    User data.
	 * @param   integer  $userId  ID of user
	 * @param   integer  $admin   Is admin user.
	 *
	 * @return  bool|\JTable      RedshopTableUser if success. False otherwise.
	 *
	 * @deprecated    __DEPLOY_VERSION__
	 */
	public function storeRedshopUser($data, $userId = 0, $admin = 0)
	{
		return RedshopHelperUser::storeRedshopUser($data, $userId, $admin);
	}

	public function storeRedshopUserShipping($data)
	{
		$extra_field = extra_field::getInstance();

		$rowShip = JTable::getInstance('user_detail', 'Table');

		if (!$rowShip->bind($data))
		{
			$this->setError($this->db->getErrorMsg());

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
			$this->setError($this->db->getErrorMsg());

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

	/**
	 * Method for synchronize Joomla User to redSHOP user
	 *
	 * @return  int   Number of synchronized user.
	 *
	 * @since   2.0.6
	 *
	 * @deprecated  2.0.6  Use RedshopInstall::synchronizeUser() instead.
	 */
	public function userSynchronization()
	{
		return RedshopInstall::synchronizeUser();
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

	/**
	 * Method for unsubscribe email from newsletter
	 *
	 * @param   string  $email  Email
	 *
	 * @return  boolean
	 *
	 * @deprecated  __DEPLOY_VERSION__
	 */
	public function newsletterUnsubscribe($email = "")
	{
		return RedshopHelperNewsletter::removeSubscribe($email);
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
			$checkbox_style    = '';

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
		$template_desc   = str_replace("{phone_optional}", '', $template_desc);
		$template_desc   = str_replace(
			"{phone}",
			'<input class="inputbox ' . $phoneIsRequired . '" type="text" name="phone" id="phone" size="32" maxlength="250" value="' . (isset($post["phone"]) ? $post["phone"] : '') . '" onblur="return searchByPhone(this.value,\'BT\');" />',
			$template_desc
		);
		$template_desc   = str_replace("{phone_lbl}", JText::_('COM_REDSHOP_PHONE'), $template_desc);

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
			$allowCompany = '';
			$taxExempt    = '';

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

		$statearray             = RedshopHelperWorld::getStateList($post, 'state_code_ST', 'ST');
		$lists['state_code_ST'] = $statearray['state_dropdown'];

		$countrystyle = (count($countryarray['countrylist']) == 1 && count($statearray['statelist']) == 0) ? 'display:none;' : '';
		$statestyle   = ($statearray['is_states'] <= 0) ? 'display:none;' : '';

		$template_desc = str_replace("{firstname_st_lbl}", JText::_('COM_REDSHOP_FIRSTNAME'), $template_desc);
		$value         = (!empty($post["firstname_ST"])) ? $post["firstname_ST"] : '';
		$template_desc = str_replace("{firstname_st}", '<input class="inputbox billingRequired valid" type="text" name="firstname_ST" id="firstname_ST" size="32" maxlength="250" value="' . $value . '" data-msg="' . JText::_('COM_REDSHOP_THIS_FIELD_IS_REQUIRED') . '"/>', $template_desc);
		$template_desc = str_replace("{lastname_st_lbl}", JText::_('COM_REDSHOP_LASTNAME'), $template_desc);
		$value         = (!empty($post["lastname_ST"])) ? $post["lastname_ST"] : '';
		$template_desc = str_replace("{lastname_st}", '<input class="inputbox billingRequired valid" type="text" name="lastname_ST" id="lastname_ST" size="32" maxlength="250" value="' . $value . '" data-msg="' . JText::_('COM_REDSHOP_THIS_FIELD_IS_REQUIRED') . '"/>', $template_desc);
		$template_desc = str_replace("{address_st_lbl}", JText::_('COM_REDSHOP_ADDRESS'), $template_desc);
		$value         = (!empty($post["address_ST"])) ? $post["address_ST"] : '';
		$template_desc = str_replace("{address_st}", '<input class="inputbox billingRequired valid" type="text" name="address_ST" id="address_ST" size="32" maxlength="250" value="' . $value . '" data-msg="' . JText::_('COM_REDSHOP_THIS_FIELD_IS_REQUIRED') . '"/>', $template_desc);
		$template_desc = str_replace("{zipcode_st_lbl}", JText::_('COM_REDSHOP_ZIP'), $template_desc);
		$value         = (!empty($post["zipcode_ST"])) ? $post["zipcode_ST"] : '';
		$template_desc = str_replace("{zipcode_st}", '<input class="inputbox billingRequired valid zipcode" type="text" name="zipcode_ST" id="zipcode_ST" size="32" maxlength="10" value="' . $value . '" onblur="return autoFillCity(this.value,\'ST\');" data-msg="' . JText::_('COM_REDSHOP_YOUR_MUST_PROVIDE_A_ZIP') . '" />', $template_desc);
		$template_desc = str_replace("{city_st_lbl}", JText::_('COM_REDSHOP_CITY'), $template_desc);
		$value         = (!empty($post["city_ST"])) ? $post["city_ST"] : '';
		$template_desc = str_replace("{city_st}", '<input class="inputbox billingRequired valid" type="text" name="city_ST" ' . $read_only . ' id="city_ST" value="' . $value . '" size="32" maxlength="250" data-msg="' . JText::_('COM_REDSHOP_THIS_FIELD_IS_REQUIRED') . '"/>', $template_desc);
		$template_desc = str_replace("{phone_st_lbl}", JText::_('COM_REDSHOP_PHONE'), $template_desc);
		$value         = (!empty($post["phone_ST"])) ? $post["phone_ST"] : '';
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
		$shopperGroupdata           = Redshop\Helper\ShopperGroup::generateList($shopperGroupId);
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
