<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Helper;

defined('_JEXEC') or die;

/**
 * Utility helper
 *
 * @since  2.0.7
 */
class Media
{
	/**
	 *  Generate thumb image with watermark
	 *
	 * @param   string   $section          Image section
	 * @param   integer  $sectionId        Section ID
	 * @param   string   $imageName        Image name
	 * @param   integer  $thumbWidth       Thumb width
	 * @param   integer  $thumbHeight      Thumb height
	 * @param   integer  $enableWatermark  Enable watermark
	 *
	 * @return  string
	 * @throws  \Exception
	 *
	 * @since   2.1.0
	 */
	public static function watermark($section, $sectionId = 0, $imageName = '', $thumbWidth = 0, $thumbHeight = 0, $enableWatermark = -1)
	{
		$enableWatermark = $enableWatermark == -1 ? \Redshop::getConfig()->get('WATERMARK_PRODUCT_IMAGE') : $enableWatermark;
		$pathMainImage   = $section . '/' . $sectionId . '/' . $imageName;

		try
		{
			// If main image not exists - display no image
			if (!\JFile::exists(REDSHOP_MEDIA_IMAGE_RELPATH . $pathMainImage))
			{
				$pathMainImage = 'noimage.jpg';

				throw new \Exception;
			}

			// If watermark not exists or disable - display simple thumb
			if ($enableWatermark <= 0 || !\JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . \Redshop::getConfig()->get('WATERMARK_IMAGE')))
			{
				throw new \Exception;
			}

			// If width and height not set - use with and height original image
			if (((int) $thumbWidth == 0 && (int) $thumbHeight == 0)
				|| ((int) $thumbWidth != 0 && (int) $thumbHeight == 0)
				|| ((int) $thumbWidth == 0 && (int) $thumbHeight != 0)
			)
			{
				list($thumbWidth, $thumbHeight) = getimagesize(REDSHOP_MEDIA_IMAGE_RELPATH . $pathMainImage);
			}

			$imageNameWithPrefix = \JFile::stripExt($imageName) . '_w' . (int) $thumbWidth . '_h' . (int) $thumbHeight . '_i'
				. \JFile::stripExt(basename(\Redshop::getConfig()->get('WATERMARK_IMAGE'))) . '.' . \JFile::getExt($imageName);

			$destinationFile = REDSHOP_MEDIA_IMAGE_RELPATH . $section . '/' . $sectionId . '/thumb/' . $imageNameWithPrefix;

			if (\JFile::exists($destinationFile))
			{
				return REDSHOP_MEDIA_IMAGE_ABSPATH . $section . '/' . $sectionId . '/thumb/' . $imageNameWithPrefix;
			}

			$filePath = JPATH_SITE . '/components/com_redshop/assets/images/product/' . \Redshop::getConfig()->get('WATERMARK_IMAGE');
			$fileName = \RedshopHelperMedia::generateImages($filePath, '', $thumbWidth, $thumbHeight, 'thumb');
			$fileInfo = pathinfo($fileName);

			ob_start();
			\RedshopHelperMedia::resizeImage(
				REDSHOP_MEDIA_IMAGE_RELPATH . $pathMainImage,
				$thumbWidth,
				$thumbHeight,
				\Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING'),
				'browser',
				false
			);

			$watermark = REDSHOP_FRONT_IMAGES_RELPATH . 'product/thumb/' . $fileInfo['basename'];

			$contents = ob_get_contents();
			ob_end_clean();

			if (!\JFile::write($destinationFile, $contents))
			{
				return REDSHOP_MEDIA_IMAGE_ABSPATH . '/' . $sectionId . '/' . $section . '/' . $imageName;
			}

			switch (\JFile::getExt(\Redshop::getConfig()->get('WATERMARK_IMAGE')))
			{
				case 'gif':
					$targetFile = imagecreatefromjpeg($destinationFile);
					$sourceFile = imagecreatefromgif($watermark);

					list($width, $height)                   = getimagesize($destinationFile);
					list($watermarkWidth, $watermarkHeight) = getimagesize($watermark);

					imagecopymerge(
						$targetFile,
						$sourceFile,
						($width - $watermarkWidth) >> 1,
						($height - $watermarkHeight) >> 1,
						0,
						0,
						$watermarkWidth,
						$watermarkHeight,
						50
					);

					imagejpeg($targetFile, $destinationFile);
					break;

				case 'png':
					$im = imagecreatefrompng($watermark);

					switch (\JFile::getExt($destinationFile))
					{
						case 'gif':
							$im2 = imagecreatefromgif($destinationFile);
							break;

						case 'jpg':
							$im2 = imagecreatefromjpeg($destinationFile);
							break;

						case 'png':
							$im2 = imagecreatefrompng($destinationFile);
							break;

						default:
							throw new \Exception;
					}

					imagecopy(
						$im2,
						$im,
						(imagesx($im2) / 2) - (imagesx($im) / 2), (imagesy($im2) / 2) - (imagesy($im) / 2),
						0,
						0,
						imagesx($im),
						imagesy($im)
					);

					$waterless = imagesx($im2) - imagesx($im);
					$rest      = ceil($waterless / imagesx($im) / 2);

					for ($n = 1; $n <= $rest; $n++)
					{
						imagecopy(
							$im2, $im, ((imagesx($im2) / 2) - (imagesx($im) / 2)) - (imagesx($im) * $n),
							(imagesy($im2) / 2) - (imagesy($im) / 2), 0, 0, imagesx($im), imagesy($im)
						);

						imagecopy(
							$im2, $im, ((imagesx($im2) / 2) - (imagesx($im) / 2)) + (imagesx($im) * $n),
							(imagesy($im2) / 2) - (imagesy($im) / 2), 0, 0, imagesx($im), imagesy($im)
						);
					}

					imagejpeg($im2, $destinationFile);
					break;

				default:
					throw new \Exception;
			}

			return REDSHOP_MEDIA_IMAGE_ABSPATH . '/' . $section . '/' . $sectionId . '/thumb/' . $imageNameWithPrefix;
		}
		catch (\Exception $e)
		{
			if ($e->getMessage())
			{
				\JFactory::getApplication()->enqueueMessage($e->getMessage(), 'warning');
			}

			if ((int) $thumbWidth == 0 && (int) $thumbHeight == 0)
			{
				$fileName = REDSHOP_MEDIA_IMAGE_ABSPATH . '/' . $pathMainImage;
			}
			else
			{
				$filePath = REDSHOP_MEDIA_IMAGE_RELPATH . $pathMainImage;
				$fileName = \RedshopHelperMedia::generateImages($filePath, '', $thumbWidth, $thumbHeight, 'thumb');
				$fileInfo = pathinfo($fileName);
				$fileName = REDSHOP_MEDIA_IMAGE_ABSPATH . $section . '/' . $sectionId . '/thumb/' . $fileInfo['basename'];
			}

			return $fileName;
		}
	}

	/**
	 * Method for create / re-create folder and add index.html inside.
	 *
	 * @param   string   $folder    Folder path
	 * @param   boolean  $reCreate  True for delete if exist and re-create
	 *
	 * @return  boolean
	 *
	 * @since   2.1.0
	 */
	public static function createFolder($folder, $reCreate = false)
	{
		if (empty($folder))
		{
			return false;
		}

		$folder = \JPath::clean($folder);

		if (\JFolder::exists($folder))
		{
			if ($reCreate === false)
			{
				return true;
			}

			\JFolder::delete($folder);
		}

		if (!\JFolder::create($folder))
		{
			return false;
		}

		return \JFile::copy(JPATH_REDSHOP_LIBRARY . '/index.html', $folder . '/index.html');
	}
}
