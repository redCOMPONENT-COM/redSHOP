<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license   GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 *            Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
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
