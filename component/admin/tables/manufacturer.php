<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class RedshopTableManufacturer extends JTable
{
	public $manufacturer_id = null;

	public $manufacturer_name = null;

	public $manufacturer_desc = null;

	public $manufacturer_email = null;

	public $manufacturer_url = null;

	public $product_per_page = 0;

	public $template_id = null;

	public $metakey = null;

	public $metadesc = null;

	public $metalanguage_setting = null;

	public $metarobot_info = null;

	public $pagetitle = null;

	public $pageheading = null;

	public $sef_url = null;

	public $published = null;

	public $ordering = null;

	public $excluding_category_list = null;

	/**
	 * RedshopTableManufacturer constructor.
	 *
	 * @param   object  &$db  Database object
	 */
	public function __construct(& $db)
	{
		parent::__construct('#__redshop_manufacturer', 'manufacturer_id', $db);
	}

	/**
	 * Bind data
	 *
	 * @param   array|object  $array  Data
	 * @param   array         $ignore Ignore fields
	 *
	 * @return  bool
	 *
	 * @since   2.0.0.3
	 */
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

	/**
	 * Validate data fields
	 *
	 * @return  bool
	 *
	 * @since   2.0.0.3
	 */
	public function check ()
	{
		if (empty($this->manufacturer_name))
		{
			return false;
		}

		return parent::check();
	}
}
