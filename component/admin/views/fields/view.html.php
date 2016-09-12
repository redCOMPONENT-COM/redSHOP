<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewFields extends RedshopViewAdmin
{
	public $state;

	public function display($tpl = null)
	{
		$document = JFactory::getDocument();

		$document->setTitle(JText::_('COM_REDSHOP_FIELDS'));

		JToolBarHelper::title(JText::_('COM_REDSHOP_FIELDS_MANAGEMENT'), 'redshop_fields48');
		JToolbarHelper::addNew();
		JToolbarHelper::EditList();
		JToolBarHelper::deleteList();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();

		$fields        = $this->get('Data');
		$pagination    = $this->get('Pagination');

		$redtemplate = Redtemplate::getInstance();
		$optiontype    = $redtemplate->getFieldTypeSections();
		$optionsection = $redtemplate->getFieldSections();
		$this->state = $this->get('State');

		$filtertype      = $this->state->get('filtertype');
		$filtersection    = $this->state->get('filtersection');

		$lists['order'] = $this->state->get('list.ordering', 'ordering');
		$lists['order_Dir'] = $this->state->get('list.direction');

		$lists['type'] = JHTML::_('select.genericlist', $optiontype, 'filtertype',
			'class="inputbox" size="1" onchange="document.adminForm.submit();" ',
			'value', 'text', $filtertype
		);
		$lists['section'] = JHTML::_('select.genericlist', $optionsection, 'filtersection',
			'class="inputbox" size="1" onchange="document.adminForm.submit();"',
			'value', 'text', $filtersection
		);

		$this->lists = $lists;
		$this->fields = $fields;
		$this->pagination = $pagination;

		parent::display($tpl);
	}
}
