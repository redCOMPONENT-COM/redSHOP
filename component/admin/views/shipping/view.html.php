<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * redSHOP Shipping Methods view
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       2.0.0
 */
class RedshopViewShipping extends RedshopViewAdmin
{
	/**
	 * The request url.
	 *
	 * @var  string
	 */
	public $request_url;

	/**
	 * @var  array
	 */
	public $shippings;

	/**
	 * @var  JPagination
	 */
	public $pagination;

	/**
	 * @var  array
	 */
	public $lists;

	/**
	 * Display the States view
	 *
	 * @param   string $tpl The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 *
	 * @throws  Exception
	 */
	public function display($tpl = null)
	{
		$context = 'shipping';

		$uri      = JUri::getInstance();
		$app      = JFactory::getApplication();
		$document = JFactory::getDocument();
		$language = JFactory::getLanguage();

		// Load language files
		$this->shippings = $this->get('Data');

		foreach ($this->shippings as $shippingMethod)
		{
			$extension = 'plg_redshop_shipping_' . strtolower($shippingMethod->element);
			$language->load($extension, JPATH_ADMINISTRATOR);
		}

		$document->setTitle(JText::_('COM_REDSHOP_SHIPPING'));

		JToolbarHelper::title(JText::_('COM_REDSHOP_SHIPPING_MANAGEMENT'), 'redshop_shipping48');

		$filter_order     = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'ordering');
		$filter_order_Dir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');

		$lists['order']     = $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;

		$pagination = $this->get('Pagination');

		$this->lists       = $lists;
		$this->pagination  = $pagination;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
