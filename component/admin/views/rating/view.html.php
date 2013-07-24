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

class ratingViewrating extends JView
{
	public function display($tpl = null)
	{
		$context = "rating";

		$uri      = JFactory::getURI();
		$app      = JFactory::getApplication();
		$document = JFactory::getDocument();
		$user     = JFactory::getUser();

		$document->setTitle(JText::_('COM_REDSHOP_RATING'));

		JToolBarHelper::title(JText::_('COM_REDSHOP_RATING_MANAGEMENT'), 'redshop_rating48');

		JToolBarHelper::addNewX();
		JToolBarHelper::editListX();
		JToolBarHelper::deleteList();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();

		$filter_order       = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'rating_id');
		$filter_order_Dir   = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');

		$lists['order']     = $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;

		$ratings            = $this->get('Data');
		$total              = $this->get('Total');
		$pagination         = $this->get('Pagination');

		$this->user         = $user;
		$this->lists        = $lists;
		$this->ratings      = $ratings;
		$this->pagination   = $pagination;
		$this->request_url  = $uri->toString();

		parent::display($tpl);
	}
}
