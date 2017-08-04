<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class Tablexmlexport_detail extends JTable
{
	public $xmlexport_id = null;

	public $filename = null;

	public $display_filename = null;

	public $parent_name = null;

	public $element_name = null;

	public $section_type = null;

	public $sync_on_request = 0;

	public $auto_sync = 0;

	public $auto_sync_interval = 0;

	public $xmlexport_date = null;

	public $xmlexport_filetag = null;

	public $xmlexport_billingtag = null;

	public $billing_element_name = null;

	public $xmlexport_shippingtag = null;

	public $shipping_element_name = null;

	public $xmlexport_orderitemtag = null;

	public $orderitem_element_name = null;

	public $xmlexport_stocktag = null;

	public $stock_element_name = null;

	public $xmlexport_prdextrafieldtag = null;

	public $prdextrafield_element_name = null;

	public $published = 0;

	public $use_to_all_users = 1;

	public $xmlexport_on_category = null;

	public function __construct(&$db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'xml_export', 'xmlexport_id', $db);
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
