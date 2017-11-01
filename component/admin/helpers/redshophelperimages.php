<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 *
 * @deprecated  2.0.0.3  Use RedshopHelperMedia instead
 */

defined('_JEXEC') or die;

/**
 * Class RedShopHelperImages
 *
 * @deprecated  2.0.0.3  Use RedshopHelperMedia instead
 */
class RedShopHelperImages
{
	/**
	 * Function cleanFileName.
	 *
	 * @param   string  $fileName  File name
	 * @param   int     $id        ID current item
	 *
	 * @return  string
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperMedia::cleanFileName() instead
	 */
	public static function cleanFileName($fileName, $id = null)
	{
		return RedshopHelperMedia::cleanFileName($fileName, $id);
	}

	/**
	 * Get redSHOP images live thumbnail path
	 *
	 * @param   string   $imageName     Image Name
	 * @param   string   $dest          Image Destination path
	 * @param   string   $command       Commands like thumb, upload etc...
	 * @param   string   $type          Thumbnail for types like, product, category, subcolor etc...
	 * @param   integer  $width         Thumbnail Width
	 * @param   integer  $height        Thumbnail Height
	 * @param   integer  $proportional  Thumbnail Proportional sizing enable / disable.
	 *
	 * @return  string   Thumbnail Live path
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperMedia::getImagePath() instead
	 */
	public static function getImagePath($imageName, $dest, $command = 'upload', $type = 'product', $width = 50,
		$height = 50, $proportional = -1)
	{
		return RedshopHelperMedia::getImagePath($imageName, $dest, $command, $type, $width, $height, $proportional);
	}

	/**
	 * Generate thumbnail for image file
	 *
	 * @param   string   $filePath      Path of an image
	 * @param   string   $dest          Destination to generate is a new file path
	 * @param   integer  $width         New width in pixel
	 * @param   integer  $height        New height in pixel
	 * @param   string   $command       Have 2 options: 'copy' or 'upload'
	 * @param   integer  $proportional  Try to make image proportionally
	 *
	 * @return  string   Return destination of new thumbnail
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperMedia::generateImages() instead
	 */
	public static function generateImages($filePath, $dest, $width, $height, $command = 'upload', $proportional = -1)
	{
		return RedshopHelperMedia::generateImages($filePath, $dest, $width, $height, $command, $proportional);
	}

	/**
	 * Copy an image to new destination
	 *
	 * @param   string   $src           Source path need to copy
	 * @param   string   $dest          Destination path to copy
	 * @param   string   $altDest       If exist alternative path will replace destination path
	 * @param   integer  $width         New width in pixel
	 * @param   integer  $height        New height in pixel
	 * @param   integer  $proportional  Try to make image proportionally
	 *
	 * @return  string   Return destination path
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperMedia::writeImage() instead
	 */
	public static function writeImage($src, $dest, $altDest, $width, $height, $proportional = -1)
	{
		return RedshopHelperMedia::writeImage($src, $dest, $altDest, $width, $height, $proportional);
	}

	/**
	 * Create  a directory
	 *
	 * @param   string  $path  New directory path
	 *
	 * @return  boolean        Return true or false
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperMedia::createDir() instead
	 */
	public static function createDir($path)
	{
		return RedshopHelperMedia::createDir($path);
	}

	/**
	 * Resize image to new resolution
	 *
	 * @param   string   $file              Current image to resize
	 * @param   integer  $width             Width in pixel
	 * @param   integer  $height            Height in pixel
	 * @param   integer  $proportional      Try to make image proportionally
	 * @param   string   $output            Have 3 options: 'browser','file', 'return'
	 * @param   boolean  $deleteOriginal    Default is true, delete originial file after resize
	 * @param   boolean  $useLinuxCommands  Default is false use @unlink(), if true use 'rm' instead
	 *
	 * @return  mixed    If $output is set by 'return': Return new file path, else return boolean
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperMedia::resizeImage() instead
	 */
	public static function resizeImage($file, $width = 0, $height = 0, $proportional = -1, $output = 'file',
		$deleteOriginal = true, $useLinuxCommands = false)
	{
		return RedshopHelperMedia::resizeImage($file, $width, $height, $proportional, $output, $deleteOriginal, $useLinuxCommands);
	}
}
