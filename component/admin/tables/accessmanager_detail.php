<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class Tableaccessmanager_detail extends JTable
{
	public $id = null;

	public $section_name = null;

	public $gid = 0;

	public $view = null;

	public $add = null;

	public $edit = null;

	public $delete = null;

	public function __construct(&$db)
	{


		parent::__construct('#__redshop_accessmanager', 'id', $db);
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
