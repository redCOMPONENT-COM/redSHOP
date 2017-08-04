<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class RedshopViewTextlibrary extends RedshopViewAdmin
{
	public $state;

	public function display($tpl = null)
	{
		$uri      = JFactory::getURI();
		$document = JFactory::getDocument();

		$document->setTitle(JText::_('COM_REDSHOP_TEXTLIBRARY'));

		JToolBarHelper::title(JText::_('COM_REDSHOP_TEXTLIBRARY_MANAGEMENT'), 'redshop_textlibrary48');
		JToolbarHelper::addNew();
		JToolbarHelper::EditList();
		JToolBarHelper::custom('copy', 'copy.png', 'copy_f2.png', JText::_('COM_REDSHOP_TOOLBAR_COPY'), true);
		JToolBarHelper::deleteList();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();

		$this->state = $this->get('State');

		$optionsection = array();
		$optionsection[] = JHTML::_('select.option', '0', JText::_('COM_REDSHOP_SELECT'));
		$optionsection[] = JHTML::_('select.option', 'product', JText::_('COM_REDSHOP_Product'));
		$optionsection[] = JHTML::_('select.option', 'category', JText::_('COM_REDSHOP_Category'));
		$optionsection[] = JHTML::_('select.option', 'newsletter', JText::_('COM_REDSHOP_Newsletter'));

		$lists['section'] = JHTML::_('select.genericlist', $optionsection, 'section',
			'class="inputbox" size="1" onchange="document.adminForm.submit();" ', 'value', 'text', $this->state->get('section')
		);

		$lists['order']     = $this->state->get('list.ordering', 'textlibrary_id');
		$lists['order_Dir'] = $this->state->get('list.direction');

		$textlibrarys       = $this->get('Data');
		$pagination         = $this->get('Pagination');

		$this->user         = JFactory::getUser();
		$this->lists        = $lists;
		$this->textlibrarys = $textlibrarys;
		$this->pagination   = $pagination;
		$this->request_url  = $uri->toString();

		parent::display($tpl);
	}
}
