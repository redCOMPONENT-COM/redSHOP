<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

/**
 * Img2Thumb class
 *
 */
class Img2Thumb
{
	public $bg_red;

	public $bg_green;

	public $bg_blue;

	public $maxSize;

	/**
	 * @var string Filename for the thumbnail
	 */
	public $fileout;

	public function Img2Thumb($filename, $newxsize = 60, $newysize = 60, $fileout = '',
	                   $thumbMaxSize = 0, $bgred = 0, $bggreen = 0, $bgblue = 0, $swap)
	{
		// New modification - checks color int to be sure within range
		if ($thumbMaxSize)
		{
			$this->maxSize = true;
		}
		else
		{
			$this->maxSize = false;
		}

		if ($bgred >= 0 || $bgred <= 255)
		{
			$this->bg_red = $bgred;
		}
		else
		{
			$this->bg_red = 0;
		}

		if ($bggreen >= 0 || $bggreen <= 255)
		{
			$this->bg_green = $bggreen;
		}
		else
		{
			$this->bg_green = 0;
		}

		if ($bgblue >= 0 || $bgblue <= 255)
		{
			$this->bg_blue = $bgblue;
		}
		else
		{
			$this->bg_blue = 0;
		}

		$this->NewImgCreate($filename, $newxsize, $newysize, $fileout, $swap);
	}

	/**
	 *    private function - do not call
	 */
	public function NewImgCreate($filename, $newxsize, $newysize, $fileout, $swap)
	{
		$type = $this->GetImgType($filename);

		$pathinfo = pathinfo($fileout);

		if (empty($pathinfo['extension']))
		{
			$fileout .= '.' . $type;
		}

		$this->fileout = $fileout;


		switch ($type)
		{
			case "gif":
				/*
				 * Unfortunately this function does not work on windows
				 * via the precompiled php installation :(
				 * it should work on all other systems however.
				*/
				if (function_exists("imagecreatefromgif"))
				{
					$orig_img = imagecreatefromgif($filename);
					break;
				}
				else
				{
					echo 'Sorry, this server doesn\'t support <b>imagecreatefromgif()</b>';
					exit;
					break;
				}

			case "jpg":
			case "jpeg":
				$orig_img = imagecreatefromjpeg($filename);
				break;
			case "png":
				$orig_img = imagecreatefrompng($filename);
				break;
		}

		$new_img = $this->NewImgResize($orig_img, $newxsize, $newysize, $filename, $swap);

		if (!empty($fileout))
		{
			$this->NewImgSave($new_img, $fileout, $type);
		}
		else
		{
			$this->NewImgShow($new_img, $type);
		}

		ImageDestroy($new_img);
		ImageDestroy($orig_img);
	}

	/**
	 * Private function - do not call
	 * Includes function ImageCreateTrueColor and ImageCopyResampled which are available only under GD 2.0.1 or higher !
	 */
	public function NewImgResize($orig_img, $newxsize, $newysize, $filename, $swap)
	{
		/*
		 * getimagesize returns array
		 * [0] = width in pixels
		 * [1] = height in pixels
		 * [2] = type
		 * [3] = img tag "width=xx height=xx" values
		 */

		$orig_size = getimagesize($filename);

		$maxX = $newxsize;
		$maxY = $newysize;

		if ($swap == 1)
		{
			if ($newysize == 50)
			{
				if ($orig_size[0] < $orig_size[1])
				{
					$newysize = $newxsize / ($orig_size[0] / $orig_size[1]);
					$adjustX  = 0;
					$adjustY  = ($maxY - $newysize) / 2;
				}
				else
				{
					$newysize = $newxsize / ($orig_size[0] / $orig_size[1]);
					$adjustX  = 0;
					$adjustY  = ($maxY - $newysize) / 2;
				}
			}
			else
			{
				if ($orig_size[0] < $orig_size[1] && $newxsize <= $newysize)
				{
					$newxsize = $newysize * ($orig_size[0] / $orig_size[1]);
					$adjustX  = ($maxX - $newxsize) / 2;
					$adjustY  = 0;
				}
				elseif ($newxsize != 0 && $newysize != 0)
				{
					$adjustX = $newxsize;
					$adjustY = $newysize;

				}
				else
				{
					$tmp      = $newxsize;
					$newxsize = $newysize;
					$newysize = $tmp;
					$newysize = $newxsize / ($orig_size[0] / $orig_size[1]);
					$adjustX  = 0;
					$adjustY  = ($maxY - $newysize) / 2;
				}
			}
		}
		else
		{
			if ($newxsize != 0 && $newysize != 0)
			{
				$adjustX = $newxsize;
				$adjustY = $newysize;
			}
			elseif ($orig_size[0] < $orig_size[1])
			{
				$newxsize = $newysize * ($orig_size[0] / $orig_size[1]);
				$adjustX  = ($maxX - $newxsize) / 2;
				$adjustY  = 0;
			}
			else
			{
				$newysize = $newxsize / ($orig_size[0] / $orig_size[1]);
				$adjustX  = 0;
				$adjustY  = ($maxY - $newysize) / 2;
			}
		}

		/* Original code removed to allow for maxSize thumbnails
		$im_out = ImageCreateTrueColor($newxsize,$newysize);
		ImageCopyResampled($im_out, $orig_img, 0, 0, 0, 0,
			$newxsize, $newysize,$orig_size[0], $orig_size[1]);
		*/

		// New modification - creates new image at maxSize
		if ($this->maxSize)
		{
			if (function_exists("imagecreatetruecolor"))
			{
				$im_out = imagecreatetruecolor($maxX, $maxY);
			}
			else
			{
				$im_out = imagecreate($maxX, $maxY);
			}

			// Need to image fill just in case image is transparent, don't always want black background
			$bgfill = imagecolorallocate($im_out, $this->bg_red, $this->bg_green, $this->bg_blue);

			if (function_exists("imageAntiAlias"))
			{
				imageAntiAlias($im_out, true);
			}

			imagealphablending($im_out, false);

			if (function_exists("imagesavealpha"))
			{
				imagesavealpha($im_out, true);
			}

			if (function_exists("imagecolorallocatealpha"))
			{
				$transparent = imagecolorallocatealpha($im_out, 255, 255, 255, 127);
			}

			if (function_exists("imagecopyresampled"))
			{
				ImageCopyResampled($im_out, $orig_img, $adjustX, $adjustY, 0, 0, $newxsize, $newysize, $orig_size[0], $orig_size[1]);
			}
			else
			{
				ImageCopyResized($im_out, $orig_img, $adjustX, $adjustY, 0, 0, $newxsize, $newysize, $orig_size[0], $orig_size[1]);
			}
		}
		else
		{
			if (function_exists("imagecreatetruecolor"))
			{
				$im_out = ImageCreateTrueColor($newxsize, $newysize);
			}
			else
			{
				$im_out = imagecreate($newxsize, $newysize);
			}

			if (function_exists("imageAntiAlias"))
			{
				imageAntiAlias($im_out, true);
			}

			imagealphablending($im_out, false);

			if (function_exists("imagesavealpha"))
			{
				imagesavealpha($im_out, true);
			}

			if (function_exists("imagecolorallocatealpha"))
			{
				$transparent = imagecolorallocatealpha($im_out, 255, 255, 255, 127);
			}

			if (function_exists("imagecopyresampled"))
			{
				ImageCopyResampled($im_out, $orig_img, 0, 0, 0, 0, $newxsize, $newysize, $orig_size[0], $orig_size[1]);
			}
			else
			{
				ImageCopyResized($im_out, $orig_img, 0, 0, 0, 0, $newxsize, $newysize, $orig_size[0], $orig_size[1]);
			}
		}

		return $im_out;
	}

