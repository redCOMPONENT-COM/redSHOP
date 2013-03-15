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


class Tableaccountgroup_detail extends JTable
{
	var $accountgroup_id = null;
	var $accountgroup_name = null;
	var $economic_vat_account = null;
	var $economic_nonvat_account = null;
	var $economic_discount_vat_account = null;
	var $economic_discount_nonvat_account = null;
	var $economic_shipping_vat_account = null;
	var $economic_shipping_nonvat_account = null;
	var $economic_discount_product_number = null;
	var $published = 1;


	function Tableaccountgroup_detail(& $db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'economic_accountgroup', 'accountgroup_id', $db);
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

