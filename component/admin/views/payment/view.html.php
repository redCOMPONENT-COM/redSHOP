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

class paymentViewpayment extends JView
{
	public function display($tpl = null)
	{
		$context = 'payment';

		$uri      = JFactory::getURI();
		$app      = JFactory::getApplication();
		$document = JFactory::getDocument();

		$document->setTitle(JText::_('COM_REDSHOP_PAYMENTS'));

		JToolBarHelper::title(JText::_('COM_REDSHOP_PAYMENT_MANAGEMENT'), 'redshop_payment48');
		JToolBarHelper::addNewX();
		JToolBarHelper::editListX();
		JToolBarHelper::deleteList();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();

		$filter_order     = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'ordering');
		$filter_order_Dir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');
		$payment_section  = $app->getUserStateFromRequest($context . 'payment_section', 'payment_section', 0);

		$lists['order']     = $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;

		$payments   = $this->get('Data');
		$total      = $this->get('Total');
		$pagination = $this->get('Pagination');

		$this->lists = $lists;
		$this->payments = $payments;
		$this->pagination = $pagination;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
