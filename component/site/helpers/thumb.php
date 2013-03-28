<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

/*** access Joomla's configuration file ***/
$my_path = dirname(__FILE__);

if (file_exists($my_path . "/../../../configuration.php"))
{
	$absolute_path = dirname($my_path . "/../../../configuration.php");
	require_once $my_path . "/../../../configuration.php";
}
else
{
	die("Joomla Configuration File not found!");
}

$absolute_path = realpath($absolute_path);

// Set flag that this is a parent file
define('_JEXEC', 1);

define('JPATH_BASE', $absolute_path);

define('DS', DIRECTORY_SEPARATOR);

require_once JPATH_BASE . '/includes/defines.php';
require_once JPATH_BASE . '/includes/framework.php';

JDEBUG ? $_PROFILER->mark('afterLoad') : null;

define('_VALID_MOS', 1);
define('IMG_WIDTH', 50);

define('BASE_PATH', "../assets/images/");


include JPATH_SITE . '/administrator/components/com_redshop/helpers/redshop.cfg.php';
include "class.img2thumb.php";

$basefilename        = @basename(urldecode($_REQUEST['filename']));
$dir                 = dirname($_REQUEST['filename']);
$filenames[]         = BASE_PATH . $dir . "/" . $basefilename;
$resized_filenames[] = BASE_PATH . $dir . "/thumb/" . $basefilename;

if (isset($_REQUEST['swap']))
{
	$swap = $_REQUEST['swap'];
}
else
{
	$swap = 1;
}

$newxsize = (int) @$_REQUEST['newxsize'] == 0 ? IMG_WIDTH : (int) @$_REQUEST['newxsize'];
$newysize = (int) @$_REQUEST['newysize'] == 0 ? IMG_WIDTH : (int) @$_REQUEST['newysize'];

// Don't allow sizes beyond 2000 pixels
$newxsize = min($newxsize, 2000);


$maxsize = false;
$bgred   = 255;
$bggreen = 255;
$bgblue  = 255;

/* Minimum security */
$file_exists = false;
$i           = 0;

foreach ($filenames as $file)
{
	if (!file_exists($file))
	{
		return;
	}
	if (file_exists($file))
	{
		$file_exists = true;
		$filename    = $file;
		break;
	}
	elseif (file_exists($resized_filenames[$i]))
	{
		$file_exists = true;
		$filename    = $resized_filenames[$i];
		break;
	}
	++$i;
}

$orig_size = getimagesize($filename);
$maxX      = $newxsize;
$maxY      = $newysize;

