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
 * View Coupon
 *
 * @package      RedSHOP.Backend
 * @subpackage  View
 * @since        2.1.0
 */
class RedshopViewCoupon extends RedshopViewForm
{
	/**
	 * Method for run before display to initial variables.
	 *
	 * @param   string  $tpl  Template name
	 *
	 * @return  void
	 *
	 * @since   2.1.0
	 *
	 * @throws  Exception
	 */
	public function beforeDisplay(&$tpl)
	{
		// Get data from the model
		$this->item = $this->model->getItem();
		$this->form = $this->model->getForm();

		$this->form->setField(
			new SimpleXMLElement(
				'<?xml version="1.0" encoding="utf-8"?>'
				. '<field label="COM_REDSHOP_COUPON_REMAINING_AMOUNT" name="voucher_products" class="form-control" '
				. 'type="redshop.coupon_remaining" coupon_id="' . $this->item->id . '" '
				. 'address_type="BT" readonly="true"/>'
			),
			null,
			true,
			'details'
		);

		$this->checkPermission();
		$this->loadFields();
	}
}
