<?php
/**
 * @package     Redshop
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Table\Traits;

defined('_JEXEC') or die;

use Joomla\String\StringHelper;
use Joomla\Registry\Registry;

/**
 * Table with automatic events support.
 *
 * @since  2.0.3
 */
trait HasAutoEvents
{
	/**
	 * Events available in this class
	 *
	 * @var    array
	 */
	protected $availableEvents = array(
		'event_after_bind'     => null,
		'event_after_check'    => null,
		'event_after_delete'   => null,
		'event_after_load'     => null,
		'event_after_publish'  => null,
		'event_after_store'    => null,
		'event_before_bind'    => null,
		'event_before_check'   => null,
		'event_before_delete'  => null,
		'event_before_load'    => null,
		'event_before_publish' => null,
		'event_before_store'   => null
	);

	/**
	 * Field name to publish/unpublish/trash table registers. Ex: state
	 *
	 * @var  string
	 */
	protected $_tableFieldState = 'state';

	/**
	 * Field name to keep creator user (created_by)
	 *
	 * @var  string
	 */
	protected $_tableFieldCreatedBy = 'created_by';

	/**
	 * Field name to keep latest modifier user (modified_by)
	 *
	 * @var  string
	 */
	protected $_tableFieldModifiedBy = 'modified_by';

	/**
	 * Field name to keep created date (created_date)
	 *
	 * @var  string
	 */
	protected $_tableFieldCreatedDate = 'created_date';

	/**
	 * Field name to keep latest modified user (modified_date)
	 *
	 * @var  string
	 */
	protected $_tableFieldModifiedDate = 'modified_date';

	/**
	 * Format for audit date fields (created_date, modified_date)
	 *
	 * @var  string
	 */
	protected $_auditDateFormat = 'Y-m-d H:i:s';

	/**
	 * True for auto-update audit field
	 *
	 * @var   boolean
	 *
	 * @since   2.0.6
	 */
	protected $updateAuditFields = true;

	/**
	 * Use automatic events for this table.
	 *
	 * @var    boolean
	 */
	protected $autoEvents = true;

	/**
	 * An array of plugin types to import.
	 *
	 * @var  array
	 */
	protected $pluginTypesToImport = array('redshop');

	/**
	 * Called after bind().
	 *
	 * Method to bind an associative array or object to the JTable instance.This
	 * method only binds properties that are publicly accessible and optionally
	 * takes an array of properties to ignore when binding.
	 *
	 * @param   mixed  $src     An associative array or object to bind to the JTable instance.
	 * @param   mixed  $ignore  An optional array or space separated list of properties to ignore while binding.
	 *
	 * @return  boolean  True on success.
	 */
	public function afterBind($src, $ignore = array())
	{
		return $this->triggerEvent('event_after_bind', array(&$src, $ignore));
	}

	/** Called after check().
	 *
	 * This method checks that the parent_id is non-zero and exists in the database.
	 * Note that the root node (parent_id = 0) cannot be manipulated with this class.
	 *
	 * @return  boolean  True if all checks pass.
	 */
	protected function afterCheck()
	{
		return $this->triggerEvent('event_after_check', func_get_args());
	}

	/**
	 * Called after delete().
	 *
	 * @param   mixed  $pk  An optional primary key value to delete.  If not set the instance property value is used.
	 *
	 * @return  boolean  True on success.
	 */
	protected function afterDelete($pk = null)
	{
		return $this->triggerEvent('event_after_delete', func_get_args());
	}

	/**
	 * Called after load().
	 *
	 * @param   mixed    $keys   An optional primary key value to load the row by, or an array of fields to match.  If not
	 *                           set the instance property value is used.
	 * @param   boolean  $reset  True to reset the default values before loading the new row.
	 *
	 * @return  boolean  True if successful. False if row not found.
	 */
	protected function afterLoad($keys = null, $reset = true)
	{
		return $this->triggerEvent('event_after_load', func_get_args());
	}

