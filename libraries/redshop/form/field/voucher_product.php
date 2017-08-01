<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Form.Field
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
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

		if ($voucherId)
		{
			$products = RedshopEntityVoucher::getInstance($voucherId)->getProducts();

			if (!$products->isEmpty())
			{
				foreach ($products->getAll() as $product)
				{
					$data = new stdClass;
					$data->value = $product->get('product_id');
					$data->text = $product->get('product_name');

					$selected[] = $data;
				}
			}
		}

		return JHtml::_(
			'redshopselect.search',
			$selected,
			'container_product',
			array(
				'select2.ajaxOptions' => array(
					'typeField' => ', alert:"voucher", voucher_id:' . $voucherId
				),
				'select2.options' => array('multiple' => true),
				'list.attr' => array('required' => 'required')
			)
		);
	}
}
