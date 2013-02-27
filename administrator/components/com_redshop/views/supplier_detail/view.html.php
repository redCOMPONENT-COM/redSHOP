<?php
/**
 * @package     redSHOP
 * @subpackage  Views
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class RedshopViewSupplier_detail extends JViewLegacy
{
    public function display($tpl = null)
    {
        require_once(JPATH_COMPONENT . DS . 'helpers' . DS . 'extra_field.php');

        JToolBarHelper::title(JText::_('COM_REDSHOP_SUPPLIER_MANAGEMENT_DETAIL'), 'redshop_manufact48');

        $uri = JFactory::getURI();

        $this->setLayout('default');

        $lists = array();

        $detail = $this->get('data');

        $isNew = ($detail->supplier_id < 1);

        $text = $isNew ? JText::_('COM_REDSHOP_NEW') : JText::_('COM_REDSHOP_EDIT');

        JToolBarHelper::title(JText::_('COM_REDSHOP_SUPPLIER') . ': <small><small>[ ' . $text . ' ]</small></small>', 'redshop_manufact48');

        JToolBarHelper::save();

        if ($isNew)
        {
            JToolBarHelper::cancel();
        }
        else
        {

            JToolBarHelper::cancel('cancel', 'Close');
        }

        $lists['published'] = JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $detail->published);

        $this->assignRef('lists', $lists);
        $this->assignRef('detail', $detail);
        $this->request_url = $uri->toString();

        parent::display($tpl);
    }
}
