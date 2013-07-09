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

class deliveryViewdelivery extends JView
{
	/**
	 * The request url.
	 *
	 * @var  string
	 */
	public $request_url;

	public function display($tpl = null)
	{
		$context = 'delivery';

		$app      = JFactory::getApplication();
		$document = JFactory::getDocument();

		$document->setTitle(JText::_('COM_REDSHOP_DELIVERY_LIST'));

		JToolBarHelper::title(JText::_('COM_REDSHOP_DELIVERY_LIST'), 'redshop_redshopcart48');
		JToolBarHelper::custom('export_data', 'save.png', 'save_f2.png', JText::_('COM_REDSHOP_EXPORT_DATA_LBL'), false);

		$uri = JFactory::getURI();

		$filter_order     = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'order_id');
		$filter_order_Dir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');

		$lists['order'] = $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;

		$this->lists = $lists;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
