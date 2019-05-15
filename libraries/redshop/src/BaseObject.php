<?php
/**
 * @package     RedShop
 * @subpackage  Libraries
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop;

defined('_JEXEC') or die;

/**
 * @package     Redshop\Bases
 *
 * @since       2.1.0
 */
class BaseObject
{
	/**
	 * Cached item
	 *
	 * @var    mixed
	 *
	 * @since  2.1.0
	 */
	protected $item = null;

	/**
	 * Proxy item properties
	 *
	 * @param   string $property Property tried to access
	 *
	 * @return  mixed   $this->item->property if it exists
	 *
	 * @since  2.1.0
	 */
	public function __get($property)
	{
		if (null != $this->item && property_exists($this->item, $property))
		{
			return $this->item->{$property};
		}

		return null;
	}

	/**
	 * Proxy item properties
	 *
	 * @param   string $property Property tried to access
	 * @param   mixed  $value    Value to assign
	 *
	 * @return  self
	 *
	 * @since   2.1.0
	 */
	public function __set($property, $value)
	{
		if (null === $this->item)
		{
			$this->item = new \stdClass;
		}

		$this->item->{$property} = $value;

		return $this;
	}

	/**
	 * Magic method isset for entity property
	 *
	 * @param   string $name Property name
	 *
	 * @return  boolean
	 *
	 * @since   2.1.0
	 */
	public function __isset($name)
	{
		return isset($this->item->{$name});
	}

	/**
	 * Ensure that clones don't modify cached data
	 *
	 * @return  void
	 *
	 * @since   2.1.0
	 */
	public function __clone()
	{
		$this->item = clone $this->item;
	}
}
