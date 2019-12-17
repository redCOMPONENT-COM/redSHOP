<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
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
	protected $copyUniqueColumns = array('name');

	/**
	 * The unique columns increment.
	 *
	 * @var  string
	 */
	protected $copyIncrement = 'dash';

	/**
	 * Method to get the record form.
	 *
	 * @param   array    $data      Data for the form. [optional]
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not. [optional]
	 *
	 * @return  mixed               A JForm object on success, false on failure
	 *
	 * @since   2.1.0
	 */
	public function getForm($data = array(), $loadData = true)
	{
		/** @var JForm $form */
		$form = parent::getForm($data, $loadData);

		if (!$form)
		{
			return false;
		}

		$id = (int) $form->getValue('id');

		if ($id)
		{
			$form->setFieldAttribute('section', 'readonly', true);
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 *
	 * @since   1.6
	 *
	 * @throws  Exception
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$app  = JFactory::getApplication();
		$data = $app->getUserState('com_redshop.edit.template.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
		}

		$this->preprocessData('com_redshop.template', $data);

		return $data;
	}
}
