<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;



class RedshopViewShopper_group extends RedshopViewAdmin
{
	public function display($tpl = null)
	{
		global $context;

		$shoppergroup = new shoppergroup;

		$uri      = JFactory::getURI();
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

		$filter_order       = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'shopper_group_id');
		$filter_order_Dir   = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');

		$lists['order']     = $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;

		$groups             = $shoppergroup->getshopperGroupListArray();

		$pagination         = $this->get('Pagination');

		$this->user         = JFactory::getUser();
		$this->lists        = $lists;
		$this->media        = $groups;
		$this->pagination   = $pagination;
		$this->request_url  = $uri->toString();

		parent::display($tpl);
	}
}
