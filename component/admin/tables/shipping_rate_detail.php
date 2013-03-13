<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class Tableshipping_rate_detail extends JTable
{
	var $shipping_rate_id = null;
	var $shipping_rate_name = null;
	var $shipping_class = null;

	var $shipping_rate_country = null;
	var $shipping_rate_volume_start = null;
	var $shipping_rate_volume_end = null;
	var $shipping_rate_weight_start = null;
	var $shipping_rate_weight_end = null;
	var $shipping_rate_ordertotal_start = null;
	var $shipping_rate_ordertotal_end = null;
	var $shipping_rate_zip_start = null;
	var $shipping_rate_zip_end = null;
	var $shipping_rate_value = null;
	var $shipping_rate_priority = null;
	var $shipping_rate_package_fee = null;
	var $shipping_rate_on_product = null;
	var $shipping_rate_on_category = null;
	var $company_only = null;
	var $apply_vat = 0;
	var $shipping_location_info = null;
	var $shipping_rate_length_start = 0;
	var $shipping_rate_length_end = 0;
	var $shipping_rate_width_start = 0;
	var $shipping_rate_width_end = 0;
	var $shipping_rate_height_start = 0;
	var $shipping_rate_height_end = 0;
	var $shipping_tax_group_id = null;
	var $shipping_rate_state = null;
	var $shipping_rate_on_shopper_group = null;
	var $consignor_carrier_code = null;
	var $economic_displaynumber = null;
	var $deliver_type = null;

	function Tableshipping_rate_detail(& $db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'shipping_rate', 'shipping_rate_id', $db);
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
