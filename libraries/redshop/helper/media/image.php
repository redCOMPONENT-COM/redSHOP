<?php
/**
 * @package     Redshop.Library
 * @subpackage  Config
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

/**
 * Redshop Helper for Media Upload Dropzone
 *
 * @package     Redshop.Library
 * @subpackage  Config
 * @since       1.5
 */
class RedshopHelperMediaImage
{
	/**
	 * Dependencies flag variable
	 *
	 * @var requireDependencies
	 **/
	protected $requireDependencies;

	/**
	 * Render Drag n Drop template in site
	 *
	 * @param   string  $id            ID of media input name
	 * @param   string  $type          Type of item want to show gallery
	 * @param   string  $sectionId     Section ID to show
	 * @param   string  $mediaSection  Section type to show
	 * @param   string  $image         URL of featured image
	 *
	 * @return  void
	 */
	public static function render($id, $type, $sectionId, $mediaSection, $image)
	{
		$imgUrl = JRoute::_('/components/com_redshop/assets/images/' . $type . '/' . $image);
		$imgFile = REDSHOP_FRONT_IMAGES_RELPATH . $type . '/' . $image;

		$file = array();

		if (!empty($image) && file_exists($imgFile))
		{
			$file = array(
				'path' => $imgUrl,
				'name' => $image,
				'size' => filesize($imgFile) ? filesize($imgFile) : 0,
				'blob' => 'data: ' . mime_content_type($imgFile) . ';base64,' . base64_encode(file_get_contents($imgFile))
			);
		}

		echo RedshopLayoutHelper::render(
			'html.dropzone',
			array(
				'id'           => $id,
				'type'         => $type,
				'sectionId'    => $sectionId,
				'mediaSection' => $mediaSection,
				'file'         => $file
			)
		);
	}

	/**
	 * Require dependecies from bower.js.
	 * Checking dependencies are existed or not then require them to header
	 *
	 * @return  boolean
	 */
	public static function requireDependencies()
	{
		$doc = new RedshopHelperDocument;

		$doc->addStylesheet(JUri::root() . '/media/com_redshop/css/dropzone/dropzone.css');
		$doc->addStylesheet(JUri::root() . '/media/com_redshop/css/cropper/cropper.css');
		$doc->addStylesheet(JUri::root() . '/media/com_redshop/css/lightbox2/css/lightbox.css');
		$doc->addBottomStylesheet(JUri::root() . '/media/com_redshop/css/media.css');

		$doc->addScript(JUri::root() . '/media/com_redshop/js/dropzone/dropzone.js');
		$doc->addScript(JUri::root() . '/media/com_redshop/js/cropper/cropper.js');
		$doc->addScript(JUri::root() . '/media/com_redshop/js/lightbox2/lightbox.js');
		$doc->addScript(JUri::root() . '/media/com_redshop/js/media.js');

		return true;
	}

	/**
	 * Render gallery pop-up for media
	 *
	 * @param   string  $id            ID of media input name
	 * @param   string  $type          Type of item want to show gallery
	 * @param   string  $sectionId     Section ID to show
	 * @param   string  $mediaSection  Section type to show
	 * @param   string  $image         URL of featured image
	 *
	 * @return  void
	 */
	public static function renderGallery($id, $type, $sectionId, $mediaSection, $image)
	{
		$imgUrl  = JUri::root() . 'components/com_redshop/assets/images/' . $type . '/' . $image;
		$imgFile = REDSHOP_FRONT_IMAGES_RELPATH . $type . '/' . $image;

		$file = array();

		JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_redshop/models');
		$media     = JModelLegacy::getInstance('Media', 'RedshopModel');

		$listMedia = $media->all();
		$gallery   = array();

		if (!empty($listMedia))
		{
			foreach ($listMedia as $lk => $lm)
			{
				$tmpFile   = REDSHOP_FRONT_IMAGES_RELPATH . $lm->media_section . '/' . $lm->media_name;

				if (file_exists($tmpFile))
				{
					$dimension = getimagesize($tmpFile);
					$mime      = mime_content_type($tmpFile);

					if ($mime)
					{
						$mime = explode('/', $mime);
						$mime = $mime[0];
					}

					$tmpImg    = array(
						'id'        => $lm->media_id,
						'url'       => JUri::root() . 'components/com_redshop/assets/images/' . $lm->media_section . '/' . $lm->media_name,
						'name'      => $lm->media_name,
						'size'      => self::sizeFilter(filesize($tmpFile)),
						'dimension' => $dimension[0] . ' x ' . $dimension[1],
						'media'     => $lm->media_section,
						'mime'      => $mime,
						'status'    => $lm->published ? '' : '-slash'
					);

					if ($image === $lm->media_name)
					{
						$tmpImg['attached'] = "true";
					}
					else
					{
						$tmpImg['attached'] = "false";
					}

					$gallery[] = $tmpImg;
				}
			}
		}

		if (!empty($image) && file_exists($imgFile))
		{
			$file = array(
				'path' => $imgUrl,
				'name' => $image,
				'size' => filesize($imgFile),
				'blob' => 'data: ' . mime_content_type($imgFile) . ';base64,' . base64_encode(file_get_contents($imgFile))
			);
		}

		echo RedshopLayoutHelper::render(
			'html.gallery',
			array(
				'id'           => $id,
				'type'         => $type,
				'sectionId'    => $sectionId,
				'mediaSection' => $mediaSection,
				'file'         => $file,
				'gallery'      => $gallery
			)
		);
	}

	/**
	 * Show file size in KB, MB, GB...
	 *
	 * @param   integer  $bytes  Volume of item
	 *
	 * @return  string
	 */
	public static function sizeFilter( $bytes )
	{
		$label = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');

		for ($i = 0; $bytes >= 1024 && $i < (count($label) - 1); $i++)
		{
			$bytes /= 1024;
		}

		return round($bytes, 2) . " " . $label[$i];
	}
}
