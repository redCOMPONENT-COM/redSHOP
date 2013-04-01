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
jimport('joomla.html.pagination');

class attributepricesViewattributeprices extends JView
{
	public function display($tpl = null)
	{
		global $context;

		$app = JFactory::getApplication();

		$section_id = JRequest::getVar('section_id');
		$section = JRequest::getVar('section');

		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_REDSHOP_ATTRIBUTE_PRICE'));

		JToolBarHelper::title(JText::_('COM_REDSHOP_ATTRIBUTE_PRICE'), 'redshop_vatrates48');

		JToolBarHelper::addNewX();
		JToolBarHelper::editListX();
		JToolBarHelper::deleteList();
		$uri = JFactory::getURI();

		$limitstart = $app->getUserStateFromRequest($context . 'limitstart', 'limitstart', '0');
		$limit      = $app->getUserStateFromRequest($context . 'limit', 'limit', '10');

		$total = $this->get('Total');
		$data = $this->get('Data');
		$pagination = new JPagination($total, $limitstart, $limit);

		$this->assignRef('user', JFactory::getUser());
		$this->assignRef('lists', $lists);
		$this->assignRef('data', $data);
		$this->assignRef('section_id', $section_id);
		$this->assignRef('section', $section);
		$this->assignRef('pagination', $pagination);
		$this->assignRef('request_url', $uri->toString());

		parent::display($tpl);
	}
}
