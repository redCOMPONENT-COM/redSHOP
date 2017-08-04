<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewAddressfields_listing extends RedshopViewAdmin
{
	public function display($tpl = null)
	{
		global $context;

		$app = JFactory::getApplication();

		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_REDSHOP_FIELDS'));

		JToolBarHelper::title(JText::_('COM_REDSHOP_ADDRESS_FIELD_MANAGEMENT'), 'redshop_fields48');

		$uri = JFactory::getURI();

		$filter_order = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'field_id');
		$filter_order_Dir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');

		$lists['order'] = $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;
		$fields = $this->get('Data');
		$pagination = $this->get('Pagination');

		$section_id = $app->getUserStateFromRequest($context . 'section_id', 'section_id', 0);

		$sectionlist = array(
			JHTML::_('select.option', '7', JText::_('COM_REDSHOP_CUSTOMER_ADDRESS')),
			JHTML::_('select.option', '8', JText::_('COM_REDSHOP_COMPANY_ADDRESS')),
			JHTML::_('select.option', '14', JText::_('COM_REDSHOP_CUSTOMER_SHIPPING_ADDRESS')),
			JHTML::_('select.option', '15', JText::_('COM_REDSHOP_COMPANY_SHIPPING_ADDRESS'))
		);

		$option = array();
		$option[0]->value = "0";
		$option[0]->text = JText::_('COM_REDSHOP_SELECT');

		if (count($sectionlist) > 0)
		{
			$option = @array_merge($option, $sectionlist);
		}

		$lists['addresssections'] = JHTML::_('select.genericlist', $option, 'section_id',
			'class="inputbox" size="1" onchange="document.adminForm.submit();"',
			'value',
			'text',
			$section_id
		);

		$this->user = JFactory::getUser();
		$this->lists = $lists;
		$this->fields = $fields;
		$this->pagination = $pagination;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
