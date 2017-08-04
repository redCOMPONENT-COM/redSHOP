<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewCatalog_request extends RedshopViewAdmin
{
	public function display($tpl = null)
	{
		$context = "rating";

		$app = JFactory::getApplication();

		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_REDSHOP_CATALOG_REQUEST'));

		JToolBarHelper::title(JText::_('COM_REDSHOP_CATALOG_REQUEST_MANAGEMENT'), 'redshop_catalogmanagement48');
		JToolBarHelper::deleteList();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();

		$uri = JFactory::getURI();

		$filter_order     = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'catalog_user_id');
		$filter_order_Dir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');

		$lists['order'] = $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;
		$catalog = $this->get('Data');
		$pagination = $this->get('Pagination');


		$this->user = JFactory::getUser();
		$this->lists = $lists;
		$this->catalog = $catalog;
		$this->pagination = $pagination;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
