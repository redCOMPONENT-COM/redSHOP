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

class xmlexportViewxmlexport extends JView
{
	function display($tpl = null)
	{
		global $context;

		$uri      = JFactory::getURI();
		$app      = JFactory::getApplication();
		$document = JFactory::getDocument();

		$document->setTitle(JText::_('COM_REDSHOP_xmlexport'));
		$model = $this->getModel('xmlexport');

		JToolBarHelper::title(JText::_('COM_REDSHOP_XML_EXPORT_MANAGEMENT'), 'redshop_export48');
		JToolBarHelper::addNewX();
		JToolBarHelper::editListX();
		JToolBarHelper::deleteList();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();

		$filter_order     = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'xmlexport_date');
		$filter_order_Dir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', 'DESC');

		$lists['order']     = $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;

		$data       = $this->get('Data');
		$total      = $this->get('Total');
		$pagination = $this->get('Pagination');

		$this->lists = $lists;
		$this->data = $data;
		$this->pagination = $pagination;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
