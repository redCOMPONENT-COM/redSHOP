<?php
/**
 * @package     Redshop\Entity\Traits\Product
 * @subpackage  Media
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

namespace Redshop\Entity\Traits\Product;

/**
 * Trait Media
 * @package Redshop\Entity\Traits\Product
 *
 * @since   2.1.0
 */
trait Media
{
	/**
	 * @var    \RedshopEntitiesCollection
	 *
	 * @since  2.1.0
	 */
	protected $media;

	/**
	 * Method for get medias of current category
	 *
	 * @return  \RedshopEntitiesCollection
	 *
	 * @since   2.1.0
	 */
	public function getMedia()
	{
		if (null === $this->media)
		{
			$this->loadMedia();
		}

		return $this->media;
	}

	/**
	 * Method for load medias
	 *
	 * @return  self
	 *
	 * @since   2.1.0
	 */
	protected function loadMedia()
	{
		$this->media = new \RedshopEntitiesCollection;

		if (!$this->hasId())
		{
			return $this;
		}

		$db    = \JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('media_id')
			->from($db->qn('#__redshop_media'))
			->where($db->qn('media_section') . ' = ' . $db->quote('product'))
			->where($db->qn('section_id') . ' = ' . $db->quote($this->getId()));

		$results = $db->setQuery($query)->loadColumn();

		if (empty($results))
		{
			return $this;
		}

		foreach ($results as $mediaId)
		{
			$this->media->add(\RedshopEntityMedia::getInstance($mediaId));
		}

		return $this;
	}

	/**
	 * Check if we have an identifier loaded
	 *
	 * @return  boolean
	 * @since   2.1.0
	 */
	abstract public function hasId();

	/**
	 * Get the id
	 *
	 * @return  integer | null
	 * @since   2.1.0
	 */
	abstract public function getId();
}
