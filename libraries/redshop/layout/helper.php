<?php
/**
 * @package     Redshop.Libraries
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_BASE') or die;

use Redshop\Twig;

JLoader::import('redshop.library');

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
     * @var    array
     */
    public static $layoutOption = array(
        'component'  => 'com_redshop',
        'layoutType' => 'Twig',
        'layoutOf'   => 'library'
    );

    /**
     * Method to render the redshop tag layout.
     *
     * @param   string  $tagName      Name tag
     * @param   string  $template     Template with current tag
     * @param   string  $tagSection   Section tag
     * @param   array   $displayData  Object which properties are used inside the layout file to build displayed output
     * @param   string  $basePath     Base path to use when loading layout files
     * @param   mixed   $options      Optional custom options to load. JRegistry or array format
     *
     * @return  void
     */
    public static function renderTag(
        $tagName,
        &$template,
        $tagSection = '',
        $displayData = null,
        $basePath = '',
        $options = null
    ) {
        if (strpos($template, $tagName) === false) {
            return;
        }

        $filePath = array('tags');

        if ($tagSection) {
            $filePath[] = $tagSection;
        } else {
            $filePath[] = 'common';
        }

        $filePath[] = str_replace(array('{', '}', ':', ' '), array('', '', '_', '_'), $tagName);

        $return   = self::render(implode('.', $filePath), $displayData, $basePath, $options);
        $template = str_replace($tagName, $return, $template);
    }

    /**
     * Method to render the layout.
     *
     * @param   string  $layoutFile   Dot separated path to the layout file, relative to base path
     * @param   array   $displayData  Object which properties are used inside the layout file to build displayed output
     * @param   string  $basePath     Base path to use when loading layout files
     * @param   mixed   $options      Optional custom options to load. JRegistry or array format
     *
     * @return  string
     */
    public static function render(
        $layoutFile,
        $displayData = null,
        $basePath = '',
        $options = array('component' => 'com_redshop')
    ) {
        $basePath = empty($basePath) ? self::$defaultBasePath : $basePath;

        // Make sure we send null to JLayoutFile if no path set
        $basePath       = empty($basePath) ? null : $basePath;
        $renderedLayout = '';

        if ($displayData === null) {
            $displayData = array();
        }

        // Check for render Twig or PHP normally
        if (!empty($options['layoutType']) && $options['layoutType'] === 'Twig') {
            // Shorter code for Scrutinizer check
            $renderedLayout = self::renderTwig($layoutFile, $displayData, $basePath, $options);
        } else {
            $layout         = new RedshopLayoutFile($layoutFile, $basePath, $options);
            $renderedLayout = $layout->render($displayData);
        }

        return $renderedLayout;
    }

    /**
     * Method to render the layout of Twig
     *
     * @param   string  $layoutFile   Dot separated path to the layout file, relative to base path
     * @param   array   $displayData  Object which properties are used inside the layout file to build displayed output
     * @param   string  $basePath     Base path to use when loading layout files
     * @param   mixed   $options      Optional custom options to load. JRegistry or array format
     *
     * @return  string
     */
    public static function renderTwig(
        $layoutFile,
        $displayData = array(),
        $basePath = '',
        $options = array('component' => 'com_redshop')
    ) {
        if (empty($options['layoutOf'])) {
            return '';
        }

        $layoutOf = Joomla\String\StringHelper::strtolower($options['layoutOf']);
        $layoutOf = Joomla\String\StringHelper::trim((string)$layoutOf);

        if ($layoutOf === '') {
            return '';
        }

        $prefix = 'redshop';

        if (!empty($options['prefix'])) {
            $prefix = $options['prefix'];
        }

        // Ensure not include strange thing
        $layoutFile = str_replace('_:', '', $layoutFile);

        $renderPath = str_replace('.', '/', $basePath . $layoutFile);
        $renderPath = '@' . /** @scrutinizer ignore-type */
            $layoutOf . '/' . $prefix . '/' . $renderPath . '.html.twig';

        return html_entity_decode(Twig::render($renderPath, $displayData));
    }
}
