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

class stateViewstate extends JView
{
	public function display($tpl = null)
	{
		JLoader::import('joomla.html.pagination');

		require_once JPATH_COMPONENT_SITE . '/helpers/helper.php';

		$context = 'state_id';

		$uri      = JFactory::getURI();
		$app      = JFactory::getApplication();
		$document = JFactory::getDocument();
		$user     = JFactory::getUser();

		$document->setTitle(JText::_('COM_REDSHOP_STATE'));

		JToolBarHelper::title(JText::_('COM_REDSHOP_STATE_MANAGEMENT'), 'redshop_region_48');
		JToolbarHelper::addNewX();
		JToolbarHelper::EditListX();
		JToolbarHelper::deleteList();

		$filter_order     = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'state_id');
		$filter_order_Dir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');

		$lists['order']     = $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;

		$db = JFactory::getDBO();
		JToolBarHelper::title(JText::_('COM_REDSHOP_STATES'), 'redshop_region_48');

		$redhelper       = new redhelper;
		$q               = "SELECT  country_id as value,country_name as text,country_jtext from #__" . TABLE_PREFIX . "_country ORDER BY country_name ASC";
		$db->setQuery($q);
		$countries       = $db->loadObjectList();
		$countries       = $redhelper->convertLanguageString($countries);

		$temps[0]        = new stdClass;
		$temps[0]->value = "0";
		$temps[0]->text  = JText::_('COM_REDSHOP_SELECT');
		$countries       = array_merge($temps, $countries);
		$country_list    = explode(',', COUNTRY_LIST);

		$tmp             = new stdClass;
		$tmp             = array_merge($tmp, $country_list);

		$country_id_filter = $app->getUserStateFromRequest($context . 'country_id_filter', 'country_id_filter', '');

		$lists['country_id'] = JHTML::_('select.genericlist', $countries, 'country_id_filter',
			'class="inputbox" size="1" onchange="document.adminForm.submit();"    ', 'value', 'text', $country_id_filter
		);

		$country_main_filter = $app->getUserStateFromRequest($context . 'country_main_filter', 'country_main_filter', '');

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
