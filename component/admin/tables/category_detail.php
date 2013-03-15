<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.model');

class Tablecategory_detail extends JTable
{
	var $category_id = null;
	var $category_name = null;
	var $category_short_description = null;
	var $category_description = null;
	var $category_template = 0;
	var $category_more_template = 0;
	var $category_full_image = null;
	var $category_thumb_image = null;
	var $category_back_full_image = null;
	var $metakey = null;
	var $metadesc = null;
	var $metalanguage_setting = null;
	var $metarobot_info = null;
	var $append_to_global_seo = 'append';
	var $pagetitle = null;
	var $pageheading = null;
	var $sef_url = null;
	var $published = null;
	var $products_per_page = null;
	var $ordering = null;
	var $compare_template_id = 0;
	var $canonical_url = null;

	function Tablecategory_detail(& $db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'category', 'category_id', $db);
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
