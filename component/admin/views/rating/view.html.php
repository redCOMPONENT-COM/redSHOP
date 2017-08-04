<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewRating extends RedshopViewAdmin
{
	public $state;

	public function display($tpl = null)
	{
		$uri      = JFactory::getURI();
		$document = JFactory::getDocument();
		$user     = JFactory::getUser();

		$document->setTitle(JText::_('COM_REDSHOP_RATING'));

		JToolBarHelper::title(JText::_('COM_REDSHOP_RATING_MANAGEMENT'), 'redshop_rating48');

		JToolbarHelper::addNew();
		JToolbarHelper::EditList();
		JToolBarHelper::deleteList();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();

		$this->state = $this->get('State');
		$lists['order']     = $this->state->get('list.ordering', 'rating_id');
		$lists['order_Dir'] = $this->state->get('list.direction', 'desc');

		$ratings            = $this->get('Data');
		$pagination         = $this->get('Pagination');

		$this->user         = $user;
		$this->lists        = $lists;
		$this->ratings      = $ratings;
		$this->pagination   = $pagination;
		$this->request_url  = $uri->toString();

		parent::display($tpl);
	}
}
