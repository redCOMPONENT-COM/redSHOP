<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewTax_group extends RedshopViewAdmin
{
	public function display($tpl = null)
	{
		global $context;

		$uri      = JFactory::getURI();
		$app      = JFactory::getApplication();
		$document = JFactory::getDocument();

		$document->setTitle(JText::_('COM_REDSHOP_TAX'));
		jimport('joomla.html.pagination');

		JToolBarHelper::title(JText::_('COM_REDSHOP_TAX_GROUP_MANAGEMENT'), 'tags redshop_vatgroup48');
		JToolbarHelper::addNew();
		JToolbarHelper::EditList();
		JToolBarHelper::deleteList();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();

		$filter_order       = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'tax_group_id');
		$filter_order_Dir   = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');

		$lists['order']     = $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;
		$media              = $this->get('Data');
		$pagination         = $this->get('Pagination');

		$this->user         = JFactory::getUser();
		$this->lists        = $lists;
		$this->media        = $media;
		$this->pagination   = $pagination;
		$this->request_url  = $uri->toString();

		parent::display($tpl);
	}
}
