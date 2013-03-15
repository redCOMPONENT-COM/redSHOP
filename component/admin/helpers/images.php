<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

class RedShopHelperImages extends JObject
{

	/**
	 * Protected! Use the getInstance
	 */
	protected function RedShopHelperImages()
	{
		// Parent Helper Construction
		parent::__construct();
	}

	public static function generateImages($file_path, $dest, $command = 'upload', $type, $width, $height, $proportional)
	{
		$info = getimagesize($file_path);
		$ret  = false;

		switch (strtolower($info['mime']))
		{
			case 'image/png':
			case 'image/jpg':
			case 'image/jpeg':
			case 'image/gif':
				if (JFile::exists($dest) && !empty($dest))
				{
					mt_srand();
					$rand1 = mt_rand(0, mt_getrandmax());
					mt_srand();
					$rand2 = mt_rand(0, mt_getrandmax());
					mt_srand();
					$rand3 = mt_rand(0, mt_getrandmax());
					mt_srand();
					$rand4 = mt_rand(0, mt_getrandmax());

					$dest = $original_path . DS . $data['image_name'] . '-' . $rand1 . $rand2 . $rand3 . $rand4 . '.' . JFile::getExt(strtolower($file['name']));

				}

				// this method should be expanded to be useable for other purposes not just making thumbs
				// but for now it just makes thumbs and proceed to the else part
				if ($command != 'thumb')
				{
					switch ($command)
					{
						case 'copy':
							if (!JFile::copy($file_path, $dest))
							{
								return false;
							}
							break;
						case 'upload':
						default:
							if (!JFile::upload($file_path, $dest))
							{
								return false;
							}
							break;
					}
				}
				else
				{
					// THUMB
					$src           = $file_path;
					$src_path_info = pathinfo($src);
					$dest          = $src_path_info['dirname'] . DS . 'thumb' . DS . $src_path_info['filename'] . '_w' . $width . '_h' . $height . '_dope' . '.' . $src_path_info['extension'];
					$alt_dest      = '';

					if (!JFile::exists($dest))
					{
						$ret = RedShopHelperImages::writeImage($src, $dest, $alt_dest, $width, $height, $proportional);
					}
					else
					{
						$ret = $dest;
					}
				}
				break;
		}

		return $ret;
	}

	public static function writeImage($src, $dest, $alt_dest, $width, $height, $proportional)
	{
		ob_start();
		RedShopHelperImages::resizeImage($src, $width, $height, $proportional, 'browser', false);
		$contents = ob_get_contents();
		ob_end_clean();

		if (JFile::exists($dest) && $alt_dest != '')
		{
			if (JFile::exists($alt_dest))
			{
				return false;
			}
			$dest = $alt_dest;
		}

		if (!JFile::write($dest, $contents))
		{
			return false;
		}

		return $dest;
	}

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
				if (!JFile::exists($path . DS . 'index.html'))
				{
					// avoid 'pass by reference' error in J1.6+
					$content = '<html><body bgcolor="#ffffff"></body></html>';

					JFile::write($path . DS . 'index.html', $content);
				}
			}
		}

		return true;
	}

	public static function resizeImage($file, $width = 0, $height = 0, $proportional = false, $output = 'file', $delete_original = true, $use_linux_commands = false)
	{
		if ($height <= 0 && $width <= 0) return false;

		// Setting defaults and meta
		$info         = getimagesize($file);
		$image        = '';
		$final_width  = 0;
		$final_height = 0;
		list($width_old, $height_old) = $info;

		// Calculating proportionality
		if ($proportional)
		{
			if ($width == 0) $factor = $height / $height_old;
			elseif ($height == 0) $factor = $width / $width_old;
			else $factor = min($width / $width_old, $height / $height_old);

			$final_width  = round($width_old * $factor);
			$final_height = round($height_old * $factor);
		}
		else
		{
			$final_width  = ($width <= 0) ? $width_old : $width;
			$final_height = ($height <= 0) ? $height_old : $height;
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
		$image_resized = imagecreatetruecolor($final_width, $final_height);
		if (($info[2] == IMAGETYPE_GIF) || ($info[2] == IMAGETYPE_PNG))
		{
			$transparency = imagecolortransparent($image);

			if ($transparency >= 0)
			{
				$trnprt_color = imagecolorsforindex($image, $transparency);
				$transparency = imagecolorallocate($image_resized, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);
				imagefill($image_resized, 0, 0, $transparency);
				imagecolortransparent($image_resized, $transparency);
			}
			elseif ($info[2] == IMAGETYPE_PNG)
			{
				imagealphablending($image_resized, false);
				$color = imagecolorallocatealpha($image_resized, 0, 0, 0, 127);
				imagefill($image_resized, 0, 0, $color);
				imagesavealpha($image_resized, true);
			}
		}
		imagecopyresampled($image_resized, $image, 0, 0, 0, 0, $final_width, $final_height, $width_old, $height_old);

		// Taking care of original, if needed
		if ($delete_original)
		{
			if ($use_linux_commands) exec('rm ' . $file);
			else @unlink($file);
		}

		// Preparing a method of providing result
		switch (strtolower($output))
		{
			case 'browser':
				//$mime = image_type_to_mime_type($info[2]);
				//header("Content-type: $mime");
				$output = null;
				break;
			case 'file':
				$output = $destPath;
				break;
			case 'return':
				return $image_resized;
				break;
			default:
				break;
		}

		// Writing image according to type to the output destination
		switch ($info[2])
		{
			case IMAGETYPE_GIF:
				imagegif($image_resized, $output);
				break;
			case IMAGETYPE_JPEG:
				imagejpeg($image_resized, $output);
				break;
			case IMAGETYPE_PNG:
				imagepng($image_resized, $output);
				break;
			default:
				@ImageDestroy($image_resized);
				@ImageDestroy($image);

				return false;
		}

		@ImageDestroy($image_resized);
		@ImageDestroy($image);

		return true;
	}
}
