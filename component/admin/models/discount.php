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
		$tz  = JFactory::getConfig()->get('offset');
		$UTC = new DateTimeZone('UTC');

		if (!empty($data['start_date']) && !is_numeric($data['start_date']))
		{
			$data['start_date'] = JFactory::getDate($data['start_date'], $tz)->setTimezone($UTC)->toUnix();
		}

		if (!empty($data['end_date']) && !is_numeric($data['end_date']))
		{
			$data['end_date'] = JFactory::getDate($data['end_date'], $tz)->setTimezone($UTC)->toUnix();
		}

		$data['start_date'] = (int) $data['start_date'];
		$data['end_date']   = (int) $data['end_date'];

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

		$dateFormat = Redshop::getConfig()->getString('DEFAULT_DATEFORMAT', 'Y-m-d');

		$item->shopper_group = RedshopEntityDiscount::getInstance($item->discount_id)->getShopperGroups()->ids();

		$spgrpdisFilter = JFactory::getApplication()->input->getInt('spgrpdis_filter', 0);

		if (empty($item->shopper_group) && !empty($spgrpdisFilter))
		{
			$item->shopper_group = $spgrpdisFilter;
		}

		$item->start_date    = !empty($item->start_date) ? JFactory::getDate($item->start_date)->format($dateFormat) : null;
		$item->end_date      = !empty($item->end_date) ? JFactory::getDate($item->end_date)->format($dateFormat) : null;

		return $item;
	}
}
