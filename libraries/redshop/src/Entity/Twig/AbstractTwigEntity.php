<?php
/**
 * @package     Redshop.Library
 * @subpackage  Entity
 *
 * @copyright   Copyright (C) 2012 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

namespace Redshop\Entity\Twig;

defined('_JEXEC') or die;

use Redshop\Entity\AbstractEntity;
use Redshop\Entity\EntityInterface;

/**
 * Base Twig Entity.
 *
 * @since  3.3.10
 */
abstract class AbstractTwigEntity implements EntityInterface
{
	/**
	 * The entity.
	 *
	 * @var  AbstractEntity
	 */
	protected $entity;

	/**
	 * Constructor.
	 *
	 * @param   AbstractEntity  $entity  The entity
	 */
	public function __construct(AbstractEntity $entity)
	{
		$this->entity = $entity;
	}

	/**
	 * Proxy item properties
	 *
	 * @param   string  $property  Property tried to access
	 *
	 * @return  mixed   $this->item->property if it exists
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function __get($property)
	{
		return $this->entity->__get($property);
	}

	/**
	 * Is user allowed to create a new item?
	 *
	 * @return  boolean
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function canCreate()
	{
		return $this->entity->canCreate();
	}

	/**
	 * Is user allowed to delete an item?
	 *
	 * @return  boolean
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function canDelete()
	{
		return $this->entity->canDelete();
	}

	/**
	 * Check if current user has permission to perform an action
	 *
	 * @param   string  $action  The action. Example: core.create
	 *
	 * @return  boolean
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function canDo($action)
	{
		return $this->entity->canDo($action);
	}

	/**
	 * Is user allowed to edit an item?
	 *
	 * @return  boolean
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function canEdit()
	{
		return $this->entity->canEdit();
	}

	/**
	 * Check if user can view this item.
	 *
	 * @return  boolean
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function canView()
	{
		return $this->entity->canView();
	}

	/**
	 * Get an item property
	 *
	 * @param   string  $property  Property to get
	 * @param   mixed   $default   Default value to assign if property === null | property === ''
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function get($property, $default = null)
	{
		return $this->entity->get($property, $default);
	}

	/**
	 * Get the item edit link
	 *
	 * @param   mixed    $itemId  Specify a custom itemId if needed. Default: joomla way to use active itemid
	 * @param   boolean  $routed  Process URL with JRoute?
	 * @param   boolean  $xhtml   Replace & by &amp; for XML compliance.
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getEditLink(string $itemId = 'inherit', bool $routed = true, bool $xhtml = true)
	{
		return $this->entity->getEditLink($itemId, $routed, $xhtml);
	}

	/**
	 * Get the item edit link with a return link to the current page.
	 *
	 * @param   mixed    $itemId  Specify a custom itemId if needed. Default: joomla way to use active itemid
	 * @param   boolean  $routed  Process URL with JRoute?
	 * @param   boolean  $xhtml   Replace & by &amp; for XML compliance.
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getEditLinkWithReturn(string $itemId = 'inherit', bool $routed = true, bool $xhtml = true)
	{
		return $this->entity->getEditLinkWithReturn($itemId, $routed, $xhtml);
	}

	/**
	 * Get the entity id.
	 *
	 * @return  mixed
	 */
	public function getId()
	{
		return $this->entity->getId();
	}

	/**
	 * Get the item on this entity.
	 *
	 * @return  mixed  Object / null
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getItem()
	{
		return $this->entity->getItem();
	}

	/**
	 * Get the item link
	 *
	 * @param   mixed    $itemId  Specify a custom itemId if needed. Default: joomla way to use active itemid
	 * @param   boolean  $routed  Process URL with JRoute?
	 * @param   boolean  $xhtml   Replace & by &amp; for XML compliance.
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getLink(string $itemId = 'inherit', bool $routed = true, bool $xhtml = false)
	{
		return $this->entity->getLink($itemId, $routed, $xhtml);
	}

	/**
	 * Check if entity has an ID set.
	 *
	 * @return  mixed
	 */
	public function hasId()
	{
		return $this->entity->hasId();
	}

	/**
	 * Check if the attached entity is loaded.
	 *
	 * @return  boolean
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function isLoaded()
	{
		return $this->entity->isLoaded();
	}
}
