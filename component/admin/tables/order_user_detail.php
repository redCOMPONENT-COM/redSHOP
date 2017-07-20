<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class Tableorder_user_detail extends JTable
{
	public $order_info_id = null;

	public $users_info_id = null;

	public $order_id = null;

	public $user_id = null;

	public $firstname = null;

	public $address_type = null;

	public $lastname = null;

	public $vat_number = null;

	public $tax_exempt = 0;

	public $requesting_tax_exempt = 0;

	public $shopper_group_id = null;

	public $published = null;

	public $is_company = null;

	public $country_code = null;

	public $state_code = null;

	public $zipcode = 0;

	public $phone = 0;

	public $city = 0;

	public $address = 0;

	public $tax_exempt_approved = 0;

	public $approved = 0;

	public $user_email = null;

	public $company_name = null;

	public $thirdparty_email = null;

	public $ean_number = null;

	public function __construct(&$db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'order_users_info', 'order_info_id', $db);
	}

	public function bind($array, $ignore = '')
	{
		if (array_key_exists('params', $array) && is_array($array['params']))
		{
			$registry = new JRegistry;
			$registry->loadArray($array['params']);
			$array['params'] = $registry->toString();
		}

		return parent::bind($array, $ignore);
	}
}
