<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Model Country
 *
 * @package     RedSHOP.Backend
 * @subpackage  Model
 * @since       2.0.0.8
 */

class RedshopModelZipcode extends RedshopModelForm
{
	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 *
	 * @since   1.6
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$app = JFactory::getApplication();
		$data = $app->getUserState('com_redshop.edit.zipcode.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
		}

		$this->preprocessData('com_redshop.zipcode', $data);

		return $data;
	}

	/**
	 * Save Zipcode
	 * 
	 * @param   array  $data  data collection
	 * 
	 * @return  bool
	 */
	public function save($data)
	{
		$table = $this->getTable('Zipcode');

		if ($data['zipcodeto'] && ($data['zipcode'] > $data['zipcodeto']))
		{
			return false;
		}

		if (!$data['zipcodeto'])
		{
			$data['zipcodeto'] = $data['zipcode'];
		}

		for ($i = $data['zipcode']; $i <= $data['zipcodeto']; $i++)
		{
			$table->country_code = $data['country_code'];
			$table->state_code = isset($data['state_code'])? $data['state_code'] : null;
			$table->city_name = $data['city_name'];
			$table->zipcode = is_numeric($data['zipcode'])? $i : $data['zipcode'];
			$table->store();
			$table->id = null;
		}

		return true;
	}
}
