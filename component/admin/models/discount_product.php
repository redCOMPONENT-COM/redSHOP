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
 * @since       __DEPLOY_VERSION__
 */
class RedshopModelDiscount_Product extends RedshopModelForm
{
	/**
	 * Method to save the form data.
	 *
	 * @param   array  $data  The form data.
	 *
	 * @return  boolean  True on success, False on error.
	 *
	 * @since   __DEPLOY_VERSION__
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

		$data['category_ids'] = isset($data['category_ids']) && is_array($data['category_ids']) ? implode(',', $data['category_ids']) : '';

		return parent::save($data);
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 *
	 * @since   __DEPLOY_VERSION__
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
	 * @since   __DEPLOY_VERSION__
	 */
	public function getItem($pk = null)
	{
		$item = parent::getItem();

		if (false === $item)
		{
			return false;
		}

		$dateFormat = Redshop::getConfig()->getString('DEFAULT_DATEFORMAT', 'Y-m-d');

		$item->shopper_group = RedshopEntityDiscount_Product::getInstance($item->discount_product_id)->getShopperGroups()->ids();
		$item->start_date    = !empty($item->start_date) ? JFactory::getDate($item->start_date)->format($dateFormat) : null;
		$item->end_date      = !empty($item->end_date) ? JFactory::getDate($item->end_date)->format($dateFormat) : null;
		$item->category_ids  = explode(',', $item->category_ids);

		return $item;
	}
}