	/**
	 * Called after publish().
	 *
	 * @param   mixed    $pks     An optional array of primary key values to update.
	 *                            If not set the instance property value is used.
	 * @param   integer  $state   The publishing state. eg. [0 = unpublished, 1 = published]
	 * @param   integer  $userId  The user id of the user performing the operation.
	 *
	 * @return  boolean  True on success; false if $pks is empty.
	 */
	public function afterPublish($pks = null, $state = 1, $userId = 0)
	{
		return $this->triggerEvent('event_after_publish', func_get_args());
	}

	/**
	 * Called after store(). Overriden to send isNew to plugins.
	 *
	 * @param   boolean  $updateNulls  True to update null values as well.
	 * @param   boolean  $isNew        True if we are adding a new item.
	 * @param   mixed    $oldItem      null for new items | JTable otherwise
	 *
	 * @return  boolean  True on success.
	 */
	protected function afterStore($updateNulls = false, $isNew = false, $oldItem = null)
	{
		return $this->triggerEvent('event_after_store', func_get_args());
	}

	/**
	 * Called before bind().
	 *
	 * Method to bind an associative array or object to the JTable instance.This
	 * method only binds properties that are publicly accessible and optionally
	 * takes an array of properties to ignore when binding.
	 *
	 * @param   mixed  &$src    An associative array or object to bind to the JTable instance.
	 * @param   mixed  $ignore  An optional array or space separated list of properties to ignore while binding.
	 *
	 * @return  boolean  True on success.
	 */
	public function beforeBind(&$src, $ignore = array())
	{
		return $this->triggerEvent('event_before_bind', array(&$src, $ignore));
	}

	/**
	 * Called before check().
	 *
	 * This method checks that the parent_id is non-zero and exists in the database.
	 * Note that the root node (parent_id = 0) cannot be manipulated with this class.
	 *
	 * @return  boolean  True if all checks pass.
	 */
	protected function beforeCheck()
	{
		return $this->triggerEvent('event_before_check', func_get_args());
	}

	/**
	 * Called before delete().
	 *
	 * @param   mixed  $pk  An optional primary key value to delete.  If not set the instance property value is used.
	 *
	 * @return  boolean  True on success.
	 */
	protected function beforeDelete($pk = null)
	{
		return $this->triggerEvent('event_before_delete', func_get_args());
	}

	/**
	 * Called before load().
	 *
	 * @param   mixed    $keys   An optional primary key value to load the row by, or an array of fields to match.  If not
	 *                           set the instance property value is used.
	 * @param   boolean  $reset  True to reset the default values before loading the new row.
	 *
	 * @return  boolean  True if successful. False if row not found.
	 */
	protected function beforeLoad($keys = null, $reset = true)
	{
		return $this->triggerEvent('event_before_load', func_get_args());
	}

	/**
	 * Called before publish().
	 *
	 * @param   mixed    $pks     An optional array of primary key values to update.
	 *                            If not set the instance property value is used.
	 * @param   integer  $state   The publishing state. eg. [0 = unpublished, 1 = published]
	 * @param   integer  $userId  The user id of the user performing the operation.
	 *
	 * @return  boolean  True on success; false if $pks is empty.
	 */
	public function beforePublish($pks = null, $state = 1, $userId = 0)
	{
		return $this->triggerEvent('event_before_publish', func_get_args());
	}

	/**
	 * Called before store(). Overriden to send isNew to plugins.
	 *
	 * @param   boolean  $updateNulls  True to update null values as well.
	 * @param   boolean  $isNew        True if we are adding a new item.
	 * @param   mixed    $oldItem      null for new items | JTable otherwise
	 *
	 * @return  boolean  True on success.
	 */
	protected function beforeStore($updateNulls = false, $isNew = false, $oldItem = null)
	{
		if (!$this->triggerEvent('event_before_store', func_get_args()))
		{
			return false;
		}

		// Audit fields optional auto-update (on by default)
		if ($this->getOption('updateAuditFields', true))
		{
			self::updateAuditFields($this);
		}

		return true;
	}

