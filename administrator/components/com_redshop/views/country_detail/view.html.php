<?php
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class country_detailVIEWcountry_detail extends JView
{
    function display ($tpl = null)
    {
        JToolBarHelper::title(JText::_('COM_REDSHOP_COUNTRY_MANAGEMENT'), 'redshop_country_48');

        $uri = JFactory::getURI();
        JToolBarHelper::save();
        JToolBarHelper::apply();
        $lists  = array();
        $detail = $this->get('data');
        $isNew  = ($detail->country_id < 1);
        $text   = $isNew ? JText::_('COM_REDSHOP_NEW') : JText::_('COM_REDSHOP_EDIT');
        if ($isNew) {
            JToolBarHelper::cancel();
        } else {

            JToolBarHelper::cancel('cancel', 'Close');
        }
        JToolBarHelper::title(JText::_('COM_REDSHOP_COUNTRY') . ': <small><small>[ ' . $text . ' ]</small></small>', 'redshop_country_48');

        $this->assignRef('detail', $detail);
        $this->assignRef('lists', $lists);
        $this->assignRef('request_url', $uri->toString());

        parent::display($tpl);
    }
}
