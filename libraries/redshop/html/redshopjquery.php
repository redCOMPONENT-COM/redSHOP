<?php
/**
 * @package     Joomla.Platform
 * @subpackage  HTML
 *
 * @copyright   Copyright (C) 2008 - 2015 Open Source Matters, Inc. All rights reserved.
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
	 * If debugging mode is on an uncompressed version of jQuery is included for easier debugging.
	 *
	 * @param   boolean  $noConflict  True to load jQuery in noConflict mode [optional]
	 * @param   mixed    $debug       Is debugging mode on? [optional]
	 * @param   boolean  $migrate     True to enable the jQuery Migrate plugin
	 *
	 * @return  void
	 */
	public static function framework($noConflict = true, $debug = null, $migrate = true)
	{
		// Only load once
		if (!empty(static::$loaded[__METHOD__]))
		{
			return;
		}

		if (version_compare(JVERSION, '3.0', '<'))
		{
			// If no debugging value is set, use the configuration setting
			if ($debug === null)
			{
				$config = JFactory::getConfig();
				$debug  = (boolean) $config->get('debug');
			}

			JHtml::script('com_redshop/jquery.js', false, true, false, false, $debug);

			// Check if we are loading in noConflict
			if ($noConflict)
			{
				JHtml::_('script', 'com_redshop/jquery-noconflict.js', false, true, false, false, false);
			}

			// Check if we are loading Migrate
			if ($migrate)
			{
				JHtml::_('script', 'com_redshop/jquery-migrate.js', false, true, false, false, $debug);
			}
		}
		else
		{
			JHtml::_('jquery.framework', $noConflict, $debug, $migrate);
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

		JHtml::stylesheet('com_redshop/jquery-ui/jquery-ui.css', array(), true);
		self::framework();
		JHtml::script('com_redshop/jquery-ui/jquery-ui.js', false, true, false, false);

		if (version_compare(JVERSION, '3.0', '>='))
		{
			// Check includes and remove core joomla jquery.ui script
			JHtml::_('jquery.ui', array('core'));
			$document = JFactory::getDocument();
			$headData = $document->getHeadData();

			if (isset($headData['scripts'][JUri::root(true) . '/media/jui/js/jquery.ui.core.min.js']))
			{
				unset($headData['scripts'][JUri::root(true) . '/media/jui/js/jquery.ui.core.min.js']);
			}

			if (JFactory::getConfig()->get('debug'))
			{
				if (isset($headData['scripts'][JUri::root(true) . '/media/jui/js/jquery.ui.core.js']))
				{
					unset($headData['scripts'][JUri::root(true) . '/media/jui/js/jquery.ui.core.js']);
				}
			}

			$document->setHeadData($headData);
		}

		static::$loaded[__METHOD__] = true;

		return;
	}

	/**
	 * Load the select2 library
	 * https://github.com/ivaynberg/select2
	 *
	 * @param   string  $selector         CSS Selector to initalise selects
	 * @param   array   $options          Optional array with options
	 * @param   bool    $getInitTemplate  Return init template or (false) set script in header
	 *
	 * @return  void
	 */
	public static function select2($selector = '.select2', $options = null, $getInitTemplate = false)
	{
		// Only load once
		if (isset(static::$loaded[__METHOD__][$selector]))
		{
			return;
		}

		self::framework();

		JHtml::script('com_redshop/select2/select2.js', false, true);
		JHtml::stylesheet('com_redshop/select2/select2.css', array(), true);

		if (version_compare(JVERSION, '3.0', '>='))
		{
			JHtml::stylesheet('com_redshop/select2/select2-bootstrap.css', array(), true);
		}

		$prefix = '';

		if (isset($options['multiple']) && $options['multiple'] == 'true')
		{
			self::ui();
			$prefix = ".select2('container').find('ul.select2-choices').sortable({
						containment: 'parent',
						start: function() { $('" . $selector . "').select2('onSortStart'); },
						update: function() { $('" . $selector . "').select2('onSortEnd'); }
					})";
		}

		$initTemplate = "
			(function($){
				$(document).ready(function () {
					$('" . $selector . "').select2(
						" . static::formatSelect2Options($options) . "
					)" . static::formatSelect2Events($options) . $prefix . ";
				});
			})(jQuery);
		";

		if ($getInitTemplate)
		{
			echo $initTemplate;
		}
		else
		{
			JFactory::getDocument()->addScriptDeclaration($initTemplate);
		}

		static::$loaded[__METHOD__][$selector] = true;

		return;
	}

	/**
	 * Function to receive & pre-process select2 events options
	 *
	 * @param   mixed  $options  Associative array/JRegistry object with options
	 *
	 * @return  string
	 */
	private static function formatSelect2Events($options)
	{
		$result = '';

		if (isset($options['events']) && is_array($options['events']))
		{
			foreach ($options['events'] as $key => $event)
			{
				$result .= ".on('" . $key . "', " . $event . ")";
			}
		}

		return $result;
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
			'formatSelectionTooBig', 'formatLoadMore', 'formatSearching', 'escapeMarkup', 'multiple', 'allowClear');
		$exclude = array('events');

		foreach ($options->toArray() as $key => $option)
		{
			if (in_array($key, $exclude))
			{
				continue;
			}

			if (!in_array($key, $functions))
			{
				$option = '"' . $option . '"';
			}

			$return[] = $key . ':' . $option;
		}

		return '{' . implode(', ', $return) . '}';
	}
}
