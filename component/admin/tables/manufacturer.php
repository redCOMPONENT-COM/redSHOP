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
	/**
	 * @var    null
	 * @since  2.0.0.3
	 */
	public $manufacturer_id = null;

	/**
	 * @var    null
	 * @since  2.0.0.3
	 */
	public $manufacturer_name = null;

	/**
	 * @var    null
	 * @since  2.0.0.3
	 */
	public $manufacturer_desc = null;

	/**
	 * @var    null
	 * @since  2.0.0.3
	 */
	public $manufacturer_email = null;

	/**
	 * @var    null
	 * @since  2.0.0.3
	 */
	public $manufacturer_url = null;

	/**
	 * @var    null
	 * @since  2.0.0.3
	 */
	public $product_per_page = 0;

	/**
	 * @var    null
	 * @since  2.0.0.3
	 */
	public $template_id = null;

	/**
	 * @var    null
	 * @since  2.0.0.3
	 */
	public $metakey = null;

	/**
	 * @var    null
	 * @since  2.0.0.3
	 */
	public $metadesc = null;

	/**
	 * @var    null
	 * @since  2.0.0.3
	 */
	public $metalanguage_setting = null;

	/**
	 * @var    null
	 * @since  2.0.0.3
	 */
	public $metarobot_info = null;

	/**
	 * @var    null
	 * @since  2.0.0.3
	 */
	public $pagetitle = null;

	/**
	 * @var    null
	 * @since  2.0.0.3
	 */
	public $pageheading = null;

	/**
	 * @var    null
	 * @since  2.0.0.3
	 */
	public $sef_url = null;

	/**
	 * @var    null
	 * @since  2.0.0.3
	 */
	public $published = null;

	/**
	 * @var    null
	 * @since  2.0.0.3
	 */
	public $ordering = null;

	/**
	 * @var    null
	 * @since  2.0.0.3
	 */
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
