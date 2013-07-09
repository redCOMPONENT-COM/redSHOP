<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die ('Restricted access');

jimport('joomla.application.component.view');

class manufacturerViewmanufacturer extends JView
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
		$context = 'manufacturer_id';

		$uri      = JFactory::getURI();
		$app      = JFactory::getApplication();
		$document = JFactory::getDocument();

		$document->setTitle(JText::_('COM_REDSHOP_MANUFACTURER'));

		JToolBarHelper::title(JText::_('COM_REDSHOP_MANUFACTURER_MANAGEMENT'), 'redshop_manufact48');
		JToolBarHelper::addNewX();
		JToolBarHelper::editListX();
		JToolBarHelper::customX('copy', 'copy.png', 'copy_f2.png', JText::_('COM_REDSHOP_TOOLBAR_COPY'), true);
		JToolBarHelper::deleteList();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();

		$filter_order     = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'm.ordering');
		$filter_order_Dir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');

		$lists ['order']     = $filter_order;
		$lists ['order_Dir'] = $filter_order_Dir;

		$manufacturer = $this->get('Data');
		$total        = $this->get('Total');
		$pagination   = $this->get('Pagination');

		$this->user = JFactory::getUser();
		$this->lists = $lists;
		$this->manufacturer = $manufacturer;
		$this->pagination = $pagination;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
