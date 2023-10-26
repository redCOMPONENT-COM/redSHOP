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

class RedshopViewProducttags_detail extends RedshopViewAdmin
{
    /**
     * Do we have to display a sidebar ?
     *
     * @var  boolean
     */
    protected $displaySidebar = false;

    public function display($tpl = null)
    {
        JToolBarHelper::title(Text::_('COM_REDSHOP_TAGS_MANAGEMENT_DETAIL'), 'redshop_textlibrary48');

        $uri = \Joomla\CMS\Uri\Uri::getInstance();

        $this->setLayout('default');

        $lists = array();

        $detail = $this->get('data');

        $isNew = ($detail->tags_id < 1);

        $text = $isNew ? Text::_('COM_REDSHOP_NEW') : Text::_('COM_REDSHOP_EDIT');

        JToolBarHelper::title(
            Text::_('COM_REDSHOP_TAGS') . ': <small><small>[ ' . $text . ' ]</small></small>',
            'redshop_textlibrary48'
        );

        JToolBarHelper::save();

        if ($isNew) {
            JToolBarHelper::cancel();
        } else {
            JToolBarHelper::cancel('cancel', Text::_('JTOOLBAR_CLOSE'));
        }

        $lists['published'] = JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $detail->published);

        $this->lists       = $lists;
        $this->detail      = $detail;
        $this->request_url = $uri->toString();

        parent::display($tpl);
    }
}
