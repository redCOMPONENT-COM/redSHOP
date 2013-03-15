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

class Tableorder_payment extends JTable
{
	var $payment_order_id = null;

	var $order_id = null;
	var $payment_method_id = null;
	var $order_payment_code = null;
	var $order_payment_cardname = null;
	var $order_payment_number = null;
	var $order_payment_ccv = null;
	var $order_payment_expire = null;
	var $order_payment_name = null;
	var $order_payment_trans_id = null;
	var $order_payment_amount = null;
	var $authorize_status = null;

	function Tableorder_payment(& $db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'order_payment', 'payment_order_id', $db);
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