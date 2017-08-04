<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewTemplate extends RedshopViewAdmin
{
	public $state;

	public function display($tpl = null)
	{
		$uri      = JFactory::getURI();
		$document = JFactory::getDocument();

		$document->setTitle(JText::_('COM_REDSHOP_TEMPLATES'));

		JToolBarHelper::title(JText::_('COM_REDSHOP_TEMPLATES_MANAGEMENT'), 'redshop_templates48');

		JToolbarHelper::addNew();
		JToolbarHelper::EditList();
		JToolBarHelper::custom('copy', 'copy.png', 'copy_f2.png', JText::_('COM_REDSHOP_TOOLBAR_COPY'), true);
		JToolBarHelper::deleteList();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();

		$this->state = $this->get('State');
		$lists['order']     = $this->state->get('list.ordering', 'template_id');
		$lists['order_Dir'] = $this->state->get('list.direction');

		$templates  = $this->get('Data');
		$pagination = $this->get('Pagination');

		$redtemplate = Redtemplate::getInstance();
		$optionsection = $redtemplate->getTemplateSections();

		$lists['section'] = JHTML::_('select.genericlist', $optionsection, 'template_section',
			'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'value', 'text', $this->state->get('template_section')
		);

		$this->user = JFactory::getUser();
		$this->lists = $lists;
		$this->templates = $templates;
		$this->pagination = $pagination;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
