<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 *
 * @since       2.0.0.3
 */

defined('_JEXEC') or die;

/**
 * Class Redshop Helper for Media
 *
 * @since  2.0.0.3
 */
class RedshopHelperMedia
{
	/**
	 * @var    array
	 *
	 * @since  2.0.3
	 */
	protected static $medias = array();

	/**
	 * Checks if the file is an image
	 *
	 * @param   string $fileName The filename
	 *
	 * @return  boolean
	 *
	 * @since   2.0.0.3
	 */
	public static function isImage($fileName)
	{
		static $imageTypes = 'xcf|odg|gif|jpg|png|bmp';

		return preg_match("/$imageTypes/i", $fileName);
	}

	/**
	 * Checks if the file is an image
	 *
	 * @param   string $fileName The filename
	 *
	 * @return  boolean
	 *
	 * @since   2.0.0.3
	 */
	public static function getTypeIcon($fileName)
	{
		// Get file extension
		return strtolower(substr($fileName, strrpos($fileName, '.') + 1));
	}

	/**
	 * Print size of an file to Kb or Mb
	 *
	 * @param   integer $size Size of file
	 *
	 * @return  string
	 *
	 * @since   2.0.0.3
	 */
	public static function parseSize($size)
	{
		if ($size < 1024)
		{
			return $size . ' bytes';
		}
		else
		{
			if ($size >= 1024 && $size < 1024 * 1024)
			{
				return sprintf('%01.2f', $size / 1024.0) . ' Kb';
			}

			else
			{
				return sprintf('%01.2f', $size / (1024.0 * 1024)) . ' Mb';
			}
		}
	}

	/**
	 * Resize current resolution of an image to new width and height
	 *
	 * @param   integer $width  New width in pixel
	 * @param   integer $height New height in pixel
	 * @param   integer $target Current resolution
	 *
	 * @return  array
	 *
	 * @since   2.0.0.3
	 */
	public static function imageResize($width, $height, $target)
	{
		/**
		 * takes the larger size of the width and height and applies the
		 * formula accordingly...this is so this script will work
		 * dynamically with any size image
		 */
		if ($width > $height)
		{
			$percentage = ($target / $width);
		}

		else
		{
			$percentage = ($target / $height);
		}

		// Gets the new value and applies the percentage, then rounds the value
		$width  = round($width * $percentage);
		$height = round($height * $percentage);

		return array($width, $height);
	}

	/**
	 * Checks amount of files in a directory
	 *
	 * @param   string $dir Directory need to be checked
	 *
	 * @return  array
	 *
	 * @since   2.0.0.3
	 */
	public static function countFiles($dir)
	{
		$total_file = 0;
		$total_dir  = 0;

		if (is_dir($dir))
		{
			$d = dir($dir);

			while (false !== ($entry = $d->read()))
			{
				if (substr($entry, 0, 1) != '.' && JFile::exists($dir . DIRECTORY_SEPARATOR . $entry)
					&& strpos($entry, '.html') === false && strpos($entry, '.php') === false
				)
				{
					$total_file++;
				}

				if (substr($entry, 0, 1) != '.' && is_dir($dir . DIRECTORY_SEPARATOR . $entry))
				{
					$total_dir++;
				}
			}

			$d->close();
		}

		return array($total_file, $total_dir);
	}

	/**
	 * Rework and standardlize file name.
	 *
	 * @param   string  $fileName File name
	 * @param   integer $id       ID current item
	 *
	 * @return  string
	 *
	 * @since   2.0.0.3
	 */
	public static function cleanFileName($fileName, $id = null)
	{
		$fileExt       = strtolower(JFile::getExt($fileName));
		$fileNameNoExt = JFile::stripExt(basename($fileName));
		$fileNameNoExt = preg_replace("/[&'#]/", '', $fileNameNoExt);
		$fileNameNoExt = JApplicationHelper::stringURLSafe($fileNameNoExt);
		$fileName      = JPath::clean($fileName);
		$segments      = explode(DIRECTORY_SEPARATOR, $fileName);

		if (strlen($fileNameNoExt) === 0)
		{
			$fileNameNoExt = $id;
		}

		$fileNameNoExt = substr($fileNameNoExt, 0, 40);
		$fileName      = time() . '_' . $fileNameNoExt . '.' . $fileExt;

		if (count($segments) > 1)
		{
			$segments[count($segments) - 1] = $fileName;

			return implode(DIRECTORY_SEPARATOR, $segments);
		}
		else
		{
			return $fileName;
		}
	}

