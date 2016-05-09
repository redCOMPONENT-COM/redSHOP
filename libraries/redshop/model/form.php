<?php
/**
 * @package     Redshop.Library
 * @subpackage  Base
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.modelform');

/**
 * Redshop Model
 *
 * @package     Redshop.library
 * @subpackage  Model
 * @since       1.5
 */
class RedshopModelForm extends JModelForm
{
	/**
	 * Context for session
	 *
	 * @var  string
	 */
	protected $context = null;

	/**
	 * The form name.
	 *
	 * @var  string
	 */
	protected $formName;

	/**
	 * Constructor.
	 *
	 * @param   array  $config  Configuration array
	 *
	 * @throws  RuntimeException
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);

		if (is_null($this->context))
		{
			$this->context = strtolower($this->option . '.edit.' . $this->getName());
		}

		if (is_null($this->formName))
		{
			$this->formName = strtolower($this->getName());
		}
	}

	/**
	 * Method for getting the form from the model.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  mixed  A JForm object on success, false on failure
	 *
	 * @since   1.5
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm(
			$this->context . '.' . $this->formName, $this->formName,
			array(
				'control' => 'jform',
				'load_data' => $loadData
			)
		);

		if (empty($form))
		{
			return false;
		}

		return $form;
	}
}
