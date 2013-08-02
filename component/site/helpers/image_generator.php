<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class ImageGenerator
{
	public function replaceSpecial($name)
	{
		$filetype = JFile::getExt($name);
		$filename = JFile::stripExt($name);
		$value = preg_replace("/[&'#]/", "", $filename);
		$value = JApplication::stringURLSafe($value) . '.' . $filetype;

		return $value;
	}

	public function originalToResized($mType, $idIncrement, $fileName, $width, $height, $watermark = 0)
	{
		if ($width <= 0 && $height <= 0)
		{
			$width = 800;
			$height = 800;
		}
		elseif ($width <= 0 && $height > 0)
		{
			$width = 100;
		}
		elseif($width > 0 && $height <= 0)
		{
			$height = 100;
		}

		$quality = IMAGE_QUALITY_OUTPUT;
		$crop = USE_IMAGE_SIZE_SWAPPING;
		$wmPosition = WATERMARK_POSITION;
		$wmTrans = 100;
		$wmFilename = WATERMARK_IMAGE;
		$folderName = $this->getFolderName($idIncrement);
		$this->makeFolder(REDSHOP_FRONT_IMAGES_RELPATH . '/thumb/' . $mType . '/' . $folderName);
		$fullFileName = $this->makeFileName($fileName, $width, $height, $quality, $crop, $watermark, $wmPosition, $wmTrans, $wmFilename);

		$sourceFile = REDSHOP_FRONT_IMAGES_RELPATH . '/' . $mType . '/' . $fileName;
		$destFile = REDSHOP_FRONT_IMAGES_RELPATH . '/thumb/' . $mType . '/' . $folderName . '/' . $fullFileName;

		if (!JFile::exists($sourceFile))
		{
			$this->raiseError(JText::sprintf('COM_REDSHOP_FILE_DOES_NOT_EXIST', $sourceFile));

			return true;
		}

		if (!$this->makeImage($sourceFile, $destFile, $width, $height, $quality, $crop, $watermark, $wmFilename, $wmPosition, $wmTrans))
		{
			return false;
		}

		$imgSize = getimagesize($destFile);

		$fileArray = array();
		$fileArray['fullFileName'] = $fullFileName;
		$fileArray['folderName'] = $folderName;
		$fileArray['width'] = $imgSize[0];
		$fileArray['height'] = $imgSize[1];

		return $fileArray;
	}

	public function getIncrementFromFilename($fileName)
	{
		preg_match_all('/-[0-9]+/', $fileName, $matches);
		$last = array_pop($matches[0]);
		$increment = str_replace('-', '', $last);

		return $increment;
	}

	public function getFolderName($folderRef)
	{
		$start = floor(($folderRef / 100) - 0.001) * 100;
		$end = ceil($folderRef / 100) * 100;
		$folderName = (($start) + 1) . '-' . $end;

		return $folderName;
	}

	public function makeFolder($folderPath)
	{
		jimport('joomla.filesystem.folder');

		if (!JFolder::exists($folderPath))
		{
			if (!JFolder::create($folderPath, 0755))
			{
				JError::raise(2, 500, $folderPath . ' ' . JText::_('COM_REDSHOP_FOLDER_CREATE_ERROR'));

				return false;
			}

			if (!JFile::exists($folderPath . '/index.html'))
			{
				$content = '<html><body bgcolor="#ffffff"></body></html>';
				JFile::write($folderPath . '/index.html', $content);
			}
		}
	}

	public function makeFileName($fileName, $width, $height, $quality, $crop, $watermark, $wmPosition, $wmTrans, $wmFilename)
	{
		jimport('joomla.filesystem.file');
		$fileNameExt = JFile::getExt($fileName);
		$fileNameNoExt = JFile::stripExt($fileName);

		$fullFileName = $fileNameNoExt . '-' . $width . '-' . $height . '-' . $quality;

		if ($crop == 1)
		{
			$fullFileName .= '-c';
		}

		if ($watermark == 1 && strlen($wmFilename) > 1)
		{
			$fullFileName .= '-wm' . '-' . $wmPosition . '-' . $wmTrans;
			$fullFileName .= '-' . preg_replace('/[^A-Za-z0-9]/', '', $wmFilename);
		}

		$fullFileName .= '.' . $fileNameExt;

		return $fullFileName;
	}

	public function addIncrement($file, $increment)
	{
		$fileNameExt = JFile::getExt($file);
		$fileNameNoExt = JFile::stripExt($file);

		return $fileNameNoExt . '-' . $increment . '.' . $fileNameExt;
	}

	public function checkUniqueName($folderPath, $file)
	{
		if (JFile::exists($folderPath . '/' . $file))
		{
			$increment = $this->getIncrementFromFilename($file);
			$fileNameExt = JFile::getExt($file);
			$fileNameNoExt = JFile::stripExt($file);
			$fileNameNoIncrement = substr($fileNameNoExt, 0, strrpos($fileNameNoExt, '-'));

			$i = 1;

			while (JFile::exists($folderPath . '/' . $fileNameNoIncrement . $i . '-' . $increment . '.' . $fileNameExt))
			{
				$i++;
			}

			$file = $fileNameNoIncrement . $i . '-' . $increment . '.' . $fileNameExt;
		}

		return $file;
	}


	public function makeImage($sourceFile, $destFile, $width, $height, $quality, $crop = 0, $watermark = 0, $wmFilename = '', $wmPosition = '', $wmTrans = 100)
	{
		if (!extension_loaded('gd') && !function_exists('gd_info'))
		{
			$this->raiseError(JText::_('COM_REDSHOP_CHECK_GD_LIBRARY'));

			return false;
		}

		if ($width == 0 || $height == 0)
		{
			$this->raiseError(JText::_('COM_REDSHOP_IMAGE_NOT_ZERO'));

			return false;
		}

		jimport('joomla.filesystem.file');

		require_once JPATH_SITE . '/components/com_redshop/helpers/wideimage/WideImage.php';

		if (!JFile::exists($destFile))
		{
			$ext = JFile::getExt($sourceFile);
			$imageinfo = getimagesize($sourceFile);
			$image = WideImage::load($sourceFile);

			if ($watermark == 0 && $crop == 0)
			{
				if ($imageinfo[0] <= $width && $imageinfo[1] <= $height)
				{
					if (!JFile::copy($sourceFile, $destFile))
					{
						$this->raiseError($sourceFile . ' -> ' . $destFile . ' ' . JText::_('COM_REDSHOP_ERROR_MOVING_FILE'));

						return false;
					}

					return true;
				}
			}

			if ($crop == 1 && $imageinfo[0] > $width && $imageinfo[1] > $height)
			{
				$image = $image->resize((int) $width, (int) $height, 'outside')->crop('center', 'middle', (int) $width, (int) $height);
			}
			else
			{
				$image = $image->resize((int) $width, (int) $height, 'inside', 'down');
			}

			if ($watermark == 1)
			{
				$positionArray = explode('_', $wmPosition);

				if (strlen($wmFilename) > 0)
				{
					$overlay = WideImage::load(REDSHOP_FRONT_IMAGES_RELPATH . '/product/' . $wmFilename);
					$image = $image->merge($overlay, $positionArray[0], $positionArray[1], $wmTrans);
				}
			}

			if (preg_match("/jp/i", $ext))
			{
				$image->saveToFile($destFile, $quality);
			}
			else
			{
				$image->saveToFile($destFile);
			}

			return true;
		}

		return true;
	}

	public function deleteImage($fileName, $mType = 'product', $idIncrement = 0, $deleteOrig = 1)
	{
		$folderName = $this->getFolderName($idIncrement);

		if ($deleteOrig)
		{
			$path = REDSHOP_FRONT_IMAGES_RELPATH . '/' . $mType . '/' . $fileName;
			if (JFile::exists($path))
				JFile::delete($path);
		}

		$OrigPattern = JFile::stripExt($fileName);
		$OrigPatternLength = strlen($OrigPattern);

		$resizedFolder = REDSHOP_FRONT_IMAGES_RELPATH . '/thumb/' . $mType . '/' . $folderName;

		if (JFolder::exists($resizedFolder))
		{
			$filesArray = JFolder::files($resizedFolder);

			for ($i = 0; $i < count($filesArray); $i++)
			{
				if (substr($filesArray[$i], 0, $OrigPatternLength) == $OrigPattern)
				{
					JFile::delete($resizedFolder . '/' . $filesArray[$i]);
				}
			}
		}
	}

	public function raiseError($message)
	{
		JError::raise(2, 500, $message);
	}
}