	/**
	 * Get redSHOP images live thumbnail path
	 *
	 * @param   string  $imageName    Image Name
	 * @param   string  $dest         Image Destination path
	 * @param   string  $command      Commands like thumb, upload etc...
	 * @param   string  $type         Thumbnail for types like, product, category, subcolor etc...
	 * @param   integer $width        Thumbnail Width
	 * @param   integer $height       Thumbnail Height
	 * @param   integer $proportional Thumbnail Proportional sizing enable / disable.
	 *
	 * @return  string   Thumbnail Live path
	 *
	 * @since  2.0.0.3
	 */
	public static function getImagePath($imageName, $dest, $command = 'upload', $type = 'product', $width = 50,
	                                    $height = 50, $proportional = -1)
	{
		// Trying to set an optional argument
		if ($proportional === -1)
		{
			$proportional = Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING');
		}

		// Set Default Type
		if ($type === '' || !$imageName)
		{
			return REDSHOP_FRONT_IMAGES_ABSPATH . 'noimage.jpg';
		}

		// Set Default Width
		if ((int) $width <= 0)
		{
			$width = 0;
		}

		// Set Default Height
		if ((int) $height <= 0)
		{
			$height = 0;
		}

		if (0 === $width && 0 === $height)
		{
			$width  = 50;
			$height = 50;
		}

		$filePath     = JPATH_SITE . '/components/com_redshop/assets/images/' . $type . '/' . $imageName;
		$physicalPath = self::generateImages($filePath, $dest, $width, $height, $command, $proportional);

		// Can not generate image
		if (!$physicalPath)
		{
			return false;
		}

		// Prevent space in file path
		$physicalPath = str_replace(' ', '%20', $physicalPath);
		$thumbUrl = REDSHOP_FRONT_IMAGES_ABSPATH . $type . '/thumb/' . basename($physicalPath);

		return $thumbUrl;
	}

	/**
	 * Generate thumbnail for image file
	 *
	 * @param   string  $filePath     Path of an image
	 * @param   string  $dest         Destination to generate is a new file path
	 * @param   integer $width        New width in pixel
	 * @param   integer $height       New height in pixel
	 * @param   string  $command      Have 2 options: 'copy' or 'upload'
	 * @param   integer $proportional Try to make image proportionally
	 *
	 * @return  string   Return destination of new thumbnail
	 *
	 * @since  2.0.0.3
	 */
	public static function generateImages($filePath, $dest, $width, $height, $command = 'upload', $proportional = -1)
	{
		// Trying to set an optional argument
		if ($proportional === -1)
		{
			$proportional = Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING');
		}

		if (!JFile::exists($filePath))
		{
			return false;
		}

		$ret = false;

		switch (exif_imagetype($filePath))
		{
			// IMAGETYPE_GIF
			case '1':

				// IMAGETYPE_JPEG
			case '2':

				// IMAGETYPE_PNG
			case '3':

				// This method should be expanded to be useable for other purposes not just making thumbs
				// But for now it just makes thumbs and proceed to the else part
				if ($command != 'thumb')
				{
					switch ($command)
					{
						case 'copy':
							if (!JFile::copy($filePath, $dest))
							{
								return false;
							}
							break;
						case 'upload':
						default:
							if (!JFile::upload($filePath, $dest))
							{
								return false;
							}
							break;
					}
				}
				// Thumb
				else
				{
					$srcPathInfo = pathinfo($filePath);
					$dest        = $srcPathInfo['dirname'] . '/thumb/' . $srcPathInfo['filename'] . '_w'
						. $width . '_h' . $height . '.' . $srcPathInfo['extension'];
					$ret         = $dest;

					if (!JFile::exists($dest))
					{
						$ret = self::writeImage($filePath, $dest, '', $width, $height, $proportional);
					}
				}

				break;
		}

		return $ret;
	}

