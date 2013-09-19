<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class Tablemedia_detail extends JTable
{
	public function __construct(&$db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'media', 'media_id', $db);
	}

	public function bind($array, $ignore = '')
	{
		if (array_key_exists('params', $array) && is_array($array['params']))
		{
			$registry = new JRegistry;
			$registry->loadArray($array['params']);
			$array['params'] = $registry->toString();
		}

		if (array_key_exists('extra_video', $array) && is_array($array['extra_video']))
		{
			$registry = new JRegistry;
			$registry->loadArray($array['extra_video']);
			$array['extra_video'] = $registry->toString();
		}

		return parent::bind($array, $ignore);
	}
}
