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
 * @since       2.0.7
 */
class RedshopModelTemplate extends RedshopModelForm
{
	/**
	 * The unique columns.
	 *
	 * @var  array
	 */
	protected $copyUniqueColumns = array('template_name');

	/**
	 * The unique columns increment.
	 *
	 * @var  string
	 */
	protected $copyIncrement = 'dash';

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
}
