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
 * Utility class for creating HTML Grids
 *
 * @package     RedSHOP.Library
 * @subpackage  HTML
 * @since       1.5
 */
abstract class JHtmlRedshopGrid
{
	/**
	 * Method to check all checkboxes in a grid
	 *
	 * @param   string  $name    The name of the form element
	 * @param   string  $tip     The text shown as tooltip title instead of $tip
	 * @param   string  $action  The action to perform on clicking the checkbox
	 *
	 * @return  string
	 *
	 * @since   3.1.2
	 */
	public static function checkall($name = 'checkall-toggle', $tip = 'JGLOBAL_CHECK_ALL', $action = 'Joomla.checkAll(this)')
	{
		if (version_compare(JVERSION, '3.0', '>='))
		{
			JHtml::_('bootstrap.tooltip');

			return '<input type="checkbox" name="' . $name . '" value="" class="hasTooltip" title="' . JHtml::tooltipText($tip) . '" onclick="' . $action . '" />';
		}
		else
		{
			return '<input type="checkbox" name="' . $name . '" value="" title="' . JText::_($tip) . '" onclick="' . $action . '" />';
		}
	}
}
