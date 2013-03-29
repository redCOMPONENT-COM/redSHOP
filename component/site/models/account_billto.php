<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('joomla.application.component.model');

/**
 * Class Account_billtoModelaccount_billto
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 * @since       1.0
 */
class Account_billtoModelaccount_billto extends JModel
{
	public $_id = null;

	public $_data = null;

	public $_table_prefix = null;

	public function __construct()
	{
		parent::__construct();

		$this->_table_prefix = '#__redshop_';
	}

	public function _initData()
	{
		if (empty($GLOBALS['billingaddresses']))
		{
			$session = JFactory::getSession();
			$auth    = $session->get('auth');

			if (isset($auth['users_info_id']) && $auth['users_info_id'])
			{
				$order_functions = new order_functions;
				$detail          = $order_functions->getBillingAddress(-$auth['users_info_id']);

				if (!isset($detail->user_id))
				{
					$detail->user_id = - $auth['users_info_id'];
				}
			}
			else
			{
				// Toggler settings
				$is_company = (DEFAULT_CUSTOMER_REGISTER_TYPE == 2) ? 1 : 0;

				// Allow registration type settings
				if (ALLOW_CUSTOMER_REGISTER_TYPE == 1)
				{
					$is_company = 0;
				}
				elseif (ALLOW_CUSTOMER_REGISTER_TYPE == 2)
				{
					$is_company = 1;
				}

				$user   = JFactory::getUser();
				$detail = new stdClass;

				$detail->users_info_id         = 0;
				$detail->user_id               = $user->id;
				$detail->id                    = $user->id;
				$detail->name                  = $user->name;
				$detail->username              = $user->username;
				$detail->email                 = $user->email;
				$detail->user_email            = $user->email;
				$detail->password              = null;
				$detail->is_company            = $is_company;
				$detail->firstname             = null;
				$detail->lastname              = null;
				$detail->address_type          = 'BT';
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
			}

			return $detail;
		}
	}

	public function store($post)
	{
		$userhelper = new rsUserhelper;

		$post['billisship']    = 1;
		$post['createaccount'] = (isset($post['username']) && $post['username'] != "") ? 1 : 0;

		$joomlauser = $userhelper->updateJoomlaUser($post);

		if (!$joomlauser)
		{
			return false;
		}

		$reduser = $userhelper->storeRedshopUser($post, $joomlauser->id);

		return $reduser;
	}
}
