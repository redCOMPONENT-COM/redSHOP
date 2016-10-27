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
	 * Render HTML for image drag'n'drop
	 *
	 * @return  void
	 */
	public static function render()
	{
		if (self::requireDependencies())
		{
			echo RedshopLayoutHelper::render(
				'component.dropzone'
			);
		}
	}

	/**
	 * Require dependecies from bower.js.
	 * Checking dependencies are existed or not then require them to header
	 *
	 * @return  boolean
	 */
	public static function requireDependencies()
	{
		$document = JFactory::getDocument();
		$doc      = new RedshopHelperDocument;
		$basepath = JPATH_SITE . '/media/com_reditem/';

		$dropzonePath = $basepath . 'dropzone';
		$cropperPath  = $basepath . 'cropper';

		if (file_exists($dropzonePath) && file_exists($cropperPath))
		{
			$document->addStylesheet('/media/com_reditem/dropzone/dist/min/dropzone.min.css');
			$document->addStylesheet('/media/com_reditem/cropper/dist/cropper.min.css');
			$document->addScript('/media/com_reditem/dropzone/dist/min/dropzone.min.js');
			$document->addScript('/media/com_reditem/cropper/dist/cropper.min.js');
			$doc->disableScript('/media/jui/js/bootstrap.min.js');
			$doc->disableScript('/media/system/js/modal.js');

			return true;
		}

		return false;
	}
}
