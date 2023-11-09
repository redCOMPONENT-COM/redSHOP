<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

class RedshopViewSample_request extends RedshopViewAdmin
{
    public function display($tpl = null)
    {
        $context = "request_id";

        $uri      = \Joomla\CMS\Uri\Uri::getInstance();
        $app      = JFactory::getApplication();
        $document = JFactory::getDocument();

        $document->setTitle(Text::_('COM_REDSHOP_CATALOG_SAMPLE'));

        JToolBarHelper::title(Text::_('COM_REDSHOP_PRODUCT_SAMPLE'), 'redshop_colorsample48');
        JToolBarHelper::deleteList();
        JToolBarHelper::publishList();
        JToolBarHelper::unpublishList();

        $filter_order     = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'request_id');
        $filter_order_Dir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');

        $lists['order']     = $filter_order;
        $lists['order_Dir'] = $filter_order_Dir;

        $catalog    = $this->get('Data');
        $pagination = $this->get('Pagination');

        $this->user        = Factory::getApplication()->getIdentity();
        $this->lists       = $lists;
        $this->catalog     = $catalog;
        $this->pagination  = $pagination;
        $this->request_url = $uri->toString();

        parent::display($tpl);
    }
}
