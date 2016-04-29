<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class Tablequestion_detail extends JTable
{
	public $question_id = null;

	public $parent_id = 0;

	public $product_id = null;

	public $user_id = null;

	public $user_name = null;

	public $user_email = null;

	public $question = null;

	public $question_date = null;

	public $telephone = null;

	public $address = null;

	public $published = 1;

	public $ordering = null;

	public function __construct(&$db)
	{
		parent::__construct('#__redshop_customer_question', 'question_id', $db);
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