	/**
	 * Method to bind an associative array or object to the JTable instance.This
	 * method only binds properties that are publicly accessible and optionally
	 * takes an array of properties to ignore when binding.
	 *
	 * @param   mixed  $src     An associative array or object to bind to the JTable instance.
	 * @param   mixed  $ignore  An optional array or space separated list of properties to ignore while binding.
	 *
	 * @return  boolean  True on success.
	 *
	 * @throws  \InvalidArgumentException
	 */
	public function bind($src, $ignore = array())
	{
		if (!$this->beforeBind($src, $ignore))
		{
			return false;
		}

		if (!$this->doBind($src, $ignore))
		{
			return false;
		}

		if (!$this->afterBind($src, $ignore))
		{
			return false;
		}

		return true;
	}

	/**
	 * Checks that the object is valid and able to be stored.
	 *
	 * This method checks that the parent_id is non-zero and exists in the database.
	 * Note that the root node (parent_id = 0) cannot be manipulated with this class.
	 *
	 * @return  boolean  True if all checks pass.
	 */
	public function check()
	{
		if (!$this->beforeCheck())
		{
			return false;
		}

		if (!$this->doCheck())
		{
			return false;
		}

		if (!$this->afterCheck())
		{
			return false;
		}

		return true;
	}

	/**
	 * Deletes this row in database (or if provided, the row of key $pk)
	 *
	 * @param   mixed  $pk  An optional primary key value to delete.  If not set the instance property value is used.
	 *
	 * @return  boolean  True on success.
	 */
	public function delete($pk = null)
	{
		// Before delete
		if (!$this->beforeDelete($pk))
		{
			return false;
		}

		// Delete
		if (!$this->doDelete($pk))
		{
			return false;
		}

		// After delete
		if (!$this->afterDelete($pk))
		{
			return false;
		}

		return true;
	}

	/**
	 * Method to bind an associative array or object to the JTable instance.This
	 * method only binds properties that are publicly accessible and optionally
	 * takes an array of properties to ignore when binding.
	 *
	 * @param   mixed  &$src    An associative array or object to bind to the JTable instance.
	 * @param   mixed  $ignore  An optional array or space separated list of properties to ignore while binding.
	 *
	 * @return  boolean  True on success.
	 *
	 * @throws  \InvalidArgumentException
	 */
	protected function doBind(&$src, $ignore = array())
	{
		// Auto-generate aliases
		if (property_exists($this, 'alias') && (empty($this->alias) || isset($src['alias'])) && empty($src['alias']))
		{
			if (!empty($src['name']))
			{
				$src['alias'] = $src['name'];
			}
			elseif (!empty($src['title']))
			{
				$src['alias'] = $src['title'];
			}

			if (!empty($src['alias']))
			{
				$src['alias'] = \JApplicationHelper::stringURLSafe($src['alias']);

				// Ensure that we don't automatically generate duplicated aliases
				$table = clone $this;

				while ($table->load(array('alias' => $src['alias'])) && $table->id != $this->id)
				{
					$src['alias'] = StringHelper::increment($src['alias'], 'dash');
				}
			}
		}

		// Auto-fill created_by and modified_by information
		$now = \JDate::getInstance();
		$nowFormatted = $now->toSql();
		$userId = \JFactory::getUser()->get('id');

		if (property_exists($this, 'created_by')
			&& empty($src['created_by']) && (is_null($this->created_by) || empty($this->created_by)))
		{
			$src['created_by']   = $userId;
		}

		if (property_exists($this, 'created_user_id')
			&& empty($src['created_user_id']) && empty($this->created_user_id))
		{
			$src['created_user_id']   = $userId;
		}

		if (property_exists($this, 'created_date')
			&& (empty($src['created_date']) || $src['created_date'] === '0000-00-00 00:00:00')
			&& (empty($this->created_date) || $this->created_date === '0000-00-00 00:00:00'))
		{
			$src['created_date'] = $nowFormatted;
		}

		if (property_exists($this, 'created_time')
			&& (empty($src['created_time']) || $src['created_time'] === '0000-00-00 00:00:00')
			&& (empty($this->created_time) || $this->created_time === '0000-00-00 00:00:00'))
		{
			$src['created_time'] = $nowFormatted;
		}

		if (property_exists($this, 'modified_by') && empty($src['modified_by']))
		{
			$src['modified_by']   = $userId;
		}

		if (property_exists($this, 'modified_user_id') && empty($src['modified_user_id']))
		{
			$src['modified_user_id']   = $userId;
		}

		if (property_exists($this, 'modified_date')
			&& (empty($src['modified_date']) || $src['modified_date'] === '0000-00-00 00:00:00'))
		{
			$src['modified_date'] = $nowFormatted;
		}

		if (property_exists($this, 'modified_time')
			&& (empty($src['modified_time']) || $src['modified_time'] === '0000-00-00 00:00:00'))
		{
			$src['modified_time'] = $nowFormatted;
		}

		if (isset($src['params']) && is_array($src['params']))
		{
			$registry = new Registry;
			$registry->loadArray($src['params']);
			$src['params'] = (string) $registry;
		}

		if (isset($src['metadata']) && is_array($src['metadata']))
		{
			$registry = new Registry;
			$registry->loadArray($src['metadata']);
			$src['metadata'] = (string) $registry;
		}

		if (isset($src['rules']) && is_array($src['rules']))
		{
			$rules = new \JAccessRules($src['rules']);
			$this->setRules($rules);
		}

		if (!parent::bind($src, $ignore))
		{
			return false;
		}

		// Generate automatic ordering. After parent:bind() so getNextOrder is able to use complex conditions
		if (property_exists($this, 'ordering') && empty($this->ordering) && empty($src['ordering']))
		{
			$this->ordering = $this->getNextOrder();

			unset($src['ordering']);
		}

		return true;
	}

