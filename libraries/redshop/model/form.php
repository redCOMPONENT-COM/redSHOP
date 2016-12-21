<?php
/**
 * @package     Redshop.Library
 * @subpackage  Base
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

use Joomla\Utilities\ArrayHelper;

jimport('joomla.application.component.modelform');

/**
 * Redshop Model
 *
 * @package     Redshop.library
 * @subpackage  Model
 * @since       1.5
 */
class RedshopModelForm extends JModelAdmin
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
	 * Added from Joomla's legacy.php to preserve static $paths
	 *
	 * @param   string  $type    The model type to instantiate
	 * @param   string  $prefix  Prefix for the model class name. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  mixed   A model object or false on failure
	 */
	public static function getInstance($type, $prefix = '', $config = array())
	{
		$return = parent::getInstance($type, $prefix, $config);

		return $return;
	}

	/**
	 * Added from Joomla's legacy.php to preserve static $paths
	 *
	 * @param   mixed   $path    A path or array[sting] of paths to search.
	 * @param   string  $prefix  A prefix for models.
	 *
	 * @return  array  An array with directory elements. If prefix is equal to '', all directories are returned.
	 */
	public static function addIncludePath($path = '', $prefix = '')
	{
		parent::addIncludePath($path, $prefix);
		$return = JModelLegacy::addIncludePath($path, $prefix);

		return $return;
	}

	/**
	 * Get a model instance.
	 *
	 * @param   string  $name    Model name
	 * @param   mixed   $client  Client. null = auto, 1 = admin, 0 = frontend
	 * @param   array   $config  An optional array of configuration
	 * @param   string  $option  Component name, use for call model from modules
	 *
	 * @return  RedshopModelForm  The model
	 *
	 * @throws  InvalidArgumentException
	 */
	public static function getAutoInstance($name, $client = null, array $config = array(), $option = 'auto')
	{
		if ($option === 'auto')
		{
			$option = JFactory::getApplication()->input->getString('option', '');
		}

		$componentName = ucfirst(strtolower(substr($option, 4)));
		$prefix = $componentName . 'Model';

		if (is_null($client))
		{
			$client = (int) JFactory::getApplication()->isAdmin();
		}

		// Admin
		if ($client === 1)
		{
			self::addIncludePath(JPATH_ADMINISTRATOR . '/components/' . $option . '/models', $prefix);
			JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/' . $option . '/tables');
		}

		// Site
		elseif ($client === 0)
		{
			self::addIncludePath(JPATH_SITE . '/components/' . $option . '/models', $prefix);
			JTable::addIncludePath(JPATH_SITE . '/components/' . $option . '/tables');
		}

		else
		{
			throw new InvalidArgumentException(
				sprintf('Cannot instantiate the model %s. Invalid client %s.', $name, $client)
			);
		}

		$model = self::getInstance($name, $prefix, $config);

		if (!$model instanceof JModelAdmin && !$model instanceof JModelLegacy)
		{
			throw new InvalidArgumentException(
				sprintf('Cannot instantiate the model %s from client %s.', $name, $client)
			);
		}

		return $model;
	}

	/**
	 * Get a backend model instance
	 *
	 * @param   string  $name    Model name
	 * @param   array   $config  An optional array of configuration
	 * @param   string  $option  Component name, use for call model from modules
	 *
	 * @return  RedshopModelForm  Model instance
	 */
	public static function getAdminInstance($name, array $config = array(), $option = 'auto')
	{
		return self::getAutoInstance($name, 1, $config, $option);
	}

	/**
	 * Get a frontend Model instance
	 *
	 * @param   string  $name    Model name
	 * @param   array   $config  An optional array of configuration
	 * @param   string  $option  Component name, use for call model from modules
	 *
	 * @return  RedshopModelForm  Model instance
	 */
	public static function getFrontInstance($name, array $config = array(), $option = 'auto')
	{
		return self::getAutoInstance($name, 0, $config, $option);
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
			$this->context . '.' . $this->formName,
			$this->formName,
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

	/**
	 * Get the associated JTable
	 *
	 * @param   string  $name    Table name
	 * @param   string  $prefix  Table prefix
	 * @param   array   $config  Configuration array
	 *
	 * @return  JTable
	 */
	public function getTable($name = null, $prefix = '', $config = array())
	{
		$class = get_class($this);

		if (empty($name))
		{
			$name = strstr($class, 'Model');
			$name = str_replace('Model', '', $name);
		}

		if (empty($prefix))
		{
			$prefix = strstr($class, 'Model', true) . 'Table';
		}

		return parent::getTable($name, $prefix, $config);
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  array  The default data is an empty array.
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState(
			$this->context . '.data',
			array()
		);

		if (empty($data))
		{
			$data = $this->getItem();
		}

		return $data;
	}

	/**
	 * Method to get a form object.
	 *
	 * @param   string   $name     The name of the form.
	 * @param   string   $source   The form source. Can be XML string if file flag is set to false.
	 * @param   array    $options  Optional array of options for the form creation.
	 * @param   boolean  $clear    Optional argument to force load a new form.
	 * @param   mixed    $xpath    An optional xpath to search for the fields.
	 *
	 * @return  mixed  JForm object on success, False on error.
	 */
	protected function loadForm($name, $source = null, $options = array(), $clear = false, $xpath = false)
	{
		// Handle the optional arguments.
		$options['control'] = ArrayHelper::getValue($options, 'control', false);

		// Create a signature hash.
		$hash = md5($source . serialize($options));

		// Check if we can use a previously loaded form.
		if (isset($this->_forms[$hash]) && !$clear)
		{
			return $this->_forms[$hash];
		}

		// Get the form.
		JFormHelper::addFormPath(JPATH_COMPONENT . '/models/forms');
		JFormHelper::addFieldPath(JPATH_COMPONENT . '/models/fields');

		try
		{
			$form = RedshopForm::getInstance($name, $source, $options, false, $xpath);

			if (isset($options['load_data']) && $options['load_data'])
			{
				// Get the data for the form.
				$data = $this->loadFormData();
			}
			else
			{
				$data = array();
			}

			// Allow for additional modification of the form, and events to be triggered.
			// We pass the data because plugins may require it.
			$this->preprocessForm($form, $data);

			// Load the data into the form after the plugins have operated.
			$form->bind($data);
		}
		catch (Exception $e)
		{
			$this->setError($e->getMessage());

			return false;
		}

		// Store the form for later.
		$this->_forms[$hash] = $form;

		return $form;
	}

	/**
	 * Method to validate the form data.
	 * Each field error is stored in session and can be retrieved with getFieldError().
	 * Once getFieldError() is called, the error is deleted from the session.
	 *
	 * @param   JForm   $form   The form to validate against.
	 * @param   array   $data   The data to validate.
	 * @param   string  $group  The name of the field group to validate.
	 *
	 * @return  mixed  Array of filtered data if valid, false otherwise.
	 */
	public function validate($form, $data, $group = null)
	{
		// Filter and validate the form data.
		$data = $form->filter($data);
		$return = $form->validate($data, $group);

		// Check for an error.
		if ($return instanceof Exception)
		{
			$this->setError($return->getMessage());

			return false;
		}

		// Check the validation results.
		if ($return === false)
		{
			$session = JFactory::getSession();

			// Get the validation messages from the form.
			foreach ($form->getErrors() as $key => $message)
			{
				$this->setError($message);

				if ($message instanceof Exception)
				{
					// Store the field error in session.
					$session->set($this->context . '.error.' . $key, $message->getMessage());
				}

				else
				{
					// Store the field error in session.
					$session->set($this->context . '.error.' . $key, $message);
				}
			}

			return false;
		}

		return $data;
	}
}
