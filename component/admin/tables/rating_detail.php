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

class Tablerating_detail extends JTable
{
	var $rating_id = 0;
	var $product_id = 0;
	var $title = null;
	var $comment = null;
	var $userid = 0;
	var $time = 0;
	var $user_rating = 0;
	var $favoured = 0;
	var $published = null;
	var $email = null;
	var $username = null;
	var $company_name = null;

	function Tablerating_detail(& $db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'product_rating', 'rating_id', $db);
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
