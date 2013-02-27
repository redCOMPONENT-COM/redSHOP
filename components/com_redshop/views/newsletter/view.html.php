<?php
/**
 * @package     redSHOP
 * @subpackage  Views
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die ('restricted access');

class newsletterViewnewsletter extends JViewLegacy
{
    public function display($tpl = null)
    {
        global $mainframe;

        $params = $mainframe->getParams('com_redshop');

        $pathway = $mainframe->getPathway();

        $pathway->addItem(JText::_('COM_REDSHOP_NEWSLETTER_SUBSCRIPTION'), '');

        $userdata = JRequest::getVar('userdata');
        $layout   = JRequest::getVar('layout');
        $user     = JFactory::getUser();

        $this->assignRef('user', $user);
        $this->assignRef('userdata', $userdata);
        $this->assignRef('params', $params);

        if ($layout == 'thankyou')
        {
            $this->setLayout('thankyou');
        }

        parent::display($tpl);
    }
}
