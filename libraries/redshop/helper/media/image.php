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
		$document      = new RedshopHelperDocument;
		$basepath = JPATH_SITE . '/media/com_reditem/';

		$dropzonePath = $basepath . 'dropzone';
		$cropperPath  = $basepath . 'cropper';

		if (file_exists($dropzonePath) && file_exists($cropperPath))
		{
			$document->addTopStylesheet('/media/com_reditem/components-font-awesome/css/font-awesome.min.css');
			$document->addStylesheet('/media/com_reditem/dropzone/dist/min/dropzone.min.css');
			$document->addStylesheet('/media/com_reditem/css/select2/select2.css');
			$document->addStylesheet('/media/com_reditem/css/select2/select2-bootstrap.css');
			$document->addStylesheet('/media/com_reditem/cropper/dist/cropper.min.css');

			$document->addScript('/media/com_reditem/dropzone/dist/min/dropzone.min.js');
			$document->addScript('/media/com_reditem/cropper/dist/cropper.min.js');
			$document->addScript('/media/com_reditem/js/select2/select2.js');
			$document->disableScript('/media/jui/js/bootstrap.min.js');
			$document->disableScript('/media/system/js/modal.js');

			return true;
		}

		return false;
	}
}
