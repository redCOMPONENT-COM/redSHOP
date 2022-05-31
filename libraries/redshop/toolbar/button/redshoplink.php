<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Toolbar
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Renders a link button
 *
 * @package     RedSHOP.Library
 * @subpackage  Toolbar
 * @since       1.5
 */
class JToolbarButtonRedshopLink extends JButton
{
    /**
     * Button type
     * @var    string
     */
    protected $_name = 'RedshopLink';

    protected $joomlaSuffix = 'j3';

    /**
     * Fetch the HTML for the button
     *
     * @param   string  $type    Unused string.
     * @param   string  $name    Name to be used as apart of the id
     * @param   string  $text    Button text
     * @param   string  $url     The link url
     * @param   string  $target  Target open link
     *
     * @return  string  HTML string for the button
     *
     * @since   1.5
     */
    public function fetchButton($type = 'RedshopLink', $name = 'back', $text = '', $url = null, $target = '_self')
    {
        // Store all data to the options array for use with JLayout
        $options           = array();
        $options['text']   = JText::_($text);
        $options['class']  = $this->fetchIconClass($name);
        $options['doTask'] = $url;
        $options['target'] = $target;

        // Instantiate a new JLayoutFile instance and render the layout
        $layout = new RedshopLayoutFile('toolbar.redshoplink', null, array('suffixes' => array($this->joomlaSuffix)));

        return $layout->render($options);
    }

    /**
     * Get the button CSS Id
     *
     * @param   string  $type  The button type.
     * @param   string  $name  The name of the button.
     *
     * @return  string  Button CSS Id
     *
     * @since   1.5
     */
    public function fetchId($type = 'RedshopLink', $name = '')
    {
        return (method_exists($this, 'getParent') ? $this->getParent() : $this->_parent)->getName()
			. '-' . $name;
    }
}