	/**
	 *  Private function - do not call
	 *
	 * @param   $new_img
	 * @param   $fileout
	 * @param   $type
	 *
	 * @return bool
	 */
	public function NewImgSave($new_img, $fileout, $type)
	{
		if (!@is_dir(dirname($fileout)))
		{
			@mkdir(dirname($fileout));
		}

		/**
		 * quality is optional, and ranges from 0 (worst quality, smaller file) to 100 (best quality, biggest file).
		 * The default is the default IJG quality value (about 75).
		 *
		 * For gif : no compression level
		 */
		$image_quality = (IMAGE_QUALITY_OUTPUT != "") ? IMAGE_QUALITY_OUTPUT : 70;

		/**
		 * Compression level: from 0 (no compression) to 9.
		 */
		$image_quality_png = round($image_quality / 10);
		$image_quality_png = $image_quality_png >= 10 ? 9 : $image_quality_png;

		switch ($type)
		{
			case "gif":
				if (!function_exists("imagegif"))
				{
					if (strtolower(substr($fileout, strlen($fileout) - 4, 4)) != ".gif")
					{
						$fileout .= ".png";
					}

					return imagepng($new_img, $fileout, $image_quality_png);

				}
				else
				{
					if (strtolower(substr($fileout, strlen($fileout) - 4, 4)) != ".gif")
					{
						$fileout .= '.gif';
					}

					return imagegif($new_img, $fileout);

				}
				break;
			case "jpg":

				if (strtolower(substr($fileout, strlen($fileout) - 4, 4)) != ".jpg")
				{
					$fileout .= ".jpg";
				}

				return imagejpeg($new_img, $fileout, $image_quality);
				break;
			case "jpeg":


				if (strtolower(substr($fileout, strlen($fileout) - 5, 5)) != ".jpeg")
				{
					$fileout .= ".jpeg";
				}

				return imagejpeg($new_img, $fileout, $image_quality);
				break;
			case "png":
				if (strtolower(substr($fileout, strlen($fileout) - 4, 4)) != ".png")
				{
					$fileout .= ".png";
				}

				return imagepng($new_img, $fileout, $image_quality_png);
				break;
		}
	}

	/**
	 * Private function - do not call
	 */
	public function NewImgShow($new_img, $type)
	{
		switch ($type)
		{
			case "gif":
				if (function_exists("imagegif"))
				{
					header("Content-type: image/gif");

					return imagegif($new_img);
					break;
				}
				else
					$this->NewImgShow($new_img, "jpg");
			case "jpg":
			case "jpeg":
				header("Content-type: image/jpeg");

				return imagejpeg($new_img);
				break;
			case "png":
				header("Content-type: image/png");

				return imagepng($new_img);
				break;
		}
	}

	/**
	 *    private function - do not call
	 *
	 *   1 = GIF, 2 = JPG, 3 = PNG, 4 = SWF,
	 *   5 = PSD, 6 = BMP,
	 *   7 = TIFF(intel byte order),
	 *   8 = TIFF(motorola byte order),
	 *   9 = JPC, 10 = JP2, 11 = JPX,
	 *   12 = JB2, 13 = SWC, 14 = IFF
	 */
	public function GetImgType($filename)
	{
		$info = getimagesize($filename);

		switch ($info[2])
		{
			case 1:
				return "gif";
				break;
			case 2:
				if (strtolower(substr($filename, strlen($filename) - 5, 5)) == ".jpeg")
					return "jpeg";

				return "jpg";
				break;
			case 3:
				return "png";
				break;
			default:
				return false;
		}
	}
}
