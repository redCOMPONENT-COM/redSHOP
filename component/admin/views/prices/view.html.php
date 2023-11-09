<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

class RedshopViewPrices extends RedshopViewAdmin
{
    public function display($tpl = null)
    {
        global $context;

        $uri      = \Joomla\CMS\Uri\Uri::getInstance();
        $app      = JFactory::getApplication();
        $document = JFactory::getDocument();

        $document->setTitle(Text::_('COM_REDSHOP_PRODUCT_PRICE'));
        jimport('joomla.html.pagination');

        $detail = $this->get('data');
        $text   = $detail[0]->product_name;

        JToolBarHelper::title(
            Text::_('COM_REDSHOP_PRODUCT_MANAGEMENT_DETAIL') . ' <small>[ ' . $text . ' ]</small>',
            'pencil-2 redshop_products48'
        );

        JToolbarHelper::addNew();
        JToolbarHelper::EditList();
        JToolBarHelper::deleteList();
        JToolBarHelper::cancel('cancel', Text::_('JTOOLBAR_CLOSE'));

        $limitstart = $app->getUserStateFromRequest($context . 'limitstart', 'limitstart', '0');
        $limit      = $app->getUserStateFromRequest($context . 'limit', 'limit', '10');

        $total     = $this->get('Total');
        $media     = $this->get('Data');
        $productId = $this->get('ProductId');

        $pagination = new JPagination($total, (int) $limitstart, (int) $limit);
        $this->user = JFactory::getUser();

        $this->media       = $media;
        $this->product_id  = $productId;
        $this->pagination  = $pagination;
        $this->request_url = $uri->toString();

        parent::display($tpl);
    }
}