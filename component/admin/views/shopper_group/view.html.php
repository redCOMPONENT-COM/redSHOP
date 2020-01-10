<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewShopper_group extends RedshopViewAdmin
{
	/**
	 * @var  string
	 */
	public $filter;

	/**
	 * @var  array
	 */
	public $media;

	public function display($tpl = null)
	{
		global $context;

		$uri      = JUri::getInstance();
		$app      = JFactory::getApplication();
		$document = JFactory::getDocument();

		$document->setTitle(JText::_('COM_REDSHOP_SHOPPER_GROUP'));
		jimport('joomla.html.pagination');

		JToolBarHelper::title(JText::_('COM_REDSHOP_SHOPPER_GROUP_MANAGEMENT'), 'users redshop_manufact48');
		JToolbarHelper::addNew();
		JToolbarHelper::EditList();
		JToolBarHelper::deleteList();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();

		$state            = $this->get('State');
		$this->filter     = $state->get('filter');

		$filter_order     = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'shopper_group_id');
		$filter_order_Dir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');

		$lists['order']     = $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;

		$pagination = $this->get('Pagination');

		$this->user        = JFactory::getUser();
		$this->lists       = $lists;
		$this->media       = $this->get('Data');
		$this->pagination  = $pagination;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
