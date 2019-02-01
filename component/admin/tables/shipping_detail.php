<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class Tableshipping_detail extends JTable
{
	public $shipping_id = null;

	public $shipping_name = null;

	public $shipping_class = null;

	public $shipping_method_code = null;

	public $published = null;

	public $shipping_details = null;

	public $params = null;

	public $plugin = null;

	public $ordering = null;

	public function __construct(&$db)
	{
		$this->_table_prefix = '#__extensions';

		parent::__construct($this->_table_prefix, 'extension_id', $db);
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
