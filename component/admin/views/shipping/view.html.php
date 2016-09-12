<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewShipping extends RedshopViewAdmin
{
	/**
	 * The request url.
	 *
	 * @var  string
	 */
	public $request_url;

	public function display($tpl = null)
	{
		$context = 'shipping';

		$uri      = JFactory::getURI();
		$app      = JFactory::getApplication();
		$document = JFactory::getDocument();
		$language = JFactory::getLanguage();

		// Load language files
		$shippings  = $this->get('Data');

		for ($l = 0, $ln = count($shippings); $l < $ln; $l++)
		{
			$extension = 'plg_redshop_shipping_' . strtolower($shippings[$l]->element);
			$language->load($extension, JPATH_ADMINISTRATOR);
		}

		$document->setTitle(JText::_('COM_REDSHOP_SHIPPING'));

		JToolBarHelper::title(JText::_('COM_REDSHOP_SHIPPING_MANAGEMENT'), 'redshop_shipping48');

		$filter_order     = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'ordering');
		$filter_order_Dir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');

		$lists['order']     = $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;

		$pagination = $this->get('Pagination');

		$this->lists       = $lists;
		$this->shippings   = $shippings;
		$this->pagination  = $pagination;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
