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

class RedshopViewAttribute_set_detail extends RedshopViewAdmin
{
    /**
     * Do we have to display a sidebar ?
     *
     * @var  boolean
     */
    protected $displaySidebar = false;

    public function display($tpl = null)
    {
        $lists = array();

        $model = $this->getModel('attribute_set_detail');

        $attributes = $model->getattributes();

        JToolBarHelper::title(Text::_('COM_REDSHOP_ATTRIBUTE_SET_DETAIL'), 'redshop_attribute_bank48');
        $document = JFactory::getDocument();

        $document->addScriptDeclaration(
            "
			var WANT_TO_DELETE = '" . Text::_('COM_REDSHOP_DO_WANT_TO_DELETE') . "';
		"
        );

        HTMLHelper::script('com_redshop/redshop.attribute-manipulation.min.js', ['relative' => true]);
        HTMLHelper::script('com_redshop/redshop.fields.min.js', ['relative' => true]);
        HTMLHelper::script('com_redshop/redshop.validation.min.js', ['relative' => true]);

        $uri = JUri::getInstance();

        $detail = $this->get('data');

        $isNew = ($detail->attribute_set_id < 1);

        $text = $isNew ? Text::_('COM_REDSHOP_NEW') : Text::_('COM_REDSHOP_EDIT');

        JToolBarHelper::title(
            Text::_('COM_REDSHOP_ATTRIBUTE_SET') . ': <small><small>[ ' . $text . ' ]</small></small>',
            'redshop_attribute_bank48'
        );

        JToolBarHelper::apply();

        JToolBarHelper::save();

        if ($isNew) {
            JToolBarHelper::cancel();
        } else {
            JToolBarHelper::cancel('cancel', Text::_('JTOOLBAR_CLOSE'));
        }

        $lists['published']  = JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $detail->published);
        $lists['attributes'] = $attributes;

        $propOprand   = array();
        $propOprand[] = JHtml::_('select.option', '+', '+');
        $propOprand[] = JHtml::_('select.option', '-', '-');
        $propOprand[] = JHtml::_('select.option', '=', '=');
        $propOprand[] = JHtml::_('select.option', '*', '*');
        $propOprand[] = JHtml::_('select.option', '/', '/');

        $lists['prop_oprand'] = $propOprand;

        $this->model       = $model;
        $this->lists       = $lists;
        $this->detail      = $detail;
        $this->request_url = $uri->toString();

        parent::display($tpl);
    }
}