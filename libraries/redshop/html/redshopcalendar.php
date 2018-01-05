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
use Redshop\Config\App;

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
	 * @param   string $value   The date value
	 * @param   string $name    The name of the text field
	 * @param   string $id      The id of the text field
	 * @param   string $format  The date format
	 * @param   mixed  $attribs Additional HTML attributes
	 *
	 * @return  string  HTML markup for a calendar field
	 *
	 * @since   1.5
	 */
	public static function calendar($value, $name, $id, $format = '', $attribs = null)
	{
		static $done;

		if ($done === null)
		{
			$done = array();
		}

		$readonly = isset($attribs['readonly']) && $attribs['readonly'] == 'readonly';
		$disabled = isset($attribs['disabled']) && $attribs['disabled'] == 'disabled';
		$format   = empty($format) ? Redshop::getConfig()->get('DEFAULT_DATEFORMAT', 'Y-m-d') : $format;
		$format   = RedshopHelperDatetime::convertPHPToMomentFormat($format);

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

		JHtml::script('com_redshop/moment.js', false, true, false, false);
		JHtml::script('com_redshop/bootstrap-datetimepicker.min.js', false, true, false, false);
		JHtml::script('com_redshop/jquery.inputmask.js', false, true);
		JHtml::stylesheet('com_redshop/bootstrap-datetimepicker.min.css', array(), true);

		// Only display the triggers once for each control.
		if (!in_array($id, $done))
		{
			JFactory::getDocument()->addScriptDeclaration(
				'(function($){
					$(document).ready(function(){
						$("#' . $id . '_wrapper").datetimepicker({
							collapse: false,
							sideBySide: true,
							showTodayButton: false,
							format: "' . $format . '",
							showClear: true,
							allowInputToggle: true,
							icons: {
								time: "fa fa-time",
								date: "fa fa-calendar",
								up: "fa fa-chevron-up",
								down: "fa fa-chevron-down",
								previous: "fa fa-chevron-left",
								next: "fa fa-chevron-right",
								today: "fa fa-calendar",
								clear: "fa fa-trash text-danger",
								close: "fa fa-remove"
							}
						});
						
						$("#' . $id . '").inputmask("' . strtolower($format) . '");
					});
				})(jQuery);'
			);
			$done[] = $id;
		}

		// Hide button using inline styles for readonly/disabled fields

		return '<div class="input-group" id="' . $id . '_wrapper">'
			. '<span class="input-group-addon" id="' . $id . '_img"><i class="fa fa-calendar"></i></span>'
			. '<input type="text" title="' . ($inputvalue ? JHtml::_('date', $value, null, null) : '') . '"'
			. ' name="' . $name . '" id="' . $id . '" value="' . htmlspecialchars($inputvalue, ENT_COMPAT, 'UTF-8') . '" ' . $attribs . ' />'
			. '<span class="input-group-addon"><strong>' . strtolower($format) . '</strong></span>'
			. '</div>';
	}
}
