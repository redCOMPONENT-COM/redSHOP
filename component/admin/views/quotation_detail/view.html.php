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
use Joomla\CMS\HTML\HTMLHelper;

class RedshopViewQuotation_detail extends RedshopViewAdmin
{
    /**
     * Do we have to display a sidebar ?
     *
     * @var  boolean
     */
    protected $displaySidebar = false;

    public function display($tpl = null)
    {
        $layout = JFactory::getApplication()->input->getCmd('layout', 'default');

        $document = JFactory::getDocument();
        $document->setTitle(Text::_('COM_REDSHOP_QUOTATION'));

        HTMLHelper::script('com_redshop/redshop.order.min.js', ['relative' => true]);
        HTMLHelper::script('com_redshop/redshop.admin.common.min.js', ['relative' => true]);
        HTMLHelper::script('com_redshop/json.min.js', ['relative' => true]);
        HTMLHelper::script('com_redshop/ajaxupload.min.js', ['relative' => true]);

        $uri   = \Joomla\CMS\Uri\Uri::getInstance();
        $lists = array();

        if ($layout != 'default') {
            $this->setLayout($layout);
        }

        $detail  = $this->get('data');
        $isNew   = ($detail->quotation_id < 1);
        $userarr = $this->get('userdata');

        $text = $isNew ? Text::_('COM_REDSHOP_NEW') : Text::_('COM_REDSHOP_EDIT');

        JToolBarHelper::title(
            Text::_('COM_REDSHOP_QUOTATION_DETAIL') . ': <small><small>[ ' . $text . ' ]</small></small>',
            'redshop_quotation48'
        );
        JToolBarHelper::apply();
        JToolBarHelper::save();
        JToolBarHelper::custom('send', 'send.png', 'send.png', Text::_('COM_REDSHOP_SEND'), false);

        if ($isNew) {
            JToolBarHelper::cancel();
        } else {
            JToolBarHelper::cancel('cancel', Text::_('JTOOLBAR_CLOSE'));
        }

        $status                    = RedshopHelperQuotation::getQuotationStatusList();
        $lists['quotation_status'] = JHTML::_(
            'select.genericlist',
            $status,
            'quotation_status',
            'class="inputbox" size="1" ',
            'value',
            'text',
            $detail->quotation_status
        );

        $this->lists         = $lists;
        $this->quotation     = $detail;
        $this->quotationuser = $userarr;
        $this->request_url   = $uri->toString();

        parent::display($tpl);
    }
}