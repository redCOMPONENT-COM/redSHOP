<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewManufacturer extends RedshopViewAdmin
{
	/**
	 * The current user.
	 *
	 * @var  JUser
	 */
	public $user;

	/**
	 * The request url.
	 *
	 * @var  string
	 */
	public $request_url;

	public function display($tpl = null)
	{
		$uri      = JFactory::getURI();

		JToolBarHelper::title(JText::_('COM_REDSHOP_MANUFACTURER_MANAGEMENT'), 'flag redshop_manufact48');
		JToolbarHelper::addNew();
		JToolbarHelper::EditList();
		JToolBarHelper::custom('copy', 'copy.png', 'copy_f2.png', JText::_('COM_REDSHOP_TOOLBAR_COPY'), true);
		JToolBarHelper::deleteList();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();

		$state = $this->get('State');
		$filter_order     = $state->get('list.ordering');
		$filter_order_Dir = $state->get('list.direction');
		$this->filter     = $state->get('filter');

		$lists ['order']     = $filter_order;
		$lists ['order_Dir'] = $filter_order_Dir;

		$manufacturer = $this->get('Data');
		$pagination   = $this->get('Pagination');

		$this->user = JFactory::getUser();
		$this->lists = $lists;
		$this->manufacturer = $manufacturer;
		$this->pagination = $pagination;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
