<?php
/**
 * @package     redSHOP
 * @subpackage  Views
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class RedshopViewPrices extends JViewLegacy
{
    public function display($tpl = null)
    {
        global $context;

        $app = JFactory::getApplication();

        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_REDSHOP_PRODUCT_PRICE'));
        jimport('joomla.html.pagination');
        JToolBarHelper::title(JText::_('COM_REDSHOP_PRODUCT_PRICE'), 'redshop_vatrates48');

        JToolBarHelper::addNewX();
        JToolBarHelper::editListX();
        JToolBarHelper::deleteList();
        $uri = JFactory::getURI();

        $limitstart = $app->getUserStateFromRequest($context . 'limitstart', 'limitstart', '0');
        $limit      = $app->getUserStateFromRequest($context . 'limit', 'limit', '10');

        $total      = $this->get('Total');
        $media      = $this->get('Data');
        $product_id = $this->get('ProductId');
        $pagination = new JPagination($total, $limitstart, $limit);

        $this->user = JFactory::getUser();
        $this->assignRef('lists', $lists);
        $this->assignRef('media', $media);
        $this->assignRef('product_id', $product_id);
        $this->assignRef('pagination', $pagination);
        $this->request_url = $uri->toString();

        parent::display($tpl);
    }
}
