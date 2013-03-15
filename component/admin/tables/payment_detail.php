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

class Tablepayment_detail extends JTable
{
	var $payment_method_id = null;
	var $payment_method_name = null;
	var $payment_class = null;
	var $payment_method_code = null;
	var $published = null;
	var $is_creditcard = null;
	var $accepted_credict_card = null;
	var $payment_extrainfo = null;
	var $payment_price = null;
	var $payment_discount_is_percent = null;
	var $payment_passkey = null;
	var $params = null;
	var $plugin = null;
	var $ordering = null;
	var $shopper_group = null;
	var $payment_oprand = '+';

	function Tablepayment_detail(& $db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'payment_method', 'payment_method_id', $db);
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
