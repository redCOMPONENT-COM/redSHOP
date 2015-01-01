<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_magicmagnifyplus
 *
 * @copyright   Copyright (C) 2005 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

if (!in_array('MagicToolboxMakeThumb', get_declared_classes()))
{

	class MagicToolboxMakeThumb
	{
		var $img;
		var $w;
		var $h;
		var $max;
		var $thumb;
		var $type;
		var $info;
		var $data;


		function MagicToolboxMakeThumb($img = null, $w = -1, $h = -1, $thumb = null, $max = 'both')
		{
			if ($img == null) return false;

			if ($w < 0 && $h < 0) return $img;

			clearstatcache();
			if ($thumb !== null && file_exists($thumb)) unlink($thumb);

			$this->img   = $img;
			$this->w     = $w;
			$this->h     = $h;
			$this->max   = $max;
			$this->thumb = $thumb;

			$this->getType();
			if (!$this->type)
			{
				return $img;
			}
			$this->load();
			$this->resize();

			if ($this->thumb == null)
			{
				return $this->data;
			}

			$this->save();
			imagedestroy($this->data);

			clearstatcache();

			return $this->thumb;
		}

		function getType()
		{

			$this->info = getimagesize($this->img);

			/*  1 = GIF, 2 = JPG, 3 = PNG, 4 = SWF, 5 = PSD, 6 = BMP, 7 = TIFF(intel byte order), 8 = TIFF(motorola byte order), 9 = JPC, 10 = JP2, 11 = JPX, 12 = JB2, 13 = SWC, 14 = IFF */

			switch ($this->info[2])
			{
				case 1:
					$this->type = "gif";
					break;
				case 2:
					$this->type = "jpg";
					break;
				case 3:
					$this->type = "png";
					break;
				// GD doesn't support BMP format
				//case 6: $this->type =  "bmp"; break;
				default:
					$this->type = false;
			}

			return $this->type;
		}

		function load()
		{
			switch ($this->type)
			{
				case "gif":
					// unfortunately this function does not work on windows
					// via the precompiled php installation :(
					// it should work on all other systems however.
					if (function_exists("imagecreatefromgif"))
					{
						$this->data = imagecreatefromgif($this->img);
					}
					else
					{
						error_log('Sorry, this server doesn\'t support <b>imagecreatefromgif()</b>');

						return;
					}
					break;
				case "jpg":
					$this->data = imagecreatefromjpeg($this->img);
					break;
				case "png":
					$this->data = imagecreatefrompng($this->img);
					break;
			}
		}

		function resize()
		{

			if (str_replace("%", "", $this->w) != $this->w) $this->w = $this->info[0] * str_replace("%", "", $this->w) / 100;
			if (str_replace("%", "", $this->h) != $this->h) $this->h = $this->info[1] * str_replace("%", "", $this->h) / 100;

			switch ($this->max)
			{
				case 'both':
				case 'all':
					if ($this->info[0] / $this->info[1] < $this->w / $this->h)
					{
						$this->w = ($this->info[0] * $this->h) / $this->info[1];
					}
					else
					{
						$this->h = ($this->info[1] * $this->w) / $this->info[0];
					}
					break;
				case 'w':
				case 'width':
					$this->w = ($this->info[0] * $this->h) / $this->info[1];
					break;
				case 'h':
				case 'height':
					$this->h = ($this->info[1] * $this->w) / $this->info[0];
					break;
			}

			$out = null;
			if (function_exists("imagecreatetruecolor"))
			{
				$out = imagecreatetruecolor($this->w, $this->h);
			}
			else
			{
				$out = imagecreate($this->w, $this->h);
			}

			if (function_exists("imageantialias"))
			{
				imageantialias($out, true);
			}

			imagealphablending($out, false);

			if (function_exists("imagesavealpha"))
			{
				imagesavealpha($out, true);
			}

			if (function_exists("imagecolorallocatealpha"))
			{
				imagecolorallocatealpha($out, 255, 255, 255, 127);
			}

			if (function_exists("imagecopyresampled"))
			{
				imagecopyresampled($out, $this->data, 0, 0, 0, 0, $this->w, $this->h, $this->info[0], $this->info[1]);
			}
			else
			{
				imagecopyresized($out, $this->data, 0, 0, 0, 0, $this->w, $this->h, $this->info[0], $this->info[1]);
			}

			imagedestroy($this->data);
			$this->data = $out;
		}

		function save()
		{
			switch ($this->type)
			{
				case "gif":
					if (!function_exists("imagegif"))
					{
						imagepng($this->data, $this->thumb);
					}
					else
					{
						imagegif($this->data, $this->thumb);
					}
					break;
				case "jpg":
					imagejpeg($this->data, $this->thumb, 100);
					break;
				case "png":
					imagepng($this->data, $this->thumb);
					break;
			}
		}

	}

}
?>
