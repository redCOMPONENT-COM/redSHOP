<?php
/**
 * @package     Redshop.Libraries
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_BASE') or die;

/**
 * Helper to render a JLayout object, storing a base path
 *
 * @package     Redshop.Libraries
 * @subpackage  Layout
 * @see         http://docs.joomla.org/Sharing_layouts_across_views_or_extensions_with_JLayout
 * @since       1.4
 */
class RedshopLayoutHelper
{
	/**
	 * A default base path that will be used if none is provided when calling the render method.
	 * Note that JLayoutFile itself will defaults to JPATH_ROOT . '/layouts' if no basePath is supplied at all
	 *
	 * @var    string
	 */
	public static $defaultBasePath = '';

	/**
	 * Method to render the layout.
	 *
	 * @param   string  $layoutFile   Dot separated path to the layout file, relative to base path
	 * @param   object  $displayData  Object which properties are used inside the layout file to build displayed output
	 * @param   string  $basePath     Base path to use when loading layout files
	 * @param   mixed   $options      Optional custom options to load. JRegistry or array format
	 *
	 * @return  string
	 */
	public static function render($layoutFile, $displayData = null, $basePath = '', $options = null)
	{
		$basePath = empty($basePath) ? self::$defaultBasePath : $basePath;

		// Make sure we send null to JLayoutFile if no path set
		$basePath = empty($basePath) ? null : $basePath;
		$layout = new RedshopLayoutFile($layoutFile, $basePath, $options);
		$renderedLayout = $layout->render($displayData);

		return $renderedLayout;
	}

	/**
	 * Method to render the redshop tag layout.
	 *
	 * @param   string  $tagName      Name tag
	 * @param   string  &$template    Template with current tag
	 * @param   string  $tagSection   Section tag
	 * @param   object  $displayData  Object which properties are used inside the layout file to build displayed output
	 * @param   string  $basePath     Base path to use when loading layout files
	 * @param   mixed   $options      Optional custom options to load. JRegistry or array format
	 *
	 * @return  void
	 */
	public static function renderTag($tagName, &$template, $tagSection = '', $displayData = null, $basePath = '', $options = null)
	{
		if (strpos($template, $tagName) !== false)
		{
			$filePath = array('tags');

			if ($tagSection)
			{
				$filePath[] = $tagSection;
			}
			else
			{
				$filePath[] = 'common';
			}

			$filePath[] = str_replace(array('{', '}', ':', ' '), array('', '', '_', '_'), $tagName);

			$return = self::render(implode('.', $filePath), $displayData, $basePath, $options);
			$template = str_replace($tagName, $return, $template);
		}
	}
}
