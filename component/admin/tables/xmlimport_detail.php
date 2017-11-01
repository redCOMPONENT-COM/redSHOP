<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class Tablexmlimport_detail extends JTable
{
	public $xmlimport_id = null;

	public $filename = null;

	public $display_filename = null;

	public $xmlimport_url = null;

	public $section_type = null;

	public $sync_on_request = 0;

	public $auto_sync = 0;

	public $auto_sync_interval = 0;

	public $override_existing = 0;

	public $add_prefix_for_existing = null;

	public $xmlimport_date = null;

	public $xmlimport_filetag = null;

	public $xmlimport_billingtag = null;

	public $xmlimport_shippingtag = null;

	public $xmlimport_orderitemtag = null;

	public $xmlimport_stocktag = null;

	public $xmlimport_prdextrafieldtag = null;

	public $element_name = null;

	public $billing_element_name = null;

	public $shipping_element_name = null;

	public $orderitem_element_name = null;

	public $stock_element_name = null;

	public $prdextrafield_element_name = null;

	public $published = 0;

	public function __construct(&$db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'xml_import', 'xmlimport_id', $db);
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
