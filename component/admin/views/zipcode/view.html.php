<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;


class RedshopViewZipcode extends RedshopViewAdmin
{
	public function display($tpl = null)
	{
		jimport('joomla.html.pagination');

		$context = 'zipcode_id';

		$uri      = JFactory::getURI();
		$app      = JFactory::getApplication();
		$document = JFactory::getDocument();

		$document->setTitle(JText::_('COM_REDSHOP_ZIPCODE'));

		JToolBarHelper::title(JText::_('COM_REDSHOP_ZIPCODE_MANAGEMENT'), 'redshop_region_48');
		JToolbarHelper::addNew();
		JToolbarHelper::EditList();
		JToolbarHelper::deleteList();

		$filter_order     = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'zipcode_id');
		$filter_order_Dir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');

		$lists['order']     = $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;

		$fields     = $this->get('Data');
		$pagination = $this->get('Pagination');

		$this->user = JFactory::getUser();
		$this->pagination = $pagination;
		$this->fields = $fields;
		$this->lists = $lists;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
