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
 * Redshop Shipping Methods field.
 *
 * @since  1.0
 */
class RedshopFormFieldShipping_Method extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.0
	 */
	public $type = 'Shipping_Method';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  array  The field input markup.
	 */
	protected function getOptions()
	{
		$shippingMethods = RedshopHelperOrder::getShippingMethodInfo();

		if (empty($shippingMethods))
		{
			return parent::getOptions();
		}

		RedshopHelperShipping::loadLanguages();

		$options     = array();
		$this->value = $this->multiple ? (array) $this->value : (string) $this->value;

		foreach ($shippingMethods as $shipping)
		{
			$option = new stdClass;

			$option->text     = JText::_($shipping->name);
			$option->value    = $shipping->element;
			$option->disable  = false;
			$option->class    = '';
			$option->selected = false;
			$option->checked  = false;

			$options[] = $option;
		}

		return array_merge(parent::getOptions(), $options);
	}
}
