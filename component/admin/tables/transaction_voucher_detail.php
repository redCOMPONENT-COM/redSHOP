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

class Tabletransaction_voucher_detail extends JTable
{
	var $transaction_voucher_id = null;
	var $voucher_id = null;
	var $voucher_code = null;
	var $amount = null;
	var $user_id = null;
	var $order_id = null;
	var $trancation_date = null;
	var $product_id = null;
	var $published = null;

	function Tabletransaction_voucher_detail(& $db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'product_voucher_transaction', 'transaction_voucher_id', $db);
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