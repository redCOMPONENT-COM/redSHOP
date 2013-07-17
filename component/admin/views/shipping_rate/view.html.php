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

class shipping_rateViewshipping_rate extends JView
{
	public function display($tpl = null)
	{
		$context = 'shipping_rate';

		$uri      = JFactory::getURI();
		$app      = JFactory::getApplication();
		$document = JFactory::getDocument();

		$shippinghelper = new shipping;

		$lists['order']     = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'shipping_rate_id');
		$lists['order_Dir'] = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');
		$id                 = $app->getUserStateFromRequest($context . 'extension_id', 'extension_id', '0');

		if ((int) $id == 0)
		{
			JError::raiseError(500, "Direct Access not allowed, go to <a href='index.php?option=com_redshop&view=shipping'>" . JText::_('COM_REDSHOP_SHIPPING') . "</a>");

			return false;
		}

		$shipping = $shippinghelper->getShippingMethodById($id);

		$shipping_rates = $this->get('Data');
		$total          = $this->get('Total');
		$pagination     = $this->get('Pagination');

		$shippingpath     = JPATH_ROOT . '/plugins/' . $shipping->folder . DS . $shipping->element . '.xml';
		$myparams         = new JRegistry($shipping->params, $shippingpath);
		$is_shipper       = $myparams->get('is_shipper');
		$shipper_location = $myparams->get('shipper_location');

		$jtitle = ($shipper_location) ? JText::_('COM_REDSHOP_SHIPPING_LOCATION') : JText::_('COM_REDSHOP_SHIPPING_RATE');
		JToolBarHelper::title($jtitle . ' <small><small>[ ' . $shipping->name . ' ]</small></small>', 'redshop_shipping_rates48');
		JToolBarHelper::addNewX();
		JToolBarHelper::editListX();

		if ($is_shipper)
		{
			JToolBarHelper::customX('copy', 'copy.png', 'copy_f2.png', JText::_('COM_REDSHOP_TOOLBAR_COPY'), true);
		}

		JToolBarHelper::deleteList();
		JToolBarHelper::cancel('cancel', JText::_('JTOOLBAR_CLOSE'));

		$this->lists = $lists;
		$this->shipping_rates = $shipping_rates;
		$this->shipping = $shipping;
		$this->pagination = $pagination;
		$this->is_shipper = $is_shipper;
		$this->shipper_location = $shipper_location;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