	/**
	 * Copy an image to new destination
	 *
	 * @param   string  $src          Source path need to copy
	 * @param   string  $dest         Destination path to copy
	 * @param   string  $altDest      If exist alternative path will replace destination path
	 * @param   integer $width        New width in pixel
	 * @param   integer $height       New height in pixel
	 * @param   integer $proportional Try to make image proportionally
	 *
	 * @return  string   Return destination path
	 *
	 * @since  2.0.0.3
	 */
	public static function writeImage($src, $dest, $altDest, $width, $height, $proportional = -1)
	{
		// Trying to set an optional argument
		if ($proportional == -1)
		{
			$proportional = Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING');
		}

		ob_start();
		self::resizeImage($src, $width, $height, $proportional, 'browser', false);
		$contents = ob_get_contents();
		ob_end_clean();

		if (JFile::exists($dest) && $altDest != '')
		{
			if (JFile::exists($altDest))
			{
				return false;
			}

			$dest = $altDest;
		}

		if (!JFile::write($dest, $contents))
		{
			return false;
		}

		return $dest;
	}

	/**
	 * Create a directory
	 *
	 * @param   string $path New directory path
	 *
	 * @return  boolean  Return true or false
	 *
	 * @since  2.0.0.3
	 */
	public static function createDir($path)
	{
		if (!JFolder::exists($path))
		{
			if (!JFolder::create($path))
			{
				return false;
			}
			else
			{
				if (!JFile::exists($path . '/index.html'))
				{
					// Avoid 'pass by reference' error in J1.6+
					$content = '<html><body bgcolor="#ffffff"></body></html>';

					JFile::write($path . '/index.html', $content);
				}
			}
		}

		return true;
	}

