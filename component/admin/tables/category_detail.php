<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 * Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

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
	var	$metadesc = null;
	var	$metalanguage_setting = null;
	var	$metarobot_info = null;
	var	$append_to_global_seo 	= 'append';
	var	$pagetitle = null;
	var	$pageheading = null;
	var	$sef_url = null;
	var $published = null;
	var $products_per_page = null;
	var $ordering 			= null;
	var $compare_template_id = 0;
	function Tablecategory_detail(& $db)
	{
	  $this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix.'category', 'category_id', $db);
	}

	function bind($array, $ignore = '')
	{
		if (key_exists( 'params', $array ) && is_array( $array['params'] )) {
			$registry = new JRegistry();
			$registry->loadArray($array['params']);
			$array['params'] = $registry->toString();
		}
		return parent::bind($array, $ignore);
	}

}
?>
