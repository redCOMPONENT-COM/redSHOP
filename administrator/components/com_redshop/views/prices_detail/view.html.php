<?php
/**
 * @package     redSHOP
 * @subpackage  Views
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

class prices_detailVIEWprices_detail extends JViewLegacy
{
    public function display($tpl = null)
    {
        $db = JFactory::getDBO();
        JToolBarHelper::title(JText::_('COM_REDSHOP_PRICE_MANAGEMENT_DETAIL'), 'redshop_vatrates48');
        $uri = JFactory::getURI();

        $this->setLayout('default');

        $lists  = array();
        $detail = $this->get('data');

        $isNew = ($detail->price_id < 1);
        $text  = $isNew ? JText::_('COM_REDSHOP_NEW') : JText::_('COM_REDSHOP_EDIT');

        JToolBarHelper::title(JText::_('COM_REDSHOP_PRICE') . ': <small><small>[ ' . $text . ' ]</small></small>', 'redshop_vatrates48');
        JToolBarHelper::save();

        if ($isNew)
        {
            JToolBarHelper::cancel();
        }
        else
        {
            JToolBarHelper::cancel('cancel', 'Close');
        }

        $lists['product_id']   = $detail->product_id;
        $lists['product_name'] = $detail->product_name;

        $q = 'SELECT shopper_group_id AS value,shopper_group_name AS text ' . 'FROM #__' . TABLE_PREFIX . '_shopper_group';
        $db->setQuery($q);
        $shoppergroup = $db->loadObjectList();

        $lists['shopper_group_name'] = JHTML::_('select.genericlist', $shoppergroup, 'shopper_group_id', 'class="inputbox" size="1"', 'value', 'text', $detail->shopper_group_id);

        $this->assignRef('lists', $lists);
        $this->assignRef('detail', $detail);
        $this->request_url = $uri->toString();
        parent::display($tpl);
    }
}