// Changes as per swaping
if ($swap == 1)
{

	if ($newxsize != 0 && $newysize != 0 && ($newxsize != 50 && $newysize != 50))
	{
		// Check aspect ratio
		$resizeRatio  = $maxY / $maxX;
		$currentRatio = $orig_size[1] / $orig_size[0];

		if ($currentRatio > $resizeRatio)
		{
			$diff    = $orig_size[0] / $orig_size[1];
			$adjustX = $newxsize = round($newysize * $diff);
			$adjustY = $newysize;
		}
		else
		{
			$diff    = $orig_size[1] / $orig_size[0];
			$adjustY = $newysize = round($newxsize * $diff);
			$adjustX = $newxsize;
		}
	}
	else
	{

		if ($newxsize == 50 && $newysize == 50)
		{
			$adjustX = 50;
			$adjustY = 50;

		}
		else
		{
			if ($orig_size[0] < $orig_size[1])
			{
				$adjustX = $newysize;

				if ($newxsize != 50)
				{
					$newxsize = $newysize * ($orig_size[0] / $orig_size[1]);
					$adjustY  = $newysize;
				}

				if ($newysize != 50)
				{
					$adjustY = ($maxX - $newxsize) / 2;
				}
			}
			elseif ($orig_size[0] > $orig_size[1])
			{
				$adjustY = $newxsize;

				if ($newysize != 50)
				{
					$newxsize = $newysize * ($orig_size[0] / $orig_size[1]);
					$adjustX  = $newxsize;
				}

				if ($newxsize != 50)
				{
					$adjustX = ($maxX - $newxsize) / 2;
				}
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

}
else
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
		if ($newxsize != 0 && $newysize != 0)
		{

			$adjustX = $newxsize;
			$adjustY = $newysize;

		}
		elseif ($orig_size[0] < $orig_size[1])
		{
			if ($newxsize != 0)
			{
				$xdiff   = $orig_size[1] - $newxsize;
				$ydiff   = (($xdiff * $orig_size[0]) / $orig_size[1]);
				$adjustX = $newxsize;
				$adjustY = $newysize - $ydiff;
			}
			elseif ($newysize != 0)
			{
				$ydiff   = $orig_size[0] - $newysize;
				$xdiff   = (($ydiff * $orig_size[1]) / $orig_size[0]);
				$adjustX = $newxsize - $xdiff;
				$adjustY = $newysize;
			}
		}
		else
		{
			if ($newxsize != 0)
			{
				$xdiff   = $orig_size[1] - $newxsize;
				$ydiff   = (($xdiff * $orig_size[0]) / $orig_size[1]);
				$adjustX = $newxsize;
				$adjustY = $newysize - $ydiff;
			}
			elseif ($newysize != 0)
			{
				$ydiff   = $orig_size[0] - $newysize;
				$xdiff   = (($ydiff * $orig_size[1]) / $orig_size[0]);
				$adjustX = $newxsize - $xdiff;
				$adjustY = $newysize;
			}
		}
	}
}


$file_exists or die('File does not exist');

$filename2 = $resized_filenames[$i];

$fileinfo = pathinfo($filename);
$file     = str_replace("." . $fileinfo['extension'], "", $fileinfo['basename']);

// In class.img2thumb in the function NewImgShow() the extension .jpg will be added to .gif if imagegif does not exist.

// If the image is a gif, and imagegif() returns false then make the extension ".gif.jpg"

if ($fileinfo['extension'] == "gif")
{
	if (function_exists("imagegif"))
	{
		$ext     = "." . $fileinfo['extension'];
		$noimgif = "";
	}
	else
	{
		$ext     = ".jpg";
		$noimgif = "." . $fileinfo['extension'];
	}
}
else
{
	$ext     = "." . $fileinfo['extension'];
	$noimgif = "";
}

if (file_exists($filename2))
{
	$fileout = $filename2;
}
else
{
	$fileout = dirname($filename2) . '/' . $file . "_" . $adjustX . "x" . $adjustY . $noimgif . $ext;
}

// Tell the user agent to cache this script/stylesheet for an hour
$age = 3600;
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $age) . ' GMT');
header('Cache-Control: max-age=' . $age . ', must-revalidate');

if (file_exists($fileout))
{

	/* We already have a resized image
	  * So send the file to the browser */

	switch (strtolower($ext))
	{
		case ".gif":
			header("Content-type: image/gif");
			readfile($fileout);
			break;
		case ".jpg":
		case ".jpeg":
			header("Content-type: image/jpeg");
			readfile($fileout);
			break;
		case ".png":
			header("Content-type: image/png");
			readfile($fileout);
			break;
	}
}
else
{
	/* We need to resize the image and Save the new one (all done in the constructor) */
	$neu = new Img2Thumb($filename, $newxsize, $newysize, $fileout, $maxsize, $bgred, $bggreen, $bgblue, $swap);

	/* Send the file to the browser */
	switch (strtolower($ext))
	{
		case ".gif":
			header("Content-type: image/gif");
			readfile($fileout);
			break;
		case ".jpg":
		case ".jpeg":
			header("Content-type: image/jpeg");
			readfile($fileout);
			break;
		case ".png":
			header("Content-type: image/png");
			readfile($fileout);
			break;
	}
}
