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
	 * @param   string  $value   The date value
	 * @param   string  $name    The name of the text field
	 * @param   string  $id      The id of the text field
	 * @param   string  $format  The date format
	 * @param   mixed   $attribs Additional HTML attributes
	 * @param   boolean $inline  Inline or not
	 *
	 * @return  string  HTML markup for a calendar field
	 *
	 * @since   1.5
	 */
	public static function calendar($value, $name, $id, $format = '', $attribs = null, $inline = false)
	{
		$format = empty($format) ? Redshop::getConfig()->getString('DEFAULT_DATEFORMAT', 'Y-m-d') : $format;

		if (is_array($attribs))
		{
			$attribs['class'] = isset($attribs['class']) ? $attribs['class'] : 'input-medium';
			$attribs['class'] = trim($attribs['class'] . ' hasTooltip');

			$attribs = ArrayHelper::toString($attribs);
		}

		JHtml::_('bootstrap.tooltip');

		// Format value when not null date ('0000-00-00 00:00:00'), otherwise blank it as it would result in 1970-01-01.
		if (!empty($value) && $value != JFactory::getDbo()->getNullDate() && strtotime($value) !== false)
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

		/** @scrutinizer ignore-deprecated */JHtml::script('com_redshop/moment.min.js', false, true);
		/** @scrutinizer ignore-deprecated */JHtml::script('com_redshop/bootstrap-datetimepicker.min.js', false, true, false, false);
		/** @scrutinizer ignore-deprecated */JHtml::script('com_redshop/jquery.inputmask.min.js', false, true);
		/** @scrutinizer ignore-deprecated */JHtml::stylesheet('com_redshop/bootstrap-datetimepicker.min.css', array(), true);

		$momentValue = false;

		if (!empty($value))
		{
			$momentValue = DateTime::createFromFormat($format, $value);
			$momentValue = false !== $momentValue ? $momentValue->getTimestamp() : false;
		}

		$defaultDate = $momentValue ? 'defaultDate: moment.unix(' . $momentValue . '),' : '';

		if ($inline)
		{
			JFactory::getDocument()->addScriptDeclaration(
				'(function($){
					$(document).ready(function(){
						$("#' . $id . '_wrapper").datetimepicker({
							collapse: true,
							sideBySide: true,
							showTodayButton: false,
							format: "' . RedshopHelperDatetime::convertPHPToMomentFormat($format) . '",
							showClear: true,
							showClose: true,
							allowInputToggle: true,
							' . $defaultDate . '
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
					});
				})(jQuery);'
			);

			// Hide button using inline styles for readonly/disabled fields
			return '<div class="input-group" id="' . $id . '_wrapper">'
				. '<span class="input-group-addon" id="' . $id . '_img"><i class="fa fa-calendar"></i></span>'
				. '<input type="text" title="' . ($inputvalue ? JHtml::_('date', $value, null, null) : '') . '"'
				. ' name="' . $name . '" id="' . $id . '" ' . $attribs . ' />'
				. '<span class="input-group-addon"><strong>' . strtolower($format) . '</strong></span>'
				. '</div>';
		}

		JFactory::getDocument()->addScriptDeclaration(
			'(function($){
				$(document).ready(function(){
					$("#' . $id . '_wrapper").datetimepicker({
						collapse: true,
						sideBySide: true,
						showTodayButton: false,
						format: "' . RedshopHelperDatetime::convertPHPToMomentFormat($format) . '",
						showClear: false,
						showClose: false,
						inline: true,
						allowInputToggle: true,
						defaultDate: "' . $value . '" ,
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
				});
			})(jQuery);'
		);

		return '<div class="input-group" id="' . $id . '_wrapper">'
			. '<input type="hidden" title="' . ($inputvalue ? JHtml::_('date', $value, null, null) : '') . '"'
			. ' name="' . $name . '" id="' . $id . '" ' . $attribs . ' />'
			. '</div>';
	}
}
