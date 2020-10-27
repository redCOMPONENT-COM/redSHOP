<?php
/**
 * @package     Redshop.Libraries
 * @subpackage  Entity
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

namespace Redshop\Entities;

defined('_JEXEC') or die;

/**
 * Collection of entities.
 *
 * @since  __DEPLOY_VERSION__
 */
class Collection implements \Countable, \Iterator
{
    /**
     * @var  array
     * @since  __DEPLOY_VERSION__
     */
    protected $entities = array();

    /**
     * Constructor.
     * @since  __DEPLOY_VERSION__
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
     * @param   \Redshop\Entity\Entity  $entity  Entity going to be added
     *
     * @return  self
     * @since  __DEPLOY_VERSION__
     */
    public function add(\Redshop\Entity\Entity $entity)
    {
        if ($entity->hasId() && !$this->has($entity->getId())) {
            $this->entities[$entity->getId()] = $entity;
        }

        return $this;
    }

    /**
     * Check if an entity is already in this collection
     *
     * @param   integer  $id  Entity identifier
     *
     * @return  boolean
     * @since  __DEPLOY_VERSION__
     */
    public function has($id)
    {
        return isset($this->entities[$id]);
    }

    /**
     * Clears the entities of the collection
     *
     * @return  self
     * @since  __DEPLOY_VERSION__
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
     * @since  __DEPLOY_VERSION__
     */
    public function count()
    {
        return count($this->entities);
    }

    /**
     * Get the active entity.
     * Iterator implementation.
     *
     * @return  mixed  \Redshop\Entity\Entity | FALSE if no entities
     * @since  __DEPLOY_VERSION__
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
     * @return  mixed  Redshop\Entity\Entity if item exists. Null otherwise
     * @since  __DEPLOY_VERSION__
     */
    public function get($id)
    {
        if ($this->has($id)) {
            return $this->entities[$id];
        }

        return null;
    }

    /**
     * Gets all the entities
     *
     * @return  array
     * @since  __DEPLOY_VERSION__
     */
    public function getAll()
    {
        return $this->entities;
    }

    /**
     * Returns ids of the entities in the collection
     *
     * @return  array
     * @since  __DEPLOY_VERSION__
     */
    public function ids()
    {
        return array_keys($this->entities);
    }

    /**
     * Check if the collection is empty
     *
     * @return  boolean
     * @since  __DEPLOY_VERSION__
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
     * @since  __DEPLOY_VERSION__
     */
    public function key()
    {
        return key($this->entities);
    }

    /**
     * Gets the next entity.
     * Iterator implementation.
     *
     * @return  mixed  Redshop\Entity\Entity | FALSE if no entities
     * @since  __DEPLOY_VERSION__
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
     * @since  __DEPLOY_VERSION__
     */
    public function remove($id)
    {
        if (!$this->has($id)) {
            return false;
        }

        unset($this->entities[$id]);

        return true;
    }

    /**
     * Method to get the first entity.
     * Iterator implementation.
     *
     * @return  mixed  Redshop\Entity\Entity | FALSE if no entities
     * @since  __DEPLOY_VERSION__
     */
    public function rewind()
    {
        return reset($this->entities);
    }

    /**
     * Sets an item. This removes previous item if it already exists
     *
     * @param   integer        $id      Entity identifier
     * @param   \Redshop\Entity\Entity  $entity  Entity
     *
     * @return  self
     * @since  __DEPLOY_VERSION__
     */
    public function set($id, \Redshop\Entity\Entity $entity)
    {
        $this->entities[$id] = $entity;

        return $this;
    }

    /**
     * Return entities as stdClass objects
     *
     * @return  array  An array of stdClass objects
     * @since  __DEPLOY_VERSION__
     */
    public function toObjects()
    {
        $objects = array();

        foreach ($this->entities as $id => $entity) {
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
     * @since  __DEPLOY_VERSION__
     */
    public function toFieldArray($fieldName)
    {
        $fields = array();

        foreach ($this->entities as $id => $entity) {
            $fields[] = $entity->getItem()->$fieldName;
        }

        return $fields;
    }

    /**
     * Check if there are still entities in the entities array.
     * Iterator implementation.
     *
     * @return  boolean
     * @since  __DEPLOY_VERSION__
     */
    public function valid()
    {
        return key($this->entities) !== null;
    }
}
