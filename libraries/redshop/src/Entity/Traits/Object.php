<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 *
 * @since       2.0.3
 */

namespace Redshop\Entity\Traits;

defined('_JEXEC') or die;

trait Object
{
	/**
	 * Identifier of the loaded instance
	 *
	 * @var  mixed
	 */
	protected $id = null;

	/**
	 * Constructor
	 *
	 * @param   mixed  $id  Identifier of the active item
	 */
	public function __construct($id = null)
	{
		if ($id)
		{
			$this->id = $id;
		}
	}

	/**
	 * Proxy item properties
	 *
	 * @param   string  $property  Property tried to access
	 *
	 * @return  mixed   $this->item->property if it exists
	 */
	public function __get($property)
	{
		if (null != $this->item && property_exists($this->item, $property))
		{
			return $this->item->$property;
		}

		return null;
	}

	/**
	 * Proxy item properties
	 *
	 * @param   string  $property  Property tried to access
	 * @param   mixed   $value     Value to assign
	 *
	 * @return  self
	 *
	 * @since   1.0
	 */
	public function __set($property, $value)
	{
		if (null === $this->item)
		{
			$this->item = new \stdClass;
		}

		$this->item->$property = $value;

		return $this;
	}

	/**
	 * Ensure that clones don't modify cached data
	 *
	 * @return  void
	 *
	 * @since   2.0
	 */
	public function __clone()
	{
		$this->item = clone $this->item;
	}

	/**
	 * Magic method isset for entity property
	 *
	 * @param   string  $name  Property name
	 *
	 * @return  boolean
	 */
	public function __isset($name)
	{
		return isset($this->item->$name);
	}

	/**
	 * Converts an array of entities into an array of objects
	 *
	 * @param   array  $entities  Array of RedshopEntity
	 *
	 * @return  array
	 *
	 * @throws  \InvalidArgumentException  If an array of RedshopEntity is not received
	 *
	 * @since   2.0.3
	 */
	public function entitiesToObjects(array $entities)
	{
		$results = array();

		if (!$entities)
		{
			return $results;
		}

		foreach ($entities as $key => $entity)
		{
			if (!$entity instanceof \RedshopEntity)
			{
				throw new \InvalidArgumentException("RedshopEntityExpected in " . __FUNCTION__);
			}

			$results[$key] = $entity->getItem();
		}

		return $results;
	}
}
