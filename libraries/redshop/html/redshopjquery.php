<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  HTML
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;
/**
 * jQuery HTML class.
 *
 * @package     RedSHOP.Library
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
     * Load the flexslider library.
     *
     * @param   string  $selector  CSS Selector to initalise selects
     * @param   array   $options   Optional array with options
     *
     * @return void
     */
    public static function flexslider($selector = '.flexslider', $options = null)
    {
        // Only load once
        if (isset(static::$loaded[__METHOD__][$selector])) {
            return;
        }

        self::framework();

        HTMLHelper::script('com_redshop/flexslider.min.js', ['relative' => true]);

        $options = static::options2Jregistry($options);

        JFactory::getDocument()->addScriptDeclaration(
            "
            (function($){
                $(document).ready(function () {
                    $('" . $selector . "').flexslider(" . $options->toString() . ");
                });
            })(jQuery);
        "
        );
        static::$loaded[__METHOD__][$selector] = true;

        return;
    }

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
        if (!empty(static::$loaded[__METHOD__])) {
            return;
        }

        if ((version_compare(JVERSION, '4.0', '<'))) {
            JHtml::_('jquery.framework', $noConflict, $debug, $migrate);
        } else {
            JHtml::_('bootstrap.framework', $noConflict, $debug, $migrate);
        }

        static::$loaded[__METHOD__] = true;
    }

    /**
     * Function to receive & pre-process javascript options
     *
     * @param   mixed  $options  Associative array/JRegistry object with options
     *
     * @return  JRegistry        Options converted to JRegistry object
     */
    private static function options2Jregistry($options)
    {
        // Support options array
        if (is_array($options)) {
            $options = new JRegistry($options);
        }

        if (!($options instanceof JRegistry)) {
            $options = new JRegistry;
        }

        return $options;
    }

    /**
     * Load bootstrap radio button style
     *
     * @param   string  $selector  CSS Selector to initalise selects
     *
     * @return void
     */
    public static function radioButton($selector = '.btn-group')
    {
        // Only load once
        if (isset(static::$loaded[__METHOD__])) {
            return;
        }

        static::$loaded[__METHOD__] = true;

        self::framework();

        JFactory::getDocument()->addScriptDeclaration(
            "
        (function($)
        {
            $(document).ready(function()
            {
                $('.radio" . $selector . " label').addClass('btn');
                $('" . $selector . " label:not(.active)').click(function()
                {
                    var label = $(this);
                    var input = $('#' + label.attr('for'));

                    if (!input.prop('checked')) {
                        label.closest('" . $selector . "').find('label').removeClass('active btn-success btn-danger btn-primary');
                        if (input.val() == '') {
                            label.addClass('active btn-primary');
                        } else if (input.val() == 0) {
                            label.addClass('active btn-danger');
                        } else {
                            label.addClass('active btn-success');
                        }
                        input.prop('checked', true);
                        input.trigger('change');
                    }
                });
                $('" . $selector . " input[checked=checked]').each(function()
                {
                    if ($(this).val() == '') {
                        $('label[for=' + $(this).attr('id') + ']').addClass('active btn-primary');
                    } else if ($(this).val() == 0) {
                        $('label[for=' + $(this).attr('id') + ']').addClass('active btn-danger');
                    } else {
                        $('label[for=' + $(this).attr('id') + ']').addClass('active btn-success');
                    }
                });
            });
        })(jQuery);
        "
        );

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
        if (isset(static::$loaded[__METHOD__][$selector])) {
            return;
        }

        self::framework();

        HTMLHelper::script('com_redshop/select2.min.js', ['relative' => true]);
        HTMLHelper::stylesheet('com_redshop/select2/select2.css', ['relative' => true]);

        if (version_compare(JVERSION, '3.0', '>=')) {
            HTMLHelper::stylesheet('com_redshop/select2/select2-bootstrap.css', ['relative' => true]);
        }

        $prefix = '';

        if (isset($options['multiple']) && $options['multiple'] == 'true') {
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

                    $('" . $selector . "').on(\"select2-removed\", function(e) {
                        if ($(this).val() == null) {
                            $(this).val(\"\").trigger(\"change\");
                        }
                    });
                });
            })(jQuery);
        ";

        if ($getInitTemplate) {
            echo $initTemplate;
        } else {
            JFactory::getDocument()->addScriptDeclaration($initTemplate);
        }

        static::$loaded[__METHOD__][$selector] = true;

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
        if (!empty(static::$loaded[__METHOD__])) {
            return;
        }

        HTMLHelper::stylesheet('com_redshop/jquery-ui/jquery-ui.min.css', ['relative' => true]);
        self::framework();

        HTMLHelper::script('com_redshop/jquery-ui.min.js', ['relative' => true]);

        // Check includes and remove core joomla jquery.ui script only for Joomla 3
        if (version_compare(JVERSION, '4.0', '<'))
        {
            JHtml::_('jquery.ui', array('core'));

            $document = JFactory::getDocument();
            $headData = $document->getHeadData();

            if (isset($headData['scripts'][JUri::root(true) . '/media/jui/js/jquery.ui.core.min.js'])) {
                unset($headData['scripts'][JUri::root(true) . '/media/jui/js/jquery.ui.core.min.js']);
            }

            if (JFactory::getConfig()->get('debug')) {
                if (isset($headData['scripts'][JUri::root(true) . '/media/jui/js/jquery.ui.core.js'])) {
                    unset($headData['scripts'][JUri::root(true) . '/media/jui/js/jquery.ui.core.js']);
                }
            }

            $document->setHeadData($headData);
        }

        static::$loaded[__METHOD__] = true;
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
        $options = static::options2Jregistry($options);

        $options->def('width', 'resolve');
        $options->def('formatNoMatches', 'function () { return "' . Text::_("LIB_REDSHOP_SELECT2_NO_MATHES") . '"; }');
        $options->def(
            'formatInputTooShort',
            'function (input, min) { var n = min - input.length; return "'
            . Text::_("LIB_REDSHOP_SELECT2_INPUT_TO_SHORT") . '" + (n == 1? "" : "' . Text::_(
                "LIB_REDSHOP_SELECT2_PREFIX"
            ) . '"); }'
        );
        $options->def(
            'formatInputTooLong',
            'function (input, max) { var n = input.length - max; return "'
            . Text::_("LIB_REDSHOP_SELECT2_TO_LONG") . '" + (n == 1? "" : "' . Text::_(
                "LIB_REDSHOP_SELECT2_PREFIX"
            ) . '"); }'
        );
        $options->def(
            'formatSelectionTooBig',
            'function (limit) { return "'
            . Text::_("LIB_REDSHOP_SELECT2_TO_BIG") . '" + (limit == 1 ? "" : "' . Text::_(
                "LIB_REDSHOP_SELECT2_PREFIX"
            ) . '"); }'
        );
        $options->def(
            'formatLoadMore',
            'function (pageNumber) { return "' . Text::_("LIB_REDSHOP_SELECT2_LOAD_MORE") . '"; }'
        );
        $options->def('formatSearching', 'function () { return "' . Text::_("LIB_REDSHOP_SELECT2_SEARCHING") . '"; }');

        $return    = array();
        $functions = array(
            'ajax',
            'initSelection',
            'formatNoMatches',
            'formatInputTooShort',
            'formatInputTooLong',
            'formatSelectionTooBig',
            'formatLoadMore',
            'formatSearching',
            'escapeMarkup',
            'multiple',
            'allowClear'
        );
        $exclude   = array('events');

        foreach ($options->toArray() as $key => $option) {
            if (in_array($key, $exclude)) {
                continue;
            }

            if (!in_array($key, $functions)) {
                $option = '"' . $option . '"';
            }

            $return[] = $key . ':' . $option;
        }

        return '{' . implode(', ', $return) . '}';
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

        if (isset($options['events']) && is_array($options['events'])) {
            foreach ($options['events'] as $key => $event) {
                $result .= ".on('" . $key . "', " . $event . ")";
            }
        }

        return $result;
    }

    /**
     * Add javascript support for Bootstrap popovers
     *
     * Use element's Title as popover content
     *
     * @param   string  $selector                       Selector for the popover
     * @param   array   $params                         An array of options for the popover.
     *                                                  Options for the popover can be:
     *                                                  animation  boolean          apply a css fade transition to the popover
     *                                                  html       boolean          Insert HTML into the popover. If false, jQuery's text method will be used to insert
     *                                                  content into the dom.
     *                                                  placement  string|function  how to position the popover - top | bottom | left | right
     *                                                  selector   string           If a selector is provided, popover objects will be delegated to the specified targets.
     *                                                  trigger    string           how popover is triggered - hover | focus | manual
     *                                                  title      string|function  default title value if `title` tag isn't present
     *                                                  content    string|function  default content value if `data-content` attribute isn't present
     *                                                  delay      number|object    delay showing and hiding the popover (ms) - does not apply to manual trigger type
     *                                                  If a number is supplied, delay is applied to both hide/show
     *                                                  Object structure is: delay: { show: 500, hide: 100 }
     *                                                  container  string|boolean   Appends the popover to a specific element: { container: 'body' }
     *
     * @return  void
     */
    public static function popover($selector = '.hasPopover', $params = array())
    {
        // Only load once
        if (isset(static::$loaded[__METHOD__][$selector])) {
            return;
        }

        // Include Bootstrap framework
        static::framework();

        $opt['animation'] = isset($params['animation']) ? $params['animation'] : true;
        $opt['html']      = isset($params['html']) ? $params['html'] : true;
        $opt['placement'] = isset($params['placement']) ? $params['placement'] : null;
        $opt['selector']  = isset($params['selector']) ? $params['selector'] : null;
        $opt['title']     = isset($params['title']) ? $params['title'] : null;
        $opt['trigger']   = isset($params['trigger']) ? $params['trigger'] : 'hover focus';
        $opt['content']   = isset($params['content']) ? $params['content'] : null;
        $opt['delay']     = isset($params['delay']) ? $params['delay'] : null;
        $opt['container'] = isset($params['container']) ? $params['container'] : 'body';

        $options = json_encode(array_filter($opt));

        // Attach the popover to the document
        JFactory::getDocument()->addScriptDeclaration(
            "jQuery(document).ready(function()
            {
                jQuery('" . $selector . "').popover(" . $options . ");
            });"
        );

        static::$loaded[__METHOD__][$selector] = true;

        return;
    }
}
