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

class taxViewtax extends JView
{
	public function display($tpl = null)
	{
		global $context;

		$uri      = JFactory::getURI();
		$app      = JFactory::getApplication();
		$document = JFactory::getDocument();

		$document->setTitle(JText::_('COM_REDSHOP_TAX'));
		jimport('joomla.html.pagination');

		JToolBarHelper::title(JText::_('COM_REDSHOP_TAX_MANAGEMENT'), 'redshop_vat48');
		JToolBarHelper::addNewX();
		JToolBarHelper::editListX();
		JToolBarHelper::deleteList();

		$filter_order     = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'tax_rate_id');
		$filter_order_Dir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');
		$limitstart       = $app->getUserStateFromRequest($context . 'limitstart', 'limitstart', '0');
		$limit            = $app->getUserStateFromRequest($context . 'limit', 'limit', '10');

		$total        = $this->get('Total');
		$media        = $this->get('Data');
		$tax_group_id = $this->get('ProductId');

		$lists['order']        = $filter_order;
		$lists['order_Dir']    = $filter_order_Dir;
		$lists['tax_group_id'] = $tax_group_id;

		$pagination = new JPagination($total, $limitstart, $limit);

		$this->user = JFactory::getUser();
		$this->lists = $lists;
		$this->media = $media;
		$this->pagination = $pagination;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
