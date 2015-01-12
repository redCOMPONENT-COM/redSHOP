<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewVoucher_detail extends RedshopView
{
	/**
	 * The request url.
	 *
	 * @var  string
	 */
	public $request_url;

	public function display($tpl = null)
	{
		JToolBarHelper::title(JText::_('COM_REDSHOP_VOUCHER_MANAGEMENT_DETAIL'), 'redshop_voucher48');

		$uri = JFactory::getURI();

		$this->setLayout('default');

		$lists = array();

		$detail = $this->get('data');

		$isNew = ($detail->voucher_id < 1);

		$text = $isNew ? JText::_('COM_REDSHOP_NEW') : JText::_('COM_REDSHOP_EDIT');

		JToolBarHelper::title(JText::_('COM_REDSHOP_VOUCHER') . ': <small><small>[ ' . $text . ' ]</small></small>', 'redshop_voucher48');
		JToolBarHelper::apply();
		JToolBarHelper::save();

		if ($isNew)
		{
			JToolBarHelper::cancel();
		}
		else
		{
			JToolBarHelper::cancel('cancel', JText::_('JTOOLBAR_CLOSE'));
		}

		$voucher_type = array(JHTML::_('select.option', 'Total', JText::_('COM_REDSHOP_TOTAL')),
			JHTML::_('select.option', 'Percentage', JText::_('COM_REDSHOP_PERCENTAGE'))
		);
		$lists['voucher_type'] = JHTML::_('select.genericlist', $voucher_type, 'voucher_type',
			'class="inputbox" size="1"', 'value', 'text', $detail->voucher_type
		);

		$lists['free_shipping'] = JHTML::_('select.booleanlist', 'free_shipping', 'class="inputbox" ', $detail->free_shipping);
		$lists['published'] = JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $detail->published);
		$model = $this->getModel('voucher_detail');

		$products_list = $model->voucher_products_sel($detail->voucher_id);

		if (count($products_list) > 0)
		{
			$result_container = $products_list;
		}
		else
		{
			$result_container = array();
		}

		$lists['voucher_product'] = JHTML::_('redshopselect.search', $result_container, 'container_product',
			array(
				'select2.ajaxOptions' => array('typeField' => ', alert:"voucher", voucher_id:' . $detail->voucher_id),
				'select2.options' => array('multiple' => true)
			)
		);

		$result = array();
		$this->lists = $lists;
		$this->detail = $detail;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
