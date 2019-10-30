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
 * @package     Redshob.Update
 *
 * @since       2.0.14
 */
class RedshopUpdate2014 extends RedshopInstallUpdate
{
	/**
	 * Method for migrate category images.
	 *
	 * @return  void
	 *
	 * @since   2.0.14
	 */
	public function migrateCategoryImages()
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select($db->qn(array('id', 'name', 'category_full_image', 'category_back_full_image')))
			->from($db->qn('#__redshop_category'))
			->where(
				'('
				. $db->qn('category_full_image') . ' IS NOT NULL OR ' . $db->qn('category_full_image') . ' <> ' . $db->quote('')
				. ') OR ('
				. $db->qn('category_back_full_image') . ' IS NOT NULL OR ' . $db->qn('category_back_full_image') . ' <> ' . $db->quote('')
				. ')'
			)
			->order($db->qn('lft'));

		$medias = $db->setQuery($query)->loadObjectList();

		if (empty($medias))
		{
			return;
		}

		$newBasePath = REDSHOP_MEDIA_IMAGE_RELPATH . 'category';
		$oldBasePath = REDSHOP_FRONT_IMAGES_RELPATH . 'category';

		foreach ($medias as $media)
		{
			// Prepare target folder.
			$path = $newBasePath . '/' . $media->id;

			if (!JFolder::exists($path))
			{
				JFolder::create($path);
			}

			// Copy index.html to this folder.
			if (!JFile::exists($path . '/index.html'))
			{
				JFile::copy(REDSHOP_MEDIA_IMAGE_RELPATH . 'index.html', $path . '/index.html');
			}

			if (!empty($media->category_full_image))
			{
				$this->storeMedia($media, $media->category_full_image, 'full', $oldBasePath, $newBasePath);
			}

			if (!empty($media->category_full_image))
			{
				$this->storeMedia($media, $media->category_back_full_image, 'back', $oldBasePath, $newBasePath);
			}
		}

		// Remove old folders
		JFolder::delete($oldBasePath);
	}

	/**
	 * Method for store media
	 *
	 * @param   object  $category     Category data
	 * @param   string  $fileName     File name
	 * @param   string  $scope        Scope of media.
	 * @param   string  $oldBasePath  Scope of media.
	 * @param   string  $newBasePath  Scope of media.
	 *
	 * @return  void
	 *
	 * @since   2.0.14
	 */
	private function storeMedia($category, $fileName, $scope, $oldBasePath, $newBasePath)
	{
		/** @var RedshopTableMedia $table */
		$table = RedshopTable::getAdminInstance('Media', array('ignore_request' => true), 'com_redshop');

		// Generate new image using MD5
		$newFileName = md5(basename($fileName)) . '.' . JFile::getExt($fileName);

		if (!$table->load(
			array(
				'media_name'    => $fileName,
				'media_section' => 'category',
				'section_id'    => $category->id,
				'media_type'    => 'images'
			)
		))
		{
			$table->section_id    = $category->id;
			$table->media_section = 'category';
			$table->media_type    = 'images';
		}

		$table->media_alternate_text = $category->name;
		$table->published            = 1;
		$table->ordering             = 0;
		$table->scope                = $scope;

		// Check old image exist.
		$oldImagePath = $oldBasePath . '/' . $fileName;

		if (!JFile::exists($oldImagePath))
		{
			return;
		}

		if (!JFile::copy($oldImagePath, $newBasePath . '/' . $category->id . '/' . $newFileName))
		{
			return;
		}

		// Update media data with new file name.
		$table->media_name = $newFileName;
		$table->store();
	}
}
