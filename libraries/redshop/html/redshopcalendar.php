<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  HTML
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
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
	 * @param   string  $tz      Timezone of input value
	 *
	 * @return  string  HTML markup for a calendar field
	 *
	 * @since   1.5
	 */
	public static function calendar($value, $name, $id, $format = '', $attribs = null, $inline = false, $tz = null)
	{
		$format = empty($format) ? Redshop::getConfig()->getString('DEFAULT_DATEFORMAT', 'Y-m-d') : $format;
		$userTz = JFactory::getConfig()->get('offset');

		if (is_array($attribs))
		{
			$attribs['class'] = isset($attribs['class']) ? $attribs['class'] : 'input-medium';
			$attribs['class'] = trim($attribs['class'] . ' hasTooltip');

			$attribs = ArrayHelper::toString($attribs);
		}

		JHtml::_('bootstrap.tooltip');

		if (empty($tz))
		{
			$tz = date_default_timezone_get();
		}

		/** @scrutinizer ignore-deprecated */JHtml::script('com_redshop/moment.min.js', false, true);
		/** @scrutinizer ignore-deprecated */JHtml::script('com_redshop/moment-timezone-with-data.min.js', false, true);
		/** @scrutinizer ignore-deprecated */JHtml::script('com_redshop/bootstrap-datetimepicker.min.js', false, true, false, false);
		/** @scrutinizer ignore-deprecated */JHtml::script('com_redshop/jquery.inputmask.min.js', false, true);
		/** @scrutinizer ignore-deprecated */JHtml::stylesheet('com_redshop/bootstrap-datetimepicker.min.css', array(), true);

		$momentValue = false;

		if (!empty($value))
		{
			$momentValue = DateTime::createFromFormat($format, $value, new DateTimeZone($tz));
			$momentValue = false !== $momentValue ? $momentValue->getTimestamp() : false;
		}

		$defaultDate = $momentValue ? 'defaultDate: moment.unix(' . $momentValue . '),' : '';

		JFactory::getDocument()->addScriptDeclaration(
			'(function($){
				$(document).ready(function(){
					$("#' . $id . '_wrapper").datetimepicker({
						timeZone: "' . $userTz . '",
						collapse: true,
						sideBySide: true,
						showTodayButton: false,
						format: "' . RedshopHelperDatetime::convertPHPToMomentFormat($format) . '",
						showClear: ' . (!$inline ? 'true' : 'false') . ',
						showClose: ' . (!$inline ? 'true' : 'false') . ',
						inline: ' . (!$inline ? 'false' : 'true') . ',
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

		if (!$inline)
		{
			// Hide button using inline styles for readonly/disabled fields
			return '<div class="input-group" id="' . $id . '_wrapper">'
				. '<span class="input-group-addon" id="' . $id . '_img"><i class="fa fa-calendar"></i></span>'
				. '<input type="text" name="' . $name . '" id="' . $id . '" ' . $attribs . ' />'
				. '<span class="input-group-addon"><strong>' . strtolower($format) . '</strong></span>'
				. '</div>';
		}

		// Hide button using inline styles for readonly/disabled fields
		return '<div class="input-group" id="' . $id . '_wrapper">'
			. '<input type="hidden" name="' . $name . '" id="' . $id . '" ' . $attribs . ' />'
			. '</div>';
	}
}
