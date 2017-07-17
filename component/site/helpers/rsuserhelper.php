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
	/**
	 * @var self
	 */
	protected static $instance;

	/**
	 * Returns the rsUserHelper object, only creating it if it doesn't already exist.
	 *
	 * @return  self  The rsUserHelper object
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

	/**
	 * Method for update Joomla user.
	 *
	 * @param   array   $data  User data.
	 *
	 * @return  boolean|JUser|stdClass       JUser if success. False otherwise.
	 *
	 * @deprecated   __DEPLOY_VERSION__  Use RedshopHelperJoomla::updateJoomlaUser
	 */
	public function updateJoomlaUser($data)
	{
		return RedshopHelperJoomla::updateJoomlaUser($data);
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

	/**
	 * Method for store user shipping data
	 *
	 * @param   array  $data  Available data.
	 *
	 * @return  boolean|Tableuser_detail  Table user if success. False otherwise.
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperUser::storeRedshopUserShipping
	 */
	public function storeRedshopUserShipping($data)
	{
		return RedshopHelperUser::storeRedshopUserShipping($data);
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

	/**
	 * Method for render billing layout
	 *
	 * @param   array    $post            Available data.
	 * @param   integer  $isCompany       Is company?
	 * @param   array    $lists           Lists
	 * @param   integer  $showShipping    Show shipping?
	 * @param   integer  $showNewsletter  Show newsletter?
	 * @param   integer  $createAccount   Is create account?
	 *
	 * @return  string                    HTML content layout.
	 *
	 * @deprecated   __DEPLOY_VERSION__   Use RedshopHelperBilling::renderTemplate instead
	 */
	public function getBillingTable($post = array(), $isCompany = 0, $lists, $showShipping = 0, $showNewsletter = 0, $createAccount = 1)
	{
		return RedshopHelperBilling::render($post, $isCompany, $lists, $showShipping, $showNewsletter, $createAccount);
	}

	/**
	 * Method for replace billing common fields
	 *
	 * @param   string  $templateHtml  Html content
	 * @param   array   $data          Data
	 * @param   array   $lists         Array select
	 *
	 * @return  string
	 *
	 * @deprecated   __DEPLOY_VERSION__  Use RedshopHelperBilling::replaceCommonFields
	 */
	public function replaceBillingCommonFields($templateHtml, $data, $lists)
	{
		return RedshopHelperBilling::replaceCommonFields($templateHtml, $data, $lists);
	}

	/**
	 * Method for replace private customer billing fields.
	 *
	 * @param   string  $templateHtml  Template content
	 * @param   array   $post          Available data.
	 * @param   array   $lists         Available list data.
	 *
	 * @return  string                 Html content after replace
	 *
	 * @deprecated   __DEPLOY_VERSION__  Use RedshopHelperBilling::replacePrivateCustomer
	 */
	public function replacePrivateCustomer($templateHtml = '', $post = array(), $lists = array())
	{
		return RedshopHelperBilling::replacePrivateCustomer($templateHtml, $post, $lists);
	}

	/**
	 * Method for replace company billing fields.
	 *
	 * @param   string  $templateHtml  Template content
	 * @param   array   $post          Available data.
	 * @param   array   $lists         Available list data.
	 *
	 * @return  string                 Html content after replace
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperBilling::replaceCompanyCustomer instead.
	 */
	public function replaceCompanyCustomer($templateHtml = '', $post = array(), $lists = array())
	{
		return RedshopHelperBilling::replaceCompanyCustomer($templateHtml, $post, $lists);
	}

	/**
	 * Method for get shipping table
	 *
	 * @param   array    $post       Available data.
	 * @param   integer  $isCompany  Is company?
	 * @param   array    $lists      List of data.
	 *
	 * @return  string
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperShipping::getShippingTable
	 */
	public static function getShippingTable($post = array(), $isCompany = 0, $lists = array())
	{
		return RedshopHelperShipping::getShippingTable($post, $isCompany, $lists);
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

	/**
	 * Method for get shopper group manufacturers of specific user.
	 *
	 * @return  string  List of manufacturer Ids.
	 *
	 * @deprecated  __DEPLOY_VERSION__
	 */
	public function getShopperGroupManufacturers()
	{
		return RedshopHelperShopper_Group::getShopperGroupManufacturers();
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
