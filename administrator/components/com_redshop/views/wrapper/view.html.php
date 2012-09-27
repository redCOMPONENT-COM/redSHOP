<?php
/**
 * @package     redSHOP
 * @subpackage  Views
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

jimport('joomla.html.pagination');

class wrapperViewwrapper extends JViewLegacy
{
    public function display($tpl = null)
    {
        $product_id = JRequest::getVar('product_id');

        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_REDSHOP_WRAPPER'));

        $data = $this->get('Data');

        JToolBarHelper::title(JText::_('COM_REDSHOP_WRAPPER'), 'redshop_wrapper48');

        JToolBarHelper::addNewX();

        JToolBarHelper::deleteList();
        JToolBarHelper::publishList();
        JToolBarHelper::unpublishList();

        $pagination = $this->get('Pagination');
        $uri        = JFactory::getURI();

        $this->user = JFactory::getUser();
        $this->assignRef('lists', $lists);
        $this->assignRef('data', $data);
        $this->assignRef('product_id', $product_id);
        $this->assignRef('pagination', $pagination);
        $this->request_url = $uri->toString();

        parent::display($tpl);
    }
}