	/**
	 * Checks that the object is valid and able to be stored.
	 *
	 * This method checks that the parent_id is non-zero and exists in the database.
	 * Note that the root node (parent_id = 0) cannot be manipulated with this class.
	 *
	 * @return  boolean  True if all checks pass.
	 */
	protected function doCheck()
	{
		return parent::check();
	}

	/**
	 * Delete one or more registers
	 *
	 * @param   string/array  $pk  Array of ids or ids comma separated
	 *
	 * @return  boolean  Deleted successfully?
	 */
	protected function doDelete($pk = null)
	{
		return parent::delete($pk);
	}

	/**
	 * Method to load a row from the database by primary key and bind the fields
	 * to the JTable instance properties.
	 *
	 * @param   mixed    $keys   An optional primary key value to load the row by, or an array of fields to match.  If not
	 *                           set the instance property value is used.
	 * @param   boolean  $reset  True to reset the default values before loading the new row.
	 *
	 * @return  boolean  True if successful. False if row not found.
	 */
	protected function doLoad($keys = null, $reset = true)
	{
		return parent::load($keys, $reset);
	}

	/**
	 * Method to set the publishing state for a row or list of rows in the database
	 * table.  The method respects checked out rows by other users and will attempt
	 * to checkin rows that it can after adjustments are made.
	 *
	 * @param   mixed    $pks     An optional array of primary key values to update.
	 *                            If not set the instance property value is used.
	 * @param   integer  $state   The publishing state. eg. [0 = unpublished, 1 = published]
	 * @param   integer  $userId  The user id of the user performing the operation.
	 *
	 * @return  boolean  True on success; false if $pks is empty.
	 *
	 * @link    https://docs.joomla.org/JTable/publish
	 */
	protected function doPublish($pks = null, $state = 1, $userId = 0)
	{
		return parent::publish($pks, $state, $userId);
	}

	/**
	 * Do the database store.
	 *
	 * @param   boolean  $updateNulls  True to update null values as well.
	 *
	 * @return  boolean
	 */
	protected function doStore($updateNulls = false)
	{
		return parent::store($updateNulls);
	}

