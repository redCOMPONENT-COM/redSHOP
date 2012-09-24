<?php
/**
 * @package     redSHOP
 * @subpackage  Views
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class questionViewquestion extends JViewLegacy
{
    function display($tpl = null)
    {
        global $mainframe, $context;
        $context  = 'question_id';
        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_REDSHOP_question'));
        $model = $this->getModel('question');

        JToolBarHelper::title(JText::_('COM_REDSHOP_QUESTION_MANAGEMENT'), 'redshop_question48');
        JToolBarHelper::addNewX();
        JToolBarHelper::editListX();
        JToolBarHelper::deleteList();
        JToolBarHelper::publishList();
        JToolBarHelper::unpublishList();

        $uri = JFactory::getURI();

        $filter_order     = $mainframe->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'question_date');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', 'DESC');
        $product_id       = $mainframe->getUserStateFromRequest($context . 'product_id', 'product_id', 0);

        $lists['order']     = $filter_order;
        $lists['order_Dir'] = $filter_order_Dir;

        $question   = $this->get('Data');
        $pagination = $this->get('Pagination');

        $option                         = $model->getProduct();
        $optionsection                  = array();
        $optionsection[0]               = new stdClass;
        $optionsection[0]->product_id   = 0;
        $optionsection[0]->product_name = JText::_('COM_REDSHOP_SELECT');
        if (count($option) > 0)
        {
            $optionsection = @array_merge($optionsection, $option);
        }
        $lists['product_id'] = JHTML::_('select.genericlist', $optionsection, 'product_id', 'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'product_id', 'product_name', $product_id);

        $this->assignRef('lists', $lists);
        $this->assignRef('question', $question);
        $this->assignRef('pagination', $pagination);
        $this->request_url = $uri->toString();

        parent::display($tpl);
    }
}
