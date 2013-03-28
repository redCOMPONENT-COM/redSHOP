<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class thumbnail
{
	public function CreatThumb($filetype, $tsrc, $dest, $n_width, $n_height)
	{
		if ($filetype == "gif")
		{
			$im = ImageCreateFromGIF($dest);

			// Original picture width is stored
			$width = ImageSx($im);

			// Original picture height is stored
			$height = ImageSy($im);
			$newimage = imagecreatetruecolor($n_width, $n_height);
			imageCopyResized($newimage, $im, 0, 0, 0, 0, $n_width, $n_height, $width, $height);

			ImageGIF($newimage, $tsrc);
			chmod("$tsrc", 0755);
		}

		if ($filetype == "jpg")
		{
			$im = ImageCreateFromJPEG($dest);

			// Original picture width is stored
			$width = ImageSx($im);

			// Original picture height is stored
			$height = ImageSy($im);
			$newimage = imagecreatetruecolor($n_width, $n_height);
			imageCopyResized($newimage, $im, 0, 0, 0, 0, $n_width, $n_height, $width, $height);
			ImageJpeg($newimage, $tsrc);
			chmod("$tsrc", 0755);
		}

		if ($filetype == "png")
		{
			$im = ImageCreateFromPNG($dest);

			// Original picture width is stored
			$width = ImageSx($im);

			// Original picture height is stored
			$height = ImageSy($im);
			$newimage = imagecreatetruecolor($n_width, $n_height);
			imageCopyResized($newimage, $im, 0, 0, 0, 0, $n_width, $n_height, $width, $height);
			imagepng($newimage, $tsrc);
			chmod("$tsrc", 0755);
		}
	}
}

class thumbnail_images
{
	public $PathImgOld;
	public $PathImgNew;
	public $NewWidth;
	public $NewHeight;
	public $mime;

	public function imagejpeg_new($NewImg, $path_img)
	{
		if ($this->mime == 'image/jpeg' || $this->mime == 'image/pjpeg')
		{
			@imagejpeg($NewImg, $path_img);
		}

		elseif ($this->mime == 'image/gif')
		{
			imagegif($NewImg, $path_img);
		}

		elseif ($this->mime == 'image/png')
		{
			imagepng($NewImg, $path_img);
		}

		else
		{
			return false;
		}

		return true;
	}

	public function imagecreatefromjpeg_new($path_img)
	{
		if ($this->mime == 'image/jpeg' or $this->mime == 'image/pjpeg')
		{
			$OldImg = imagecreatefromjpeg($path_img);
		}

		elseif ($this->mime == 'image/gif')
		{
			$OldImg = imagecreatefromgif($path_img);
		}

		elseif ($this->mime == 'image/png')
		{
			$OldImg = imagecreatefrompng($path_img);
		}

		else
		{
			return false;
		}

		return $OldImg;
	}

	public function create_thumbnail_images()
	{
		$PathImgOld = $this->PathImgOld;
		$PathImgNew = $this->PathImgNew;
		$NewWidth = $this->NewWidth;
		$NewHeight = $this->NewHeight;

		$Oldsize = @getimagesize($PathImgOld);
		$this->mime = $Oldsize['mime'];
		$OldWidth = $Oldsize[0];
		$OldHeight = $Oldsize[1];

		if ($NewHeight == '' and $NewWidth != '')
		{
			$NewHeight = ceil(($OldHeight * $NewWidth) / $OldWidth);
		}
		elseif ($NewWidth == '' and $NewHeight != '')
		{
			$NewWidth = ceil(($OldWidth * $NewHeight) / $OldHeight);
		}
		elseif ($NewHeight == '' and $NewWidth == '')
		{
			return false;
		}

		$OldHeight_castr = ceil(($OldWidth * $NewHeight) / $NewWidth);
		$castr_bottom = ($OldHeight - $OldHeight_castr) / 2;

		$OldWidth_castr = ceil(($OldHeight * $NewWidth) / $NewHeight);
		$castr_right = ($OldWidth - $OldWidth_castr) / 2;

		if ($castr_bottom > 0)
		{
			$OldWidth_castr = $OldWidth;
			$castr_right = 0;
		}
		elseif ($castr_right > 0)
		{
			$OldHeight_castr = $OldHeight;
			$castr_bottom = 0;
		}
		else
		{
			$OldWidth_castr = $OldWidth;
			$OldHeight_castr = $OldHeight;
			$castr_right = 0;
			$castr_bottom = 0;
		}

		$OldImg = $this->imagecreatefromjpeg_new($PathImgOld);

		if ($OldImg)
		{
			$NewImg_castr = imagecreatetruecolor($OldWidth_castr, $OldHeight_castr);

			if ($NewImg_castr)
			{
				imagecopyresampled(
					$NewImg_castr, $OldImg, 0, 0, $castr_right, $castr_bottom,
					$OldWidth_castr, $OldHeight_castr, $OldWidth_castr, $OldHeight_castr
				);

				$NewImg = imagecreatetruecolor($NewWidth, $NewHeight);

				if ($NewImg)
				{
					imagecopyresampled($NewImg, $NewImg_castr, 0, 0, 0, 0, $NewWidth, $NewHeight, $OldWidth_castr, $OldHeight_castr);
					imagedestroy($NewImg_castr);
					imagedestroy($OldImg);

					if (!$this->imagejpeg_new($NewImg, $PathImgNew))
					{
						return false;
					}

					imagedestroy($NewImg);
				}
			}
		}
		else
		{
			return false;
		}

		return true;
	}
}
