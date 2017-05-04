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
 * Model Supplier
 *
 * @package     RedSHOP.Backend
 * @subpackage  Model
 * @since       2.0.4
 */
class RedshopModelSupplier extends RedshopModelForm
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
		$data = $app->getUserState('com_redshop.edit.supplier.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
		}

		$this->preprocessData('com_redshop.supplier', $data);

		return $data;
	}

	/**
	 * Method to duplicate suppliers.
	 *
	 * @param   array  &$pks  An array of primary key IDs.
	 *
	 * @return  boolean|JException  Boolean true on success, JException instance on error
	 */
	public function duplicate(&$pks)
	{
		$user = JFactory::getUser();
		$db   = $this->getDbo();

		$table = $this->getTable();

		foreach ($pks as $pk)
		{
			if ($table->load($pk, true))
			{
				// Reset the id to create a new record.
				$table->id = 0;

				// Unpublish duplicate module
				$table->published = 0;

				if (!$table->check() || !$table->store())
				{
					throw new Exception($table->getError());
				}
			}
			else
			{
				throw new Exception($table->getError());
			}
		}

		return true;
	}
}
