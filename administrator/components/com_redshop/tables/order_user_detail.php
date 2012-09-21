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
	var $requesting_tax_exempt 	= 0;
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

		parent::__construct($this->_table_prefix.'order_users_info', 'order_info_id', $db);
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
