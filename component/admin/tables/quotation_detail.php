<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class Tablequotation_detail extends JTable
{
	public $quotation_id               = null;

	public $quotation_number           = null;

	public $user_id                    = null;

	public $user_info_id               = null;

	public $user_email                 = null;

	public $order_id                   = null;

	public $quotation_total            = null;

	public $quotation_subtotal         = null;

	public $quotation_tax              = null;

	public $quotation_discount         = 0;

	public $quotation_special_discount = 0;

	public $quotation_status           = null;

	public $quotation_cdate            = null;

	public $quotation_mdate            = null;

	public $quotation_note             = null;

	public $quotation_customer_note    = null;

	public $quotation_ipaddress        = null;

	public $quotation_encrkey          = null;

	public function __construct(&$db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'quotation', 'quotation_id', $db);
	}

	public function bind($array, $ignore = '')
	{
		if (array_key_exists('params', $array) && is_array($array['params']))
		{
			$registry = new JRegistry;
			$registry->loadArray($array['params']);
			$array['params'] = $registry->toString();
		}

		return parent::bind($array, $ignore);
	}
}
