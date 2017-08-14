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
 * Model Template
 *
 * @package     RedSHOP.Backend
 * @subpackage  Model
 * @since       __DEPLOY_VERSION__
 */
class RedshopModelTemplate extends RedshopModelForm
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
		$data = $app->getUserState('com_redshop.edit.template.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
		}

		$this->preprocessData('com_redshop.template', $data);

		return $data;
	}

	/**
	 * Method to duplicate suppliers.
	 *
	 * @param   array  $pks  An array of primary key IDs.
	 *
	 * @throws  Exception
	 *
	 * @return  boolean|JException  Boolean true on success, JException instance on error
	 */
	public function duplicate(&$pks)
	{
		$table = $this->getTable();

		foreach ($pks as $pk)
		{
			if ($table->load($pk))
			{
				// Reset the id to create a new record.
				$table->template_id = 0;

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
