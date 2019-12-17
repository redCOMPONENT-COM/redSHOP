<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Model Manufacturer
 *
 * @package     RedSHOP.Backend
 * @subpackage  Model
 * @since       2.1.0
 */
class RedshopModelManufacturer extends RedshopModelForm
{
	/**
	 * The unique columns.
	 *
	 * @var  array
	 */
	protected $copyUniqueColumns = array('name');

	/**
	 * Method for run after success copy record
	 *
	 * @param   JTable  $source  Source record
	 * @param   JTable  $target  Target record
	 *
	 * @return  void
	 */
	public function afterCopy($source, $target)
	{
		// Copy media file if necessary
		$media = RedshopEntityManufacturer::getInstance()->bind($source)->getMedia();

		if (!$media->isValid())
		{
			return;
		}

		/** @var RedshopTableMedia $table */
		$table = RedshopTable::getAdminInstance('Media', array('ignore_request' => true), 'com_redshop');
		$table->bind((array) $media->getItem());

		// Copy new image for this media
		$newFileName = md5($target->name) . '.' . JFile::getExt($media->get('media_name'));
		\Redshop\Helper\Media::createFolder(REDSHOP_MEDIA_IMAGE_RELPATH . 'manufacturer/' . $target->id);
		\Redshop\Helper\Media::createFolder(REDSHOP_MEDIA_IMAGE_RELPATH . 'manufacturer/' . $target->id . '/thumb');
		JFile::copy($media->getImagePath(), REDSHOP_MEDIA_IMAGE_RELPATH . 'manufacturer/' . $target->id . '/' . $newFileName);

		// Store media table
		$table->set('media_id', 0);
		$table->set('media_name', $newFileName);
		$table->set('section_id', $target->id);
		$table->store();
	}
}