	/**
	 * Resize image to new resolution
	 *
	 * @param   string  $file             Current image to resize
	 * @param   integer $width            Width in pixel
	 * @param   integer $height           Height in pixel
	 * @param   integer $proportional     Try to make image proportionally
	 * @param   string  $output           Have 3 options: 'browser','file', 'return'
	 * @param   boolean $deleteOriginal   Default is true, delete originial file after resize
	 * @param   boolean $useLinuxCommands Default is false use @unlink(), if true use 'rm' instead
	 *
	 * @return  mixed    If $output is set by 'return': Return new file path, else return boolean
	 *
	 * @since  2.0.0.3
	 */
	public static function resizeImage($file, $width = 0, $height = 0, $proportional = -1, $output = 'file',
	                                   $deleteOriginal = true, $useLinuxCommands = false)
	{
		// Trying to set an optional argument
		if ($proportional === -1)
		{
			$proportional = Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING');
		}

		if ($height <= 0 && $width <= 0)
		{
			return false;
		}

		// Setting defaults and meta
		$info = getimagesize($file);
		list($widthOld, $heightOld) = $info;
		$horizontalCenter = 0;
		$verticalCenter   = 0;

		// Calculating proportionality resize
		switch ($proportional)
		{
			case '1':
				if ($width === 0)
				{
					$factor = $height / $heightOld;
				}
				elseif ($height === 0)
				{
					$factor = $width / $widthOld;
				}
				else
				{
					$factor = min($width / $widthOld, $height / $heightOld);
				}

				$finalWidth  = round($widthOld * $factor);
				$finalHeight = round($heightOld * $factor);
				break;

			// Resize and cropped
			case '2':
				$width     = ($width <= 0) ? $widthOld : $width;
				$height    = ($height <= 0) ? $heightOld : $height;
				$ratioOrig = $widthOld / $heightOld;

				if ($width / $height > $ratioOrig)
				{
					$finalHeight = $width / $ratioOrig;
					$finalWidth  = $width;
				}
				else
				{
					$finalWidth  = $height * $ratioOrig;
					$finalHeight = $height;
				}

				$xMid             = $finalWidth / 2;
				$yMid             = $finalHeight / 2;
				$horizontalCenter = $xMid - ($width / 2);
				$verticalCenter   = $yMid - ($height / 2);
				break;

			// Not proportionality resize
			case '0':
			default:
				$finalWidth  = ($width <= 0) ? $widthOld : $width;
				$finalHeight = ($height <= 0) ? $heightOld : $height;
		}

		// Loading image to memory according to type
		switch ($info[2])
		{
			case IMAGETYPE_GIF:
				$image = imagecreatefromgif($file);
				break;
			case IMAGETYPE_JPEG:
				$image = imagecreatefromjpeg($file);
				break;
			case IMAGETYPE_PNG:
				$image = imagecreatefrompng($file);
				break;
			default:
				return false;
		}

		// This is the resizing/resampling/transparency-preserving magic
		$imageResized = imagecreatetruecolor($finalWidth, $finalHeight);

		if (($info[2] == IMAGETYPE_GIF) || ($info[2] == IMAGETYPE_PNG))
		{
			$transparency = imagecolortransparent($image);

			if ($info[2] == IMAGETYPE_PNG)
			{
				imagealphablending($imageResized, false);
				$color = imagecolorallocatealpha($imageResized, 0, 0, 0, 127);
				imagefill($imageResized, 0, 0, $color);
				imagesavealpha($imageResized, true);
			}

			elseif ($transparency >= 0)
			{
				$transparentColor = imagecolorsforindex($image, $transparency);

				$transparency = imagecolorallocate($imageResized, $transparentColor['red'], $transparentColor['green'], $transparentColor['blue']);

				imagefill($imageResized, 0, 0, $transparency);
				imagecolortransparent($imageResized, $transparency);
			}
		}

		imagecopyresampled($imageResized, $image, 0, 0, 0, 0, $finalWidth, $finalHeight, $widthOld, $heightOld);

		if ($proportional === 2)
		{
			$thumb = imagecreatetruecolor($width, $height);
			imagecopyresampled($thumb, $imageResized, 0, 0, $horizontalCenter, $verticalCenter, $width, $height, $width, $height);
			$imageResized = $thumb;
		}

		// Taking care of original, if needed
		if ($deleteOriginal)
		{
			if ($useLinuxCommands)
			{
				exec('rm ' . $file);
			}

			else
			{
				JFile::delete($file);
			}
		}

		// Preparing a method of providing result
		switch (strtolower($output))
		{
			case 'browser':
			case 'file':
				$output = null;
				break;
			case 'return':
				return $imageResized;
				break;
			default:
				break;
		}

		// Writing image according to type to the output destination
		switch ($info[2])
		{
			case IMAGETYPE_GIF:
				imagegif($imageResized, $output);
				break;
			case IMAGETYPE_JPEG:
				imagejpeg($imageResized, $output, Redshop::getConfig()->get('IMAGE_QUALITY_OUTPUT'));
				break;
			case IMAGETYPE_PNG:
				$pngQuality = (Redshop::getConfig()->get('IMAGE_QUALITY_OUTPUT') - 100) / 11.111111;
				$pngQuality = round(abs($pngQuality));
				imagepng($imageResized, $output, $pngQuality);
				break;
			default:
				@imagedestroy($imageResized);
				@imagedestroy($image);

				return false;
		}

		@imagedestroy($imageResized);
		@imagedestroy($image);

		return true;
	}

