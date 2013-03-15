<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class Tablequestion_detail extends JTable
{
	var $question_id = null;
	var $parent_id = 0;
	var $product_id = null;
	var $user_id = null;
	var $user_name = null;
	var $user_email = null;
	var $question = null;
	var $question_date = null;
	var $telephone = null;
	var $address = null;
	var $published = 1;
	var $ordering = null;

	function Tablequestion_detail(& $db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'customer_question', 'question_id', $db);
	}

	function bind($array, $ignore = '')
	{
		if (key_exists('params', $array) && is_array($array['params']))
		{
			$registry = new JRegistry();
			$registry->loadArray($array['params']);
			$array['params'] = $registry->toString();
		}

		return parent::bind($array, $ignore);
	}

}