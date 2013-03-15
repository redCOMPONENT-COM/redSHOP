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

class Tablemanufacturer_detail extends JTable
{
	var $manufacturer_id = null;
	var $manufacturer_name = null;
	var $manufacturer_desc = null;
	var $manufacturer_email = null;
	var $manufacturer_url = null;
	var $product_per_page = 0;
	var $template_id = null;
	var $metakey = null;
	var $metadesc = null;
	var $metalanguage_setting = null;
	var $metarobot_info = null;
	var $pagetitle = null;
	var $pageheading = null;
	var $sef_url = null;
	var $published = null;
	var $ordering = null;
	var $excluding_category_list = null;

	function Tablemanufacturer_detail(& $db)
	{
		$this->_table_prefix = '#__redshop_';
		parent::__construct($this->_table_prefix . 'manufacturer', 'manufacturer_id', $db);
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

?>