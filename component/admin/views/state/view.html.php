<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewState extends RedshopViewAdmin
{
	public function display($tpl = null)
	{
		JLoader::import('joomla.html.pagination');


		$uri      = JFactory::getURI();
		$document = JFactory::getDocument();

		$document->setTitle(JText::_('COM_REDSHOP_STATE'));

		JToolBarHelper::title(JText::_('COM_REDSHOP_STATE_MANAGEMENT'), 'redshop_region_48');
		JToolbarHelper::addNew();
		JToolbarHelper::EditList();
		JToolbarHelper::deleteList();

		$state = $this->get('State');
		$lists['order']     = $state->get('list.ordering', 'state_id');
		$lists['order_Dir'] = $state->get('list.direction');

		$db = JFactory::getDbo();
		JToolBarHelper::title(JText::_('COM_REDSHOP_STATES'), 'redshop_region_48');

		$redhelper       = redhelper::getInstance();
		$q               = "SELECT  id as value,country_name as text,country_jtext from #__redshop_country ORDER BY country_name ASC";
		$db->setQuery($q);
		$countries       = $db->loadObjectList();

		$countries       = $redhelper->convertLanguageString($countries);

		$defSelect = new StdClass;
		$defSelect->value = "0";
		$defSelect->text  = JText::_('COM_REDSHOP_SELECT');

		$temps           = array($defSelect);
		$countries       = array_merge($temps, $countries);

		$country_id_filter = $state->get('country_id_filter');

		$lists['id'] = JHTML::_('select.genericlist', $countries, 'country_id_filter',
			'class="inputbox" size="1" onchange="document.adminForm.submit();"    ', 'value', 'text', $country_id_filter
		);

		$country_main_filter = $state->get('country_main_filter');

		$fields                    = $this->get('Data');
		$pagination                = $this->get('Pagination');

		$this->country_main_filter = $country_main_filter;
		$this->user                = JFactory::getUser();
		$this->pagination          = $pagination;
		$this->fields              = $fields;
		$this->lists               = $lists;
		$this->request_url         = $uri->toString();

		parent::display($tpl);
	}
}
