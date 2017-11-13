<?php
/**
 * @package     Redshop.Library
 * @subpackage  Entity
 *
 * @copyright   Copyright (C) 2012 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

namespace Redshop\Entity;

defined('_JEXEC') or die;

/**
 * Collection of entities.
 *
 * @since  __DEPLOY_VERSION__
 */
class EntityCollection extends CoreEntityCollection
{
	/**
	 * Intersect two collections.
	 *
	 * @param   EntityCollection  $collection  Collection to intersect.
	 *
	 * @return  static
	 *
	 * @since   4.1.1
	 */
	public function intersect(EntityCollection $collection)
	{
		$intersection = new static;

		if ($collection->isEmpty())
		{
			return $intersection;
		}

		$commonIds = array_intersect(array_keys($this->entities), $collection->ids());

		foreach ($commonIds as $id)
		{
			$intersection->add($this->entities[$id]);
		}

		return $intersection;
	}

	/**
	 * Merge another collection on this collection.
	 *
	 * @param   EntityCollection  $collection  Collection to merge
	 *
	 * @return  static
	 *
	 * @since   4.1.1
	 */
	public function merge(EntityCollection $collection)
	{
		$merge = clone $this;

		if ($collection->isEmpty())
		{
			return $merge;
		}

		foreach ($collection as $item)
		{
			$merge->add($item);
		}

		return $merge;
	}

	/**
	 * Get a collection of these elements ordered by a specific property.
	 *
	 * @param   string  $property   Property to use for ordering
	 * @param   string  $direction  'asc' | 'desc'
	 *
	 * @return  static
	 *
	 * @since   4.1.1
	 */
	public function sortByProperty($property, $direction = 'asc')
	{
		$direction = strtolower($direction);
		$sorted = [];

		foreach ($this->entities as $entity)
		{
			$sorted[$entity->id] = $this->searchEntityProperty($entity, $property);
		}

		if ($direction === 'desc')
		{
			arsort($sorted);
		}
		else
		{
			asort($sorted);
		}

		$orderedCollection = new EntityCollection;

		foreach ($sorted as $id => $property)
		{
			$orderedCollection->add($this->entities[$id]);
		}

		return $orderedCollection;
	}

	/**
	 * Shit function to try to search a property on an entity.
	 *
	 * @param   EntityInterface  $entity    Entity where we want to search the propery
	 * @param   string           $property  Property to search. Examples: `created_date` | `data.duration_start_date`.
	 *
	 * @return  string
	 *
	 * @since   4.1.1
	 */
	private function searchEntityProperty(EntityInterface $entity, $property)
	{
		$properties = explode('.', $property);
		$searchObject = $entity->getItem();

		foreach ($properties as $position => $property)
		{
			if ($position + 1 === count($properties))
			{
				if (!property_exists($searchObject, $property))
				{
					return 	'';

					continue;
				}

				return (string) $searchObject->{$property};
			}

			if (!property_exists($searchObject, $property))
			{
				$getter = 'get' . ucfirst(strtolower($property));

				if (method_exists($entity, $getter))
				{
					$searchObject = (object) $entity->$getter();

					continue;
				}
			}

			$searchObject = (object) $searchObject->{$property};
		}
	}

	/**
	 * Get this collection on read
	 *
	 * @return  EntityCollection
	 *
	 * @since   __DEPLOY_VERSION__
	 *
	 * @throws  \RuntimeException
	 */
	public function toTwigEntities()
	{
		$collection = new EntityCollection;

		foreach ($this->entities as $entity)
		{
			if (!$entity instanceof TwigableEntityInterface)
			{
				throw new \RuntimeException(sprintf('Entity of type `%s` does not implement `TwigableEntityInterface`', get_class($entity)));
			}

			$collection->add($entity->getTwigEntity());
		}

		return $collection;
	}
}
