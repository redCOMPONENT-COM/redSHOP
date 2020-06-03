<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Form.Field
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');

/**
 * Redshop Voucher Product Search field.
 *
 * @since  1.0
 */
class RedshopFormFieldCoupon_User extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.0
	 */
	public $type = 'Coupon_User';

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
		$couponId = isset($this->element['coupon_id']) ? (int)$this->element['coupon_id'] : false;
		$selected  = array();
		$typeField = ', alert:"coupon"';

		if ($couponId) {
			$users  = RedshopEntityCoupon::getInstance($couponId)->getUsers();
			$typeField .= ', coupon_id:' . $couponId;

			if (!$users->isEmpty()) {
				foreach ($users->getAll() as $user) {
					$data        = new stdClass;
					$data->value = $user->get('user_id');
					$data->text  = $user->get('user_email');

					$selected[$user->get('user_id')] = $data;
				}
			}
		}

		if (!empty($this->value)) {
			$values = !$this->multiple || !is_array($this->value) ? array($this->value) : $this->value;
			$db     = JFactory::getDbo();

			$query = $db->getQuery(true)
				->select($db->qn(array('user_id', 'user_email')))
				->from($db->qn('#__redshop_users_info'))
				->where($db->qn('user_id') . ' IN (' . implode(',', $values) . ')')
				->where($db->qn('address_type') . ' = ' . $db->q('ST'));

			$users = $db->setQuery($query)->loadObjectList();

			foreach ($users as $user) {
				if (isset($selected[$user->user_id])) {
					continue;
				}

				$data        = new stdClass;
				$data->value = $user->user_id;
				$data->text  = $user->user_email;

				$selected[$user->user_id] = $data;
			}
		}

		return JHtml::_(
			'redshopselect.search',
			$selected,
			'jform[' . $this->fieldname . ']',
			array(
				'select2.ajaxOptions' => array(
					'typeField' => $typeField
				),
				'select2.options'     => array('multiple' => true),
				'list.attr'           => array('required' => 'required')
			)
		);
	}
}
