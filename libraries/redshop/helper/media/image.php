<?php
/**
 * @package     Redshop.Library
 * @subpackage  Config
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
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
	 * @param   string  $id            ID of media input name
	 * @param   string  $type          Type of item want to show gallery
	 * @param   string  $sectionId     Section ID to show
	 * @param   string  $mediaSection  Section type to show
	 * @param   string  $image         URL of featured image
	 * @param   bool    $showMedia     Show pop-up of media or not.
	 *
	 * @return  string
	 */
	public static function render($id, $type, $sectionId, $mediaSection, $image, $showMedia = true)
	{
		self::requireDependencies();

		$imgUrl  = JRoute::_('/components/com_redshop/assets/images/' . $type . '/' . $image);
		$imgFile = REDSHOP_FRONT_IMAGES_RELPATH . $type . '/' . $image;

		$file = array();

		if (!empty($image) && file_exists($imgFile))
		{
			$file = array(
				'path' => $imgUrl,
				'name' => $image,
				'size' => filesize($imgFile) ? filesize($imgFile) : 0,
				'blob' => 'data: ' . self::getMimeType($imgFile) . ';base64,' . base64_encode(file_get_contents($imgFile))
			);
		}

		return RedshopLayoutHelper::render(
			'media.dropzone',
			array(
				'id'           => $id,
				'type'         => $type,
				'sectionId'    => $sectionId,
				'mediaSection' => $mediaSection,
				'file'         => $file,
				'showMedia'    => $showMedia
			)
		);
	}

	/**
	 * Require dependencies from bower.js.
	 * Checking dependencies are existed or not then require them to header
	 *
	 * @return  boolean
	 */
	public static function requireDependencies()
	{
		JHtml::stylesheet('com_redshop/dropzone/dropzone.css', array(), true);
		JHtml::stylesheet('com_redshop/cropper/cropper.css', array(), true);
		JHtml::stylesheet('com_redshop/lightbox2/css/lightbox.css', array(), true);
		JHtml::stylesheet('com_redshop/redshop.media.css', array(), true);

		JHtml::script('com_redshop/dropzone/dropzone.js', false, true);
		JHtml::script('com_redshop/cropper/cropper.js', false, true);
		JHtml::script('com_redshop/lightbox2/lightbox.js', false, true);
		JHtml::script('com_redshop/redshop.media.js', false, true);

		return true;
	}

	/**
	 * Render gallery pop-up for media
	 *
	 * @param   string  $id            ID of media input name
	 * @param   string  $type          Type of item want to show gallery
	 * @param   string  $sectionId     Section ID to show
	 * @param   string  $mediaSection  Section type to show
	 * @param   string  $image         URL of featured image
	 *
	 * @return  void
	 */
	public static function renderGallery($id, $type, $sectionId, $mediaSection, $image)
	{
		$imgUrl  = JUri::root() . 'components/com_redshop/assets/images/' . $type . '/' . $image;
		$imgFile = REDSHOP_FRONT_IMAGES_RELPATH . $type . '/' . $image;

		$file = array();

		JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_redshop/models');
		$media     = JModelLegacy::getInstance('Media', 'RedshopModel');

		$listMedia = $media->all();
		$gallery   = array();

		if (!empty($listMedia))
		{
			foreach ($listMedia as $lk => $lm)
			{
				$tmpFile = REDSHOP_FRONT_IMAGES_RELPATH . $lm->media_section . '/' . $lm->media_name;

				if (file_exists($tmpFile))
				{
					$dimension = getimagesize($tmpFile);

					if ($dimension)
					{
						$dimension = $dimension[0] . ' x ' . $dimension[1];
					}

					$tmpImg    = array(
						'id'        => $lm->media_id,
						'url'       => JUri::root() . 'components/com_redshop/assets/images/' . $lm->media_section . '/' . $lm->media_name,
						'name'      => $lm->media_name,
						'size'      => self::sizeFilter(filesize($tmpFile)),
						'dimension' => $dimension,
						'media'     => $lm->media_section,
						'mime'      => substr($lm->media_type, 0, -1),
						'status'    => $lm->published ? '' : '-slash'
					);

					if ($image === $lm->media_name)
					{
						$tmpImg['attached'] = "true";
					}
					else
					{
						$tmpImg['attached'] = "false";
					}

					$gallery[] = $tmpImg;
				}
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
			'media.gallery',
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

	/**
	 * Show file size in KB, MB, GB...
	 *
	 * @param   integer  $bytes  Volume of item
	 *
	 * @return  string
	 */
	public static function sizeFilter($bytes)
	{
		$label = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');

		for ($i = 0; $bytes >= 1024 && $i < (count($label) - 1); $i++)
		{
			$bytes /= 1024;
		}

		return round($bytes, 2) . " " . $label[$i];
	}

	/**
	 * Method for get all media files of redSHOP
	 *
	 * @param   string  $selectedImage  Selected file.
	 *
	 * @return  array                   List of media files.
	 *
	 * @since   2.0.3
	 */
	public static function getMediaFiles($selectedImage = '')
	{
		JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_redshop/models');
		$media     = JModelLegacy::getInstance('Media', 'RedshopModel');
		$listMedia = $media->all();

		if (empty($listMedia))
		{
			return array();
		}

		$gallery   = array();

		foreach ($listMedia as $lk => $lm)
		{
			$tmpFile = REDSHOP_FRONT_IMAGES_RELPATH . $lm->media_section . '/' . $lm->media_name;

			if (file_exists($tmpFile))
			{
				$dimension = getimagesize($tmpFile);

				if ($dimension)
				{
					$dimension = $dimension[0] . ' x ' . $dimension[1];
				}

				$tmpImg    = array(
					'id'        => $lm->media_id,
					'url'       => JUri::root() . 'components/com_redshop/assets/images/' . $lm->media_section . '/' . $lm->media_name,
					'name'      => $lm->media_name,
					'size'      => self::sizeFilter(filesize($tmpFile)),
					'dimension' => $dimension,
					'media'     => $lm->media_section,
					'mime'      => substr($lm->media_type, 0, -1),
					'status'    => $lm->published ? '' : '-slash'
				);

				if ($selectedImage === $lm->media_name)
				{
					$tmpImg['attached'] = "true";
				}
				else
				{
					$tmpImg['attached'] = "false";
				}

				$gallery[] = $tmpImg;
			}
		}

		return $gallery;
	}

	/**
	 * Method for get MIME Type of specific file.
	 *
	 * @param   string  $path  Path of file.
	 *
	 * @return  mixed          Mime type of file.
	 *
	 * @since   2.0.3
	 */
	public static function getMimeType($path)
	{
		if (empty($path) || !JFile::exists($path))
		{
			return false;
		}

		if (function_exists('mime_content_type'))
		{
			return mime_content_type($path);
		}

		if (function_exists('finfo_file') && function_exists('finfo_open'))
		{
			return finfo_file(finfo_open(FILEINFO_MIME_TYPE), $path);
		}

		return false;
	}
}
