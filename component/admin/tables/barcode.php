<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

/**
 * Barcode reder/generator Model
 *
 * @package    redSHOP
 * @version    1.2
 */
class TableBarcode extends JTable
{
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $log_id = null;

	/**
	 * @var string
	 */
	var $order_id = null;

	var $user_id = null;
	var $barcode = null;
	var $search_date = null;


	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function TableBarcode(& $db)
	{

		$this->_table_prefix = '#__redshop_';
		parent::__construct($this->_table_prefix . 'orderbarcode_log', 'log_id', $db);
	}
}