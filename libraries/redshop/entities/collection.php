<?php
/**
 * @package     Redshop.Libraries
 * @subpackage  Entity
 *
 * @copyright   Copyright (C) 2012 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

/**
 * Collection of entities.
 *
 * @since  2.0.3
 */
class RedshopEntitiesCollection implements Countable, Iterator
{
	/**
	 * @var  array
	 */
	protected $entities = array();

	/**
	 * Constructor.
	 *
	 * @param   array  $entities  Entities to initialise the collection
	 */
	public function __construct(array $entities = array())
	{
		$this->entities = $entities;
	}

	/**
	 * Adds an entity to the collection. It won't add any entity that already exists
	 *
	 * @param   RedshopEntity  $entity  Entity going to be added
	 *
	 * @return  self
	 */
	public function add(RedshopEntity $entity)
	{
		if ($entity->hasId() && !$this->has($entity->getId()))
		{
			$this->entities[$entity->getId()] = $entity;
		}

		return $this;
	}

	/**
	 * Clears the entities of the collection
	 *
	 * @return  self
	 */
	public function clear()
	{
		$this->entities = array();

		return $this;
	}

	/**
	 * Gets the count of entities in this collection
	 *
	 * @return  integer
	 */
	public function count()
	{
		return count($this->entities);
	}

	/**
	 * Get the active entity.
	 * Iterator implementation.
	 *
	 * @return  mixed  RedshopEntity | FALSE if no entities
	 */
	public function current()
	{
		return current($this->entities);
	}

	/**
	 * Get an item by it's id
	 *
	 * @param   integer  $id  Item's identifier
	 *
	 * @return  mixed  RedshopEntity if item exists. Null otherwise
	 */
	public function get($id)
	{
		if ($this->has($id))
		{
			return $this->entities[$id];
		}

		return null;
	}

	/**
	 * Gets all the entities
	 *
	 * @return  array
	 */
	public function getAll()
	{
		return $this->entities;
	}

	/**
	 * Check if an entity is already in this collection
	 *
	 * @param   integer  $id  Entity identifier
	 *
	 * @return  boolean
	 */
	public function has($id)
	{
		return isset($this->entities[$id]);
	}

	/**
	 * Returns ids of the entities in the collection
	 *
	 * @return  array
	 */
	public function ids()
	{
		return array_keys($this->entities);
	}

	/**
	 * Check if the collection is empty
	 *
	 * @return  boolean
	 */
	public function isEmpty()
	{
		return !$this->entities;
	}

	/**
	 * Return the id of the active entity.
	 * Iterator implementation.
	 *
	 * @return  mixed  integer | FALSE if no entities
	 */
	public function key()
	{
		return key($this->entities);
	}

	/**
	 * Gets the next entity.
	 * Iterator implementation.
	 *
	 * @return  mixed  RedshopEntity | FALSE if no entities
	 */
	public function next()
	{
		return next($this->entities);
	}

	/**
	 * Removes an item from the collection
	 *
	 * @param   integer  $id  Entity identifier
	 *
	 * @return  boolean
	 */
	public function remove($id)
	{
		if (!$this->has($id))
		{
			return false;
		}

		unset($this->entities[$id]);

		return true;
	}

	/**
	 * Method to get the first entity.
	 * Iterator implementation.
	 *
	 * @return  mixed  RedshopEntity | FALSE if no entities
	 */
	public function rewind()
	{
		return reset($this->entities);
	}

	/**
	 * Sets an item. This removes previous item if it already exists
	 *
	 * @param   integer        $id      Entity identifier
	 * @param   RedshopEntity  $entity  Entity
	 *
	 * @return  self
	 */
	public function set($id, RedshopEntity $entity)
	{
		$this->entities[$id] = $entity;

		return $this;
	}

	/**
	 * Return entities as stdClass objects
	 *
	 * @return  array  An array of stdClass objects
	 */
	public function toObjects()
	{
		$objects = array();

		foreach ($this->entities as $id => $entity)
		{
			$objects[$id] = $entity->getItem();
		}

		return $objects;
	}

	/**
	 * Return entities as an array of a single field
	 *
	 * @param   string  $fieldName  Name of the field to return in the array
	 *
	 * @return  array  An array of fields
	 */
	public function toFieldArray($fieldName)
	{
		$fields = array();

		foreach ($this->entities as $id => $entity)
		{
			$fields[] = $entity->getItem()->$fieldName;
		}

		return $fields;
	}

	/**
	 * Check if there are still entities in the entities array.
	 * Iterator implementation.
	 *
	 * @return  boolean
	 */
	public function valid()
	{
		return key($this->entities) !== null;
	}
}
