<?php
/**
 * @package     redSHOP
 * @subpackage  Views
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'helpers' . DS . 'category.php');

class product_miniViewproduct_mini extends JViewLegacy
{
    public function display($tpl = null)
    {
        global $mainframe, $context;

        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_REDSHOP_PRODUCT'));

        $uri = JFactory::getURI();

        $filter_order     = $mainframe->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'product_id');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');
        //$limitstart     = $mainframe->getUserStateFromRequest( $context.'limitstart',      'limitstart', 	  '0' );
        //$limit = $mainframe->getUserStateFromRequest( $context.'limit',  'limit', '10' );

        $search_field = $mainframe->getUserStateFromRequest($context . 'search_field', 'search_field', '');
        $keyword      = $mainframe->getUserStateFromRequest($context . 'keyword', 'keyword', '');
        $category_id  = $mainframe->getUserStateFromRequest($context . 'category_id', 'category_id', '');

        $product_category = new product_category();
        $categories       = $product_category->getCategoryListArray();

        $temps                   = array();
        $temps[0]->category_id   = "0";
        $temps[0]->category_name = JText::_('COM_REDSHOP_SELECT');
        $categories              = @array_merge($temps, $categories);

        $lists['category'] = JHTML::_('select.genericlist', $categories, 'category_id', 'class="inputbox" onchange="document.adminForm2.submit();"      ', 'category_id', 'category_name', $category_id);

        $lists['order']     = $filter_order;
        $lists['order_Dir'] = $filter_order_Dir;
        $products           = $this->get('Data');

        $pagination = $this->get('Pagination');

        $this->assignRef('keyword', $keyword);
        $this->assignRef('search_field', $search_field);
        $this->assignRef('user', JFactory::getUser());
        $this->assignRef('lists', $lists);
        $this->assignRef('products', $products);
        $this->assignRef('pagination', $pagination);
        $this->assignRef('request_url', $uri->toString());
        parent::display($tpl);
    }
}

