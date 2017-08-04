<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  HTML
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
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

			return '<input type="checkbox" name="' . $name . '" value="" class="hasTooltip" title="'
				. JHtml::tooltipText($tip) . '" onclick="' . $action . '" />';
		}
		else
		{
			return '<input type="checkbox" name="' . $name . '" value="" title="' . JText::_($tip) . '" onclick="' . $action . '" />';
		}
	}

	/**
	 * Method for render text with slide if length is longer than count.
	 *
	 * @param   string  $data   String data
	 * @param   int     $count  Count of maximum length
	 *
	 * @return  string
	 *
	 * @since   2.0.4
	 */
	public static function slideText($data = '', $count = 50)
	{
		if (empty($data))
		{
			return '';
		}

		if (strlen($data) <= $count)
		{
			return $data;
		}

		JText::script('COM_REDSHOP_GRID_SLIDERTEXT_MORE');
		JText::script('COM_REDSHOP_GRID_SLIDERTEXT_LESS');

		$teaser = JHtml::_('string.truncate', $data, $count, true, false);

		return "<span class='rs-teaser'>" . $teaser . "</span>
			<span class='rs-full'>" . $data . "</span>
			<span class='rs-more badge label-success'>" . JText::_('COM_REDSHOP_GRID_SLIDERTEXT_MORE') . "</span>";
	}

	/**
	 * Method for render HTML of inline edit field.
	 *
	 * @param   string  $name     DOM name of field
	 * @param   string  $value    Value of field
	 * @param   string  $display  Value of field
	 * @param   int     $id       DOM ID of field
	 * @param   string  $type     Field type (text)
	 *
	 * @return  string
	 *
	 * @since   2.0.6
	 */
	public static function inline($name = '', $value = '', $display = '', $id = 0, $type = 'text')
	{
		if (!in_array($type, array('text', 'number', 'redshop.text')))
		{
			return $value;
		}

		JHtml::script('com_redshop/redshop.inline.js', false, true, false, false);
		JText::script('COM_REDSHOP_SUCCESS');
		JText::script('COM_REDSHOP_DATA_UPDATE_SUCCESS');
		JText::script('COM_REDSHOP_FAIL');
		JText::script('COM_REDSHOP_DATA_UPDATE_FAIL');

		$html = '<input type="' . $type . '" id="' . $name . '-' . $id . '-edit-inline" value="' . $value . '"'
			. 'name="jform_inline[' . $id . '][' . $name . ']" class="form-control edit-inline" '
			. ' data-original-value="' . $value . '" disabled="disabled" style="display: none;" />';
		$html .= '<div id="' . $name . '-' . $id . '" data-target="' . $name . '-' . $id . '-edit-inline" data-id="' . $id . '" '
			. 'class="label-edit-inline">' . $display . '</div>';

		return $html;
	}

	/**
	 * Returns a checked-out icon
	 *
	 * @param   integer       $i           The row index.
	 * @param   string        $editorName  The name of the editor.
	 * @param   string        $time        The time that the object was checked out.
	 * @param   string|array  $prefix      An optional task prefix or an array of options
	 * @param   boolean       $enabled     True to enable the action.
	 * @param   string        $checkbox    An optional prefix for checkboxes.
	 * @param   string        $formId      An optional form id
	 *
	 * @return  string  The required HTML.
	 */
	public static function checkedOut($i, $editorName, $time, $prefix = '', $enabled = false, $checkbox = 'cb', $formId = 'adminForm')
	{
		if (is_array($prefix))
		{
			$options  = $prefix;
			$enabled  = array_key_exists('enabled', $options) ? $options['enabled'] : $enabled;
			$checkbox = array_key_exists('checkbox', $options) ? $options['checkbox'] : $checkbox;
			$prefix   = array_key_exists('prefix', $options) ? $options['prefix'] : '';
		}

		$text           = addslashes(htmlspecialchars($editorName, ENT_COMPAT, 'UTF-8'));
		$date           = addslashes(htmlspecialchars(JHtml::_('date', $time, JText::_('DATE_FORMAT_LC')), ENT_COMPAT, 'UTF-8'));
		$time           = addslashes(htmlspecialchars(JHtml::_('date', $time, 'H:i'), ENT_COMPAT, 'UTF-8'));
		$active_title   = JText::_('JLIB_HTML_CHECKIN') . '::' . $text . '<br />' . $date . '<br />' . $time;
		$inactive_title = JText::_('JLIB_HTML_CHECKED_OUT') . '::' . $text . '<br />' . $date . '<br />' . $time;

		return self::action(
			$i, 'checkin', $prefix, JText::_('JLIB_HTML_CHECKED_OUT'), $active_title, $inactive_title, true, 'lock',
			'lock', $enabled, false, $checkbox, $formId
		);
	}

	/**
	 * Returns an action on a grid
	 *
	 * @param   integer       $i               The row index
	 * @param   string        $task            The task to fire
	 * @param   string|array  $prefix          An optional task prefix or an array of options
	 * @param   string        $text            An optional text to display
	 * @param   string        $active_title    An optional active tooltip to display if $enable is true
	 * @param   string        $inactive_title  An optional inactive tooltip to display if $enable is true
	 * @param   boolean       $tip             An optional setting for tooltip
	 * @param   string        $active_class    An optional active HTML class
	 * @param   string        $inactive_class  An optional inactive HTML class
	 * @param   boolean       $enabled         An optional setting for access control on the action.
	 * @param   boolean       $translate       An optional setting for translation.
	 * @param   string        $checkbox        An optional prefix for checkboxes.
	 * @param   string        $formId          An optional form id
	 * @param   string        $buttonClass     An optional button class
	 *
	 * @return  string         The Html code
	 */
	public static function action($i, $task, $prefix = '', $text = '', $active_title = '', $inactive_title = '',
	                              $tip = false, $active_class = '', $inactive_class = '',
	                              $enabled = true, $translate = true, $checkbox = 'cb', $formId = 'adminForm', $buttonClass = '')
	{
		if (is_array($prefix))
		{
			$options        = $prefix;
			$active_title   = array_key_exists('active_title', $options) ? $options['active_title'] : $active_title;
			$inactive_title = array_key_exists('inactive_title', $options) ? $options['inactive_title'] : $inactive_title;
			$tip            = array_key_exists('tip', $options) ? $options['tip'] : $tip;
			$active_class   = array_key_exists('active_class', $options) ? $options['active_class'] : $active_class;
			$inactive_class = array_key_exists('inactive_class', $options) ? $options['inactive_class'] : $inactive_class;
			$enabled        = array_key_exists('enabled', $options) ? $options['enabled'] : $enabled;
			$translate      = array_key_exists('translate', $options) ? $options['translate'] : $translate;
			$checkbox       = array_key_exists('checkbox', $options) ? $options['checkbox'] : $checkbox;
			$formId         = array_key_exists('formId', $options) ? $options['formId'] : $formId;
			$buttonClass    = array_key_exists('buttonClass', $options) ? $options['buttonClass'] : $buttonClass;
			$prefix         = array_key_exists('prefix', $options) ? $options['prefix'] : '';
		}

		if ($tip)
		{
			JHtml::_('redshopjquery.popover');
		}

		if ($enabled)
		{
			// Prepare the class.
			if ($active_class === 'plus')
			{
				$buttonClass = 'published';
			}

			elseif ($active_class === 'minus')
			{
				$buttonClass = 'unpublished';
			}

			$buttonClass .= $tip ? ' hasPopover' : '';

			$html[] = '<a class="btn btn-small btn-sm ' . $buttonClass . '"';
			$html[] = ' href="javascript:void(0);" onclick="return listItemTask(\'' . $checkbox . $i . '\',\''
				. $prefix . $task . '\',\'' . $formId . '\')"';
			$html[] = ' title="' . addslashes(htmlspecialchars($translate ? JText::_($active_title) : $active_title, ENT_COMPAT, 'UTF-8')) . '">';
			$html[] = '<i class="fa fa-' . $active_class . '">';
			$html[] = '</i>';
			$html[] = '</a>';
		}
		else
		{
			$html[] = '<a class="btn btn-small disabled jgrid ' . $buttonClass . ' ' . ($tip ? 'hasPopover' : '') . '" ';
			$html[] = ' title="' . addslashes(htmlspecialchars($translate ? JText::_($inactive_title) : $inactive_title, ENT_COMPAT, 'UTF-8')) . '">';

			if ($active_class == "protected")
			{
				$html[] = '<i class="fa fa-lock"></i>';
			}
			else
			{
				$html[] = '<i class="fa fa-' . $active_class . '"></i>';
			}

			$html[] = '</a>';
		}

		return implode($html);
	}
}
