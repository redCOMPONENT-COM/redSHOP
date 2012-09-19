<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 * Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

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
	var $requesting_tax_exempt 	= null;
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

		parent::__construct($this->_table_prefix.'users_info', 'users_info_id', $db);
	}

	function bind($array, $ignore = '')
	{
		if (key_exists( 'params', $array ) && is_array( $array['params'] )) {
			$registry = new JRegistry();
			$registry->loadArray($array['params']);
			$array['params'] = $registry->toString();
		}

		return parent::bind($array, $ignore);
	}

}
?>
