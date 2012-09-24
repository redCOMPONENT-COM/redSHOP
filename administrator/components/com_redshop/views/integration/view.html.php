<?php
/**
 * @package     redSHOP
 * @subpackage  Views
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');
class integrationViewintegration extends JView
{
    function display($tpl = null)
    {
        JToolBarHelper::title(JText::_('COM_REDSHOP_INTEGRATION'), 'redshop_products48');

        $task = JRequest :: getVar('task');

        switch ($task)
        {
            case 'googlebase' :
                $tpl = 'gbase';
                break;
        }
        parent::display($tpl);
    }
}