	/**
	 * Generate automatic events for this table.
	 *
	 * Example of generated events:
	 * Array
	 * (
	 * 'event_after_bind'     => 'onRedshopTableAfterBindCountry'
	 * 'event_after_check'    => 'onRedshopTableAfterCheckCountry'
	 * 'event_after_delete'   => 'onRedshopTableAfterDeleteCountry'
	 * 'event_after_load'     => 'onRedshopTableAfterLoadCountry'
	 * 'event_after_publish'  => 'onRedshopTableAfterPublishCountry'
	 * 'event_after_store'    => 'onRedshopTableAfterStoreCountry'
	 * 'event_before_bind'    => 'onRedshopTableBeforeBindCountry'
	 * 'event_before_check'   => 'onRedshopTableBeforeCheckCountry'
	 * 'event_before_delete'  => 'onRedshopTableBeforeDeleteCountry'
	 * 'event_before_load'    => 'onRedshopTableBeforeLoadCountry'
	 * 'event_before_publish' => 'onRedshopTableBeforePublishCountry'
	 * 'event_before_store'   => 'onRedshopTableBeforeStoreCountry'
	 * )
	 *
	 * @return  void
	 */
	protected function generateEventsConfig()
	{
		$instanceName   = strtolower($this->getInstanceName());
		$instancePrefix = strtolower($this->getInstancePrefix());

		$eventsPrefix = 'on' . ucfirst($instancePrefix) . 'Table';
		$eventsSuffix = ucfirst($instanceName);

		foreach ($this->availableEvents as $eventKey => &$event)
		{
			if (null === $event)
			{
				$eventParts = explode('_', str_replace('event_', '', $eventKey));
				$eventName = implode('', array_map("ucfirst", $eventParts));

				$event = $eventsPrefix . ucfirst($eventName) . $eventsSuffix;
			}
		}

		if (empty($this->pluginTypesToImport))
		{
			$this->pluginTypesToImport[] = $instancePrefix;
		}
	}

	/**
	 * Import the plugin types.
	 *
	 * @return  void
	 */
	private function importPluginTypes()
	{
		foreach ($this->pluginTypesToImport as $type)
		{
			\JPluginHelper::importPlugin($type);
		}
	}

	/**
	 * Method to load a row from the database by primary key and bind the fields
	 * to the JTable instance properties.
	 *
	 * @param   mixed    $keys   An optional primary key value to load the row by, or an array of fields to match.  If not
	 *                           set the instance property value is used.
	 * @param   boolean  $reset  True to reset the default values before loading the new row.
	 *
	 * @return  boolean  True if successful. False if row not found.
	 */
	public function load($keys = null, $reset = true)
	{
		if (!$this->beforeLoad($keys, $reset))
		{
			return false;
		}

		if (!$this->doLoad($keys, $reset))
		{
			return false;
		}

		if (!$this->afterLoad($keys, $reset))
		{
			return false;
		}

		return true;
	}

	/**
	 * Method to set the publishing state for a row or list of rows in the database
	 * table.  The method respects checked out rows by other users and will attempt
	 * to checkin rows that it can after adjustments are made.
	 *
	 * @param   mixed    $pks     An optional array of primary key values to update.
	 *                            If not set the instance property value is used.
	 * @param   integer  $state   The publishing state. eg. [0 = unpublished, 1 = published]
	 * @param   integer  $userId  The user id of the user performing the operation.
	 *
	 * @return  boolean  True on success; false if $pks is empty.
	 *
	 * @link    https://docs.joomla.org/JTable/publish
	 */
	public function publish($pks = null, $state = 1, $userId = 0)
	{
		if (!$this->beforePublish($pks, $state, $userId))
		{
			return false;
		}

		if (!$this->doPublish($pks, $state, $userId))
		{
			return false;
		}

		if (!$this->afterPublish($pks, $state, $userId))
		{
			return false;
		}

		return true;
	}

