<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

jimport('joomla.html.pagination');

class RedshopViewAttributeprices extends RedshopViewAdmin
{
    public function display($tpl = null)
    {
        global $context;

        $app = JFactory::getApplication();

        $section_id = $app->input->get('section_id');
        $section    = $app->input->get('section');

        $document = JFactory::getDocument();
        $document->setTitle(Text::_('COM_REDSHOP_ATTRIBUTE_PRICE'));

        JToolBarHelper::title(Text::_('COM_REDSHOP_ATTRIBUTE_PRICE'), 'redshop_vatrates48');

        JToolbarHelper::addNew();
        JToolbarHelper::EditList();
        JToolBarHelper::deleteList();
        $uri = \Joomla\CMS\Uri\Uri::getInstance();

        $limitstart = $app->getUserStateFromRequest($context . 'limitstart', 'limitstart', '0');
        $limit      = $app->getUserStateFromRequest($context . 'limit', 'limit', '10');

        $total      = $this->get('Total');
        $data       = $this->get('Data');
        $pagination = new JPagination($total, $limitstart, $limit);

        $this->user        = Factory::getApplication()->getIdentity();
        $this->data        = $data;
        $this->section_id  = $section_id;
        $this->section     = $section;
        $this->pagination  = $pagination;
        $this->request_url = $uri->toString();

        parent::display($tpl);
    }
}