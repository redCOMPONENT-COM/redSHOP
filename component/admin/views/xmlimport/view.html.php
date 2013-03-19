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

class xmlimportViewxmlimport extends JView
{
	public function display($tpl = null)
	{
		global $mainframe, $context;

		$document = & JFactory::getDocument();
		$document->setTitle(JText::_('COM_REDSHOP_xmlimport'));
		$model = $this->getModel('xmlimport');

		JToolBarHelper::title(JText::_('COM_REDSHOP_XML_IMPORT_MANAGEMENT'), 'redshop_import48');
		JToolBarHelper::addNewX();
		JToolBarHelper::editListX();
		JToolBarHelper::deleteList();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();

		$uri =& JFactory::getURI();
		$filter_order = $mainframe->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'xmlimport_date');
		$filter_order_Dir = $mainframe->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', 'DESC');

		$lists['order'] = $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;

		$data = & $this->get('Data');
		$total = & $this->get('Total');
		$pagination = & $this->get('Pagination');

		$this->assignRef('lists', $lists);
		$this->assignRef('data', $data);
		$this->assignRef('pagination', $pagination);
		$this->assignRef('request_url', $uri->toString());

		parent::display($tpl);
	}
}
