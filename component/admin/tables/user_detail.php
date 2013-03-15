<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.model');

class Tableuser_detail extends JTable
{
	var $users_info_id = null;
	var $user_email = null;
	var $user_id = null;
	var $firstname = null;
	var $address_type = null;
	var $lastname = null;
	var $company_name = null;
	var $vat_number = null;
	var $requesting_tax_exempt = null;
	var $tax_exempt = 0;
	var $shopper_group_id = null;
	var $is_company = null;
	var $address = 0;
	var $city = 0;
	var $country_code = null;
	var $state_code = null;
	var $zipcode = 0;
	var $phone = 0;
	var $tax_exempt_approved = 0;
	var $approved = 0;
	var $ean_number = null;
	var $accept_terms_conditions = 0;
	var $veis_vat_number = null;
	var $veis_status = null;

//	var $requisition_number = null;

	function Tableuser_detail(& $db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'users_info', 'users_info_id', $db);
	}

	function bind($array, $ignore = '')
	{
		if (key_exists('params', $array) && is_array($array['params']))
		{
			$registry = new JRegistry();
			$registry->loadArray($array['params']);
			$array['params'] = $registry->toString();
		}

		return parent::bind($array, $ignore);
	}

}
