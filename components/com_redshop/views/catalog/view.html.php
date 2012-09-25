<?php
/**
 * @package     redSHOP
 * @subpackage  Views
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die ('restricted access');

class catalogViewcatalog extends JViewLegacy
{
    function display($tpl = null)
    {
        global $mainframe;

        $params = $mainframe->getParams('com_redshop');
        $layout = JRequest::getVar('layout');
        if ($layout == "sample")
        {
            $this->setLayout('sample');
        }
        $this->assignRef('params', $params);
        parent::display($tpl);
    }
}

