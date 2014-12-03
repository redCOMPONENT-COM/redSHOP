<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::load('RedshopHelperAdminQuotation');

class RedshopViewQuotation extends RedshopView
{
	/**
	 * The request url.
	 *
	 * @var  string
	 */
	public $request_url;

	public function display($tpl = null)
	{
		$context = 'quotation_id';

		$quotationHelper = new quotationHelper;

		$uri      = JFactory::getURI();
		$app      = JFactory::getApplication();
		$document = JFactory::getDocument();

		$document->setTitle(JText::_('COM_REDSHOP_quotation'));

		JToolBarHelper::title(JText::_('COM_REDSHOP_QUOTATION_MANAGEMENT'), 'redshop_quotation48');
		JToolBarHelper::addNew();
		RedshopToolbarHelper::link('index.php?option=com_redshop&view=quotation&format=csv', 'save', JText::_('COM_REDSHOP_EXPORT_DATA_LBL'));
		JToolBarHelper::editList();
		JToolBarHelper::deleteList();

		$filter_order     = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'quotation_cdate');
		$filter_order_Dir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', 'DESC');
		$filter_status    = $app->getUserStateFromRequest($context . 'filter_status', 'filter_status', 0);

		$lists['order']     = $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;

		$quotation  = $this->get('Data');
		$total      = $this->get('Total');
		$pagination = $this->get('Pagination');

		$optionsection = $quotationHelper->getQuotationStatusList();
		$lists['filter_status'] = JHTML::_('select.genericlist', $optionsection, 'filter_status',
			'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'value', 'text', $filter_status
		);

		$this->lists = $lists;
		$this->quotation = $quotation;
		$this->pagination = $pagination;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
