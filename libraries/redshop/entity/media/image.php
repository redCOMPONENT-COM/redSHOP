<?php
/**
 * @package     Redshop.Library
 * @subpackage  Entity
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

use Imagine\Image\Palette\RGB;
use Imagine\Gd\Image;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use ImageOptimizer\OptimizerFactory;

/**
 * Media image entity
 *
 * @package     Redshop.Library
 * @subpackage  Entity
 * @since       2.1.0
 */
class RedshopEntityMediaImage extends RedshopEntityMedia
{
	/**
	 * Get the associated table
	 *
	 * @param   string  $name  Main name of the Table. Example: Article for ContentTableArticle
	 *
	 * @return  RedshopTable
	 * @throws  Exception
	 */
	public function getTable($name = null)
	{
		return RedshopTable::getAdminInstance('Media', array(), 'com_redshop');
	}

	/**
	 * Method get image path
	 *
	 * @return string
	 */
	public function getImagePath()
	{
		if (!$this->hasId())
		{
			return '';
		}

		return JPath::clean(
			REDSHOP_MEDIA_IMAGE_RELPATH . $this->get('media_section')
			. '/' . $this->get('section_id') . '/' . $this->get('media_name')
		);
	}

	/**
	 * Method get image path
	 *
	 * @return string
	 */
	public function getAbsImagePath()
	{
		if (!$this->hasId())
		{
			return '';
		}

		return REDSHOP_MEDIA_IMAGE_ABSPATH . $this->get('media_section')
			. '/' . $this->get('section_id') . '/' . $this->get('media_name');
	}

	/**
	 * Method for generate thumbnail
	 *
	 * @param   integer  $width   Width of thumbnail
	 * @param   integer  $height  Height of thumbnail
	 * @param   boolean  $crop    Is crop image or not
	 * @param   boolean  $force   Force create image.
	 *
	 * @return  array             List of relative and absolute path
	 * @throws  Exception
	 *
	 * @since   2.1.0
	 */
	public function generateThumb($width, $height, $crop = false, $force = false)
	{
		$result = array('rel' => '', 'abs' => '');

		// Check if this is correct media image
		if (!$this->hasId())
		{
			return $result;
		}

		// Get original file path
		$sourceFile = $this->getImagePath();

		if (empty($sourceFile) || !JFile::exists($sourceFile))
		{
			return $result;
		}

		$destinationFile  = JFile::stripExt(basename($this->get('media_name')));
		$destinationFile .= '_w' . $width . '_h' . $height;
		$destinationFile .= '.' . JFile::getExt($this->get('media_name'));

		// Create thumb folder if not exist
		$thumbPath = REDSHOP_MEDIA_IMAGE_RELPATH . $this->get('media_section')
			. '/' . $this->get('section_id') . '/thumb/';
		\Redshop\Helper\Media::createFolder($thumbPath);

		$result = array(
			'rel' => JPath::clean(
				$thumbPath . $destinationFile
			),
			'abs' => REDSHOP_MEDIA_IMAGE_ABSPATH . $this->get('media_section')
				. '/' . $this->get('section_id') . '/thumb/' . $destinationFile
		);

		if ($force === false && JFile::exists($result['rel']))
		{
			return $result;
		}

		$originalMemoryLimit = ini_get('memory_limit');
		ini_set('memory_limit', '1024M');

		$data      = file_get_contents($sourceFile);
		$resource  = imagecreatefromstring($data);
		$imagine   = new Imagine;
		$image     = new Image($resource, new RGB, $imagine->getMetadataReader()->readFile($sourceFile));
		$box       = new Box($width, $height);
		$mode      = $crop ? ImageInterface::THUMBNAIL_OUTBOUND : ImageInterface::THUMBNAIL_INSET;
		$thumbnail = $image->thumbnail($box, $mode);

		$thumbnail->save($result['rel']);

		unset($thumbnail, $image, $imagine);

		$factory   = new OptimizerFactory;
		$optimizer = $factory->get();
		$optimizer->optimize($result['rel']);

		// Memory limit back to normal
		ini_set('memory_limit', $originalMemoryLimit);

		return $result;
	}
}
