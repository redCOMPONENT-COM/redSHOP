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

class Tableshopper_group_detail extends JTable
{
	var $shopper_group_id = null;
	var $shopper_group_name = null;
	var $shopper_group_customer_type = null;
	var $shopper_group_portal = null;
	var $shopper_group_categories = null;
	var $shopper_group_url = null;
	var $shopper_group_logo = null;
	var $shopper_group_introtext = null;
	var $shopper_group_desc = null;
	var $parent_id = null;
	var $default_shipping = 0;
	var $default_shipping_rate = null;
//	var $tax_exempt_on_shipping = 0;
//	var $tax_exempt = 0;
	var $show_price_without_vat = 0;
	var $show_price = 'global';
	var $use_as_catalog = 'global';
	var $published = null;
	var $shopper_group_cart_checkout_itemid = 0;
	var $tax_group_id = 0;
	var $apply_product_price_vat = 0;
	var $shopper_group_cart_itemid = 0;
	var $shopper_group_quotation_mode = 0;
	var $shopper_group_manufactures = null;

	function Tableshopper_group_detail(& $db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'shopper_group', 'shopper_group_id', $db);
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
