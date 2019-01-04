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
 * Model Mass Discount
 *
 * @package     RedSHOP.Backend
 * @subpackage  Model
 * @since       2.0.3
 */
class RedshopModelMass_Discount extends RedshopModelForm
{
	/**
	 * Method to save the form data.
	 *
	 * @param   array $data The form data.
	 *
	 * @return  boolean  True on success, False on error.
	 *
	 * @since   2.1.1
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

		return parent::save($data);
	}
}
