<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewAccountgroup_detail extends RedshopViewAdmin
{
    /**
     * Do we have to display a sidebar ?
     *
     * @var  boolean
     */
    protected $displaySidebar = false;

    /**
     * @param null $tpl
     * @return mixed|void
     */
    public function display($tpl = null)
    {
        $uri = \JUri::getInstance();

        JToolBarHelper::save();
        JToolBarHelper::apply();

        $lists = array();
        $detail = $this->get('data');
        $isNew = ($detail->accountgroup_id < 1);

        $text = $isNew ? JText::_('COM_REDSHOP_NEW') : JText::_('COM_REDSHOP_EDIT');

        if ($isNew) {
            \JToolBarHelper::cancel();
        } else {
            \JToolBarHelper::cancel('cancel', JText::_('JTOOLBAR_CLOSE'));
        }

        \JToolBarHelper::title(JText::_('COM_REDSHOP_ECONOMIC_ACCOUNT_GROUP') . ': <small><small>[ ' . $text . ' ]</small></small>', 'redshop_accountgroup48');

        $lists['published'] = \JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $detail->published);

        $this->detail = $detail;
        $this->lists = $lists;
        $this->requestUrl = $uri->toString();

        parent::display($tpl);
    }
}
