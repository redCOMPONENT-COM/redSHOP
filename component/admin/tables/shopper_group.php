<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\Utilities\ArrayHelper;

/**
 * Table Shopper Group Detail
 *
 * @package     RedSHOP.Backend
 * @subpackage  Table
 * @since       __DEPLOY_VERSION__
 */
class RedshopTableShopper_Group extends RedshopTable
{
	/**
	 * The table name without the prefix. Ex: cursos_courses
	 *
	 * @var  string
	 */
	protected $_tableName = 'redshop_shopper_group';

	/**
	 * The table key column. Usually: id
	 *
	 * @var  string
	 */
	protected $_tableKey = 'shopper_group_id';

	/**
	 * Called before bind().
	 *
	 * Method to bind an associative array or object to the JTable instance.This
	 * method only binds properties that are publicly accessible and optionally
	 * takes an array of properties to ignore when binding.
	 *
	 * @param   mixed  &$src    An associative array or object to bind to the JTable instance.
	 * @param   mixed  $ignore  An optional array or space separated list of properties to ignore while binding.
	 *
	 * @return  boolean  True on success.
	 */
	public function beforeBind(&$src, $ignore = array())
	{
		// Bind: Categories
		if (isset($src['shopper_group_categories']) && !empty($src['shopper_group_categories']) && is_array($src['shopper_group_categories']))
		{
			$src['shopper_group_categories'] = ArrayHelper::toInteger($src['shopper_group_categories']);
			$src['shopper_group_categories'] = array_unique(array_filter($src['shopper_group_categories']));
			$src['shopper_group_categories'] = implode(',', $src['shopper_group_categories']);
		}

		// Bind: Manufacturers
		if (isset($src['shopper_group_manufactures']) && !empty($src['shopper_group_manufactures']) && is_array($src['shopper_group_manufactures']))
		{
			$src['shopper_group_manufactures'] = ArrayHelper::toInteger($src['shopper_group_manufactures']);
			$src['shopper_group_manufactures'] = array_unique(array_filter($src['shopper_group_manufactures']));
			$src['shopper_group_manufactures'] = implode(',', $src['shopper_group_manufactures']);
		}

		return parent::beforeBind($src, $ignore);
	}

	/**
	 * Method to bind an associative array or object to the JTable instance.This
	 * method only binds properties that are publicly accessible and optionally
	 * takes an array of properties to ignore when binding.
	 *
	 * @param   mixed  &$src    An associative array or object to bind to the JTable instance.
	 * @param   mixed  $ignore  An optional array or space separated list of properties to ignore while binding.
	 *
	 * @return  boolean  True on success.
	 *
	 * @throws  \InvalidArgumentException
	 */
	protected function doBind(&$src, $ignore = array())
	{
		if (empty($src['shopper_group_categories']) && empty($this->shopper_group_categories))
		{
			$this->shopper_group_categories = null;
			unset($src['shopper_group_categories']);
		}

		return parent::doBind($src, $ignore);
	}
}
