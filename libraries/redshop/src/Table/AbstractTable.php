<?php
/**
 * @package     Aesir.Core
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Table;

defined('_JEXEC') or die;

use Joomla\Utilities\ArrayHelper;
use Redshop\Table\Traits\HasAutoEvents;
use Redshop\Table\Traits\HasInstanceName;
use Redshop\Table\Traits\HasInstancePrefix;

/**
 * Base table class.
 *
 * @since  3.2.3
 */
abstract class AbstractTable extends \JTable implements TableInterface
{
	use HasAutoEvents;
	use HasInstanceName;
	use HasInstancePrefix;

	/**
	 * The table name without the prefix. Ex: cursos_courses
	 *
	 * @var  string
	 */
	protected $_tableName = null;

	/**
	 * The table key column. Usually: id
	 *
	 * @var  string
	 */
	protected $_tableKey = 'id';

	/**
	 * Array with alias for "special" columns such as ordering, hits etc etc
	 *
	 * @var    array
	 */
	protected $_columnAlias = array();

	/**
	 * The options.
	 *
	 * @var  array
	 */
	protected $options = array();

	/**
	 * Constructor
	 *
	 * @param   \JDatabaseDriver  $db  A database connector object
	 *
	 * @throws  \UnexpectedValueException
	 */
	public function __construct(&$db)
	{
		// Keep checking _tbl value for standard defined tables
		if (empty($this->_tbl) && !empty($this->_tableName))
		{
			// Add the table prefix
			$this->_tbl = '#__' . $this->_tableName;
		}

		$key = $this->_tbl_key;

		if (empty($key) && !empty($this->_tbl_keys))
		{
			$key = $this->_tbl_keys;
		}

		// Keep checking _tbl_key for standard defined tables
		if (empty($key) && !empty($this->_tableKey))
		{
			$this->_tbl_key = $this->_tableKey;
			$key            = $this->_tbl_key;
		}

		if (empty($this->_tbl) || empty($key))
		{
			throw new \UnexpectedValueException(sprintf('Missing data to initialize %s table | id: %s', $this->_tbl, $key));
		}

		if ($this->autoEvents)
		{
			$this->generateEventsConfig();
		}

		parent::__construct($this->_tbl, $key, $db);
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
		if (!parent::check())
		{
			return false;
		}

		return true;
	}

	/**
	 * Delete one or more registers
	 *
	 * @param   string /array  $pk  Array of ids or ids comma separated
	 *
	 * @return  boolean  Deleted successfuly?
	 */
	protected function doDelete($pk = null)
	{
		// Initialise variables.
		$k = $this->_tbl_key;

		// Multiple keys
		$multiplePrimaryKeys = count($this->_tbl_keys) > 1;

		// We are dealing with multiple primary keys
		if ($multiplePrimaryKeys)
		{
			// Received an array of ids?
			if (is_null($pk))
			{
				$pk = array();

				foreach ($this->_tbl_keys AS $key)
				{
					$pk[$key] = $this->$key;
				}
			}
			elseif (is_array($pk))
			{
				foreach ($this->_tbl_keys AS $key)
				{
					$pk[$key] = !empty($pk[$key]) ? $pk[$key] : $this->$key;
				}
			}
		}
		// Standard Joomla delete method
		else
		{
			if (is_array($pk))
			{
				// Sanitize input.
				$pk = ArrayHelper::toInteger($pk);
				$pk = \RedshopHelperUtility::quote($pk);
				$pk = implode(',', $pk);
			}
			// Try the instance property value
			elseif (empty($pk) && $this->{$k})
			{
				$pk = $this->{$k};
			}
		}

		// If no primary key is given, return false.
		if ($pk === null)
		{
			return false;
		}

		// Delete the row by primary key.
		$query = $this->_db->getQuery(true);
		$query->delete($this->_db->quoteName($this->_tbl));

		if ($multiplePrimaryKeys)
		{
			foreach ($this->_tbl_keys AS $k)
			{
				$query->where($this->_db->quoteName($k) . ' = ' . $this->_db->quote($pk[$k]));
			}
		}
		else
		{
			$query->where($this->_db->quoteName($this->_tbl_key) . ' IN (' . $pk . ')');
		}

		$this->_db->setQuery($query);
		$this->_db->execute();

		// Check for a database error.
		if ($this->_db->getErrorNum())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		return true;
	}

	/**
	 * Method to set the publishing state for a row or list of rows in the database
	 * table.  The method respects checked out rows by other users and will attempt
	 * to checkin rows that it can after adjustments are made.
	 *
	 * @param   mixed   $pks      An optional array of primary key values to update.
	 *                            If not set the instance property value is used.
	 * @param   integer $state    The publishing state. eg. [0 = unpublished, 1 = published]
	 * @param   integer $userId   The user id of the user performing the operation.
	 *
	 * @return  boolean  True on success; false if $pks is empty.
	 *
	 * @link    https://docs.joomla.org/JTable/publish
	 */
	public function doPublish($pks = null, $state = 1, $userId = 0)
	{
		// Sanitize input
		$userId = (int) $userId;
		$state  = (int) $state;

		if (!is_null($pks))
		{
			if (!is_array($pks))
			{
				$pks = array($pks);
			}

			foreach ($pks as $key => $pk)
			{
				if (!is_array($pk))
				{
					$pks[$key] = array($this->_tbl_key => $pk);
				}
			}
		}

		// If there are no primary keys set check to see if the instance key is set.
		if (empty($pks))
		{
			$pk = array();

			foreach ($this->_tbl_keys AS $key)
			{
				if ($this->$key)
				{
					$pk[$key] = $this->$key;
				}
				// We don't have a full primary key - return false
				else
				{
					$this->setError(\JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));

					return false;
				}
			}

			$pks = array($pk);
		}

