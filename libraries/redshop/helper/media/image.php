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

        $imgUrl = JUri::root() . 'media/com_redshop/files/' . $mediaSection . '/' . $sectionId . '/' . $image;

		$imgFile = JPATH_ROOT . '/media/com_redshop/files/' . $mediaSection . '/' . $sectionId . '/' . $image;

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
		/* Init some variables using in javascript */
		$maxFileSize = self::parseSize(ini_get('upload_max_filesize'));

		$allowMime = array(
				'image/jpeg',
				'image/jpg',
				'image/png',
				'image/gif',
				'video/mp4',
				'video/flv',
				'audio/mp3',
				'audio/flac',
				'application/vnd.ms-excel'
			);

		$allowMime = implode(',', $allowMime);

		$script   = [];
		$script[] = "var mediaConfig = new Object();";
		$script[] = "mediaConfig.allowmime = '$allowMime';";
		$script[] = "mediaConfig.maxFileSize = $maxFileSize;";

		$script = implode(' ', $script);

		try
		{
			/* Add script declaration */
			$doc = JFactory::getDocument();
			$doc->addScriptDeclaration($script);

			/* Load StyleSheets */
			JHtml::stylesheet('com_redshop/dropzone/dropzone.css', array(), true);
			JHtml::stylesheet('com_redshop/cropper/cropper.css', array(), true);
			JHtml::stylesheet('com_redshop/lightbox2/css/lightbox.css', array(), true);
			JHtml::stylesheet('com_redshop/redshop.media.css', array(), true);

			/* Load Javascript */
			JHtml::script('com_redshop/dropzone/dropzone.js', false, true);
			JHtml::script('com_redshop/cropper/cropper.js', false, true);
			JHtml::script('com_redshop/lightbox2/lightbox.js', false, true);
			JHtml::script('com_redshop/redshop.media.js', false, true);
		}
		catch (Exception $e)
		{
			return false;
		}

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
	    $imgUrl = JUri::root() . 'media/com_redshop/files/' . $mediaSection . '/' . $sectionId . '/' . $image;
		$imgFile = JPATH_ROOT . 'media/com_redshop/files/' . $mediaSection . '/' . $sectionId . '/' . $image;;

		$file = array();

		JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_redshop/models');
		$media     = JModelLegacy::getInstance('Media', 'RedshopModel');

		$listMedia = $media->all();
		$gallery   = array();

		if (!empty($listMedia))
		{
			foreach ($listMedia as $lk => $lm)
			{
				$tmpFile = REDSHOP_FRONT_IMAGES_RELPATH . $lm->section . '/' . $lm->name;

				if (file_exists($tmpFile))
				{
					$dimension = getimagesize($tmpFile);

					if ($dimension)
					{
						$dimension = $dimension[0] . ' x ' . $dimension[1];
					}

					$tmpImg    = array(
						'id'        => $lm->id,
						'url'       => JUri::root() . 'media/com_redshop/files/' . $lm->section . '/' . $lm->section_id . '/' . $lm->name,
						'name'      => $lm->name,
						'size'      => self::sizeFilter(filesize($tmpFile)),
						'dimension' => $dimension,
						'media'     => $lm->section,
						'mime'      => substr($lm->type, 0, -1),
						'status'    => $lm->published ? '' : '-slash'
					);

					if ($image === $lm->name)
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
			$tmpFile = REDSHOP_FRONT_IMAGES_RELPATH . $lm->section . '/' . $lm->name;

			if (file_exists($tmpFile))
			{
				$dimension = getimagesize($tmpFile);

				if ($dimension)
				{
					$dimension = $dimension[0] . ' x ' . $dimension[1];
				}

				$path = JUri::root() . 'media/com_redshop/files/' . $lm->section . '/' . $lm->section_id . '/' . $lm->name;

				$tmpImg    = array(
					'id'        => $lm->id,
					'url'       => JUri::root() . 'media/com_redshop/files/' . $lm->section . '/' . $lm->section_id . '/' . $lm->name,
					'name'      => $lm->name,
					'size'      => self::sizeFilter(filesize($tmpFile)),
					'dimension' => $dimension,
					'media'     => $lm->section,
					'mime'      => substr($lm->type, 0, -1),
					'status'    => $lm->published ? '' : '-slash'
				);

				if ($selectedImage === $lm->name)
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

	/**
	 * parseSize description
	 * 
	 * @param   string  $size  filesize in php.ini
	 * 
	 * @return  int    filesize in bytes
	 */
	public static function parseSize($size)
	{
		$unit = preg_replace('/[^bkmgtpezy]/i', '', $size);
		$size = preg_replace('/[^0-9\.]/', '', $size);

		if ($unit)
		{
			// Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
			return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
		}
		else
		{
			return round($size);
		}
	}
}
