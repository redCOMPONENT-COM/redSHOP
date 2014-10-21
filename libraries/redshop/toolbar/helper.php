<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Application
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

// No direct access
defined('_JEXEC') or die;

/**
 * Utility class for the button bar.
 *
 * @package     Redshop.Library
 * @subpackage  Application
 * @since       1.4
 */
abstract class RedshopToolbarHelper
{
	/**
	 * Writes a custom option and task button for the button bar.
	 *
	 * @param   string  $link  Link.
	 * @param   string  $icon  The image to display.
	 * @param   string  $alt   The alt text for the icon image.
	 *
	 * @return void
	 */
	public static function link($link = '', $icon = '', $alt = '')
	{
		$bar = JToolBar::getInstance('toolbar');

		// Strip extension.
		$icon = preg_replace('#\.[^.]*$#', '', $icon);

		// Add a standard button.
		$bar->appendButton('Link', $icon, $alt, $link);
	}
}
