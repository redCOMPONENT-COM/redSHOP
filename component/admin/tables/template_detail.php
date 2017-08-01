<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class Tabletemplate_detail extends JTable
{
	public $template_id = null;

	public $template_name = null;

	public $template_section = null;

	public $template_desc = null;

	public $shipping_methods = null;

	public $payment_methods = null;

	public $order_status = null;

	public $published = null;

	/**
	 * @var boolean
	 */
	public $checked_out = 0;

	/**
	 * @var time
	 */
	public $checked_out_time = 0;

	public function __construct(& $db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'template', 'template_id', $db);
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
