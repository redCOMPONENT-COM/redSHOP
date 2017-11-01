<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Application
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
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
abstract class RedshopToolbarHelper extends JToolBarHelper
{
	/**
	 * Writes a custom option and task button for the button bar.
	 *
	 * @param   string  $link    Link.
	 * @param   string  $icon    The image to display.
	 * @param   string  $alt     The alt text for the icon image.
	 * @param   string  $target  Target open link
	 *
	 * @return void
	 */
	public static function link($link = '', $icon = '', $alt = '', $target = '_self')
	{
		$bar = RedshopToolbar::getInstance('toolbar');
		$bar->addButtonPath(__DIR__ . '/button');

		// Strip extension.
		$icon = preg_replace('#\.[^.]*$#', '', $icon);

		// Add a standard button.
		$bar->appendButton('RedshopLink', $icon, $alt, $link, $target);
	}

	/**
	 * Writes a custom option and task button for the button bar.
	 *
	 * @param   string  $groupName   The group name.
	 * @param   string  $groupTitle  The group title.
	 *
	 * @return RedshopToolbar
	 */
	public static function createGroup($groupName, $groupTitle)
	{
		$bar = new RedshopToolbar($groupName, $groupTitle);

		return $bar;
	}
}
