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
class RedshopFormFieldVoucher_Product extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.0
	 */
	public $type = 'Voucher_Product';

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
		$voucherId = isset($this->element['voucher_id']) ? (int) $this->element['voucher_id'] : false;
		$selected  = array();
		$typeField = ', alert:"voucher"';

		if ($voucherId)
		{
			$products   = RedshopEntityVoucher::getInstance($voucherId)->getProducts();
			$typeField .= ', voucher_id:' . $voucherId;

			if (!$products->isEmpty())
			{
				foreach ($products->getAll() as $product)
				{
					$data        = new stdClass;
					$data->value = $product->get('product_id');
					$data->text  = $product->get('product_name');

					$selected[$product->get('product_id')] = $data;
				}
			}
		}

		if (!empty($this->value))
		{
			$values = !$this->multiple || !is_array($this->value) ? array($this->value) : $this->value;
			$db     = JFactory::getDbo();

			$query = $db->getQuery(true)
				->select($db->qn(array('product_id', 'product_name')))
				->from($db->qn('#__redshop_product'))
				->where($db->qn('product_id') . ' IN (' . implode(',', $values) . ')');

			$products = $db->setQuery($query)->loadObjectList();

			foreach ($products as $product)
			{
				if (isset($selected[$product->product_id]))
				{
					continue;
				}

				$data        = new stdClass;
				$data->value = $product->product_id;
				$data->text  = $product->product_name;

				$selected[$product->product_id] = $data;
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
