<?php
/**
 * @package     Joomla.Platform
 * @subpackage  HTML
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

/**
 * jQuery HTML class.
 *
 * @package     RedSHOP.Platform
 * @subpackage  HTML
 * @since       1.5
 */
abstract class JHtmlRedshopjquery
{
	/**
	 * Array containing information for loaded files
	 *
	 * @var  array
	 */
	protected static $loaded = array();

	/**
	 * Load the jQuery framework
	 *
	 * @return  void
	 */
	public static function framework()
	{
		// Only load once
		if (!empty(static::$loaded[__METHOD__]))
		{
			return;
		}

		if (version_compare(JVERSION, '3.0', '<'))
		{
			JHtml::script('com_redshop/jquery.js', false, true, false, false);
		}
		else
		{
			JHtml::_('jquery.framework');
		}

		static::$loaded[__METHOD__] = true;

		return;
	}

	/**
	 * Load the jQuery UI library
	 *
	 * @return  void
	 *
	 * @since   1.5
	 */
	public static function ui()
	{
		// Only load once
		if (!empty(static::$loaded[__METHOD__]))
		{
			return;
		}

		self::framework();

		JHtml::script('com_redshop/jquery-ui/jquery-ui.min.js', false, true, false, false);
		JHtml::stylesheet('com_redshop/jquery-ui/jquery-ui.min.css', array(), true);

		static::$loaded[__METHOD__] = true;

		return;
	}

	/**
	 * Load the select2 library
	 * https://github.com/ivaynberg/select2
	 *
	 * @param   string  $selector  CSS Selector to initalise selects
	 * @param   array   $options   Optional array with options
	 *
	 * @return  void
	 */
	public static function select2($selector = '.select2', $options = null)
	{
		// Only load once
		if (isset(static::$loaded[__METHOD__][$selector]))
		{
			return;
		}

		self::framework();

		JHtml::script('com_redshop/select2/select2.min.js', false, true);
		JHtml::stylesheet('com_redshop/select2/select2.css', array(), true);

		if (version_compare(JVERSION, '3.0', '>='))
		{
			JHtml::stylesheet('com_redshop/select2/select2-bootstrap.css', array(), true);
		}

		// Generate options with default values
		$optionsToString = static::formatSelect2Options($options);
		$prefix = '';

		if (isset($options['multiple']) && $options['multiple'] == 'true')
		{
			self::ui();
			$prefix = ".select2('container').find('ul.select2-choices').sortable({
						containment: 'parent',
						start: function() { $('" . $selector . "').select2('onSortStart'); },
						update: function() { $('" . $selector . "').select2('onSortEnd'); }
					});";
		}

		JFactory::getDocument()->addScriptDeclaration("
			(function($){
				$(document).ready(function () {
					$('" . $selector . "').select2(
						" . $optionsToString . "
					)" . $prefix . "
				});
			})(jQuery);
		");

		static::$loaded[__METHOD__][$selector] = true;

		return;
	}

	/**
	 * Function to receive & pre-process select2 options
	 *
	 * @param   mixed  $options  Associative array/JRegistry object with options
	 *
	 * @return  string           The options ready for the select2() function
	 */
	private static function formatSelect2Options($options)
	{
		// Support options array
		if (is_array($options))
		{
			$options = new JRegistry($options);
		}

		if (!($options instanceof JRegistry))
		{
			$options = new JRegistry;
		}

		$options->def('width', 'resolve');
		$options->def('formatNoMatches', 'function () { return "' . JText::_("LIB_REDSHOP_SELECT2_NO_MATHES") . '"; }');
		$options->def('formatInputTooShort', 'function (input, min) { var n = min - input.length; return "'
			. JText::_("LIB_REDSHOP_SELECT2_INPUT_TO_SHORT") . '" + (n == 1? "" : "' . JText::_("LIB_REDSHOP_SELECT2_PREFIX") . '"); }');
		$options->def('formatInputTooLong', 'function (input, max) { var n = input.length - max; return "'
			. JText::_("LIB_REDSHOP_SELECT2_TO_LONG") . '" + (n == 1? "" : "' . JText::_("LIB_REDSHOP_SELECT2_PREFIX") . '"); }');
		$options->def('formatSelectionTooBig', 'function (limit) { return "'
			. JText::_("LIB_REDSHOP_SELECT2_TO_BIG") . '" + (limit == 1 ? "" : "' . JText::_("LIB_REDSHOP_SELECT2_PREFIX") . '"); }');
		$options->def('formatLoadMore', 'function (pageNumber) { return "' . JText::_("LIB_REDSHOP_SELECT2_LOAD_MORE") . '"; }');
		$options->def('formatSearching', 'function () { return "' . JText::_("LIB_REDSHOP_SELECT2_SEARCHING") . '"; }');

		$return = array();
		$functions = array('ajax', 'initSelection', 'formatNoMatches', 'formatInputTooShort', 'formatInputTooLong',
			'formatSelectionTooBig', 'formatLoadMore', 'formatSearching', 'escapeMarkup');

		foreach ($options->toArray() as $key => $option)
		{
			if (!in_array($key, $functions))
			{
				$option = '"' . $option . '"';
			}

			$return[] = $key . ':' . $option;
		}

		return '{' . implode(', ', $return) . '}';
	}
}
