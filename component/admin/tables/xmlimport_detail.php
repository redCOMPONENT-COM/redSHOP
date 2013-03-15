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

class Tablexmlimport_detail extends JTable
{
	var $xmlimport_id = null;
	var $filename = null;
	var $display_filename = null;
	var $xmlimport_url = null;
	var $section_type = null;
	var $sync_on_request = 0;
	var $auto_sync = 0;
	var $auto_sync_interval = 0;
	var $override_existing = 0;
	var $add_prefix_for_existing = null;
	var $xmlimport_date = null;
	var $xmlimport_filetag = null;
	var $xmlimport_billingtag = null;
	var $xmlimport_shippingtag = null;
	var $xmlimport_orderitemtag = null;
	var $xmlimport_stocktag = null;
	var $xmlimport_prdextrafieldtag = null;
	var $element_name = null;
	var $billing_element_name = null;
	var $shipping_element_name = null;
	var $orderitem_element_name = null;
	var $stock_element_name = null;
	var $prdextrafield_element_name = null;
	var $published = 0;

	function Tablexmlimport_detail(& $db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'xml_import', 'xmlimport_id', $db);
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