<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


jimport('joomla.application.component.view');

class textlibraryViewtextlibrary extends JView
{
	public function display($tpl = null)
	{
		$context = 'textlibrary_id';

		$uri      = JFactory::getURI();
		$app      = JFactory::getApplication();
		$document = JFactory::getDocument();

		$document->setTitle(JText::_('COM_REDSHOP_TEXTLIBRARY'));

		JToolBarHelper::title(JText::_('COM_REDSHOP_TEXTLIBRARY_MANAGEMENT'), 'redshop_textlibrary48');
		JToolBarHelper::addNewX();
		JToolBarHelper::editListX();
		JToolBarHelper::customX('copy', 'copy.png', 'copy_f2.png', JText::_('COM_REDSHOP_TOOLBAR_COPY'), true);
		JToolBarHelper::deleteList();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();

		$filter_order     = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'textlibrary_id');
		$filter_order_Dir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');

		$section = $app->getUserStateFromRequest($context . 'section', 'section', 0);

		$optionsection = array();
		$optionsection[] = JHTML::_('select.option', '0', JText::_('COM_REDSHOP_SELECT'));
		$optionsection[] = JHTML::_('select.option', 'product', JText::_('COM_REDSHOP_Product'));
		$optionsection[] = JHTML::_('select.option', 'category', JText::_('COM_REDSHOP_Category'));
		$optionsection[] = JHTML::_('select.option', 'newsletter', JText::_('COM_REDSHOP_Newsletter'));

		$lists['section'] = JHTML::_('select.genericlist', $optionsection, 'section',
			'class="inputbox" size="1" onchange="document.adminForm.submit();" ', 'value', 'text', $section
		);

		$lists['order']     = $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;

		$textlibrarys       = $this->get('Data');
		$total              = $this->get('Total');
		$pagination         = $this->get('Pagination');

		$this->user         = JFactory::getUser();
		$this->lists        = $lists;
		$this->textlibrarys = $textlibrarys;
		$this->pagination   = $pagination;
		$this->request_url  = $uri->toString();

		parent::display($tpl);
	}
}
