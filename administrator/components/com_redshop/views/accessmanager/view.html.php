<?php
/**
 * @package     redSHOP
 * @subpackage  Views
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class accessmanagerViewaccessmanager extends JViewLegacy
{
    public function display($tpl = null)
    {
        global $mainframe;

        JToolBarHelper::title(JText::_('COM_REDSHOP_ACCESS_MANAGER'), 'redshop_catalogmanagement48');
        if (ENABLE_BACKENDACCESS)
        {
            parent::display($tpl);
        }
        else
        {
            $msg = JText::_('COM_REDSHOP_PLEASE_ENABLE_ACCESS_MANAGER_FIRST');
            $mainframe->redirect('index.php?option=com_redshop&view=configuration', $msg);
        }
    }
}
