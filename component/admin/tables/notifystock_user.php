<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class Tablenotifystock_user extends JTable
{
	public $id = null;

	public $product_id = null;

	public $property_id = null;

	public $subproperty_id = null;

	public $user_id = null;

	public $notification_status = null;

	public function __construct(&$db)
	{

		parent::__construct('#__redshop_notifystock_users', 'id', $db);
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
