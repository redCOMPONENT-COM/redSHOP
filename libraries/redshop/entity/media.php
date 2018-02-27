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
 * @since       2.1.0
 */
class RedshopEntityMedia extends \Redshop\Entity\AbstractEntity
{
	/**
	 * @var  \Redshop\Entity\AbstractEntity
	 */
	protected $sectionEntity;

	/**
	 * Method for get section entity
	 *
	 * @return  \Redshop\Entity\AbstractEntity
	 *
	 * @since   2.1.0
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
	 * @since   2.1.0
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
