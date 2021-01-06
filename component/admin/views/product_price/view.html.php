<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewProduct_price extends RedshopViewAdmin
{
    public function display($tpl = null)
    {
        $productId = JFactory::getApplication()->input->get('pid');

        $db       = JFactory::getDbo();
        $document = JFactory::getDocument();

        $document->setTitle(JText::_('COM_REDSHOP_PRODUCT_PRICE'));
        jimport('joomla.html.pagination');
        JToolBarHelper::title(JText::_('COM_REDSHOP_PRODUCT_PRICE'), 'redshop_vatrates48');

	    $query = $db->getQuery(true)
		    ->select('*')
		    ->from($db->qn('#__redshop_product'))
		    ->where($db->qn('product_id') . ' = ' . $db->q($productId));
	    $product = $db->setQuery($query)->loadObject();

	    $query = $db->getQuery(true)
		    ->select('g.*')
		    ->select('p.*')
		    ->select('p.price_id')
		    ->select('p.price_quantity_end')
		    ->select('p.price_quantity_start')
		    ->from($db->qn('#__redshop_shopper_group', 'g'))
		    ->leftjoin(
			    $db->qn('#__redshop_product_price', 'p') . ' ON ' . $db->qn('g.id') . ' = ' . $db->qn('p.shopper_group_id') . ' AND product_id' . ' = ' . $productId
		    );

	    $prices =  $db->setQuery($query)->loadObjectList();

        $this->product = $product;

        $this->prices = $prices;

        parent::display($tpl);
    }
}
