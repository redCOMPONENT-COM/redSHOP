<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Barcode reder/generator Model
 *
 * @package     Redshop
 * @subpackage  Barcode
 * @since       1.2
 */
class TableBarcode extends JTable
{
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	public $log_id = null;

	/**
	 * @var string
	 */
	public $order_id = null;

	public $user_id = null;

	public $barcode = null;

	public $search_date = null;

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	public function __construct(&$db)
	{

		parent::__construct('#__redshop_orderbarcode_log', 'log_id', $db);
	}
}
