<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 *
 * @deprecated  2.0.0.3  Use RedshopHelperMedia instead
 */

defined('_JEXEC') or die;

/**
 * Class Helper for thumbnail
 *
 * @deprecated  2.0.0.3  Use RedshopHelperMedia instead
 */
class thumbnail
{
	/**
	 * Create thumbnail from gif/jpg/png image
	 *
	 * @param   string   $filetype  Have 3 options: gif, png, jpg
	 * @param   string   $tsrc      Source image
	 * @param   string   $dest      Destination to create thumbnail
	 * @param   integer  $nWidth    Width in pixel
	 * @param   integer  $nHeight   Height in pixel
	 *
	 * @return  string   Destination of new thumbnail
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperMedia::createThumb() instead
	 */
	public function createThumb($filetype, $tsrc, $dest, $nWidth, $nHeight)
	{
		return RedshopHelperMedia::createThumb($filetype, $tsrc, $dest, $nWidth, $nHeight);
	}
}

/**
 * Class Helper for thumbnail images
 *
 * @deprecated  2.0.0.3
 */
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
