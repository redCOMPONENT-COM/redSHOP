<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Class RedshopModelAccount_Billto
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 * @since       1.0
 */
class RedshopModelAccount_Billto extends RedshopModel
{
	/**
	 * @var  object
	 */
	public $_data = null;

	/**
	 * Init billing address data
	 *
	 * @return   mixed
	 */
	public function _initData()
	{
		$billingAddress = Redshop\User\Billing\Billing::getGlobal();

		if (empty($billingAddress) || $billingAddress == new stdClass())
		{
			$auth = JFactory::getSession()->get('auth');

			if (isset($auth['users_info_id']) && $auth['users_info_id'])
			{
				$detail = RedshopHelperOrder::getBillingAddress(-$auth['users_info_id']);

				if (!isset($detail->user_id))
				{
					$detail->user_id = -$auth['users_info_id'];
				}
			}
			else
			{
				// Toggle settings
				$isCompany = (Redshop::getConfig()->get('DEFAULT_CUSTOMER_REGISTER_TYPE') == 2) ? 1 : 0;

				// Allow registration type settings
				if (Redshop::getConfig()->get('ALLOW_CUSTOMER_REGISTER_TYPE') == 1)
				{
					$isCompany = 0;
				}
				elseif (Redshop::getConfig()->get('ALLOW_CUSTOMER_REGISTER_TYPE') == 2)
				{
					$isCompany = 1;
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
				$detail->is_company            = $isCompany;
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

		return null;
	}

	/**
	 * Method for store billing address
	 *
	 * @param   array  $post  Data
	 *
	 * @return  boolean|Tableuser_detail
	 * @throws  Exception
	 */
	public function store($post)
	{
		$post['billisship']    = 1;
		$post['createaccount'] = (isset($post['username']) && $post['username'] != "") ? 1 : 0;

		$joomlaUser = RedshopHelperJoomla::updateJoomlaUser($post);

		if (!$joomlaUser)
		{
			return false;
		}

		return RedshopHelperUser::storeRedshopUser($post, $joomlaUser->id);
	}
}
