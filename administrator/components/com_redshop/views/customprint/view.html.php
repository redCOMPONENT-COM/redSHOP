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

class customprintViewcustomprint extends JView
{
    function display($tpl = null)
    {
        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_REDSHOP_CUSTOM_VIEWS'));

        $customviews = $this->get('Data');
        JToolBarHelper::title(JText::_('COM_REDSHOP_CUSTOM_VIEWS'), 'redshop_statistic48');

        $this->assignRef('customviews', $customviews);
        parent::display($tpl);
    }
}
