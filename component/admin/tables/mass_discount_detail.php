<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class Tablemass_discount_detail extends JTable
{
	public $mass_discount_id = 0;

	public $discount_name = null;

	public $discount_product = null;

	public $category_id = null;

	public $discount_type = null;

	public $discount_amount = null;

	public $discount_startdate = null;

	public $discount_enddate = null;

	public $manufacturer_id = null;

	public function __construct(&$db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'mass_discount', 'mass_discount_id', $db);
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

	/**
	 * Validate all table fields
	 *
	 * @return  bool
	 *
	 * @since  2.0.2
	 */
	public function check()
	{
		if (empty($this->discount_name))
		{
			JFactory::getApplication()->enqueueMessage(JText::_('COM_REDSHOP_MASS_DISCOUNT_MISSING_DISCOUNT_NAME'), 'error');

			return false;
		}

		if (empty($this->discount_amount))
		{
			JFactory::getApplication()->enqueueMessage(JText::_('COM_REDSHOP_MASS_DISCOUNT_DISCOUNT_AMOUNT_MUST_BE_LARGER_THAN_ZERO'), 'error');

			return false;
		}

		if (is_null($this->discount_type))
		{
			JFactory::getApplication()->enqueueMessage(JText::_('COM_REDSHOP_MASS_DISCOUNT_DISCOUNT_TYPE_IS_REQUIRED'), 'error');

			return false;
		}

		return parent::check();
	}
}