	/**
	 * Create thumbnail from gif/jpg/png image
	 *
	 * @param   string  $fileType Have 3 options: gif, png, jpg
	 * @param   string  $srcImg   Source image
	 * @param   string  $destImg  Destination to create thumbnail
	 * @param   integer $nWidth   Width in pixel
	 * @param   integer $nHeight  Height in pixel
	 *
	 * @return  string   Destination of new thumbnail
	 *
	 * @since  2.0.0.3
	 */
	public function createThumb($fileType, $srcImg, $destImg, $nWidth, $nHeight)
	{
		$newImg = null;

		if ($fileType === "gif")
		{
			$im = imagecreatefromgif($destImg);

			// Original picture width is stored
			$width = imagesx($im);

			// Original picture height is stored
			$height = imagesy($im);
			$newImg = imagecreatetruecolor($nWidth, $nHeight);
			imagecopyresized($newImg, $im, 0, 0, 0, 0, $nWidth, $nHeight, $width, $height);

			imagegif($newImg, $srcImg);
			chmod("$srcImg", 0755);
		}

		if ($fileType === "jpg")
		{
			$im = imagecreatefromjpeg($destImg);

			// Original picture width is stored
			$width = imagesx($im);

			// Original picture height is stored
			$height = imagesy($im);
			$newImg = imagecreatetruecolor($nWidth, $nHeight);
			imagecopyresized($newImg, $im, 0, 0, 0, 0, $nWidth, $nHeight, $width, $height);
			imagejpeg($newImg, $srcImg);
			chmod("$srcImg", 0755);
		}

		if ($fileType === "png")
		{
			$im = imagecreatefrompng($destImg);

			// Original picture width is stored
			$width = imagesx($im);

			// Original picture height is stored
			$height = imagesy($im);
			$newImg = imagecreatetruecolor($nWidth, $nHeight);
			imagecopyresized($newImg, $im, 0, 0, 0, 0, $nWidth, $nHeight, $width, $height);
			imagepng($newImg, $srcImg);
			chmod("$srcImg", 0755);
		}

		return $newImg;
	}

	/**
	 * Method for get additional media images
	 *
	 * @param   int    $sectionId Section Id
	 * @param   string $section   Section name
	 * @param   string $mediaType Media type
	 *
	 * @return  array
	 *
	 * @since   2.0.3
	 */
	public static function getAdditionMediaImage($sectionId = 0, $section = '', $mediaType = 'images')
	{
		$key = $sectionId . '_' . $section . '_' . $mediaType;

		if (!array_key_exists($key, static::$medias))
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select('m.*')
				->from($db->qn('#__redshop_media', 'm'))
				->where($db->qn('m.media_section') . ' = ' . $db->quote($section))
				->where($db->qn('m.media_type') . ' = ' . $db->quote($mediaType))
				->where($db->qn('m.section_id') . ' = ' . (int) $sectionId)
				->where($db->qn('m.published') . ' = 1')
				->order($db->qn('m.ordering') . ',' . $db->qn('m.media_id') . ' ASC');

			switch ($section)
			{
				case 'product':
					$query->select('p.*')
						->leftJoin(
							$db->qn('#__redshop_product', 'p') . ' ON ' . $db->qn('p.product_id') . ' = ' . $db->qn('m.section_id')
						);
					break;

				case 'property':
					$query->select('p.*')
						->leftJoin(
							$db->qn('#__redshop_product_attribute_property', 'p')
							. ' ON ' . $db->qn('p.property_id') . ' = ' . $db->qn('m.section_id')
						);
					break;

				case 'subproperty':
					$query->select('p.*')
						->leftJoin(
							$db->qn('#__redshop_product_subattribute_color', 'p')
							. ' ON ' . $db->qn('p.subattribute_color_id') . ' = ' . $db->qn('m.section_id')
						);
					break;

				case 'manufacturer':
					$query->select('p.*')
						->leftJoin(
							$db->qn('#__redshop_manufacturer', 'p') . ' ON ' . $db->qn('p.manufacturer_id') . ' = ' . $db->qn('m.section_id')
						);
					break;

				default:
					break;
			}

			static::$medias[$key] = $db->setQuery($query)->loadObjectList();
		}

