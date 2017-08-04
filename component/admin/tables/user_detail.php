<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class Tableuser_detail extends JTable
{
	public $users_info_id = null;

	public $user_email = null;

	public $user_id = null;

	public $firstname = null;

	public $address_type = null;

	public $lastname = null;

	public $company_name = null;

	public $vat_number = null;

	public $requesting_tax_exempt = null;

	public $tax_exempt = 0;

	public $shopper_group_id = null;

	public $is_company = null;

	public $address = 0;

	public $city = 0;

	public $country_code = null;

	public $state_code = null;

	public $zipcode = 0;

	public $phone = 0;

	public $tax_exempt_approved = 0;

	public $approved = 0;

	public $ean_number = null;

	public $accept_terms_conditions = 0;

	public $veis_vat_number = null;

	public $veis_status = null;

	public function __construct(& $db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'users_info', 'users_info_id', $db);
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
