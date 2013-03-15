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

class Tableorder_user_detail extends JTable
{
	var $order_info_id = null;
	var $users_info_id = null;
	var $order_id = null;
	var $user_id = null;
	var $firstname = null;
	var $address_type = null;
	var $lastname = null;
	var $vat_number = null;
	var $tax_exempt = 0;
	var $requesting_tax_exempt = 0;
	var $shopper_group_id = null;
	var $published = null;
	var $is_company = null;
	var $country_code = null;
	var $state_code = null;
	var $zipcode = 0;
	var $phone = 0;
	var $city = 0;
	var $address = 0;
	var $tax_exempt_approved = 0;
	var $approved = 0;
	var $user_email = null;
	var $company_name = null;
	var $thirdparty_email = null;
	var $ean_number = null;

//	var $requisition_number = null;

	function Tableorder_user_detail(& $db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'order_users_info', 'order_info_id', $db);
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
?>
