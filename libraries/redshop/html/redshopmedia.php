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
abstract class JHtmlRedshopmedia
{
	/**
	 * Show modal media
	 *
	 * If debugging mode is on an uncompressed version of jQuery is included for easier debugging.
	 *
	 * @param   string  $mediaSection  Media Section. Ex: product
	 * @param   string  $sectionId     Id of section
	 * @param   string  $sectionName   Name of section
	 * @param   int     $count         Number of media uploaded
	 * @param   string  $class         Class of modal, default is 'modal'
	 * @param   string  $handler       Default is iframe
	 * @param   int     $width         Width of modal
	 * @param   height  $height        Height of modal
	 *
	 * @return  void
	 */
	public static function show($mediaSection, $sectionId, $sectionName, $count, $class = 'modal', $handler = 'iframe', $width = 1050, $height = 450)
	{
		JHTMLBehavior::modal();

		return '<a class="modal"
			   href="index.php?option=com_redshop&view=media&section_id=' . $sectionId . '&showbuttons=1&media_section=' . $mediaSection . '&section_name=' . $sectionName . '&tmpl=component"
			   rel="{handler: \'' . $handler . '\', size: {x: ' . $width . ', y: ' . $height . '}}" title=""><img
					src="' . REDSHOP_ADMIN_IMAGES_ABSPATH . 'media16.png" align="absmiddle"
					alt="media"> (' . $count . ')</a>';
	}
}