		foreach ($pks as $pk)
		{
			// Update the publishing state for rows with the given primary keys.
			$query = $this->_db->getQuery(true)
				->update($this->_tbl)
				->set($this->_db->quoteName($this->getColumnAlias('published')) . ' = ' . (int) $state);

			// Determine if there is checkin support for the table.
			if (property_exists($this, 'checked_out') || property_exists($this, 'checked_out_time'))
			{
				$query->where('(' . $this->getColumnAlias('checked_out') . ' = 0'
					. ' OR ' . $this->getColumnAlias('checked_out') . ' IS NULL'
					. ' OR ' . $this->getColumnAlias('checked_out') . ' = ' . (int) $userId . ')');
				$checkin = true;
			}
			else
			{
				$checkin = false;
			}

			// Build the WHERE clause for the primary keys.
			$this->appendPrimaryKeys($query, $pk);

			$this->_db->setQuery($query);

			try
			{
				$this->_db->execute();
			}
			catch (\RuntimeException $e)
			{
				$this->setError($e->getMessage());

				return false;
			}

			// If checkin is supported and all rows were adjusted, check them in.
			if ($checkin && (count($pks) == $this->_db->getAffectedRows()))
			{
				$this->checkIn($pk);
			}

			// If the JTable instance value is in the list of primary keys that were set, set the instance.
			$ours = true;

			foreach ($this->_tbl_keys AS $key)
			{
				if ($this->{$key} != $pk[$key])
				{
					$ours = false;
				}
			}

			if ($ours)
			{
				$publishedField          = $this->getColumnAlias('published');
				$this->{$publishedField} = $state;
			}
		}

		$this->setError('');

		return true;
	}

	/**
	 * Do the database store.
	 *
	 * @param   boolean $updateNulls True to update null values as well.
	 *
	 * @return  boolean
	 */
	protected function doStore($updateNulls = false)
	{
		$k = $this->_tbl_keys;

		// Implement JObservableInterface: Pre-processing by observers
		$this->_observers->update('onBeforeStore', array($updateNulls, $k));

		$currentAssetId = 0;

		if (!empty($this->asset_id))
		{
			$currentAssetId = $this->asset_id;
		}

		// The asset id field is managed privately by this class.
		if ($this->_trackAssets)
		{
			unset($this->asset_id);
		}

		// If a primary key exists update the object, otherwise insert it.
		$isInsert = $this->hasPrimaryKey() && (clone $this)->load($this->getPrimaryKey()) ? false : true;

		$result = $isInsert ? $this->_db->insertObject($this->_tbl, $this, $this->_tbl_keys[0])
			: $this->_db->updateObject($this->_tbl, $this, $this->_tbl_keys, $updateNulls);

		// If the table is not set to track assets return true.
		if ($this->_trackAssets)
		{
			if ($this->_locked)
			{
				$this->_unlock();
			}

			/*
			 * Asset Tracking
			 */
			$parentId = $this->_getAssetParentId();
			$name     = $this->_getAssetName();
			$title    = $this->_getAssetTitle();

			/** @var \JTableAsset $asset */
			$asset = self::getInstance('Asset', 'JTable', array('dbo' => $this->getDbo()));
			$asset->loadByName($name);

			// Re-inject the asset id.
			$this->asset_id = $asset->id;

			// Check for an error.
			$error = $asset->getError();

			if ($error)
			{
				$this->setError($error);

				return false;
			}
			else
			{
				// Specify how a new or moved node asset is inserted into the tree.
				if (empty($this->asset_id) || $asset->parent_id != $parentId)
				{
					$asset->setLocation($parentId, 'last-child');
				}

				// Prepare the asset to be stored.
				$asset->parent_id = $parentId;
				$asset->name      = $name;
				$asset->title     = $title;

				if ($this->_rules instanceof \JAccessRules)
				{
					$asset->rules = (string) $this->_rules;
				}

				if (!$asset->check() || !$asset->store())
				{
					$this->setError($asset->getError());

					return false;
				}
				else
				{
					// Create an asset_id or heal one that is corrupted.
					if (empty($this->asset_id) || ($currentAssetId != $this->asset_id && !empty($this->asset_id)))
					{
						// Update the asset_id field in this table.
						$this->asset_id = (int) $asset->id;

						$query = $this->_db->getQuery(true)
							->update($this->_db->quoteName($this->_tbl))
							->set('asset_id = ' . (int) $this->asset_id);
						$this->appendPrimaryKeys($query);
						$this->_db->setQuery($query)->execute();
					}
				}
			}
		}

		// Implement JObservableInterface: Post-processing by observers
		$this->_observers->update('onAfterStore', array(&$result));

		return $result;
	}

	/**
	 * Get a table option value.
	 *
	 * @param   string $key     The key
	 * @param   mixed  $default The default value
	 *
	 * @return  mixed             The value or the default value
	 */
	public function getOption($key, $default = null)
	{
		if (isset($this->options[$key]))
		{
			return $this->options[$key];
		}

		return $default;
	}

	/**
	 * Set a table option value.
	 *
	 * @param   string $key The key
	 * @param   mixed  $val The default value
	 *
	 * @return  self
	 */
	public function setOption($key, $val)
	{
		$this->options[$key] = $val;

		return $this;
	}
}
