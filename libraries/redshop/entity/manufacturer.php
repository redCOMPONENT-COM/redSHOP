<?php
/**
 * @package     Redshop.Library
 * @subpackage  Entity
 *
 * @copyright   Copyright (C) 2012 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

/**
 * Manufacturer Entity
 *
 * @package     Redshop.Library
 * @subpackage  Entity
 * @since       2.0.3
 */
class RedshopEntityManufacturer extends RedshopEntity
{
	/**
	 * @var  RedshopEntityMediaImage
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected $media;

	/**
	 * Get the associated table
	 *
	 * @param   string  $name  Main name of the Table. Example: Article for ContentTableArticle
	 *
	 * @return  RedshopTable
	 */
	public function getTable($name = "Tablemanufacturer_detail")
	{
		return JTable::getInstance('Manufacturer_detail', 'Table');
	}

	/**
	 * Method for get medias of current category
	 *
	 * @return  RedshopEntityMediaImage
	 *
	 * @since   __DEPLOY_VERSION__
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
	 * @since   __DEPLOY_VERSION__
	 */
	protected function loadMedia()
	{
		if (!$this->hasId())
		{
			return $this;
		}

		$this->media = RedshopEntityMediaImage::getInstance();

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__redshop_media'))
			->where($db->qn('media_section') . ' = ' . $db->quote('manufacturer'))
			->where($db->qn('section_id') . ' = ' . $db->quote($this->getId()));

		$result = $db->setQuery($query)->loadObject();

		if (empty($result))
		{
			return $this;
		}

		$this->media = RedshopEntityMediaImage::getInstance($result->media_id);
		$this->media->bind($result);

		return $this;
	}
}
