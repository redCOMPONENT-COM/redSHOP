<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * The mass discounts view
 *
 * @package     RedSHOP.Backend
 * @subpackage  States.View
 * @since       2.0.3
 */
class RedshopViewMass_Discounts extends RedshopViewList
{
	/**
	 * Column for render published state.
	 *
	 * @var    array
	 * @since  2.0.6
	 */
	protected $stateColumns = array();

	/**
	 * Method for render 'Published' column
	 *
	 * @param   array   $config  Row config.
	 * @param   int     $index   Row index.
	 * @param   object  $row     Row data.
	 *
	 * @return  string
	 *
	 * @since   2.0.7
	 */
	public function onRenderColumn($config, $index, $row)
	{
		$value = $row->{$config['dataCol']};

		switch ($config['dataCol'])
		{
			case 'type':
				if ($value == 1)
				{
					return '<span class="label label-success">' . JText::_('COM_REDSHOP_MASS_DISCOUNT_TYPE_OPTION_PERCENTAGE') . '</span>';
				}

				return '<span class="label label-primary">' . JText::_('COM_REDSHOP_MASS_DISCOUNT_TYPE_OPTION_TOTAL') . '</span>';

			case 'start_date':
			case 'end_date':
				if (empty($value))
				{
					return '';
				}

				return JFactory::getDate($value)->format(Redshop::getConfig()->get('DEFAULT_DATEFORMAT', 'd-m-Y'));

			case 'discount_product':
				if (empty($value))
				{
					return '';
				}

				return $this->generateList($value, 'Product', 'product_name');

			case 'category_id':
				if (empty($value))
				{
					return '';
				}

				return $this->generateList($value, 'Category', 'name');

			case 'manufacturer_id':
				if (empty($value))
				{
					return '';
				}

				return $this->generateList($value, 'Manufacturer', 'manufacturer_name');

			default:
				return parent::onRenderColumn($config, $index, $row);
		}
	}

	/**
	 * Method for return list of object->property
	 *
	 * @param   string  $ids       Array list.
	 * @param   string  $entity    Entity class
	 * @param   string  $property  Property name
	 *
	 * @return  string
	 *
	 * @since   2.0.7
	 */
	protected function generateList($ids, $entity, $property)
	{
		if (empty($ids) || empty($entity) || empty($property))
		{
			return '';
		}

		$ids    = explode(',', $ids);
		$return = array();
		$entity = 'RedshopEntity' . $entity;

		if (!class_exists($entity))
		{
			return '';
		}

		foreach ($ids as $id)
		{
			$return[] = $entity::getInstance($id)->get($property);
		}

		return implode('<br />', $return);
	}
}
