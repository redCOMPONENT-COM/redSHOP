<?php
/**
 * @package     Redshop\Repositories
 * @subpackage  Base
 *
 * @copyright   Copyright (C) 2012 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

namespace Redshop\Repositories;

/**
 * @package     Redshop\Repositories
 *
 * @since       2.1.0
 */
abstract class Base extends QueryBuilder
{
	/**
	 * @return string
	 * @throws \ReflectionException
	 *
	 * @since  2.1.0
	 */
	public function __toString()
	{
		return $this->toString();
	}

	/**
	 * @return string
	 * @throws \ReflectionException
	 *
	 * @since 2.1.0
	 */
	public function toString()
	{
		$reflect = new \ReflectionClass($this);

		return $reflect->getShortName();
	}

	/**
	 * @param   integer      $id      Id
	 * @param   array|object $default Default value
	 *
	 * @return  \RedshopEntityBase
	 *
	 * @since   2.1.0
	 * @throws  \ReflectionException
	 */
	public function getById($id, $default = null)
	{
		$entityClassName = 'RedshopEntity' . ucfirst($this->toString());

		/**
		 * @var \RedshopEntity $entity
		 */
		$entity = call_user_func(array($entityClassName, 'getInstance'), $id);

		if ($entity->isValid() || $default === null)
		{
			return $entity;
		}

		$entity->bind($default);

		return $entity;
	}

	/**
	 * @param   integer $offset Offset
	 * @param   integer $limit  Limit
	 *
	 * @return  array|mixed|\RedshopEntitiesCollection
	 *
	 * @since   2.1.0
	 * @throws  \ReflectionException
	 */
	public function getAll($offset = null, $limit = null)
	{
		$items = parent::getAll($offset, $limit);

		if (empty($items))
		{
			return array();
		}

		$collections = new \RedshopEntitiesCollection;

		foreach ($items as $item)
		{
			$collections->add($this->getById($item));
		}

		$this->reset();

		return $collections;
	}
}
