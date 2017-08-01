<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.html.pagination');

class RedshopViewAccountgroup extends RedshopViewAdmin
{
	public function display($tpl = null)
	{
		global $context;

		$app = JFactory::getApplication();

		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_REDSHOP_ECONOMIC_ACCOUNT_GROUP'));

		JToolBarHelper::title(JText::_('COM_REDSHOP_ECONOMIC_ACCOUNT_GROUP'), 'redshop_accountgroup48');
		JToolbarHelper::addNew();
		JToolbarHelper::EditList();
		JToolbarHelper::deleteList();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();

		$uri                = JFactory::getURI();

		$filter_order       = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'accountgroup_id');
		$filter_order_Dir   = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');
		$lists['order']     = $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;

		$detail             = $this->get('Data');
		$pagination         = $this->get('Pagination');

		$this->pagination   = $pagination;
		$this->detail       = $detail;
		$this->lists        = $lists;
		$this->request_url  = $uri->toString();

		parent::display($tpl);
	}
}
