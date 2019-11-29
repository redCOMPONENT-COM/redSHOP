<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Form.Field
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');

/**
 * Redshop Voucher Product Search field.
 *
 * @since  1.0
 */
class RedshopFormFieldCoupon_Remaining extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.0
	 */
	public $type = 'Coupon_Remaining';

	/**
	 * Method to get the field input markup for a generic list.
	 * Use the multiple attribute to enable multiselect.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   3.7.0
	 */
	protected function getInput()
	{
		$couponId = isset($this->element['coupon_id']) ? (int) $this->element['coupon_id'] : false;

		if ($couponId !== false)
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select($db->qn('coupon_value'))
				->from($db->qn('#__redshop_coupons_transaction'))
				->where($db->qn('coupon_id') . ' = ' . $couponId);

			return '<label>' . $db->setQuery($query)->loadResult() . '</label>';
		}

		return '<label></label>';
	}
}
