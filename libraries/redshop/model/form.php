<?php
/**
 * @package     Redshop.Library
 * @subpackage  Base
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

use Joomla\String\StringHelper;

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
	 * The unique columns.
	 *
	 * @var  array
	 */
	protected $copyUniqueColumns = array();

	/**
	 * The unique columns increment.
	 *
	 * @var  string
	 */
	protected $copyIncrement = 'default';

	/**
	 * Constructor.
	 *
	 * @param   array  $config  Configuration array
	 *
	 * @throws  Exception
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
	 * @throws  Exception
	 */
	public static function getAutoInstance($name, $client = null, array $config = array(), $option = 'auto')
	{
		if ($option === 'auto')
		{
			$option = JFactory::getApplication()->input->getString('option', '');
		}

		$componentName = ucfirst(strtolower(substr($option, 4)));
		$prefix        = $componentName . 'Model';

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
	 *
	 * @throws  Exception
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
	 *
	 * @throws  Exception
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

	/**
	 * Get the associated JTable
	 *
	 * @param   string  $name    Table name
	 * @param   string  $prefix  Table prefix
	 * @param   array   $config  Configuration array
	 *
	 * @return  JTable
	 *
	 * @throws  Exception
	 */
	public function getTable($name = null, $prefix = 'RedshopTable', $config = array())
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
	 *
	 * @throws  Exception
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
		$data   = $form->filter($data);
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

	/**
	 * Method to rename value to unique in current table.
	 *
	 * @param   string  $fieldName   Field name
	 * @param   string  $fieldValue  Field value
	 * @param   string  $style       The the style (default|dash)
	 * @param   string  $tableName   Use table with name in value
	 *
	 * @return  string  Unique field value
	 *
	 * @since   1.5
	 *
	 * @throws  Exception
	 */
	protected function renameToUniqueValue($fieldName, $fieldValue, $style = 'default', $tableName = '')
	{
		$table = $this->getTable($tableName);

		while ($table->load(array($fieldName => $fieldValue)))
		{
			$fieldValue = StringHelper::increment($fieldValue, $style);
		}

		return $fieldValue;
	}

	/**
	 * Method to duplicate items.
	 *
	 * @param   array  $pks  An array of primary key IDs.
	 *
	 * @return  boolean      Boolean true on success, JException instance on error
	 *
	 * @throws  Exception
	 */
	public function copy(&$pks)
	{
		$table = $this->getTable();

		foreach ($pks as $pk)
		{
			if (!$table->load($pk, true))
			{
				throw new Exception($table->getError());
			}

			$source = clone $table;

			// Reset the id to create a new record.
			$table->{$table->getKeyName()} = 0;

			// Unpublish duplicate module
			if (property_exists($table, 'published'))
			{
				$table->published = 0;
			}
			elseif (property_exists($table, 'state'))
			{
				$table->state = 0;
			}

			if (!empty($this->copyUniqueColumns))
			{
				foreach ($this->copyUniqueColumns as $copyColumn)
				{
					$table->{$copyColumn} = $this->renameToUniqueValue($copyColumn, $table->{$copyColumn}, $this->copyIncrement);
				}
			}

			if (!$table->check())
			{
				throw new Exception($table->getError());
			}

			if (!$table->store())
			{
				throw new Exception($table->getError());
			}

			$this->afterCopy($source, clone $table);
		}

		return true;
	}

	/**
	 * Method for run after success copy record
	 *
	 * @param   JTable  $source  Source record
	 * @param   JTable  $target  Target record
	 *
	 * @return  void
	 */
	public function afterCopy($source, $target)
	{
	}
}
