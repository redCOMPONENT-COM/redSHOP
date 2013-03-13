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

class Tablequotation_detail extends JTable
{
	var $quotation_id = null;
	var $quotation_number = null;
	var $user_id = null;
	var $user_info_id = null;
	var $user_email = null;
	var $order_id = null;
	var $quotation_total = null;
	var $quotation_subtotal = null;
	var $quotation_tax = null;
	var $quotation_discount = 0;
	var $quotation_status = null;
	var $quotation_cdate = null;
	var $quotation_mdate = null;
	var $quotation_note = null;
	var $quotation_ipaddress = null;
	var $quotation_encrkey = null;

	function Tablequotation_detail(& $db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'quotation', 'quotation_id', $db);
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