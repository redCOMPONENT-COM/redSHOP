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
 * This entity can be added to collections.
 *
 * @since  __DEPLOY_VERSION__
 */
interface EntityInterface
{
	/**
	 * Get the entity id.
	 *
	 * @return  mixed
	 */
	public function getId();

	/**
	 * Check if entity has an id set.
	 *
	 * @return  mixed
	 */
	public function hasId();

	/**
	 * Check if entity has loaded data.
	 *
	 * @return  boolean  True if data load success. False otherwise.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function isLoaded();

	/**
	 * Get the item on this entity.
	 *
	 * @return  mixed  Object / null
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getItem();
}
