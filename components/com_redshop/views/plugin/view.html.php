<?php
/**
 * @package     redSHOP
 * @subpackage  Views
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die ('restricted access');

class pluginViewplugin extends JViewLegacy
{
    function display($tpl = null)
    {
        ob_clean();
        parent::display($tpl);
    }
}
