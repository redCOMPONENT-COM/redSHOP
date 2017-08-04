<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewStockroom extends RedshopViewAdmin
{
	/**
	 * The request url.
	 *
	 * @var  string
	 */
	public $request_url;

	public $state;

	public function display($tpl = null)
	{
		$uri      = JFactory::getURI();
		$document = JFactory::getDocument();

		$document->setTitle(JText::_('COM_REDSHOP_STOCKROOM'));

		JToolBarHelper::title(JText::_('COM_REDSHOP_STOCKROOM_MANAGEMENT'), 'redshop_stockroom48');
		JToolBarHelper::custom('listing', 'copy.png', 'copy_f2.png', JText::_('COM_REDSHOP_LISTING'), false);
		JToolbarHelper::addNew();
		JToolbarHelper::EditList();
		JToolBarHelper::custom('copy', 'copy.png', 'copy_f2.png', JText::_('COM_REDSHOP_TOOLBAR_COPY'), true);
		JToolBarHelper::deleteList();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();

		$this->state = $this->get('State');
		$lists ['order']     = $this->state->get('list.ordering', 'stockroom_id');
		$lists ['order_Dir'] = $this->state->get('list.direction');

		$stockroom  = $this->get('Data');
		$pagination = $this->get('Pagination');

		$this->lists = $lists;
		$this->stockroom = $stockroom;
		$this->pagination = $pagination;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
