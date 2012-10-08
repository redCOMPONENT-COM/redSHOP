<?php
/**
 * @package     redSHOP
 * @subpackage  Views
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class RedshopViewAttribute_set_detail extends JViewLegacy
{
    public function display($tpl = null)
    {
        $option = JRequest::getVar('option');
        $lists  = array();

        $model = $this->getModel('attribute_set_detail');

        $attributes = $model->getattributes();

        JToolBarHelper::title(JText::_('COM_REDSHOP_ATTRIBUTE_SET_DETAIL'), 'redshop_attribute_bank48');

        $document = JFactory::getDocument();

        $document->addScriptDeclaration("

		var WANT_TO_DELETE = '" . JText::_('COM_REDSHOP_DO_WANT_TO_DELETE') . "';

		");

        $document->addScript('components/' . $option . '/assets/js/fields.js');

        $document->addScript('components/' . $option . '/assets/js/select_sort.js');

        $document->addScript('components/' . $option . '/assets/js/validation.js');

        $uri = JFactory::getURI();

        $detail = $this->get('data');

        $isNew = ($detail->attribute_set_id < 1);

        $text = $isNew ? JText::_('COM_REDSHOP_NEW') : JText::_('COM_REDSHOP_EDIT');

        JToolBarHelper::title(JText::_('COM_REDSHOP_ATTRIBUTE_SET') . ': <small><small>[ ' . $text . ' ]</small></small>', 'redshop_attribute_bank48');

        JToolBarHelper::apply();

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

        $lists['attributes'] = $attributes;

        $this->assignRef('model', $model);
        $this->assignRef('lists', $lists);
        $this->assignRef('detail', $detail);
        $this->request_url = $uri->toString();

        parent::display($tpl);
    }
}
