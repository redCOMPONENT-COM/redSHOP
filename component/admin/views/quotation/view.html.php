<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Class RedshopViewQuotation
 *
 * @since  1.6.0
 */
class RedshopViewQuotation extends RedshopViewAdmin
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
	public $state;

	/**
	 * @param   null  $tpl  Template
	 *
	 * @return  void
	 */
	public function display($tpl = null)
	{
		$uri      = JUri::getInstance();
		$document = JFactory::getDocument();

		$document->setTitle(JText::_('COM_REDSHOP_quotation'));

		JToolBarHelper::title(JText::_('COM_REDSHOP_QUOTATION_MANAGEMENT'), 'redshop_quotation48');
		JToolBarHelper::addNew();
		RedshopToolbarHelper::link('index.php?option=com_redshop&view=quotation&format=csv', 'save', JText::_('COM_REDSHOP_EXPORT_DATA_LBL'));
		JToolBarHelper::editList();
		JToolBarHelper::deleteList();

		$this->state  = $this->get('State');
		$filterStatus = $this->state->get('filter_status', 0);

		$lists['order']     = $this->state->get('list.ordering', 'q.quotation_cdate');
		$lists['order_Dir'] = $this->state->get('list.direction', 'desc');

		$quotation  = $this->get('Items');
		$pagination = $this->get('Pagination');

		$optionsection          = RedshopHelperQuotation::getQuotationStatusList();
		$lists['filter_status'] = JHTML::_('select.genericlist', $optionsection, 'filter_status',
			'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'value', 'text', $filterStatus
		);

		$this->lists       = $lists;
		$this->quotation   = $quotation;
		$this->pagination  = $pagination;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
