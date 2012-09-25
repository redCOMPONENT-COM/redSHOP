<?php
/**
 * @package     redSHOP
 * @subpackage  Views
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class zip_importViewzip_import extends JViewLegacy
{
    function display($tpl = null)
    {
        $layout = JRequest::getVar('layout');
        if ($layout == 'confirmupdate')
        {
            $this->setLayout('confirmupdate');
        }
        else
        {
            /* Load the data to export */
            $result = $this->get('Data');
            $this->assignRef('result', $result);
        }
        parent::display($tpl);
    }
}

