<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  HTML
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

use Joomla\Utilities\ArrayHelper;

/**
 * Utility class for creating HTML Calendar
 *
 * @package     RedSHOP.Library
 * @subpackage  HTML
 * @since       1.5
 */
abstract class JHtmlRedshopcalendar
{
	/**
	 * Displays a calendar control field
	 *
	 * @param   string  $value    The date value
	 * @param   string  $name     The name of the text field
	 * @param   string  $id       The id of the text field
	 * @param   string  $format   The date format
	 * @param   mixed   $attribs  Additional HTML attributes
	 *
	 * @return  string  HTML markup for a calendar field
	 *
	 * @since   1.5
	 */
	public static function calendar($value, $name, $id, $format = '%Y-%m-%d', $attribs = null)
	{
		static $done;

		if ($done === null)
		{
			$done = array();
		}

		$readonly = isset($attribs['readonly']) && $attribs['readonly'] == 'readonly';
		$disabled = isset($attribs['disabled']) && $attribs['disabled'] == 'disabled';

		if (is_array($attribs))
		{
			$attribs['class'] = isset($attribs['class']) ? $attribs['class'] : 'input-medium';
			$attribs['class'] = trim($attribs['class'] . ' hasTooltip');

			$attribs = ArrayHelper::toString($attribs);
		}

		JHtml::_('bootstrap.tooltip');

		// Format value when not nulldate ('0000-00-00 00:00:00'), otherwise blank it as it would result in 1970-01-01.
		if ($value && $value != JFactory::getDbo()->getNullDate() && strtotime($value) !== false)
		{
			$tz = date_default_timezone_get();
			date_default_timezone_set('UTC');
			$inputvalue = strftime($format, strtotime($value));
			date_default_timezone_set($tz);
		}
		else
		{
			$inputvalue = '';
		}

		// Load the calendar behavior
		JHtml::_('behavior.calendar');

		// Only display the triggers once for each control.
		if (!in_array($id, $done))
		{
			$document = JFactory::getDocument();
			$document
				->addScriptDeclaration(
					'jQuery(document).ready(function($) {Calendar.setup({
			// Id of the input field
			inputField: "' . $id . '",
			// Format of the input field
			ifFormat: "' . $format . '",
			// Trigger for the calendar (button ID)
			button: "' . $id . '_img",
			// Alignment (defaults to "Bl")
			align: "Tl",
			singleClick: true,
			firstDay: ' . JFactory::getLanguage()->getFirstDay() . '
			});});'
				);
			$done[] = $id;
		}

		// Hide button using inline styles for readonly/disabled fields
		$btn_style = ' style=""';
		$div_class = ' class="input-append"';

		return '<div' . $div_class . '>'
			. '<input type="text" title="' . ($inputvalue ? JHtml::_('date', $value, null, null) : '')
			. '" name="' . $name . '" id="' . $id . '" value="' . htmlspecialchars($inputvalue, ENT_COMPAT, 'UTF-8') . '" ' . $attribs . ' />'
			. '<button type="button" class="btn" id="' . $id . '_img"' . $btn_style . '><span class="icon-calendar"></span></button>'
			. '</div>';
	}
}
