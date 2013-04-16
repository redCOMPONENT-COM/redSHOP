<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class Tablecontainer_detail extends JTable
{
	public $container_id = null;

	public $container_name = null;

	public $creation_date = null;

	public $container_desc = null;

	public $min_del_time = null;

	public $max_del_time = null;

	public $container_volume = null;

	public $stockroom_id = null;

	public $manufacture_id = null;

	public $supplier_id = null;

	public $published = null;

	public function __construct(&$db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'container', 'container_id', $db);
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
