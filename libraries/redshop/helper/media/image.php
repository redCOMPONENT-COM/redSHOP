<?php
/**
 * @package     Redshop.Library
 * @subpackage  Config
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

/**
 * Redshop Helper for Media Upload Dropzone
 *
 * @package     Redshop.Library
 * @subpackage  Config
 * @since       1.5
 */
class RedshopHelperMediaImage
{
	/**
	 * Render Drag n Drop template in site
	 *
	 * @param   string  $sectionId     Section ID, ex: <product ID>
	 * @param   string  $mediaSection  Section for media, ex: 'product'
	 * @param   string  $image         Current image in detail
	 *
	 * @return  void
	 */
	public static function render($id, $type, $sectionId, $mediaSection, $image)
	{
		if (self::requireDependencies())
		{
			$imgUrl = JRoute::_('/components/com_redshop/assets/images/' . $type . '/' . $image);
			$imgFile = REDSHOP_FRONT_IMAGES_RELPATH . $type . '/' . $image;

			$file = array();

			if (!empty($image) && file_exists($imgFile))
			{
				$file = array(
					'path' => $imgUrl,
					'name' => $image,
					'size' => filesize($imgFile) ? filesize($imgFile) : 0,
					'blob' => 'data: ' . mime_content_type($imgFile) . ';base64,' . base64_encode(file_get_contents($imgFile))
				);
			}

			echo RedshopLayoutHelper::render(
				'component.dropzone',
				array(
					'id'           => $id,
					'type'         => $type,
					'sectionId'    => $sectionId,
					'mediaSection' => $mediaSection,
					'file'         => $file
				)
			);
		}
	}

	/**
	 * Require dependecies from bower.js.
	 * Checking dependencies are existed or not then require them to header
	 *
	 * @return  boolean
	 */
	public static function requireDependencies()
	{
		$document      = new RedshopHelperDocument;
		$basepath = JPATH_SITE . '/media/com_reditem/';

		$dropzonePath = $basepath . 'dropzone';
		$cropperPath  = $basepath . 'cropper';

		if (file_exists($dropzonePath) && file_exists($cropperPath))
		{
			$document->addStylesheet('/media/com_reditem/dropzone/dist/min/dropzone.min.css');
			$document->addStylesheet('/media/com_reditem/cropper/dist/cropper.min.css');

			$document->addScript('/media/com_reditem/dropzone/dist/min/dropzone.min.js');
			$document->addScript('/media/com_reditem/cropper/dist/cropper.min.js');
			$document->addScript('/media/com_reditem/fuse.js/src/fuse.min.js');

			return true;
		}

		return false;
	}

	/**
	 * Render Gallery
	 *
	 * @return  void
	 */
	public static function renderGallery($id, $type, $sectionId, $mediaSection, $image)
	{
		$imgUrl = JRoute::_('/components/com_redshop/assets/images/' . $type . '/' . $image);
		$imgFile = REDSHOP_FRONT_IMAGES_RELPATH . $type . '/' . $image;

		$file = array();

		JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_redshop/models');
		$media   = JModelLegacy::getInstance('Media', 'RedshopModel');

		$listMedia = $media->all($type);
		$gallery = array();

		if (!empty($listMedia))
		{
			foreach ($listMedia as $lk => $lm)
			{
				$tmpFile = REDSHOP_FRONT_IMAGES_RELPATH . $type . '/' . $lm->media_name;
				$dimension = getimagesize($tmpFile);
				$tmpImg = array(
					'id'  => $lm->media_id,
					'url' => JRoute::_('/components/com_redshop/assets/images/' . $type . '/' . $lm->media_name, true, -1),
					'name' => $lm->media_name,
					'size' => self::sizeFilter(filesize($tmpFile)),
					'dimension' => $dimension[0] . ' x ' . $dimension[1]
				);
				$gallery[] = $tmpImg;
			}
		}

		if (!empty($image) && file_exists($imgFile))
		{
			$file = array(
				'path' => $imgUrl,
				'name' => $image,
				'size' => filesize($imgFile),
				'blob' => 'data: ' . mime_content_type($imgFile) . ';base64,' . base64_encode(file_get_contents($imgFile))
			);
		}

		echo RedshopLayoutHelper::render(
			'component.gallery',
			array(
				'id'           => $id,
				'type'         => $type,
				'sectionId'    => $sectionId,
				'mediaSection' => $mediaSection,
				'file'         => $file,
				'gallery'      => $gallery
			)
		);
	}

	public static function sizeFilter( $bytes )
	{
		$label = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
		for($i = 0; $bytes >= 1024 && $i < (count( $label ) -1 ); $bytes /= 1024, $i++);
		return(round( $bytes, 2 ) . " " . $label[$i]);
	}
}
