<?php
/**
 * @package     Redshop.Library
 * @subpackage  Entity
 *
 * @copyright   Copyright (C) 2012 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

/**
 * Media entity
 *
 * @package     Redshop.Library
 * @subpackage  Entity
 * @since       __DEPLOY_VERSION__
 */
class RedshopEntityMedia extends RedshopEntity
{
	/**
	 * @var  RedshopEntity
	 */
	protected $sectionEntity;

	/**
	 * Method for get section entity
	 *
	 * @return  RedshopEntity
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getSection()
	{
		if ($this->sectionEntity === null)
		{
			$this->loadSectionEntity();
		}

		return $this->sectionEntity;
	}

	/**
	 * Method for load section entity
	 *
	 * @return  self
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function loadSectionEntity()
	{
		if (!$this->hasId())
		{
			return $this;
		}

		$entityClass         = 'RedshopEntity' . ucfirst($this->get('media_section'));
		$this->sectionEntity = $entityClass::getInstance($this->get('section_id'));

		return $this;
	}
}
