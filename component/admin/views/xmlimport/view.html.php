<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewXmlimport extends RedshopView
{
	public function display($tpl = null)
	{
		global $context;

		$uri      = JFactory::getURI();
		$app      = JFactory::getApplication();
		$document = JFactory::getDocument();

		$document->setTitle(JText::_('COM_REDSHOP_xmlimport'));
		$model = $this->getModel('xmlimport');

		JToolBarHelper::title(JText::_('COM_REDSHOP_XML_IMPORT_MANAGEMENT'), 'redshop_import48');
		JToolbarHelper::addNew();
		JToolbarHelper::EditList();
		JToolBarHelper::deleteList();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();

		$filter_order       = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'xmlimport_date');
		$filter_order_Dir   = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', 'DESC');

		$lists['order']     = $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;

		$data               = $this->get('Data');
		$total              = $this->get('Total');
		$pagination         = $this->get('Pagination');

		$this->lists        = $lists;
		$this->data         = $data;
		$this->pagination   = $pagination;
		$this->request_url  = $uri->toString();

		parent::display($tpl);
	}
}
