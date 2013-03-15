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

class Tablexmlexport_detail extends JTable
{
	var $xmlexport_id = null;
	var $filename = null;
	var $display_filename = null;
	var $parent_name = null;
	var $element_name = null;
	var $section_type = null;
	var $sync_on_request = 0;
	var $auto_sync = 0;
	var $auto_sync_interval = 0;
	var $xmlexport_date = null;
	var $xmlexport_filetag = null;
	var $xmlexport_billingtag = null;
	var $billing_element_name = null;
	var $xmlexport_shippingtag = null;
	var $shipping_element_name = null;
	var $xmlexport_orderitemtag = null;
	var $orderitem_element_name = null;
	var $xmlexport_stocktag = null;
	var $stock_element_name = null;
	var $xmlexport_prdextrafieldtag = null;
	var $prdextrafield_element_name = null;
	var $published = 0;
	var $use_to_all_users = 1;
	var $xmlexport_on_category = null;

	function Tablexmlexport_detail(& $db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'xml_export', 'xmlexport_id', $db);
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