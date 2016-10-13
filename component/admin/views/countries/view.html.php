<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.html.pagination');

/**
 * View Countries
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       [version> [<description>]
 */

class RedshopViewCountries extends RedshopViewAdmin
{
	/**
	 * Display template function
	 *
	 * @param   object  $tpl  template variable
	 *
	 * @return void
	 *
	 * @since 1.x
	 */

	public function display($tpl = null)
	{
		global $context;

		$context  = 'id';
		$app      = JFactory::getApplication();
		$uri      = JFactory::getURI();

		$state		  	= $this->get('State');
		$filterOrder    = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'id');
		$filterOrderDir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');

		$lists['order']     = $filterOrder;
		$lists['order_Dir'] = $filterOrderDir;

		$this->user         = JFactory::getUser();
		$this->fields		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');
		$this->lists        = $lists;
		$this->requestUrl  	= $uri->toString();
		$this->filter       = $state->get('filter');

		$this->addToolBar();

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function addToolBar()
	{
		$title = JText::_('COM_REDSHOP_COUNTRY_MANAGEMENT');

		if ($this->pagination->total)
		{
			$title .= "<span style='font-size: 0.5em; vertical-align: middle;'>(" . $this->pagination->total . ")</span>";
		}

		JToolBarHelper::title($title, 'redshop_country_48');
		JToolBarHelper::addNew('country.add');
		JToolBarHelper::editList('country.edit');
		JToolBarHelper::deleteList('', 'countries.delete');
	}
}
