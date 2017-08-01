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
 * Base Entity.
 *
 * @since  2.0.3
 */
abstract class RedshopEntity extends RedshopEntityBase
{
	/**
	 * Option of the component containing the tables. Example: com_content
	 *
	 * @var    string
	 * @since  2.0.3
	 */
	protected $component = 'com_redshop';

	/**
	 * Asset of this for this entity
	 *
	 * @var    JTable
	 * @since  2.0.3
	 */
	protected $asset;

	/**
	 * Converts an array of entities into an array of objects
	 *
	 * @param   array  $entities  Array of RedshopbEntity
	 *
	 * @return  array
	 *
	 * @throws  InvalidArgumentException  If an array of RedshopbEntity is not received
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
			if (!$entity instanceof RedshopEntity)
			{
				throw new InvalidArgumentException("RedshopEntityExpected in " . __FUNCTION__);
			}

			$results[$key] = $entity->getItem();
		}

		return $results;
	}
}