		return static::$medias[$key];
	}

	/**
	 *  Generate thumb image with watermark
	 *
	 * @param   string  $section         Image section
	 * @param   string  $imageName       Image name
	 * @param   string  $thumbWidth      Thumb width
	 * @param   string  $thumbHeight     Thumb height
	 * @param   integer $enableWatermark Enable watermark
	 *
	 * @return  string
	 *
	 * @since   2.0.6
	 */
	public static function watermark($section, $imageName = '', $thumbWidth = '', $thumbHeight = '', $enableWatermark = -1)
	{
		if ($enableWatermark == -1)
		{
			$enableWatermark = Redshop::getConfig()->get('WATERMARK_PRODUCT_IMAGE');
		}

		$pathMainImage = $section . '/' . $imageName;

		try
		{
			// If main image not exists - display noimage
			if (!file_exists(REDSHOP_FRONT_IMAGES_RELPATH . $pathMainImage))
			{
				$pathMainImage = 'noimage.jpg';
				throw new Exception;
			}

			// If watermark not exists or disable - display simple thumb
			if ($enableWatermark < 0
				|| !file_exists(REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . Redshop::getConfig()->get('WATERMARK_IMAGE'))
			)
			{
				throw new Exception;
			}

			// If width and height not set - use with and height original image
			if (((int) $thumbWidth == 0 && (int) $thumbHeight == 0)
				|| ((int) $thumbWidth != 0 && (int) $thumbHeight == 0)
				|| ((int) $thumbWidth == 0 && (int) $thumbHeight != 0)
			)
			{
				list($thumbWidth, $thumbHeight) = getimagesize(REDSHOP_FRONT_IMAGES_RELPATH . $pathMainImage);
			}

			$imageNameWithPrefix = JFile::stripExt($imageName) . '_w' . (int) $thumbWidth . '_h' . (int) $thumbHeight . '_i'
				. JFile::stripExt(basename(Redshop::getConfig()->get('WATERMARK_IMAGE'))) . '.' . JFile::getExt($imageName);

			$destinationFile = REDSHOP_FRONT_IMAGES_RELPATH . $section . '/thumb/' . $imageNameWithPrefix;

			if (JFile::exists($destinationFile))
			{
				return REDSHOP_FRONT_IMAGES_ABSPATH . $section . '/thumb/' . $imageNameWithPrefix;
			}

			$filePath = JPATH_SITE . '/components/com_redshop/assets/images/product/' . Redshop::getConfig()->get('WATERMARK_IMAGE');

			$fileName = self::generateImages($filePath, '', $thumbWidth, $thumbHeight, 'thumb', Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING'));

			$fileInfo  = pathinfo($fileName);
			$watermark = REDSHOP_FRONT_IMAGES_RELPATH . 'product/thumb/' . $fileInfo['basename'];

			ob_start();
			self::resizeImage(
				REDSHOP_FRONT_IMAGES_RELPATH . $pathMainImage,
				$thumbWidth,
				$thumbHeight,
				Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING'),
				'browser',
				false
			);
			$contents = ob_get_contents();
			ob_end_clean();

			if (!JFile::write($destinationFile, $contents))
			{
				return REDSHOP_FRONT_IMAGES_ABSPATH . $section . "/" . $imageName;
			}

			switch (JFile::getExt(Redshop::getConfig()->get('WATERMARK_IMAGE')))
			{
				case 'gif':
					$dest = imagecreatefromjpeg($destinationFile);
					$src  = imagecreatefromgif($watermark);

					list($width, $height) = getimagesize($destinationFile);
					list($watermarkWidth, $watermarkHeight) = getimagesize($watermark);

					imagecopymerge(
						$dest, $src, ($width - $watermarkWidth) >> 1, ($height - $watermarkHeight) >> 1, 0, 0, $watermarkWidth, $watermarkHeight, 50
					);

					imagejpeg($dest, $destinationFile);

					break;

				case 'png':
					$im = imagecreatefrompng($watermark);

					switch (JFile::getExt($destinationFile))
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
							throw new Exception;
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

					throw new Exception;
			}

			return REDSHOP_FRONT_IMAGES_ABSPATH . $section . '/thumb/' . $imageNameWithPrefix;
		}
		catch (Exception $e)
		{
			if ($e->getMessage())
			{
				JFactory::getApplication()->enqueueMessage($e->getMessage(), 'warning');
			}

			if ((int) $thumbWidth == 0 && (int) $thumbHeight == 0)
			{
				$fileName = REDSHOP_FRONT_IMAGES_ABSPATH . $pathMainImage;
			}
			else
			{
				$filePath = JPATH_SITE . '/components/com_redshop/assets/images/' . $pathMainImage;
				$fileName = self::generateImages(
					$filePath, '', $thumbWidth, $thumbHeight, 'thumb', Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
				);
				$fileInfo = pathinfo($fileName);
				$fileName = REDSHOP_FRONT_IMAGES_ABSPATH . $section . '/thumb/' . $fileInfo['basename'];
			}

			return $fileName;
		}
	}
}
