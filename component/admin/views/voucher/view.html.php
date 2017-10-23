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
 * View Voucher
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       2.0.7
 */
class RedshopViewVoucher extends RedshopViewForm
{
	/**
	 * Method for get page title.
	 *
	 * @return  string
	 *
	 * @since   2.0.6
	 */
	public function getTitle()
	{
		return JText::_('COM_REDSHOP_VOUCHER_MANAGEMENT') . ': <small>[ ' . JText::_('COM_REDSHOP_EDIT') . ' ]</small>';
	}

	/**
	 * Method for run before display to initial variables.
	 *
	 * @param   string  $tpl  Template name
	 *
	 * @return  void
	 *
	 * @since   2.0.6
	 */
	public function beforeDisplay(&$tpl)
	{
		// Get data from the model
		$this->item = $this->model->getItem();
		$this->form = $this->model->getForm();

		$productField = '<?xml version="1.0" encoding="utf-8"?>'
			. '<field label="COM_REDSHOP_VOUCHER_PRODUCTS" description="COM_REDSHOP_VOUCHER_PRODUCTS_DESC" name="voucher_products"'
			. ' type="redshop.voucher_product" voucher_id="' . $this->item->id . '" class="form-control"/>';
		$productField = new SimpleXMLElement($productField);

		$this->form->setField($productField, null, true, 'details');

		$this->checkPermission();
		$this->loadFields();
	}
}