	/**
	 * Method to store a node in the database table.
	 *
	 * @param   boolean  $updateNulls  True to update null values as well.
	 *
	 * @return  boolean                True on success.
	 */
	public function store($updateNulls = false)
	{
		$k = $this->_tbl_key;

		$isNew = !$this->hasPrimaryKey();

		$oldItem = null;

		if (!$isNew)
		{
			$oldItem = clone $this;

			if ($this->getOption('skip.checkPrimary', false) == false)
			{
				$data = array();

				foreach ($this->_tbl_keys as $key)
				{
					$data[$key] = $this->{$key};
				}

				if (!$oldItem->load($data))
				{
					$this->setError(\JText::sprintf('LIB_REDSHOP_FIELD_ERROR_CANNOT_LOAD_FIELD', $this->{$k}));

					return false;
				}
			}
		}

		if (!$this->beforeStore($updateNulls, $isNew, $oldItem))
		{
			return false;
		}

		// Store
		if (!$this->doStore($updateNulls))
		{
			return false;
		}

		if (!$this->afterStore($updateNulls, $isNew, $oldItem))
		{
			return false;
		}

		return true;
	}

	/**
	 * Trigger an event.
	 *
	 * @param   string  $eventKey  Key of the event in the availableEvents array
	 * @param   array   $params    Arguments for the event being triggered
	 *
	 * @return  boolean
	 */
	protected function triggerEvent($eventKey, $params = array())
	{
		$eventKey = trim($eventKey);

		if (!$eventKey)
		{
			return false;
		}

		if (!isset($this->availableEvents[$eventKey]))
		{
			return true;
		}

		$eventName = $this->availableEvents[$eventKey];

		// Import the plugin types
		$this->importPluginTypes();

		// First param will be always this table
		array_unshift($params, $this);

		// Trigger the event
		$results = \RedshopHelperUtility::getDispatcher()->trigger($eventName, $params);

		if (count($results) && in_array(false, $results, true))
		{
			return false;
		}

		return true;
	}

	/**
	 * Method to update audit fields using a static function, to reuse in non-children classes like RNestedTable
	 *
	 * @param   self  $tableInstance  Table instance
	 *
	 * @return  void
	 *
	 * @since   2.0.6
	 */
	public static function updateAuditFields(&$tableInstance)
	{
		$tableFieldCreatedBy    = $tableInstance->get('_tableFieldCreatedBy');
		$tableFieldCreatedDate  = $tableInstance->get('_tableFieldCreatedDate');
		$tableFieldModifiedBy   = $tableInstance->get('_tableFieldModifiedBy');
		$tableFieldModifiedDate = $tableInstance->get('_tableFieldModifiedDate');
		$auditDateFormat        = $tableInstance->get('_auditDateFormat');

		// Optional created_by field updated when present
		if (!$tableInstance->hasPrimaryKey() && property_exists($tableInstance, $tableFieldCreatedBy))
		{
			$user = \JFactory::getUser();

			if ($user->id)
			{
				$tableInstance->{$tableFieldCreatedBy} = $user->id;
			}
			else
			{
				$tableInstance->{$tableFieldCreatedBy} = null;
			}
		}

		// Optional created_date field updated when present
		if (!$tableInstance->hasPrimaryKey() && property_exists($tableInstance, $tableFieldCreatedDate))
		{
			$tableInstance->{$tableFieldCreatedDate} = \JFactory::getDate()->format($auditDateFormat);
		}

		// Optional modified_by field updated when present
		if (property_exists($tableInstance, $tableFieldModifiedBy))
		{
			if (!isset($user))
			{
				$user = \JFactory::getUser();
			}

			if ($user->id)
			{
				$tableInstance->{$tableFieldModifiedBy} = $user->id;
			}
			else
			{
				$tableInstance->{$tableFieldModifiedBy} = null;
			}
		}

		// Optional modified_date field updated when present
		if (property_exists($tableInstance, $tableFieldModifiedDate))
		{
			$tableInstance->{$tableFieldModifiedDate} = \JFactory::getDate()->format($auditDateFormat);
		}
	}
}
