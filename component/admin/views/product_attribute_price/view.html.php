<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

class RedshopViewProduct_attribute_price extends RedshopViewAdmin
{
    public function display($tpl = null)
    {
        $db         = JFactory::getDbo();
        $jinput     = JFactory::getApplication()->input;
        $section_id = $jinput->get('section_id');
        $section    = $jinput->get('section');
        $cid        = $jinput->get('cid');
        $uri        = \Joomla\CMS\Uri\Uri::getInstance();
        $document   = JFactory::getDocument();

        $document->setTitle(Text::_('COM_REDSHOP_PRODUCT_PRICE'));
        jimport('joomla.html.pagination');
        JToolBarHelper::title(Text::_('COM_REDSHOP_PRODUCT_PRICE'), 'redshop_vatrates48');

        $sql = "SELECT * FROM #__redshop_product WHERE product_id = '$cid'";
        $db->setQuery($sql);
        $product = $db->loadObject();

        $sql = "SELECT g.*,p.product_price,p.price_id,p.price_quantity_end,p.price_quantity_start 
                FROM #__redshop_shopper_group g 
                LEFT JOIN #__redshop_product_attribute_price p 
                ON g.id = p.shopper_group_id 
                AND section_id = '$section_id'";
        $db->setQuery($sql);

        $prices = $db->loadObjectList();
        $uri    = \Joomla\CMS\Uri\Uri::getInstance();

        $this->product = $product;

        $this->prices = $prices;

        $this->section_id = $section_id;
        $this->section    = $section;
        $this->cid        = $cid;

        $this->request_url = $uri->toString();

        parent::display($tpl);
    }
}