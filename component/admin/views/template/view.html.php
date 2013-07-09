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

class templateViewtemplate extends JView
{
	public function display($tpl = null)
	{
		$context = 'template_id';

		$uri      = JFactory::getURI();
		$app      = JFactory::getApplication();
		$document = JFactory::getDocument();

		$document->setTitle(JText::_('COM_REDSHOP_TEMPLATES'));

		JToolBarHelper::title(JText::_('COM_REDSHOP_TEMPLATES_MANAGEMENT'), 'redshop_templates48');

		JToolBarHelper::addNewX();
		JToolBarHelper::editListX();
		JToolBarHelper::customX('copy', 'copy.png', 'copy_f2.png', JText::_('COM_REDSHOP_TOOLBAR_COPY'), true);
		JToolBarHelper::deleteList();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();

		$context = 'template';

		$filter_order     = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'template_id');
		$filter_order_Dir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');
		$template_section = $app->getUserStateFromRequest($context . 'template_section', 'template_section', 0);

		$lists['order']     = $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;

		$templates  = $this->get('Data');
		$total      = $this->get('Total');
		$pagination = $this->get('Pagination');

		$redtemplate = new Redtemplate;
		$optionsection = $redtemplate->getTemplateSections();

		$lists['section'] = JHTML::_('select.genericlist', $optionsection, 'template_section',
			'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'value', 'text', $template_section
		);

		$this->user = JFactory::getUser();
		$this->lists = $lists;
		$this->templates = $templates;
		$this->pagination = $pagination;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
