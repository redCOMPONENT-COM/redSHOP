<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  HTML
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

/**
 * jQuery HTML class.
 *
 * @package     RedSHOP.Library
 * @subpackage  HTML
 * @since       2.0.0.4
 */
abstract class JHtmlRedshopWrapper
{
	/**
	 * Show modal media
	 *
	 * If debugging mode is on an uncompressed version of jQuery is included for easier debugging.
	 *
	 * @param   int     $productId  Id of product
	 * @param   int     $count      Number of media uploaded
	 * @param   string  $class      Class of modal, default is 'modal'
	 * @param   string  $handler    Default is iframe
	 * @param   int     $width      Width of modal
	 * @param   height  $height     Height of modal
	 *
	 * @return  void
	 */
	public static function showButton($productId, $count, $class = 'modal', $handler = 'iframe', $width = 1050, $height = 450)
	{
		JHTMLBehavior::modal();

		$displayData = [
			'productId'		=> $productId,
			'count'			=> $count,
			'class'			=> $class,
			'handler'		=> $handler,
			'width'			=> $width,
			'height'		=> $height,
		];

		return RedshopLayoutHelper::render('html.wrapper.button', $displayData);
	}
}
