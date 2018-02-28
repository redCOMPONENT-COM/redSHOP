<?php
/**
 * @package     Redshop.Library
 * @subpackage  Entity
 *
 * @copyright   Copyright (C) 2012 - 2017 redCOMPONENT.com. All rights reserved.
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
 * @since       __DEPLOY_VERSION__
 */
class RedshopEntityMediaImage extends RedshopEntityMedia
{
	/**
	 * Get the associated table
	 *
	 * @param   string $name Main name of the Table. Example: Article for ContentTableArticle
	 *
	 * @return  RedshopTable
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
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function generateThumb($width, $height, $crop = false, $force = false)
	{
		$result = array('rel' => '', 'abs' => '');

		if (!$this->hasId())
		{
			return $result;
		}

		$destinationFile  = JFile::stripExt(basename($this->get('media_name')));
		$destinationFile .= '_w' . $width . '_h' . $height;
		$destinationFile .= '.' . JFile::getExt($this->get('media_name'));

		$result = array(
			'rel' => JPath::clean(
				REDSHOP_MEDIA_IMAGE_RELPATH . $this->get('media_section')
				. '/' . $this->get('section_id') . '/thumb/' . $destinationFile
			),
			'abs' => REDSHOP_MEDIA_IMAGE_ABSPATH . $this->get('media_section')
				. '/' . $this->get('section_id') . '/thumb/' . $destinationFile
		);

		if (JFile::exists($result['rel']) && $force === false)
		{
			return $result;
		}

		$originalMemoryLimit = ini_get('memory_limit');
		ini_set('memory_limit', '1024M');

		$sourceFile = $this->getImagePath();

		$data      = file_get_contents($sourceFile);
		$resource  = imagecreatefromstring($data);
		$imagine   = new Imagine;
		$image     = new Image($resource, new RGB, $imagine->getMetadataReader()->readFile($sourceFile));
		$box       = new Box($width, $height);
		$mode      = $crop ? ImageInterface::THUMBNAIL_OUTBOUND : ImageInterface::THUMBNAIL_INSET;
		$thumbnail = $image->thumbnail($box, $mode);

		$thumbnail->save($result['rel']);

		unset($thumbnail);
		unset($image);
		unset($imagine);

		$factory   = new OptimizerFactory;
		$optimizer = $factory->get();
		$optimizer->optimize($result['rel']);

		// Memory limit back to normal
		if ($originalMemoryLimit != '-1')
		{
			ini_set('memory_limit', $originalMemoryLimit);
		}

		return $result;
	}
}
