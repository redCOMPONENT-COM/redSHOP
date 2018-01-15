<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Model Discount Product
 *
 * @package     RedSHOP.Backend
 * @subpackage  Model
 * @since       2.1.0
 */
class RedshopModelDiscount_Product extends RedshopModelForm
{
	/**
	 * Method to save the form data.
	 *
	 * @param   array  $data  The form data.
	 *
	 * @return  boolean       True on success, False on error.
	 *
	 * @since   2.1.0
	 */
	public function save($data)
	{
		if (!empty($data['start_date']) && !is_numeric($data['start_date']))
		{
			$data['start_date'] = JFactory::getDate($data['start_date'])->toUnix();
		}

		if (!empty($data['end_date']) && !is_numeric($data['end_date']))
		{
			$data['end_date'] = JFactory::getDate($data['end_date'])->toUnix();
		}

		$data['start_date']   = (int) $data['start_date'];
		$data['end_date']     = (int) $data['end_date'];
		$data['category_ids'] = isset($data['category_ids']) && is_array($data['category_ids']) ? implode(',', $data['category_ids']) : '';

		return parent::save($data);
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 *
	 * @since   2.1.0
	 *
	 * @throws  Exception
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$app  = JFactory::getApplication();
		$data = $app->getUserState('com_redshop.edit.discount_product.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
		}

		$this->preprocessData('com_redshop.discount_product', $data);

		return $data;
	}

	/**
	 * Method to get a single record.
	 *
	 * @param   integer  $pk  The id of the primary key.
	 *
	 * @return  JObject|boolean  Object on success, false on failure.
	 *
	 * @since   2.1.0
	 */
	public function getItem($pk = null)
	{
		$item = parent::getItem();

		if (false === $item)
		{
			return false;
		}

		$item->shopper_group = RedshopEntityDiscount_Product::getInstance($item->discount_product_id)->getShopperGroups()->ids();
		$item->category_ids  = explode(',', $item->category_ids);

		return $item;
	}
}
