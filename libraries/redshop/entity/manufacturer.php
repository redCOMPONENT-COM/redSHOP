<?php
/**
 * @package     Redshop.Library
 * @subpackage  Entity
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
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
	 * @since  2.1.0
	 */
	protected $media;

	/**
	 * Get the associated table
	 *
	 * @param   string  $name  Main name of the Table. Example: Article for ContentTableArticle
	 *
	 * @return  RedshopTableManufacturer
	 * @throws  Exception
	 */
	public function getTable($name = "Manufacturer")
	{
		return RedshopTable::getAdminInstance($name, array('ignore_request' => true), 'com_redshop');
	}

	/**
	 * Method for get medias of current category
	 *
	 * @return  RedshopEntityMediaImage
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
		$this->media = RedshopEntityMediaImage::getInstance();

		if (!$this->hasId())
		{
			return $this;
		}

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
