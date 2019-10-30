<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Updates
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Update class
 *
 * @package   Redshob.Update
 *
 * @since     2.0.13
 */
class RedshopUpdate2013 extends RedshopInstallUpdate
{
	/**
	 * Method for migrate manufacturer images.
	 *
	 * @return  void
	 * @throws  Exception
	 *
	 * @since   2.0.13
	 */
	public function migrateManufacturerImages()
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__redshop_media'))
			->where($db->qn('media_section') . ' = ' . $db->quote('manufacturer'))
			->order($db->qn('section_id'));

		$medias = $db->setQuery($query)->loadObjectList();

		if (empty($medias))
		{
			return;
		}

		$newBasePath = REDSHOP_MEDIA_IMAGE_RELPATH . 'manufacturer';
		$oldBasePath = REDSHOP_FRONT_IMAGES_RELPATH . 'manufacturer';

		foreach ($medias as $media)
		{
			/** @var RedshopTableMedia $table */
			$table = RedshopTable::getAdminInstance('Media', array('ignore_request' => true), 'com_redshop');

			$table->bind((array) $media);

			// In case this media don't have media file. Delete this.
			if (empty($table->media_name))
			{
				$table->delete();

				continue;
			}

			// Prepare target folder.
			$path = $newBasePath . '/' . $table->section_id;

			if (!JFolder::exists($path))
			{
				JFolder::create($path);
			}

			// Copy index.html to this folder.
			if (!JFile::exists($path . '/index.html'))
			{
				JFile::copy(REDSHOP_MEDIA_IMAGE_RELPATH . 'index.html', $path . '/index.html');
			}

			// Check old image exist.
			$oldImagePath = $oldBasePath . '/' . $table->media_name;

			if (!JFile::exists($oldImagePath))
			{
				continue;
			}

			// Generate new image using MD5
			$newFileName = md5(basename($table->media_name)) . '.' . JFile::getExt($table->media_name);

			if (!JFile::copy($oldImagePath, $path . '/' . $newFileName))
			{
				continue;
			}

			// Update media data with new file name.
			$table->media_name = $newFileName;
			$table->store();
		}

		// Remove old folders
		JFolder::delete($oldBasePath);
	}
}
