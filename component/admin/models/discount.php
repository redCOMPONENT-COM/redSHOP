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
 * Model Discount
 *
 * @package     RedSHOP.Backend
 * @subpackage  Model
 * @since       2.1.0
 */
class RedshopModelDiscount extends RedshopModelForm
{
	use Redshop\Model\Traits\HasDateTimeRange;

	/**
	 * Method to save the form data.
	 *
	 * @param   array $data The form data.
	 *
	 * @return  boolean  True on success, False on error.
	 *
	 * @since   1.6
	 */
	public function save($data)
	{
		$this->handleDateTimeRange($data['start_date'], $data['end_date']);

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
		$data = $app->getUserState('com_redshop.edit.discount.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
		}

		$this->preprocessData('com_redshop.discount', $data);

		return $data;
	}

	/**
	 * Method to get a single record.
	 *
	 * @param   integer $pk The id of the primary key.
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

		$item->shopper_group = RedshopEntityDiscount::getInstance($item->discount_id)->getShopperGroups()->ids();

		$spgrpdisFilter = JFactory::getApplication()->input->getInt('spgrpdis_filter', 0);

		if (empty($item->shopper_group) && !empty($spgrpdisFilter))
		{
			$item->shopper_group = $spgrpdisFilter;
		}

		return $item;
	}
}
